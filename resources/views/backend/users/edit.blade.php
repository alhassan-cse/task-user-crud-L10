@extends('backend.layouts.app')
 
@section('content')
<style>
    .is-invalid {
     border-color: red;
     -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
     box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}
.invalid-feedback{
    color:red;
}
</style>
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit User</h3>
            </div> 
            <div class="box-body">
                <div class="panel panel-default message_show"></div> 
                <div class="form-group">
                    <label for="name">Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ $user->name }}" required>
                    <span class="name-invalid-feedback text-danger" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email address <span class="text-red">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" value="{{ $user->email }}" required> 
                    <span class="email-invalid-feedback text-danger" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Enter phone" value="{{ $user->phone }}" required>
                    <span class="phone-invalid-feedback text-danger" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password">
                    <span class="password-invalid-feedback text-danger" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="confirmed_password">Confirmed Password</label>
                    <input type="password" class="form-control @error('confirmed_password') is-invalid @enderror" id="confirmed_password" name="confirmed_password" placeholder="Enter confirmed password">
                    <span class="confirmed_password-invalid-feedback text-danger" role="alert"> </span>
                </div> 
                <input type="hidden" id="userURL" value="{{ route('users.update', $user->id) }}">
            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-primary" id="user_edit_button">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(".message_show").hide();
    $(document).on("click", "#user_edit_button", function() {
        var name =  $("#name").val();
        var email =  $("#email").val();
        var phone =  $("#phone").val();
        var password =  $("#password").val();
        var confirmed_password =  $("#confirmed_password").val();
        var userURL =  $("#userURL").val();
        
        if(name==''){
            var name_text = "Name is required.";
            $(".name-invalid-feedback").html(name_text);
            return false;
        }
        else{
            var name_text = "";
            $("name-invalid-feedback").html(name_text);
        }

        if(email==''){
            var email_text = "Email is required.";
            $(".email-invalid-feedback").html(email_text);
            return false;
        }
        else{ 
            // isEmail check
            if( !isEmail(email)) { 
                var email_text = "Email is not valid.";
                $(".email-invalid-feedback").html(email_text);
                return false;
            }
            var email_text = "";
            $(".email-invalid-feedback").html(email_text);
        }
        
        if(phone==''){
            var phone_text = "Phone is required.";
            $(".phone-invalid-feedback").html(phone_text);
            return false;
        }
        else{
            var phone_text = "";
            $(".phone-invalid-feedback").html(phone_text);
        }

        // if(password==''){
        //     var password_text = "Password is required.";
        //     $(".password-invalid-feedback").html(password_text);
        //     return false;
        // }
        // else{
        //     var password_text = "";
        //     $(".password-invalid-feedback").html(password_text);
        // }

        if(password !=''){
            var pswlen = password.length;
            if (pswlen < 8) {
                // alert('minmum  8 characters needed')
                var password_text = "Password minmum  8 characters needed.";
                $(".password-invalid-feedback").html(password_text);
                return false;
            }
        }
        
         
        // if(confirmed_password==''){
        //     var confirmed_password_text = "Confirmed password is required.";
        //     $(".confirmed_password-invalid-feedback").html(confirmed_password_text);
        //     return false;
        // }
        // else{
        //     var confirmed_password_text = "";
        //     $(".confirmed_password-invalid-feedback").html(confirmed_password_text);
        // }

        if(confirmed_password !=''){
            var con_pswlen = confirmed_password.length;
            if (con_pswlen < 8) {
                // alert('minmum  8 characters needed')
                var password_text = "Confirmed Password minmum  8 characters needed.";
                $(".confirmed_password-invalid-feedback").html(password_text);
                return false;
            }
        }
         
        if(password !=''){
            if(password != confirmed_password){
                var confirmed_password_text = "Confirmed password doesn\'t  match.";
                $(".confirmed_password-invalid-feedback").html(confirmed_password_text);
                return false;
            }
        } 
        else{
            var confirmed_password_text = "";
            $(".confirmed_password-invalid-feedback").html(confirmed_password_text);
        }

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        
        $.ajax({
            url:userURL,
            method:"PUT",
            data:{
                _token: "{{ csrf_token() }}",
                name:name,
                email:email,
                phone:phone,
                password:password,
                confirmed_password:confirmed_password
            },
            success: function(data){
                if(data.val){
                    var success_message_text = "User has been udated successfully.";
                    var class_name = "alert alert-success";
                    $(".message_show").html(success_message_text);
                    $(".message_show").addClass(class_name); 
                }
                else{
                    var error_message_text = "Something went wrong.";
                    var class_name = "alert alert-danger";
                    $(".message_show").html(error_message_text);
                    $(".message_show").addClass(class_name);
                }
                $(".message_show").show();
                setTimeout(function(){
                    $('.message_show').fadeOut();
                }, 3000);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Something went wrong');
            }
        });
    });
</script>
@endpush