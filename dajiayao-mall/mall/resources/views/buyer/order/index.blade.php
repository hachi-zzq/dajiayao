<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <title>丫摇小店</title>
    <script>!function e(t,r,i){function n(o,l){if(!r[o]){if(!t[o]){var s="function"==typeof require&&require;if(!l&&s)return s(o,!0);if(a)return a(o,!0);var m=new Error("Cannot find module '"+o+"'");throw m.code="MODULE_NOT_FOUND",m}var u=r[o]={exports:{}};t[o][0].call(u.exports,function(e){var r=t[o][1][e];return n(r?r:e)},u,u.exports,e,t,r,i)}return r[o].exports}for(var a="function"==typeof require&&require,o=0;o<i.length;o++)n(i[o]);return n}({1:[function(e,t,r){"use strict";!function(e,t){function r(){var t=a.getBoundingClientRect().width;t/s>540&&(t=540*s);var r=t/10;a.style.fontSize=r+"px",u.rem=e.rem=r}var i,n=e.document,a=n.documentElement,o=n.querySelector('meta[name="viewport"]'),l=n.querySelector('meta[name="flexible"]'),s=0,m=0,u=t.flexible||(t.flexible={});if(o){console.warn("将根据已有的meta标签来设置缩放比例");var c=o.getAttribute("content").match(/initial\-scale=([\d\.]+)/);c&&(m=parseFloat(c[1]),s=parseInt(1/m))}else if(l){var d=l.getAttribute("content");if(d){var p=d.match(/initial\-dpr=([\d\.]+)/),f=d.match(/maximum\-dpr=([\d\.]+)/);p&&(s=parseFloat(p[1]),m=parseFloat((1/s).toFixed(2))),f&&(s=parseFloat(f[1]),m=parseFloat((1/s).toFixed(2)))}}if(!s&&!m){var v=(e.navigator.appVersion.match(/android/gi),e.navigator.appVersion.match(/iphone/gi)),h=e.devicePixelRatio;s=v?h>=3&&(!s||s>=3)?3:h>=2&&(!s||s>=2)?2:1:1,m=1/s}if(a.setAttribute("data-dpr",s),!o)if(o=n.createElement("meta"),o.setAttribute("name","viewport"),o.setAttribute("content","initial-scale="+m+", maximum-scale="+m+", minimum-scale="+m+", user-scalable=no"),a.firstElementChild)a.firstElementChild.appendChild(o);else{var x=n.createElement("div");x.appendChild(o),n.write(x.innerHTML)}e.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(r,300)},!1),e.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(r,300))},!1),"complete"===n.readyState?n.body.style.fontSize=12*s+"px":n.addEventListener("DOMContentLoaded",function(e){n.body.style.fontSize=12*s+"px"},!1),r(),u.dpr=e.dpr=s,u.refreshRem=r,u.rem2px=function(e){var t=parseFloat(e)*this.rem;return"string"==typeof e&&e.match(/rem$/)&&(t+="px"),t},u.px2rem=function(e){var t=parseFloat(e)/this.rem;return"string"==typeof e&&e.match(/px$/)&&(t+="rem"),t}}(window,window.lib||(window.lib={}))},{}]},{},[1]);
        //# sourceMappingURL=flexible.js.map
    </script>
    <link rel="stylesheet" href="/assets/stylesheets/customer/customer.css?{{$hash_file['customer_css']}}"/>
    <link rel="stylesheet" href="/assets/stylesheets/customer/orders.css?{{$hash_file['orders_css']}}"/>
