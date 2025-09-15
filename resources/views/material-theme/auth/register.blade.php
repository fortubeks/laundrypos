@extends('material-theme.layouts.guest')
@section('contents')
<section>
    <div class="page-header min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                    <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../material/assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                    <div class="card card-plain">
                        <div class="card-header">
                            <h4 class="font-weight-bolder">Sign Up</h4>
                            <p class="mb-0">Start managing your laundry business with ease</p>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger text-white" role="alert">
                            <strong>Error!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if(isset($status))
                        <div class="alert alert-danger text-white" role="alert">
                            <strong>Error!</strong> {{$status}}
                        </div>
                        @endif
                        <div class="card-body">
                            <form role="form" action="{{route('register')}}" method="post">
                                @csrf
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="number" class="form-control" name="phone" value="{{old('phone')}}">
                                </div>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="form-check form-check-info text-start ps-0">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                                    </label>
                                </div>
                                <div class="text-center">
                                    <p class="d-none"><input type="text" name="myfield" value=""></p>
                                    <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Sign Up</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-2 text-sm mx-auto">
                                Already have an account?
                                <a href="{{route('login')}}" class="text-primary text-gradient font-weight-bold">Sign in</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection