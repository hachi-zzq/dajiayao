@extends('layouts.master')

@section('title')增加店主@stop

@section('page-title')
    <div class="page-title">
        <h3>增加店主</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li><a href="{{route('sellers')}}">店主管理</a></li>
                <li class="active">增加店主</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-body">
                @include('layouts.tips')
                <form class="form-horizontal" action="{{route('addSellerPost')}}" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">手机号</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="mobile" id="mobile" maxlength="11">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">姓名</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="realname" id="realname" maxlength="64">
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">确定增加</button>
                        <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop