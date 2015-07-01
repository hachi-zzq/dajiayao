@extends('layouts.master')

@section('title')单品管理@stop

@section('page-title')
    <div class="page-title">
        <h3>单品管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('adminIndex')}}">Home</a></li>
                <li><a href="{{route('adminItems')}}">单品管理</a></li>
                <li class="active">增加</li>
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
                    <form class="form-horizontal" action="{{route('adminItemsAddPost')}}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">供应商</label>
                            <div class="col-md-4">
                                <select name="supplier" class="form-control m-b-sm">
                                    @foreach($suppliers as $supply)
                                        <option value="{{$supply->id}}">{{$supply->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名称</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-title" id="" maxlength="128">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">规格</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-spec" id="" maxlength="128">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">编码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-code" id="" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">条形码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-barcode" id="" maxlength="30">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品类型</label>
                            <div class="col-md-4">
                                <select name="item-type" class="form-control m-b-sm">
                                    @foreach($itemTypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">库存</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-stock" maxlength="10">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品描述</label>
                            <div class="col-md-4">
                                <textarea class="input-small form-control" id="item-comment" name="item-comment" rows="3" placeholder="输入一些描述信息 ..."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">重量</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <input type="text" class="form-control" name="item-weight" id="" aria-describedby="basic-addon-weight" maxlength="6">
                                    <span class="input-group-addon" id="basic-addon-weight">克</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">体积</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <input type="text" class="form-control" name="item-volume" id="" aria-describedby="basic-addon-volume" maxlength="6">
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
                                    <input type="text" class="form-control" name="item-market-price" id="" maxlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">销售价</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <span class="input-group-addon">¥</span>
                                    <input type="text" class="form-control" name="item-price" id="txtItemPrice" maxlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">单品佣金比例</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="item-commission-ratio" id="txtItemCommissionRatio" maxlength="6" value="{{$setting->value}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">佣金折算金额</label>
                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <span class="input-group-addon">¥</span>
                                    <input type="text" class="form-control" name="item-commission" id="txtItemCommission" value="0" readonly="">
                                </div>
                                <p class="help-block">佣金金额随销售价与佣金比例变动</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运费计算方式</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-postage-type" value="{{\Dajiayao\Model\Item::POSTAGE_TYPE_SELLER}}" checked>卖家承担（包邮）</label>
                                <label><input type="radio" name="item-postage-type" value="{{\Dajiayao\Model\Item::POSTAGE_TYPE_BUYER}}">买家承担</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否直营</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-is-direct-sale" value="{{\Dajiayao\Model\Item::IS_DIRECT_SALE_YES}}">是</label>
                                <label><input type="radio" name="item-is-direct-sale" value="{{\Dajiayao\Model\Item::IS_DIRECT_SALE_NO}}" checked>否</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品中心状态</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="tem-shelf-status" value="{{\Dajiayao\Model\Item::SHELF_STATUS_YES}}" checked>允许上架</label>
                                <label><input type="radio" name="item-shelf-status" value="{{\Dajiayao\Model\Item::SHELF_STATUS_NO}}">不允许上架</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">销售状态</label>
                            <div class="col-md-4">
                                <label><input type="radio" name="item-sale-status" value="{{\Dajiayao\Model\Item::SALE_STATUS_YES}}" checked>在售</label>
                                <label><input type="radio" name="item-sale-status" value="{{\Dajiayao\Model\Item::SALE_STATUS_NO}}">停售</label>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定增加</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop