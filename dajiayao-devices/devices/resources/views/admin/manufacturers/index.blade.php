@extends('layouts.master')
@section('title')厂商管理@stop
@section('page-title')
    <div class="page-title">
        <h3>厂商管理</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">厂商管理</li>
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
                    <div style="float: right; margin-left: 100px;">
                        <button type="button" class="btn btn-primary" id="btn-add-wxpage"><a style="color: white;" href="{{route('addManufacturer')}}"><i class="fa fa-plus"></i>增加厂商</a></button>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="row">#</th>
                            <th>名称</th>
                            <th>网址</th>
                            <th>地址</th>
                            <th>Email</th>
                            <th>电话</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($manufacturers as $manufacturer)
                            <tr>
                                <td scope="row">
                                    <label><div class="checker"></div>{{$manufacturer->id}}</label>
                                </td>
                                <td>{{$manufacturer->name}}</td>
                                <td>
                                    @if($manufacturer->website)
                                    <a href="{{$manufacturer->website}}" target="_blank" title="{{$manufacturer->website}}"><span class="fa fa-globe"></span></a>
                                    @endif
                                </td>
                                <td>{{$manufacturer->address}}</td>
                                <td>
                                    <a href="mailto:luyu@dajiayao.cc"></a>
                                    {{$manufacturer->email}}
                                </td>
                                <td>{{$manufacturer->phone}}</td>
                                <td>{{$manufacturer->comment}}</td>
                                <td>
                                    <a href="{{route('updateManufacturer',array('id'=>$manufacturer->id))}}" title="编辑"><span class="fa fa-edit"></span></a>
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