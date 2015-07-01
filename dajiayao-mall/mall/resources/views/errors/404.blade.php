@extends('layouts.nologin')
@section('title')大家摇 - 直销平台@stop
@section('body-class'){{'page-error'}}@stop
@section('content')
<main class="page-content">
    <div class="page-inner">
        <div class="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <h1 class="text-xxl text-primary text-center">404</h1>
                    <div class="details">
                        <h3>未找到</h3>
                        <p>您查找的页面不存在，返回<a href="{{route('adminIndex')}}">首页</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@stop