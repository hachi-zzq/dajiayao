@extends('layouts.master')

@section('title')增加页面@stop

@section('page-title')
    <div class="page-title">
        <h3>摇一摇页面</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="/admin/wxpages">摇一摇页面</a></li>
                <li class="active">增加页面</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-body">
                @if(Session::has('result') && Session::get('result') == false)
                <div class="alert alert-danger" role="alert">{{Session::get('msg')}}</div>
                @endif
                <form class="form-horizontal" action="/admin/wxpages/add" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">微信公众号</label>
                        <div class="col-md-4">
                            <select class="form-control m-b-sm" name="wx-mp-id">
                                @foreach($wxmps as $mp)
                                    <option value="{{$mp->id}}">{{$mp->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">缩略图</label>
                        <div class="col-md-4">
                            <input type="file" name="file-icon" id="file-icon"/>
                            <p class="help-block">建议尺寸 120x120像素</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">主标题</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="txt-title"/>
                            <p class="help-block">不超过6个字</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">副标题</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="txt-subtitle"/>
                            <p class="help-block">不超过7个字</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">跳转URL</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="请输入自定义URL" name="txt-url"/>
                            <p class="help-block">建议页面大小不超过200KB</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注信息</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="请输入备注信息" name="txt-comment"/>
                            <p class="help-block">建议填写，方便页面配置设备</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定</button>
                            <button type="button" class="btn btn-default" onclick="history.back(-1);" style="margin-left: 20px;">取消</button>

                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                </form>
            </div>
        </div>
    </div>
</div>
@stop