</head>
<body>
<div class="sans shop">
    <header class="navigation navigationWithToolbar">
        <div class="navigationBar">
            <h1 class="pageHeading">订单管理</h1>
        </div>
        <nav class="toolbar quartered clearfix"><a href="/buyer/orders/list?status=all" class="toolDrawer {{$input['status']== 'all' || !isset($input['status'])? 'toolDrawerOpen' : ''}}">全部</a><a href="/buyer/orders/list?status=no_paid" class="toolDrawer {{$input['status']== 'no_paid' ? 'toolDrawerOpen' : ""}}">待付款</a><a href="/buyer/orders/list?status=no_send" class="toolDrawer  {{$input['status']== 'no_send' ? 'toolDrawerOpen' : ""}}">待发货</a><a href="/buyer/orders/list?status=no_received" class="toolDrawer {{$input['status']== 'no_received' ? 'toolDrawerOpen' : ""}}">待收货</a></nav>
    </header>
    <section>
        @foreach($orders as $order)
        <div class="list isolated">
            <div class="item"><a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}" class="orderShopLink"><span class="orderShopAvatar" style="background-image: url('{{$order->shop->thumbnail}}');"></span>{{$order->shop->name}}<span class="yyicon-arrow-right"></span></a><span class="orderState orderStateHint {{$order->statusClass}}"></span></div>
            <div class="item">
                <div class="productReviews clearfix">
                    @foreach($order->orderItems as $orderItem)
                    <div class="productReview"><img src="{{is_null($orderItem->items->image->first()) ? '' : $orderItem->items->image->first()->url}}" alt="{{$orderItem->items->title}}" class="reviewImage"><span class="reviewAmount"><span class="reviewMultiply">x</span>{{$orderItem->quantity}}</span></div>
                    @endforeach
                </div>
            </div>

            @if($order->orderItems->count() > 4)
            <div class="item">
                <div class="showTheRest">显示其余<span class="emhasizedFigure">{{$order->orderItems->count()-4}}</span>项</div>
            </div>
            @endif

            <div class="item">
                <div class="orderSum">共<span class="emhasizedFigure">{{$order->orderItems->count()}}</span>件商品<span class="grandTotalLabel">订单金额</span><span class="grandTotal"><span class="currencySymbol">¥</span>{{$order->grand_total}}</span></div>
            </div>
            <div class="item">
                <div class="orderOperations">
                        @if($order->status == Dajiayao\Model\Order::STATUS_TO_PAY)
                            <a href="{{route('setOrderStatus',[$order->order_number,'cancel'])}}"><button class="button strokedButton cancelStrokedButton">取消订单</button></a>
                            <a href="{{route('orderDetail',[$order->order_number])}}"><button class="button strokedButton">查看详情</button></a>
                            <button class="button strokedButton primaryStrokedButton buttonPay" data-ordernumber="{{$order->order_number}}">立即付款</button>
                        @endif

                        @if($order->status == Dajiayao\Model\Order::STATUS_TO_RECEIVE)
                            <a href="{{route('orderDetail',[$order->order_number])}}"><button class="button strokedButton">查看详情</button></a>
                            <a href="{{route('setOrderStatus',[$order->order_number,'received'])}}"><button class="button strokedButton primaryStrokedButton">确认收货</button></a>
                        @endif

                        @if($order->status == Dajiayao\Model\Order::STATUS_FINISH || $order->status == Dajiayao\Model\Order::STATUS_REFUND)
                                <a href="{{route('setOrderStatus',[$order->order_number,'delete'])}}"><button class="button strokedButton">删除订单</button></a>
                            <a href="{{route('orderDetail',[$order->order_number])}}"><button class="button strokedButton">查看详情</button></a>
                            <a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}"><button class="button strokedButton">再次购买</button></a>
                        @endif


                        @if( $order->status == Dajiayao\Model\Order::STATUS_CLOSED)
                                <a href="{{route('setOrderStatus',[$order->order_number,'delete'])}}"><button class="button strokedButton">删除订单</button></a>
                                <a href="{{route('orderDetail',[$order->order_number])}}"><button class="button strokedButton">查看详情</button></a>
                                <a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}"><button class="button strokedButton">再次购买</button></a>

                        @endif

                        @if($order->status == Dajiayao\Model\Order::STATUS_TO_DELIVER)
                            <a href="{{route('orderDetail',[$order->order_number])}}"><button class="button strokedButton">查看详情</button></a>
{{--                            <a href="{{route('setOrderStatus',[$order->order_number,'payback'])}}"><button class="button strokedButton primaryStrokedButton">退款</button></a>--}}
                        @endif
                </div>
            </div>
        </div>
        @endforeach

    </section>
    <div class="watermark"></div>
</div>
<script src="/assets/scripts/vendor.js?{{$hash_file['vendor_js']}}"></script>
<script src="/assets/scripts/customer/orders.js?{{$hash_file['orders_js']}}"></script>
</body>
</html>