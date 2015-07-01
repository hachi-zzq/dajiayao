@extends('layouts.master')
@section('title')
    Home
@stop
@section('page-title')
<div class="page-title">
    <h3>主页</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">{{$devicesCount}}</p>
                    <span class="info-box-title">iBeacon 个数</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-film"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">{{$wxDevicesCount}}</p>
                    <span class="info-box-title">微信设备数</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-bag"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">{{$appsCount}}</p>
                    <span class="info-box-title">接入应用数</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-puzzle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel info-box panel-white">
            <div class="panel-body">
                <div class="info-box-stats">
                    <p class="counter">{{$wxPagesCount}}</p>
                    <span class="info-box-title">摇一摇页面数</span>
                </div>
                <div class="info-box-icon">
                    <i class="icon-docs"></i>
                </div>
            </div>
        </div>
    </div>
</div><!-- Row -->
<div class="row" style="display:none;">
    <div class="col-lg-9 col-md-12">
        <div class="panel panel-white">
            <div class="row">
                <div class="col-sm-8">
                    <div class="visitors-chart">
                        <div class="panel-heading">
                            <h4 class="panel-title">Visitors</h4>
                        </div>
                        <div class="panel-body">
                            <div id="flotchart1" style="display: none;"></div>
                            <div id="flotchart2" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                {{--<div class="col-sm-4">--}}
                    {{--<div class="stats-info">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<h4 class="panel-title">Browser Stats</h4>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body">--}}
                            {{--<ul class="list-unstyled">--}}
                                {{--<li>Google Chrome<div class="text-success pull-right">32%<i class="fa fa-level-up"></i></div></li>--}}
                                {{--<li>Firefox<div class="text-success pull-right">25%<i class="fa fa-level-up"></i></div></li>--}}
                                {{--<li>Internet Explorer<div class="text-success pull-right">16%<i class="fa fa-level-up"></i></div></li>--}}
                                {{--<li>Safari<div class="text-danger pull-right">13%<i class="fa fa-level-down"></i></div></li>--}}
                                {{--<li>Opera<div class="text-danger pull-right">7%<i class="fa fa-level-down"></i></div></li>--}}
                                {{--<li>Mobile &amp; tablet<div class="text-success pull-right">4%<i class="fa fa-level-up"></i></div></li>--}}
                                {{--<li>Others<div class="text-success pull-right">3%<i class="fa fa-level-up"></i></div></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
</div><!-- Row -->
@stop

@section('js')
<script src="/themeforest/plugins/flot/jquery.flot.min.js"></script>
<script src="/themeforest/plugins/flot/jquery.flot.time.min.js"></script>
<script src="/themeforest/plugins/flot/jquery.flot.symbol.min.js"></script>
<script src="/themeforest/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="/themeforest/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/themeforest/plugins/curvedlines/curvedLines.js"></script>
<script src="/themeforest/plugins/metrojs/MetroJs.min.js"></script>
<script src="/themeforest/js/pages/dashboard.js"></script>
@stop