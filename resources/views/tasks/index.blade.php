<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
     body {
      background-color: #f8f9fa;
    }

    .form-container,
    .table-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    }

    .form-container h2,
    .table-container h3 {
      margin-bottom: 20px;
      text-align: center;
    }

    .form-control:focus {
      border-color: #80bdff;
      box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .dataTables_wrapper .dataTables_length select {
      padding: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
      min-width: 50px;
    }
    #des{
      resize:none;
    }
  </style>
  </head>
  <body>
    <div class="container mt-4">
  <div class="row">
  <div class="col-md-12">
      <div class="table-container">
        <h3>All Tasks</h3>
        <table id="myTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>id</th>
              <th>Title</th>
              <th>Priority</th>
              <th>Due Date</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data will be populated dynamically -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-container">
        <h2>Add Your Task</h2>
        <form id="taskform" name="taskform" method="post">
          <div class="row">
          <div class="col-6 mb-2">
          
          
        <label for="name" class="form-label">Task Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Task Title">
        <div id="title_error" class="text-danger error"></div>
      </div>
      <div class="col-6 mb-3">
        <label for="priority" class="form-label">Task Priority </label>
        <select name="priority" id="priority" class="form-control">
          <option value="1">Default</option>
          <option value="2">Important</option>
          <option value="3">Most Important</option>
        </select>
      </div>

      <div class="mb-2">
        <label for="due_date" class="form-label">Due Date</label>
        <input type="date" class="form-control" name="due_date" id="due_date">
        <div id="due_date_error" class="text-danger error"></div>
      </div>
      <div class="mb-3">
        <label for="des" class="form-label">Description</label>
        <textarea name="description" id="des" rows="3" class="form-control"></textarea>
        <div id="description_error" class="text-danger error"></div>
      </div>
      
      <div class="mb-3 idclass">
      <input type="hidden" name="id" value="" class="form-control m-input">
      </div>
      <button id="btntask" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.min.js"></script>
<script>
  // let table = new DataTable('#myTable');
</script>
<script>
   $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#myTable').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ url('') }}",
           columns: [
                    { data: 'id'},
                    { data: 'title'},
                    { data: 'priority' },
                    { data: 'due_date'},
                    { data: 'description'},
                    {data: 'action'}
                 ],
                 order: [[0, 'desc']]
       });
      //  $.fn.dataTable.ext.errMode = 'throw';




    $('#taskform').submit(function(e) {
let table = new DataTable('#myTable');
e.preventDefault();

var form = new FormData(this);
// alert('sasda');
$.ajax({
   type:'POST',
   url: "{{ url('save')}}",
   data: form,
   cache:false,
   contentType: false,
   processData: false,
   
   success: (data) => {
    if (data.errors) {
      alert('sdasd');
            // Display validation errors to the user
            $.each(data.errors, function(field, errors) {
                // Show errors for each field
                $('#'+field+'_error').html(errors.join('<br>'));
            });
        }
    this.reset();
    table.ajax.reload();
    $('.idclass').empty();
    $('#btntask').html('Save');
    $('#title_error').html('');
    $('#description_error').html('');
    $('#due_date_error').html('');
    
   },
   error: function(data) {
        // Handle error response
        if (data.status === 422) {
            // If validation errors, display them
            var errors = data.responseJSON.errors;
            $.each(errors, function(field, fieldErrors) {
                $('#'+field+'_error').html(fieldErrors.join('<br>'));
            });
        } else {
          console.log(data);
            // Handle other types of errors
        }
    }
  //  error: function(data){
  //     console.log(data);
  //   }
  });
});


$(document).ready(function() {
  $(document).on('click', '.editform', function() {
    var $id = $(this).data('id');
    $.ajax({
        type:"get",
        url: "{{ url('edit') }}"+"/"+$id,
        dataType: 'json',
        success: function(res){
          $('#title').val(res.title);
          $('#priority').val(res.priority);
          $('#due_date').val(res.due_date);
          $('#des').val(res.description);
          $('#des').focus();
          $('#btntask').html('Update');
          // Assuming res.id contains the value you want to set for the input's value attribute
// $('.idclass').remove('<input type="hidden" name="id" value="' + res.id + '" class="form-control m-input">');
$('.idclass').empty().append('<input type="hidden" name="id" value="' + res.id + '" class="form-control m-input">');



       }
    });
  });


  /////////////////////////////////////////////////////////////////////////
  $(document).on('click', '.deletetask', function() {
    if (confirm("Delete Task?") == true) {
    let table = new DataTable('#myTable');
    var $id = $(this).data('id');
    $.ajax({
        type:"get",
        url: "{{ url('delete') }}"+"/"+$id,
        dataType: 'json',
        success: function(res){
          $('.modal').removeClass('show');
          // $(".modal .close").click()
          table.ajax.reload();


       }
    });
  }
  });


});


</script>
  </body>
</html>