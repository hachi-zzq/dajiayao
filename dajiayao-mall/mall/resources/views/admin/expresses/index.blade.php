@extends('layouts.master')

@section('title')物流公司管理@stop

@section('page-title')
<div class="page-title">
    <h3>物流公司管理</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">物流公司管理</li>
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
                <button type="button" class="btn btn-primary"><a href="{{route('addExpress')}}" style="color: white;">增加物流公司</a></button>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>排序</th>
                            <th>物流公司</th>
                            <th>物流公司代码</th>
                            <th>物流公司网址</th>
                            <th>电话</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expresses as $express)
                        <tr>
                            <td>{{$express->sort}}</td>
                            <td>{{$express->name}}</td>
                            <td>{{$express->code}}</td>
                            <td><a href="{{$express->website}}">{{$express->website}}</a></td>
                            <td>{{$express->phone}}</td>
                            <td>
                                <a href="{{route('updateExpress',array('id'=>$express->id))}}">编辑</a>
                                <a href="{{route('deleteExpress',array('id'=>$express->id))}}" onclick="return confirm('确认删除？')">删除</a>
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