@extends('layouts.master')
@section('title')摇一摇页面@stop
@section('page-title')
    <div class="page-title">
        <h3>摇一摇页面</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">摇一摇页面</li>
            </ol>
        </div>
    </div>
@stop
@section('css')
    <style type="text/css">
        .wx_page_content{
            width: 260px;
            height: 76px;
            padding: 10px;zoom: 1; background-color:#f5f5f5;
        }
        .wx_page_img_div{
            float: left;
            margin-right: 10px;
            line-height: 1;
        }
        a:hover{
            text-decoration: none;
        }
    </style>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white" style="min-width:900px;">
                <div class="panel-body">

                    @if(Session::has('result'))
                        @if(Session::get('result') == false)
                            <div class="alert alert-danger" role="alert">{{Session::get('msg')}}</div>
                        @else
                            <div class="alert alert-success" role="alert">{{Session::get('msg')}}</div>
                        @endif
                    @endif
                        <div id="error-tips" class="alert alert-danger" role="alert" style="display:none"></div>

                <div style="float: right; margin-left: 20px;">
                    <button type="button" class="btn btn-primary" id="btn-add-wxpage"><a style="color: white;" href="/admin/wxpages/add"><i class="fa fa-plus"></i>增加页面</a></button>
                </div>

                <form class="form-inline" style="float: left">
                    <div class="col-md-3" style="width: 100%;float:left;">
                        <select class="form-control" name="wx_mp_id">
                            <option value="0">全部</option>
                            @foreach($wxmps as $mp)
                                <option value="{{$mp->id}}" @if($wx_mp_id == $mp->id){{'selected="selected"'}}@endif>{{$mp->name}}</option>
                            @endforeach
                        </select>
                    <input type="text" style="width: 250px;" name="kw" class="form-control" id="filter_kw" placeholder="关键字" value="{{$kw}}"/>
                    <button type="submit" class="btn btn-info">筛选</button>
                    </div>

                </form>

                    <div class="table-responsive" style="overflow-x: inherit;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                                    <th>#</th>
                                @endif
                                <th>创建时间</th>
                                <th>页面</th>
                                <th>备注信息</th>
                                <th>设备数量</th>
                                <th>公众号</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wxpages as $page)
                                <tr>
                                    @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                                        <td scope="row">{{$page->id}}</td>
                                    @endif
                                    <td>{{date("Y-m-d",strtotime($page->created_at))}}</td>
                                    <td>
                                        <div class="wx_page_content">
                                            <div class="wx_page_img_div">
                                                <img width="56px" height="56px" src="{{$page->icon_url}}"/>
                                            </div>
                                            <div>
                                                <div style="margin-bottom: 12px;">
                                                    {{$page->title}}
                                                </div>
                                                <div>
                                                    {{$page->description}}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$page->comment}}</td>
                                    <td>{{$page->getDeviceCount()}}</td>
                                    <td>{{$page->mp->name}}</td>
                                    <td>
                                        <a href="/admin/wxpages/bind/{{$page->id}}">配置到设备</a>
                                        <a href="/admin/wxpages/update/{{$page->id}}">编辑</a>
                                        <a href="/admin/wxpages/delete/{{$page->id}}" class="wxpage-delete" onclick="return confirm('确认删除吗？')">删除</a>
                                        <input type="hidden" class="hiddenPageId" value="{{$page->id}}"/>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop