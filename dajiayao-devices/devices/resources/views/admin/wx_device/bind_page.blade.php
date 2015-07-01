@extends('layouts.master')
@section('title')微信设备管理@stop

@section('page-title')
    <div class="page-title">
        <h3>微信设备管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('adminWxDevicesIndex')}}">设备列表</a></li>
                <li class="active">绑定页面</li>
            </ol>
        </div>
    </div>
@stop
@section('css')
    <style type="text/css">
        .wx_page_content{
            width: 260px;
            height: 76px;
            padding: 10px;
            zoom: 1;
            background-color:#f5f5f5;
        }
        .wx_page_content .wx_page_img_div{
            float: left;margin-right: 10px;
        }
        .wx_page_content a:hover{
            text-decoration: none;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white" style="min-width:900px;">
                <div class="panel-body">
                    {{--<div id="flotchart1" style="display: none;"></div>--}}
                    {{--<div id="flotchart2" style="display: none;"></div>--}}
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
                    <div class="alert alert-danger alert-dismissible" id="error-tips" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <label style="margin-bottom: 0px;">操作失败</label>
                    </div>
                    <div class="alert alert-success alert-dismissible" id="success-tips" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        导出成功，请<a href="#" target="_blank" class="alert-link">点此下载</a>
                    </div>
                    <div role="tabpanel">
                        <form method="post" action="{{route('adminGetBindPages')}}">
                        <div style="padding: 10px 0 0 10px">
                            <button type="submit" class="btn btn-primary wx_device_apply"  data-toggle="modal">保存</button>
                            <a href="{{route('adminWxDevicesIndex')}}"><button type="button"  class="btn btn-success wx_device_apply"  data-toggle="modal">返回</button></a>
                        </div>

                        <!-- Tab Pane -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="row"><input type="checkbox" class="check-mail-all" id="select-all">#</th>
                                            <th>创建时间</th>
                                            <th>页面</th>
                                            <th>备注信息</th>
                                            <th>设备数量</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pages as $page)
                                            <tr>
                                                <td scope="row">
                                                    <label>
                                                        <span class="checker"><input type="checkbox" class="check-item"
                                                                                    name="page_id[]"
                                                                                    value="{{$page->id}}" @if($page->bind_status== 1)
                                                                                    checked @endif></span>{{$page->id}}
                                                    </label>
                                                </td>
                                                <td>{{date("Y-m-d",strtotime($page->created_at))}}</td>
                                                <td>
                                                    <div class="wx_page_content">
                                                        <a href="/admin/wxpages/update/{{$page->id}}">
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
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>{{$page->comment}}</td>
                                                <td>{{$page->getDeviceCount()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- Tab Pane 1 -->
                        </div>
                            <input type="hidden" name="device_id" value="{{$device_id}}">
                            </form>
                    </div>
                </div>
            </div><!-- panel -->
        </div><!-- col-md-12 -->
    </div><!-- Row -->



@stop

@section('js')
    <script src="/themeforest/js/pages/form-elements.js"></script>

    <script>
        $(function(){
            $("#select-all").click(function(){
                if($(this).parent('span').attr('class') !== 'checked'){
                    $(".check-item").parent("span").removeAttr("class", "checked");
                    $('.check-item').removeAttr('checked');
                }else{
                    $(".check-item").parent("span").attr("class", "checked");
                    $('.check-item').attr('checked','checked');
                }

            })

            $(".check-item").click(function(){
                if($(this).attr('checked') == 'checked'){
                    $(this).removeAttr('checked');
                }else{
                    $(this).attr('checked','checked');
                }
            })
        })
    </script>
@stop