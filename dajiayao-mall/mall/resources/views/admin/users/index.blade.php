@extends('layouts.master')
@section('title')用户管理@stop
@section('page-title')
    <div class="page-title">
        <h3>用户管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">用户管理</li>
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
                    <div style="float: right; margin-left: 100px;">
                        <button type="button" class="btn btn-primary" id="btn-add-wxpage"><a style="color: white;" href="{{route('addUser')}}"><i class="fa fa-plus"></i>增加用户</a></button>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="row">#</th>
                            <th>用户名</th>
                            <th>Email</th>
                            <th>角色</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td scope="row">
                                    {{$user->id}}
                                </td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->userRoleName()}}</td>
                                <td>
                                    @if($user->status == \Dajiayao\User::STATUS_NORMAL)
                                        <span class="label label-info">{{$user->statusName()}}</span>
                                    @else
                                        <span class="label label-primary">{{$user->statusName()}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(\Auth::user()->username == 'admin')
                                        <a href="{{route('updateUser',array('id'=>$user->id))}}" title="编辑"><span class="fa fa-edit"></span></a>
                                        <a href="{{route('manualLogin',array('id'=>$user->id))}}" title="登录"><span class="fa fa-sign-in"></span></a>
                                        <a href="{{route('updatePassword',array('id'=>$user->id))}}" title="修改密码"><span class="fa fa-key"></span></a>
                                    @endif
                                    @if($user->role == \Dajiayao\User::ROLE_SUPPLIER)
                                        @if($user->status == \Dajiayao\User::STATUS_NORMAL)
                                            <a href="{{route('updateUserStatus',array('id'=>$user->id))}}" title="冻结"><span class="fa fa-lock"></span></a>
                                        @elseif($user->status == \Dajiayao\User::STATUS_DISABLED)
                                            <a href="{{route('updateUserStatus',array('id'=>$user->id))}}" title="解冻"><span class="fa fa-unlock"></span></a>
                                        @endif
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