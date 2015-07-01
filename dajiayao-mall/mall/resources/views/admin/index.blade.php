@extends('layouts.master')

@section('title')
    Home
@stop

@section('page-title')
<div class="page-title">
    <h3>首页</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{route('adminIndex')}}">Home</a></li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">233</p>
                    <span class="info-box-title">Count</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-bag"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">233</p>
                    <span class="info-box-title">Count</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-film"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">233</p>
                    <span class="info-box-title">Count</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-puzzle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">233</p>
                    <span class="info-box-title">Count</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-docs"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@stop