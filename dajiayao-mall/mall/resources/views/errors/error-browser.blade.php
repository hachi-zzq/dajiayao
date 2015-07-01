@extends('layouts.nologin')
@section('title')大家摇 - 直销平台@stop
@section('body-class'){{'page-error'}}@stop
@section('content')
<main class="page-content">
    <div class="page-inner">
        <div class="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <div class="details">
                        <h3>浏览器版本过低</h3>
                        <p>建议您使用<a href="http://rj.baidu.com/soft/detail/14744.html?ald">Chrome浏览器</a>访问。返回<a href="{{route('adminIndex')}}">首页</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@stop
