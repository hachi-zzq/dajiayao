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
    <div class="sans shop trailingSpace">
        <header class="navigation">
            <div class="navigationBar">
                <h1 class="pageHeading">订单详情</h1>
            </div>
        </header>
        <section class="list isolated">
            <div class="item orderTitle">
                <h1 class="dualLineUpper">订单号 {{$order->order_number}}</h1>
                <p class="dualLineLower trivial">下单时间 {{$order->created_at}}</p><span class="orderState orderStateLabel {{$order->statusClass}}"></span>
            </div>
        </section>
        <section class="list isolated">
            <div class="item deliveryAddress depth"><span class="yyicon-location-outline"></span>
                <h3 class="dualLineUpper">{{$order->address->receiver}} {{$order->address->mobile}}</h3>
                <p class="dualLineLower trivial">{{$address}}</p>
            </div>
        </section>

        @if($order->status == Dajiayao\Model\Order::STATUS_TO_RECEIVE)
        <section class="list isolated"><a href="{{$order->express_number ? sprintf('http://m.kuaidi100.com/index_all.html?postid=%s#result',$order->express_number) : '#'}}" class="item singleLine expressInfo depth"><span class="yyicon-gift"></span>物流信息 - {{$order->express ? $order->express->name : "暂无"}} {{$order->express_number or "暂无"}}<span class="yyicon-arrow-right"></span></a></section>
        @endif

        <section class="list isolated">
            <div class="item depth"><a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}" class="orderShopLink orderShopLinkLine"><span class="orderShopAvatar" style="background-image: url('{{$order->shop->thumbnail}}');"></span>{{$order->shop->name}}<span class="yyicon-arrow-right"></span></a></div>

            @foreach($order->orderItems as $orderItem)
            <div class="item">
                <h3 class="dualLineUpper orderProductName">{{$orderItem->name}}</h3>
                <p class="dualLineLower orderProductDetail">{{$orderItem->items->spec}}</p><img src="{{$orderItem->items->image->first()->url}}" alt="{{$orderItem->title}}" class="orderProductImage">
                <div class="orderProductPrice">
                    <div class="emphases dualLineUpper"><span class="currencySymbol">￥</span><span class="orderUnitPrice">{{$orderItem->price}}</span></div>
                    <div class="dualLineLower"><span class="orderProductMultiply">×</span>{{$orderItem->quantity}}</div>
                </div>
            </div>
            @endforeach
            <div class="item orderBill">
                <div class="orderBillLine">
                    <div class="label">商品总额</div>
                    <div class="formText">
                        <div class="billCost"><span class="currencySymbol">￥</span>{{$order->item_total}}</div>
                    </div>
                </div>
                <div class="orderBillLine">
                    <div class="label">运费</div>
                    <div class="formText">
                        <div class="billCost"><span class="currencySymbol">￥</span>{{ abs($order->postage-0)>0.00001 ? $order->postage : '0.00'}}</div>
                    </div>
                </div>
                <div class="orderBillLine">
                    <div class="label">实付款(含运费)</div>
                    <div class="formText">
                        <div class="billCost billGrandTotal"><span class="currencySymbol">￥</span>{{$order->grand_total}}</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="orderOperationBar">
            <div class="orderOperationBanner">
                @if($order->status == Dajiayao\Model\Order::STATUS_TO_PAY)
                <div class="orderCountdown"><span class="countdownClock"><span class="countdownPart">0</span><span class="countdownTimeSymbol">:</span><span class="countdownPart">{{date("i",strtotime($order->created_at)+3600*1-time())}}</span></span>订单将关闭</div>
                @endif
                    <div class="orderOperationBarButtons">


                    @if($order->status == Dajiayao\Model\Order::STATUS_TO_PAY)
                            <a href="{{route('setOrderStatus',[$order->order_number,'cancel'])}}"><button class="button strokedButton cancelStrokedButton">取消订单</button></a>
                        <button class="button strokedButton primaryStrokedButton buttonPay" data-ordernumber="{{$order->order_number}}">立即付款</button>
                    @endif

                    @if($order->status == Dajiayao\Model\Order::STATUS_TO_RECEIVE)
                            <a href="{{route('setOrderStatus',[$order->order_number,'received'])}}"><button class="button strokedButton primaryStrokedButton">确认收货</button></a>
                            {{--<div class="minor orderAutoConfirm"><span class="yyicon-time"></span>7天12小时后自动确认</div>--}}
                    @endif

                    @if($order->status == Dajiayao\Model\Order::STATUS_FINISH || $order->status == Dajiayao\Model\Order::STATUS_CLOSED)
                            <a href="{{route('setOrderStatus',[$order->order_number,'delete'])}}"><button class="button strokedButton">删除订单</button></a>
                            <a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}"> <button class="button strokedButton">再次购买</button></a>
                    @endif

                    @if($order->status == Dajiayao\Model\Order::STATUS_TO_DELIVER)
                        {{--<button class="button strokedButton primaryStrokedButton">退款</button>--}}
                    @endif
                </div>
            </div>
        </section>
    </div>
    <script src="/assets/scripts/vendor.js?{{$hash_file['vendor_js']}}"></script>
    <script src="/assets/scripts/customer/orders.js?{{$hash_file['orders_js']}}"></script>
    </body>
    </html>