<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>同步微信数据</title>

</head>
<body>

@extends('layouts.master')

@section('page-title')
    <div class="page-title">
        <h3>同步微信</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">同步微信</li>
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
                    <form class="form-horizontal" action="{{route('adminPostSyncWeixin')}}" method="post">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">公众号</label>
                            <div class="col-md-4">
                                <select class="form-control m-b-sm" name="mp">
                                    @foreach($mps as $mp)
                                    <option value="{{\Dajiayao\Model\WeixinMp::find($mp)->id}}">{{\Dajiayao\Model\WeixinMp::find($mp)->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-4">
                                <input type="hidden" name="_token" value="{!! csrf_token()!!}"/>
                                <button type="submit" class="btn btn-success">同步</button>
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