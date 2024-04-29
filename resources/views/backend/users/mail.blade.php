@extends('backend.layouts.app')
 
@section('content')
<style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
<div class="box">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12"> 
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th> 
                        <th>Email</th>
                        <th>Phone</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table> 
        </div>
    </div>
</div>
 
<!-- Modal -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Email Send</h4>
            </div>
            
            <div class="modal-body">
                <div class="panel panel-default message_show d-none"></div> 
                <input type="hidden" class="form-control" id="name" name="name">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject">
                </div>
                <div class="form-group">
                    <label for="message">Message Body</label>
                    <textarea type="email" class="form-control" id="message" name="message" placeholder="Enter message" rows="6" cols="50"></textarea> 
                </div>
                <div class="text-center">
                    <i class="fa fa-refresh fa-spin loading-option d-none"></i>
                </div>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="email_send_button">Send</button>
            </div>
        </div> 
    </div>
</div> 
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
 
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                // scrollX: true,
            //"order": [[ 0, "desc" ]],
            ajax: "{{ route('users.index') }}",

            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

    $(document).on("click","#email_send",function() {
        $(".loading-option").hide();
        var data = $(this).attr('data-id');
        var data = data.split('_');
        $("#email").val(data[0]);
        $("#name").val(data[1]);
    }); 
 
</script> 

<script>
    $(document).on("click","#email_send_button",function() {
        $(".loading-option").show();
        var name =  $("#name").val();
        var email =  $("#email").val();
        var subject =  $("#subject").val();
        var message =  $("#message").val();
        $.ajax({
            url:"{{ route('user.mail.send') }}",
            method:"POST",
            data:{
                _token: "{{ csrf_token() }}",
                name:name,
                email:email,
                subject:subject,
                message:message
            },
            success: function(data){
                // alert('Email Send Successfully'); 
                $(".message_show").removeClass('d-none');
                var html = "Email Send Successfully";
                var class_name = "alert alert-success";
                $(".message_show").html(html);
                $(".message_show").addClass(class_name);
                $(".loading-option").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // alert('Something went wrong');
                var html = "Something went wrong.";
                var class_name = "alert alert-danger";
                $(".message_show").html(html);
                $(".message_show").addClass(class_name);
                $(".loading-option").hide();
            }
            
        });
    }); 
    $(document).on("click","#destroy",function(e) { 
        e.preventDefault();
        var id = $(this).attr('data-id');
        var userURL = $(this).data('url');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
                $.ajax({
                    url:userURL,
                    type: 'DELETE',
                    dataType: 'json',
                    data:{
                        _token: "{{ csrf_token() }}",
                        id:id
                    },
                    success:function(data){
                       // alert(data.success);
                       // table.draw();
                    //    table.ajax.reload();
                         $('.data-table').DataTable().ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                       alert(textStatus, errorThrown);
                    }
                });
                
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    icon: "error"
                });
            }
        });
    });
</script>
@endpush

