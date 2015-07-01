@extends('layouts.nologin')
@section('title')大家摇 - 直销平台登录@stop
@section('body-class'){{'page-login'}}@stop
@section('content')
<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-3 center">
                    <div class="login-box">
                        <a href="/" class="logo-name text-lg text-center">丫摇直销中心</a>
                        <p class="text-center m-t-md">请登录您的账号</p>
                        @if(Session::has('error'))
                            <div class="alert alert-danger" role="alert">{{Session::get('err_msg')}}</div>
                        @endif

                        <form class="m-t-md" action="login" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            </div>

                            <button type="submit" class="btn btn-success btn-block">Login</button>
                            <a href="#" class="display-block text-center m-t-md text-sm">Forgot Password?</a>
                            {{--<p class="text-center m-t-xs text-sm">Do not have an account?</p>--}}
                            {{--<a href="#" class="btn btn-default btn-block m-t-md">Create an account</a>--}}
                        </form>
                        <p class="text-center m-t-xs text-sm">2015 &copy; Modern by Steelcoders.</p>
                    </div>
                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->
</main><!-- Page Content -->
@stop