@extends('layouts.app')

@section('content')
<div class="container">
</br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$data['post']['title']}}</div>
                <div class="card-body">
                    <img style="max-width:250px; height: auto;float:left;padding-right: 1em;" src="/images/{{$data['post']['image_url']}}" />
                    <div style="float:left;">
                        <p>Author: {{$data['post']['user']['username']}}</p> 
                        <p>Created at: {{ \Carbon\Carbon::parse($data['post']['created_at'])->format('M d, Y')}}</p>                       
                        {!!$data['post']['content']!!}
                    </div>
                </div>
            </div>
        </div>   
    </div>
    </br>
    <div id= "comment" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Comment</h5>
            </button>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
            <div id="editor" style="max-height:350px;overflow-y:scroll;">
                <p>Don't write lame stuff...</p>
            </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
            <button onclick="postComment()" type="button" class="btn btn-primary">Save changes</button>
            <button onclick="document.getElementById('comment').style.display='none'" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
<div class="container">
@if(Auth::check())
    <button onclick="document.getElementById('comment').style.display='block'" class="btn btn-secondary">Add Comment</button>
@else
    <button onclick="alert('Please login to comment!')" class="btn btn-secondary">Add Comment</button>
@endif
</div>
</br>
    <div class="row">
        <div class="col-md-12"> 
            <div class="card">
            <div class="card-header">Comments</div> 
            @foreach($data['post']['comments'] as $comment)
                <div id="comment_{{$comment['id']}}" class="card-body d-flex" style="border-bottom:1px solid #D8D8D8;"> 
                <div style="flex:1;">
                    <span class="badge badge-primary badge-pill" title="Likes">{{$comment['likes']}}</span>
                    {{$comment['user']['username']}}
                </div>
                <div style="flex:8;display:flex;" class="d-flex justify-content-between">            
                    <div>{!! $comment['comment'] !!}</div>
                     <!-- put a check to see if Auth::check() && Auth::user()->id == $comment['user_id'] -->                     
                    <div>
                    @if((Auth::check() && Auth::user()->id == $comment['user_id']) || (Auth::check() && Auth::user()->isAdmin()))
                        <button onclick="deleteComment('{{$comment['id']}}')" type="button" class="btn btn-danger btn-sm">Delete</button>
                    @endif
                    @if(Auth::check())
                        <i id="like_{{$comment['id']}}" onclick="likeComment('{{$comment['id']}}')" class="material-icons" style="cursor: pointer;" title="Like">thumb_up_alt</i>
                        <i onclick="reportComment('{{$comment['id']}}')" class="material-icons" style="cursor: pointer;" title="Report">outlined_flag</i>
                    @endif    
                    </div>                   
                    </div>
                </div>
            @endforeach  
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Initialize Quill editor -->
<script type="text/javascript">
    // on window load, initialize the fancy wysiwyg editor, this is all good
    window.onload = function(e){
    window.quill = new Quill('#editor', {
      theme: 'snow'  // or 'bubble'
    });
  }

  function getMetaContent(name){
    return document.getElementsByTagName('meta')[name].getAttribute("content");
  }

  function postComment() {
      // this you will be able to use to save comment
    // get all form inputs
    var comment = document.getElementsByClassName("ql-editor")[0].innerHTML;

    // append all form data
    var formData = new FormData();
    formData.append('comment', comment);
    // append blog id as post_id to formData, otherway, the second param is what you dynamically pass into this blade, it might just be id
    // the left side is what you are naming the property on the form as
    // lets do a dd() in the controller and try posting something and watch the network tab
    formData.append("post_id", "{{$data['post']['id']}}"); 
    var token = getMetaContent('csrf-token');
    
    // post to route using XMLHTTPrequest (plain/vanilla js)
    var xhr = new XMLHttpRequest();   // new HttpRequest instance

    // this us the route of the endpoint you will hit to post the comment to
    xhr.open("POST", "/blog/comment/save");

    // need to pass along token with header for authorization
    xhr.setRequestHeader("X-CSRF-TOKEN", token);

    // need to handle response here when it is saved using onreadystatechange
    xhr.onreadystatechange = function () {
      // status is good and it saved
      if(xhr.readyState === 4 && xhr.status === 200) {
        alert('Post has been saved!');
        // redirect to post edit
        var response = JSON.parse(xhr.response);
        //window.location = '/editor/blog/' + response.payload.slug;
      }
      // if there was an error
      if (xhr.readyState === 4 && xhr.status === 422) {
          // for now we'll just log but eventually will handle something differently
          var errors = xhr.responseText;
          alert(errors);                          
      }
    };
    xhr.send(formData);
  }; 
  //delete comment
  function deleteComment(id) {

    // append all form data
    var token = getMetaContent('csrf-token');
    
    // post to route using XMLHTTPrequest (plain/vanilla js)
    var xhr = new XMLHttpRequest();   // new HttpRequest instance

    // this is the url to hit to delete the blog
    xhr.open("DELETE", "/blog/comment/" + id + "/delete");

    // need to pass along token with header for authorization
    xhr.setRequestHeader("X-CSRF-TOKEN", token);

    // need to handle response here when it is saved using onreadystatechange
    xhr.onreadystatechange = function () {
      // status is good and it saved
      if(xhr.readyState === 4 && xhr.status === 200) {
          alert('Comment has been defeated');
          // document.getElementById
          var element = document.getElementById('comment_'+ id).innerHTML = '';
          // set the innerHTML of that element to a blank string
        // redirect to all blogs page or admin or wherever upon successful deletion
        // window.location = '/editor/blog/' + response.payload.slug;
      }
      // if there was an error
      if (xhr.readyState === 4 && xhr.status === 422) {
          // for now we'll just log but eventually will handle something differently
          var errors = xhr.responseText;
          alert(errors);                          
      }
    };
    xhr.send();
  }; 
  function likeComment(id) {
    var likeElem = document.querySelector('#like_'+ id);
    // check to see if this has already been liked by seeing if a class exists on it
    if (likeElem.classList.contains('liked') == false){
        // all the code to run if 
        // append all form data
        var token = getMetaContent('csrf-token');

        // post to route using XMLHTTPrequest (plain/vanilla js)
        var xhr = new XMLHttpRequest();   // new HttpRequest instance

        // this is the url to hit to delete the blog
        xhr.open("PUT", "/blog/comment/" + id + "/like");

        // need to pass along token with header for authorization
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        // need to handle response here when it is saved using onreadystatechange
        xhr.onreadystatechange = function () {
        // status is good and it saved
            if(xhr.readyState === 4 && xhr.status === 200) {
                alert('Comment has been liked!');
                // document.getElementById
                var element = document.getElementById('like_'+ id).classList.add('liked');
            }
            // if there was an error
            if (xhr.readyState === 4 && xhr.status === 422) {
                // for now we'll just log but eventually will handle something differently
                var errors = xhr.responseText;
                alert(errors);                          
            }
        };
        xhr.send();
    }        
    }; 
</script>