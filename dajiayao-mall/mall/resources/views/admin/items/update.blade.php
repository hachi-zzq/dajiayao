@extends('layouts.master')

@section('title')单品管理@stop

@section('page-title')
    <div class="page-title">
        <h3>单品管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li><a href="{{route('adminItems')}}">单品管理</a></li>
                <li class="active">编辑</li>
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
                    <form class="form-horizontal" action="{{route('adminItemsUpdatePost', $item->id)}}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">供应商</label>
                            <div class="col-md-4">
                                <p class="form-control-static">{{$supplier->title}}</p>
                                <input type="hidden" name="supplier" value="{{$supplier->id}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-title" id="" value="{{$item->title}}" maxlength="128">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">规格</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-spec" id="" value="{{$item->spec}}" maxlength="128">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">编码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-code" id="" value="{{$item->code}}" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">条形码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-barcode" id="" value="{{$item->barcode}}" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品类型</label>
                            <div class="col-md-4">
                                <select name="item-type" class="form-control m-b-sm">
                                    @foreach($itemTypes as $type)
                                        <option value="{{$type->id}}" @if($type->id == $item->type_id){{'selected'}}@endif>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">库存</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-stock" maxlength="10" value="{{$item->stock}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品描述</label>
                            <div class="col-md-4">
                                <textarea class="input-small form-control" id="item-comment" name="item-comment" rows="3">{{$item->comment}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">重量</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <input type="text" class="form-control" name="item-weight" id="" aria-describedby="basic-addon-weight" value="{{$item->weight}}" maxlength="6">
                                    <span class="input-group-addon" id="basic-addon-weight">克</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">体积</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <input type="text" class="form-control" name="item-volume" id="" aria-describedby="basic-addon-volume" value="{{$item->volume}}" maxlength="6">
                                    <span class="input-group-addon" id="basic-addon-volume">毫升</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">图片</label>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="item-image" id="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">市场参考价</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <span class="input-group-addon">¥</span>
                                    <input type="text" class="form-control" name="item-market-price" id="" value="{{$item->market_price}}" maxlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">销售价</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <span class="input-group-addon">¥</span>
                                    <input type="text" class="form-control" name="item-price" id="txtItemPrice" value="{{$item->price}}" maxlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单品佣金比例</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-commission-ratio" id="txtItemCommissionRatio" maxlength="6" value="{{$item->commission_ratio}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">佣金折算金额</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <span class="input-group-addon">¥</span>
                                    <input type="text" class="form-control" name="item-commission" id="txtItemCommission" readonly value="{{($item->price) * ($item->commission_ratio)}}">
                                </div>
                                <p class="help-block">佣金金额随销售价与佣金比例变动</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运费计算方式</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-postage-type" value="{{\Dajiayao\Model\Item::POSTAGE_TYPE_SELLER}}" @if($item->postage_type == \Dajiayao\Model\Item::POSTAGE_TYPE_SELLER){{'checked'}}@endif>卖家承担（包邮）</label>
                                <label><input type="radio" name="item-postage-type" value="{{\Dajiayao\Model\Item::POSTAGE_TYPE_BUYER}}" @if($item->postage_type == \Dajiayao\Model\Item::POSTAGE_TYPE_BUYER){{'checked'}}@endif>买家承担</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否直营</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-is-direct-sale" value="{{\Dajiayao\Model\Item::IS_DIRECT_SALE_YES}}" @if($item->is_direct_sale==\Dajiayao\Model\Item::IS_DIRECT_SALE_YES){{'checked'}}@endif>是</label>
                                <label><input type="radio" name="item-is-direct-sale" value="{{\Dajiayao\Model\Item::IS_DIRECT_SALE_NO}}" @if($item->is_direct_sale==\Dajiayao\Model\Item::IS_DIRECT_SALE_NO){{'checked'}}@endif>否</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品中心状态</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-shelf-status" value="{{\Dajiayao\Model\Item::SHELF_STATUS_YES}}" @if($item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_YES){{'checked'}}@endif>允许上架</label>
                                <label><input type="radio" name="item-shelf-status" value="{{\Dajiayao\Model\Item::SHELF_STATUS_NO}}" @if($item->shelf_status == \Dajiayao\Model\Item::SHELF_STATUS_NO){{'checked'}}@endif>不允许上架</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">销售状态</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-sale-status" value="{{\Dajiayao\Model\Item::SALE_STATUS_YES}}" @if($item->sale_status == \Dajiayao\Model\Item::SALE_STATUS_YES){{'checked'}}@endif>在售</label>
                                <label><input type="radio" name="item-sale-status" value="{{\Dajiayao\Model\Item::SALE_STATUS_NO}}" @if($item->sale_status == \Dajiayao\Model\Item::SALE_STATUS_NO){{'checked'}}@endif>停售</label>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定修改</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop