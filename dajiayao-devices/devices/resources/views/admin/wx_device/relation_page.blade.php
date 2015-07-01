@extends('layouts.master')
@section('title')设备信息@stop
@section('page-title')
    <div class="page-title">
        <h3>设备-页面信息</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('adminWxDevicesIndex')}}">设备</a></li>

                <li class="active">设备信息</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">

                    @if(Session::has('result') && Session::get('result') == true)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('msg')}}
                        </div>
                    @endif
                    @if(Session::has('result') && Session::get('result') == false)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('msg')}}
                        </div>
                    @endif
                        <div style="float: right; margin-left: 20px;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>增加页面</button>
                            {{--<button type="button" class="btn btn-primary" id="btn-add-wxpage"><i class="fa fa-plus"></i>增加页面</button>--}}
                        </div>

                        <div class="table-responsive" style="overflow-x: inherit;">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>页面</th>
                                    <th>备注信息</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bind_pages as $bind_page)
                                    <tr id="page-bind-row-{{$bind_page->page->id}}">
                                        <td>{{$bind_page->page->id}}</td>
                                        <td>
                                            <div class="wx_page_content">
                                                <div class="wx_page_img_div" style="float: left;">
                                                    <img width="56px" height="56px" src="{{$bind_page->page->icon_url}}"/>
                                                </div>
                                                <div style="float:left;margin: 6px">
                                                    <div style="margin-bottom: 12px">
                                                        {{$bind_page->page->title}}
                                                    </div>
                                                    <div>
                                                        {{$bind_page->page->description}}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                        <td>{{$bind_page->page->comment}}</td>

                                        <td>
                                            <a href="#" class="page-unbind" data-id="{{$bind_page->page->id}}">删除</a></button>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <input type="hidden" name="device_id" id="device_id" value="{{$device_id}}">
                        </div>
                </div>
            </div>
            <!-- panel -->
        </div>
        <!-- col-md-12 -->
    </div><!-- Row -->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="window.location.reload()">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">页面列表</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>页面</th>
                            <th>备注信息</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pages as $page)
                            <tr id="page-row-{{$page->id}}">
                                <td>{{$page->id}}</td>
                                <td>
                                    <div class="wx_page_content">
                                        <div class="wx_page_img_div" style="float: left;">
                                            <img width="56px" height="56px" src="{{$page->icon_url}}"/>
                                        </div>
                                        <div style="float:left;margin: 6px">
                                            <div style="margin-bottom: 12px">
                                                {{$page->title}}
                                            </div>
                                            <div>
                                                {{$page->description}}
                                            </div>
                                        </div>
                                    </div>
                                </td>


                                <td>{{$page->comment}}</td>

                                <td>
                                    <a href="#" class="page-bind" data-id="{{$page->id}}">增加</a></button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">关闭</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="/themeforest/js/pages/form-elements.js"></script>

    <script>
        $(function(){
            $(".page-bind").click(function(){
                var pageId = $(this).attr("data-id");
                var deviceId = $("#device_id").attr('value');
                $.ajax({
                    'url':'/admin/wx_devices/bind_page',
                    'data':"page_id="+pageId+"&device_id="+deviceId+"&flag=1",
                    'success':function(ret){
                        console.log(ret);
                        alert(ret.message);
                        if(ret.msgcode == 0){
                            $("#page-row-"+pageId).remove()
                        }
                    },
                    'error':function(){
                        alert('ajax error');
                    }
                })
            })

            $(".page-unbind").click(function(){
                var pageId = $(this).attr("data-id");
                var deviceId = $("#device_id").attr('value');
                $.ajax({
                    'url':'/admin/wx_devices/bind_page',
                    'data':"page_id="+pageId+"&device_id="+deviceId+"&flag=0",
                    'success':function(ret){
                        console.log(ret);
                        alert(ret.message);
                        if(ret.msgcode == 0){
                            $("#page-bind-row-"+pageId).remove()
                        }
                    },
                    'error':function(){
                        alert('ajax error');
                    }
                })
            })
        })
    </script>
@stop
