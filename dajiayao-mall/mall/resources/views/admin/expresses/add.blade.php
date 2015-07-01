@extends('layouts.master')

@section('title')物流公司管理@stop

@section('page-title')
    <div class="page-title">
        <h3>物流公司管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="{{route('expresses')}}">物流公司管理</a></li>
                <li class="active">增加</li>
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
                    <form class="form-horizontal" action="{{route('addExpress')}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">物流公司名称</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" id="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">物流公司代码</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="code" id="" maxlength="10">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">网址</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="website" id="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">电话</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="phone" id="" maxlength="11">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="sort" id="" maxlength="3">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定增加</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop