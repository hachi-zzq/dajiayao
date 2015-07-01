@extends('layouts.master')

@section('title')全局交易设置@stop

@section('page-title')
    <div class="page-title">
        <h3>全局交易设置</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">全局交易设置</li>
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
                    <form class="form-horizontal" action="{{route('updateSetting')}}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_PAYMENT_DURATION]->name}}</label>

                            <div class="col-md-4">
                                <div class="input-group m-b-sm">

                                    <input type="text" class="form-control" aria-describedby="basic-addon-hour"
                                           name="{{\Dajiayao\Model\Setting::KEY_ORDER_PAYMENT_DURATION}}"
                                           value="{{$items[\Dajiayao\Model\Setting::KEY_ORDER_PAYMENT_DURATION]->value}}"/>
                                    <span class="input-group-addon" id="basic-addon-hour">小时</span>

                                </div>
                                @if($items[\Dajiayao\Model\Setting::KEY_ORDER_PAYMENT_DURATION]->description)
                                    <p class="help-block">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_PAYMENT_DURATION]->description}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_AUTO_RECEIVE_DURATION]->name}}</label>

                            <div class="col-md-4">
                                <div class="input-group m-b-sm">

                                    <input type="text" class="form-control" aria-describedby="basic-addon-hour2"
                                           name="{{\Dajiayao\Model\Setting::KEY_ORDER_AUTO_RECEIVE_DURATION}}"
                                           value="{{$items[\Dajiayao\Model\Setting::KEY_ORDER_AUTO_RECEIVE_DURATION]->value}}"/>
                                    <span class="input-group-addon" id="basic-addon-hour2">小时</span>

                                </div>
                                @if($items[\Dajiayao\Model\Setting::KEY_ORDER_AUTO_RECEIVE_DURATION]->description)
                                    <p class="help-block">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_AUTO_RECEIVE_DURATION]->description}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{$items[\Dajiayao\Model\Setting::KEY_COMMISSIONS_RATE]->name}}</label>

                            <div class="col-md-4">
                                <input type="text" class="form-control"
                                       name="{{\Dajiayao\Model\Setting::KEY_COMMISSIONS_RATE}}"
                                       value="{{$items[\Dajiayao\Model\Setting::KEY_COMMISSIONS_RATE]->value}}"/>
                                @if($items[\Dajiayao\Model\Setting::KEY_COMMISSIONS_RATE]->description)
                                    <p class="help-block">{{$items[\Dajiayao\Model\Setting::KEY_COMMISSIONS_RATE]->description}}</p>
                                @endif
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_POSTAGE]->name}}</label>

                            <div class="col-md-4">
                                <div class="input-group m-b-sm">
                                    <input type="text" class="form-control" aria-describedby="basic-addon-yuan"
                                           name="{{\Dajiayao\Model\Setting::KEY_ORDER_POSTAGE}}"
                                           value="{{$items[\Dajiayao\Model\Setting::KEY_ORDER_POSTAGE]->value}}"/>
                                    <span class="input-group-addon" id="basic-addon-yuan">元</span>
                                </div>

                                @if($items[\Dajiayao\Model\Setting::KEY_ORDER_POSTAGE]->description)
                                    <p class="help-block">{{$items[\Dajiayao\Model\Setting::KEY_ORDER_POSTAGE]->description}}</p>
                                @endif
                            </div>

                        </div>


                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">确定修改</button>
                            <button type="button" class="btn btn-default" onclick="window.history.back()">取消</button>
                        </div>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop