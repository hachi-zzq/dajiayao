@extends('layouts.master')

@section('title')店铺管理@stop

@section('page-title')
    <div class="page-title">
        <h3>店铺管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="{{route('shops')}}">店铺管理</a></li>
                <li class="active">编辑</li>
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
                    <form class="form-horizontal" action="{{route('updateShop', $shop->id)}}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">店铺名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" id="" value="{{$shop->name}}" maxlength="128">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">头像</label>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="image" id="">
                                <p class="help-block">建议尺寸 120x120像素</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-md-4">
                                <textarea class="input-small form-control" id="comment" name="comment" rows="3">{{$shop->comment}}</textarea>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定修改</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop