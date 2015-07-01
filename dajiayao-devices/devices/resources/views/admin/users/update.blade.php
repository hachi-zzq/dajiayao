@extends('layouts.master')
@section('title')更新用户信息@stop

@section('page-title')
    <div class="page-title">
        <h3>更新用户信息</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li><a href="{{route('deviceModels')}}">用户管理</a></li>
                <li class="active">更新用户信息</li>
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

                    <form class="form-horizontal" action="{{route('updateUser',array('id'=>$user->id))}}" method="post">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-10">
                                <p class="help-block">{{$user->username}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="{{$user->email}}">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" name="_token" value="{!! csrf_token()!!}"/>
                                <button type="submit" class="btn btn-success">提交</button>
                                <button type="button" class="btn btn-default" onclick="history.back(-1);" style="margin-left: 20px;">取消</button>

                            </div>
                        </div>
                    </form>

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