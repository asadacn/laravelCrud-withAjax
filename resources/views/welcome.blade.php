<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel Crud') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        {{-- Bootstrap --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        
<style>
body{
font-family: Nunito
}
</style>
    </head>
    <body>
       <div class="container p-4">
           
           <div class="card">
             <div class="card-header">
              <h1 class="h3 text-uppercase">Laravel Crud - Contact App</h1>
             </div>
               <div class="card-body">
            <div class="form-row mb-2">
                <div class="col-auto mb-2"> <a id="add" class="btn  btn-primary border" href="#" data-toggle="modal" data-target="#contactFormModal" >CREATE CONTACT</a></div>
                <div class="col-sm-8"><input class="form-control" type="text" name="search" id="search" placeholder="Search contact..."></div>
            </div>
            <div class="table-responsive border p-2 rounded">
                <h4 class="h4 text-uppercase">Contact list</h4>
            <table class="table table-striped">
                <thead class="text-uppercase">
                  <tr >
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contacts as $contact)
                  <tr>
                  <th scope="row">{{++$i}}</th>
                    <td>{{$contact->name}}</td>
                    <td>{{$contact->contact}}</td>
                    <td>{{$contact->email}}</td>
                    <td>{{$contact->address}}</td>
                    <td><div class="btn-group" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-light border-white" onclick="edit({{$contact->id}})"><img src="{{asset('edit.png')}}" alt="EDIT" width="20px"> </button>
                      <button type="button" class="btn btn-sm btn-light border-white" onclick="del({{$contact->id}})"><img src="{{asset('delete.png')}}" alt="DELETE" width="20px"> </button>
                    </div></td>
                  </tr>
                  @endforeach
                 
                </tbody>
              </table>
            </div>
        </div>
           </div>
       </div>


  <!-- Modal -->
  <div class="modal fade" id="contactFormModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="contactFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contactFormModalLabel">Contact Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="contactForm">
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="Contact name" required>
                </div>
                <div class="form-group">
                  <label for="contact">Contact No. <span class="text-danger">*</span></label>
                  <input type="text" minlength="11" maxlength="13" name="contact" class="form-control" id="contact" placeholder="Contact number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email address">
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea  name="address" class="form-control" id="address" placeholder="Contact address"></textarea>
                  </div>
                  <input id="cid" type="hidden" name="id">
                  <div class="form-group">
                    <a id="saveBtn"  class="btn btn-primary text-light w-100">Save Contact</a>
                    <a id="updateBtn"  class="btn btn-primary text-light w-100">Update Contact</a>
                  </div>
              </form>
              <div id="message" class="" style="display: none">
                {{-- Validation Errors --}}
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
//Create contact
  $('#saveBtn').on('click',function(event) {
    event.preventDefault();

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax(
        {
        url: "{{route('create')}}",
        type: 'POST', 
        dataType: "JSON",
        data:$('#contactForm').serialize(),
        success: function (response)
        {

        if (response.success) {
          $('#contactFormModal').modal('hide');
          Swal.fire({
          title:'Saved!',
          text:'Contact successfully saved',
          icon:'success',
          onClose: () => {
            document.getElementById("contactForm").reset();
            location.reload(true);
        }}
        );
        }
        console.log(response);  
        },
        error: function(xhr) {
          $("#message").html('');
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            $("#message").append("<li class='alert alert-danger'>"+item+"</li>").show();
          });

        console.log(xhr.responseText); 
      }
      });
  });

  //Update Contact
  $('#updateBtn').on('click',function(event) {
    event.preventDefault();
    
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax(
        {
        url: "{{url('contact/update')}}"+'/'+$('#cid').val(),
        type: 'PUT', 
        dataType: "JSON",
        data:$('#contactForm').serialize(),
        success: function (response)
        {

        if (response.success) {
          $('#contactFormModal').modal('hide');
          Swal.fire({
          title:'Saved!',
          text:'Contact successfully saved',
          icon:'success',
          onClose: () => {
            document.getElementById("contactForm").reset();
            location.reload(true);
        }}
        );
        }
        console.log(response);  
        },
        error: function(xhr) {
          $("#message").html('');
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            $("#message").append("<li class='alert alert-danger'>"+item+"</li>").show();
          });

        console.log(xhr.responseText); 
      }
      });
  });

//EDIT CONTACT INFO
  function edit(id) { 

    $("#updateBtn").show();
    $("#saveBtn").hide();
    $("#message").html('');
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax(
        {
        url: "{{url('/contact/get')}}"+'/'+id,
        type: 'GET', 
        dataType: "JSON",
        success: function (response)
        {
          let contact = response.contact;
          $('#cid').val(contact.id);
          $('#name').val(contact.name);
          $('#contact').val(contact.contact);
          $('#email').val(contact.email);
          $('#address').val(contact.address);
          $('#contactFormModal').modal('show');
        
          console.log(response)
        },
        error: function(xhr){
          console.log(xhr.responseText)
        }
        });
  }

 //Delete Contact Info
  function del(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#08cc0f',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax(
        {
        url: "{{url('contact/delete')}}"+'/'+id,
        type: 'DELETE', 
        dataType: "JSON",
        data: {
            "id": id 
        },
        success: function (response)
        {
          Swal.fire({
          title:'Deleted!',
          text:response.success,
          icon:'success',
          onClose: () => {
            location.reload(true);
        }}
        );
        console.log(response);  
        },
        error: function(xhr) {
        console.log(xhr.responseText); 
      }
      });
      }
      })
  }

//Search Contact
 $('#search').on('keyup',function(){
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $.ajax(
        {
        url: "{{route('search')}}",
        type: 'GET', 
        dataType: "json",
        data:{key:$(this).val()},
        success: function (response)
        {
          $('tbody').html(response.html);
        },
        error:function(xhr){
        console.log(xhr.responseText);
        }

 })
 });
 //Toggle buttons
  $('#add').on('click',function(){
    $("#updateBtn").hide();
      $("#saveBtn").show();
  })
//reset form
  $('#contactFormModal').on('hidden.bs.modal', function (e) {
            document.getElementById("contactForm").reset();
            $("#message").html('');
          })
</script>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
