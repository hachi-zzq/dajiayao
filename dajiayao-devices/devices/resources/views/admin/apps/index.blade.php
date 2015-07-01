@extends('layouts.master')
@section('title')应用管理@stop

@section('page-title')
    <div class="page-title">
        <h3>应用管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">应用管理</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white" style="min-width:900px;">

                <div class="panel-body">
                    @include('layouts.tips')
                    @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                    <div style="float: right; margin-left: 100px;">
                        <button type="button" class="btn btn-primary" id="btn-add-wxpage"><a style="color: white;" href="{{route('addApp')}}"><i class="fa fa-plus"></i>增加应用</a></button>
                    </div>
                    @endif
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="row">#</th>
                            <th>名称</th>
                            <th>类型</th>
                            <th>设备</th>
                            <th>APP_ID</th>
                            <th>APP_SECRET</th>
                            <th>用户</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($apps as $app)
                            <tr>
                                <td scope="row">
                                    <label><div class="checker"></div>{{$app->id}}</label>
                                </td>
                                <td>{{$app->name}}</td>
                                <td>{{$app->typeName()}}</td>
                                <td>
                                    @if($app->type==\Dajiayao\Model\App::TYPE_WEIXIN and $app->getMp())
                                    <a href="{{route('adminWxDevicesIndex')}}?wx_mp_id={{$app->getMp()->id}}">查看</a>
                                    @endif
                                </td>
                                <td>{{$app->app_id}}</td>
                                <td>{{$app->app_secret}}</td>
                                <td>{{{$app->user->username}}}</td>

                                <td>
                                @if($app->status == \Dajiayao\Model\App::STATUS_NORMAL)
                                    <span class="label label-info">{{$app->statusName()}}</span>
                                @else
                                    <span class="label label-primary">{{$app->statusName()}}</span>
                                @endif
                                </td>

                                <td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                                    @if($app->status == \Dajiayao\Model\App::STATUS_NORMAL)
                                        <a href="{{route('updateAppStatus',array('id'=>$app->id))}}" title="冻结"><span class="fa fa-lock"></span></a>
                                    @elseif($app->status == \Dajiayao\Model\App::STATUS_LOCKED)
                                        <a href="{{route('updateAppStatus',array('id'=>$app->id))}}" title="解冻"><span class="fa fa-unlock"></span></a>
                                    @endif
                                    @endif
                                    <a href="{{route('updateApp',array('id'=>$app->id))}}" title="编辑"><span class="fa fa-edit"></span></a>
                                        @if($app->type==\Dajiayao\Model\App::TYPE_WEIXIN)
                                        <a href="{{route('updateAppMp',array('id'=>$app->id))}}" title="编辑公众号"><span class="fa fa-wechat"></span></a>
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- panel -->
        </div>
        <!-- col-md-12 -->
    </div><!-- Row -->
@stop

@section('js')
    <script type="text/javascript">
    </script>
@stop