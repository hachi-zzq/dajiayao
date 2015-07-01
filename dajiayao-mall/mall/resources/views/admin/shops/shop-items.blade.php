@extends('layouts.master')

@section('title')店铺管理@stop

@section('page-title')
    <div class="page-title">
        <h3>店铺管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li class="active">店铺管理</li>
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



                    <div class="table-responsive">
                        <form class="form-inline" action="{{route('addItems',array('id'=>$shop->id))}}" method="post">
                            <div class="col-md-3" style="width: 100%;">
                                <input type="text" style="width: 250px;" name="item-code" class="form-control" id="filter_kw" placeholder="商品编码" value=""/>
                                <button type="submit" class="btn btn-info">增加</button>
                            </div>

                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="row"><input type="checkbox" id="check-items-all"></th>
                                <th style="min-width: 50px">排序</th>
                                <th style="min-width: 120px;max-width: 300px;">商品名称</th>
                                <th style="min-width: 90px">编码</th>
                                <th style="min-width: 90px">销售价格</th>
                                <th style="min-width: 90px">上架状态</th>
                                <th style="min-width: 60px">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shopItems as $shopItem)
                                <tr>
                                    <td scope="row">
                                        <label><div class="checker"><input type="checkbox" class="check-items-single"></div></label>
                                    </td>
                                    <td>{{$shopItem->sort}}</td>

                                    <td>
                                        <span class="pull-left thumb-sm ">
                                            <img style="height:36px;padding-right: 4px;" src="{{url($shopItem->item->getFirstImage())}}">
                                        </span>
                                        {{$shopItem->item->title}}
                                    </td>
                                    <td>{{$shopItem->item->code}}</td>

                                    <td>¥{{$shopItem->item->price}}</td>
                                    <td>
                                        @if($shopItem->item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_NO)
                                            <span class="label label-danger">不可上架</span>
                                        @else
                                            @if($shopItem->status == \Dajiayao\Model\ShopItem::STATUS_YES)
                                                <span class="label label-success">已上架</span>
                                            @else
                                                <span class="label label-warning">未上架</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($shopItem->item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_YES)
                                            @if($shopItem->status == \Dajiayao\Model\ShopItem::STATUS_YES)
                                                <a href="{{route('changeShopItemStatus',array('shopItemId'=>$shopItem->id))}}">下架</a>
                                            @else
                                                <a href="{{route('changeShopItemStatus',array('shopItemId'=>$shopItem->id))}}">上架</a>
                                            @endif
                                        @endif
                                        <a href="{{route('deleteShopItem',array('shopItemId'=>$shopItem->id))}}" onclick="return confirm('确定删除该商品？')">删除</a>
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