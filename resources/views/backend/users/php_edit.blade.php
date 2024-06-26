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
                <h3 class="box-title">Add User</h3>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST" role="form">
                @csrf
                @method('put')
                <div class="box-body"> 
                    <div class="form-group">
                        <label for="name">Name <span class="text-red">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ $user->name }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email address <span class="text-red">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" value="{{ $user->email }}" required> 
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone <span class="text-red">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Enter phone" value="{{ $user->phone }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirmed_password">Confirmed Password</label>
                        <input type="password" class="form-control @error('confirmed_password') is-invalid @enderror" id="confirmed_password" name="confirmed_password" placeholder="Enter confirmed password">
                        @error('confirmed_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="user_edit_button">Submit</button>
                </div>
            <form>
        </div>
    </div>
</div>
@endsection

 