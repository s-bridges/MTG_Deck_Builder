@extends('layouts.app')

@section('content')
 
   <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1>New Blog Post</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-8">
          <div class="form-group">
            <input class="form-control" type="text" placeholder="Title" name="title" id="title" value="" />
          </div>
          <div class="form-group">
            <input class="form-control" type="text" placeholder="Meta Description" name="meta_description" id="meta_description" value="" />
          </div>
          <div class="form-group">
            <input class="form-control" type="text" placeholder="Meta Title" name="meta_title" id="meta_title" value="" />
          </div>
          <div class="form-group">
            <label for="imageInput">Upload Featured Image (200x200 Min.)</label>
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
  window.onload = function(e){ 
    window.quill = new Quill('#editor', {
        theme: 'snow'  // or 'bubble'
      });
  }
  function getMetaContent(name){
    return document.getElementsByTagName('meta')[name].getAttribute("content");
  }

  function savePost() {
    var content = document.getElementsByClassName("ql-editor")[0].innerHTML;
    var title = document.getElementById("title").value;
    var meta_description = document.getElementById("meta_description").value;
    var meta_title = document.getElementById("meta_title").value;
    // var input_img = document.getElementById("input_img").value;
    var input_img = document.getElementById('input_img').files[0];

    var formData = new FormData();
    formData.append('title', title);
    formData.append('meta_description', meta_description);
    formData.append('content', content);
    formData.append('input_img', input_img);
    var token = getMetaContent('csrf-token');
    // post to route using XMLHTTPrequest (plain/vanilla js)
    var xhr = new XMLHttpRequest();   // new HttpRequest instance 
    xhr.open("POST", "/admin/blog/save");
    // xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.setRequestHeader("X-CSRF-TOKEN", token);

    // need to handle response here when it is saved using onreadystatechange
    xhr.onreadystatechange = function () {
      // status is good and it saved
      if(xhr.readyState === 4 && xhr.status === 200) {
        alert('Post has been saved!');
      }
      // if there was an error
      if (xhr.readyState === 4 && xhr.status === 422) {
          // for now we'll just log but eventually will handle something differently
          var errors = xhr.responseText;
        alert(errors);                          
      }
    };
    xhr.send(formData);
    // xhr.send(JSON.stringify({ "title": title, "content": content , "meta_title":meta_title, "meta_description": meta_description, "input_img":input_img}));
  }; 
</script>