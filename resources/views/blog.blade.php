@extends('layouts.app')

@section('content')
 
   <div class="container">
      <div class="row">
        <div class="col-sm-12">
        @if (isset($title))
          <h1>Update Blog Post</h1>
        @else
          <h1>New Blog Post</h1>
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-sm-8">
          <!-- editing -->
          @if (isset($title))
            <div class="form-group">
              <label for="title">Title</label>
              <input class="form-control" type="text" placeholder="Title" name="title" id="title" value="{{$title}}" />
            </div>
            <div class="form-group">
              <label for="meta_description">Meta Description</label>
              <input class="form-control" type="text" placeholder="Meta Description" name="meta_description" id="meta_description" value="{{$meta_description}}" />
            </div>
            <div class="form-group">
              <label for="meta_title">Meta Title</label>
              <input class="form-control" type="text" placeholder="Meta Title" name="meta_title" id="meta_title" value="{{$meta_title}}" />
            </div>
          @else
          <div class="form-group">
            <label for="title">Title</label>
            <input class="form-control" type="text" placeholder="Title" name="title" id="title" value="" />
          </div>
          <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <input class="form-control" type="text" placeholder="Meta Description" name="meta_description" id="meta_description" value="" />
          </div>
          <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input class="form-control" type="text" placeholder="Meta Title" name="meta_title" id="meta_title" value="" />
          </div>
          @endif
          
          @if(isset($image_url))
            <img id="currentImg" style="max-width:125px; height:auto;" src="/images/{{$image_url}}" />
          @endif
          <div class="form-group">
            @if(isset($image_url))
            <label for="imageInput">Replace Featured Image (200x200 Min.)</label>
            @else
            <label for="imageInput">Upload Featured Image (200x200 Min.)</label>
            @endif
              <input class="form-control-file" name="input_img" type="file" id="input_img">
            </div>
        </div>
        <div class="col-sm-4">
          <button onclick="savePost()" type="button" class="btn btn-outline-primary float-right">Save</button><br>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div id="editor" style="max-height:350px;overflow-y:scroll;">
            <p>Start writing some kickass content...</p>
          </div>
        </div>
      </div>
    </div>
@endsection
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Initialize Quill editor -->
<script type="text/javascript">
    @if (isset($content) && $content = addslashes($content))
      // this is an edit
      var oldContent = '{!! $content !!}';
      var slug = '{{$slug}}';
    @else 
      // this is a new post
      var oldContent = false;
    @endif
  window.onload = function(e){
    window.quill = new Quill('#editor', {
      theme: 'snow'  // or 'bubble'
    });
    
    if (oldContent) {
      // set the old content
      document.getElementsByClassName("ql-editor")[0].innerHTML = oldContent;
      // if there is old content, this is an edit
      window.isEdit = true;
      window.slug = slug;
    }
  }
  function getMetaContent(name){
    return document.getElementsByTagName('meta')[name].getAttribute("content");
  }

  function savePost() {
    // get all form inputs
    var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
    var title = document.getElementById("title").value;
    var meta_description = document.getElementById("meta_description").value;
    var meta_title = document.getElementById("meta_title").value;
    var input_img = document.getElementById('input_img').files[0];

    // append all form data
    var formData = new FormData();
    formData.append('title', title);
    formData.append('meta_description', meta_description);
    formData.append('meta_title', meta_title);
    formData.append('content', content);
    formData.append('input_img', input_img);
    var token = getMetaContent('csrf-token');
    
    // post to route using XMLHTTPrequest (plain/vanilla js)
    var xhr = new XMLHttpRequest();   // new HttpRequest instance
    if (window.isEdit) {
      // if update
      formData.append('is_edit', true);
      formData.append('slug', window.slug);
      xhr.open("POST", "/admin/blog/update");
    } else {
      formData.append('is_edit', false);
      // new post
      xhr.open("POST", "/admin/blog/save");
    }
    // need to pass along token with header for authorization
    xhr.setRequestHeader("X-CSRF-TOKEN", token);

    // need to handle response here when it is saved using onreadystatechange
    xhr.onreadystatechange = function () {
      // status is good and it saved
      if(xhr.readyState === 4 && xhr.status === 200) {
        alert('Post has been saved!');
        // redirect to post edit
        var response = JSON.parse(xhr.response);
        window.location = '/admin/blog/' + response.payload.slug;
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
</script>