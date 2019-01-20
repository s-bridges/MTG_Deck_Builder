@extends('layouts.app')

@section('content')
 
   <div class="container">
      <div class="row">
        <h1>Blog Post</h1>
      </div>

      <!-- Create the editor container -->
      <div id="editor">
        <p>Hello World!</p>
        <p>Some initial <strong>bold</strong> text</p>
        <p><br></p>
      </div>
      <button onclick="savePost()" type="button" class="btn btn-outline-primary float-right">Save</button><br>
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

  function savePost() {
    var content = window.quill.getContents();
    console.log(content);
    // console.log(markupStr);
    // post to route using XMLHTTPrequest (plain/vanilla js)
    var xhr = new XMLHttpRequest();   // new HttpRequest instance 
    xhr.open("POST", "/blog/save");
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

    // need to handle response here when it is saved using onreadystatechange
    xhr.onreadystatechange = function () {
      // status is good and it saved
      if(xhr.readyState === 4 && xhr.status === 200) {
        console.log(xhr.responseText);
      }
      // if there was an error
      if (xhr.readyState === 4 && xhr.status === 403) {
          // for now we'll just log but eventually will handle something differently
        console.log(xhr.responseText);                           
      }
    };

    xhr.send(JSON.stringify({ "email": "hello@user.com", "content": content }));
  }; 

</script>
<!-- <script>

        // this is what's used to get the contents of the html element with the id of summernote,
        // that will be stored as a string in the database
        // this needs to be surrounded by a click function
        function savePost() {
          var markupStr = document.getElementsByClassName('note-editable')[0].innerHTML;
          console.log(markupStr);
          // post to route using XMLHTTPrequest (plain/vanilla js)
          var xhr = new XMLHttpRequest();   // new HttpRequest instance 
          xhr.open("POST", "/blog/save");
          xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

          // need to handle response here when it is saved using onreadystatechange
          xhr.onreadystatechange = function () {
            // status is good and it saved
            if(xhr.readyState === 4 && xhr.status === 200) {
              console.log(xhr.responseText);
            }
            // if there was an error
            if (xhr.readyState === 4 && xhr.status === 403) {
               // for now we'll just log but eventually will handle something differently
              console.log(xhr.responseText);                           
            }
          };

          xhr.send(JSON.stringify({ "email": "hello@user.com", "content": markupStr }));
        }; 
      </script> -->