@extends('layout')

@section('title', 'My Profile')

@section('extra-css')
@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <div>
                <a href="/">Home</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <span class="visited">My Profile</span>
            </div>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container profile-section">
        <div class="sidebar">
            <div class="inner-sidebar">
                <ul>
                    @if (request()->url() == route('profile.edit'))
                        <li class="active">My Profile</li>
                    @else
                        <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
                    @endif

                    @if (request()->url() == route('orders.index'))
                        <li class="active">My Orders</li>
                    @else
                        <li><a href="">My Orders</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="products-section-all">
            <h1 class="stylish-heading">My Profile</h1>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Your Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Your Name">

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('name') }}
                        </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">Your Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Your Email Address">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('email') }}
                        </span>
                    @enderror
                </div>

                <span class="hr"></span>


                <h3>Change Password</h3>

                <span class="input-note">Leave these fields blank to keep the current password</span>

                <div class="form-group row">
                    <label for="old_password" class="col-md-4 col-form-label text-md-right">Your Old Password</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Your Old Password">

                    @error('old_password')  
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('old_password') }}
                        </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="new_password" class="col-md-4 col-form-label text-md-right">Your New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Your New Password">

                    @error('new_password')
                        <span class="invalid-feedback" role="alert">
                            {{ $errors->first('new_password') }}
                        </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Your New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Your New Password">
                </div>

                <div class="spacer"></div>
                <button type="submit" class="auth-button">Update Profile</button>
            </form> <!-- end form -->
        </div>
    </div> <!-- end product-section -->

@endsection

@section('extra-js')
@endsection

