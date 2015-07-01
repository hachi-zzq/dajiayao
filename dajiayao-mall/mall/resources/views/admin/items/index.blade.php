@extends('layouts.master')

@section('title')单品管理@stop

@section('css')
    <style type="text/css">
        .media-body{
            overflow: hidden;zoom: 1;
        }

    </style>
@stop


@section('page-title')
    <div class="page-title">
        <h3>单品管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li class="active">单品管理</li>
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
                    <button type="button" class="btn btn-primary"><a href="{{route('adminItemsAdd')}}" style="color: white;">增加商品</a></button>
                    <button type="button" class="btn btn-success" id="btn-puton">上架</button>
                    <button type="button" class="btn btn-danger" id="btn-putoff">下架</button>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="row"><input type="checkbox" id="check-items-all"></th>
                                <th style="min-width: 120px;max-width: 300px;">商品名称</th>
                                <th style="min-width: 100px">编码</th>
                                <th style="min-width: 85px">销售价格</th>
                                <th style="min-width: 90px">供应商</th>
                                <th style="min-width: 100px">更新时间</th>
                                <th style="min-width: 85px">上架店铺</th>
                                <th style="min-width: 70px">总销量</th>
                                <th style="min-width: 70px">今销量</th>
                                <th style="min-width: 85px">允许上架</th>
                                <th style="min-width: 50px">在售</th>
                                <th style="min-width: 50px">直营</th>
                                <th style="min-width: 90px">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td scope="row">
                                        <label><div class="checker"><input type="checkbox" class="check-items-single"></div></label>
                                    </td>
                                    <td>
                                <span class="pull-left thumb-sm ">
                                    <img style="height:36px;padding-right: 4px;" src="{{url(Dajiayao\Library\Util\ImageUtil::getRuleImgSize($item->imgurl ,72,72))}}">
                                </span>
                                        {{$item->title.' '.$item->spec}}
                                    </td>
                                    <td>{{$item->code}}</td>
                                    <td>¥{{$item->price}}</td>
                                    <td>{{$item->supplier->title}}</td>
                                    <td>{{$item->updated_at->format('Y-m-d')}}</td>
                                    <td>{{$item->shopCount}}</td>
                                    <td>{{$item->saleCount}}</td>
                                    <td>{{$item->todaySaleCount}}</td>
                                    <td>
                                        @if($item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_YES)
                                            <span class="label label-success">{{"是"}}</span>
                                        @else
                                            <span class="label label-danger">{{"否"}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->sale_status == \Dajiayao\Model\Item::SALE_STATUS_YES)
                                            <span class="label label-success">{{"是"}}</span>
                                        @else
                                            <span class="label label-danger">{{"否"}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_direct_sale == \Dajiayao\Model\Item::IS_DIRECT_SALE_YES)
                                            <span class="label label-success">{{"是"}}</span>
                                        @else
                                            <span class="label label-danger">{{"否"}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('adminItemsUpdate', [$item->id])}}">编辑</a>
                                        @if($item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_YES)
                                            <a href="{{route('adminItemsShelfStatus', [$item->id])}}">下架</a>
                                        @else
                                            <a href="{{route('adminItemsShelfStatus', [$item->id])}}">上架</a>
                                        @endif
                                        <input type="hidden" class="hiddenItemId" value="{{$item->id}}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!!$items->appends(Input::all())->render()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop