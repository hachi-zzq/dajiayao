<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <title>我购买过的</title>
    <script>!function e(t,r,i){function n(o,l){if(!r[o]){if(!t[o]){var s="function"==typeof require&&require;if(!l&&s)return s(o,!0);if(a)return a(o,!0);var m=new Error("Cannot find module '"+o+"'");throw m.code="MODULE_NOT_FOUND",m}var u=r[o]={exports:{}};t[o][0].call(u.exports,function(e){var r=t[o][1][e];return n(r?r:e)},u,u.exports,e,t,r,i)}return r[o].exports}for(var a="function"==typeof require&&require,o=0;o<i.length;o++)n(i[o]);return n}({1:[function(e,t,r){"use strict";!function(e,t){function r(){var t=a.getBoundingClientRect().width;t/s>540&&(t=540*s);var r=t/10;a.style.fontSize=r+"px",u.rem=e.rem=r}var i,n=e.document,a=n.documentElement,o=n.querySelector('meta[name="viewport"]'),l=n.querySelector('meta[name="flexible"]'),s=0,m=0,u=t.flexible||(t.flexible={});if(o){console.warn("将根据已有的meta标签来设置缩放比例");var c=o.getAttribute("content").match(/initial\-scale=([\d\.]+)/);c&&(m=parseFloat(c[1]),s=parseInt(1/m))}else if(l){var d=l.getAttribute("content");if(d){var p=d.match(/initial\-dpr=([\d\.]+)/),f=d.match(/maximum\-dpr=([\d\.]+)/);p&&(s=parseFloat(p[1]),m=parseFloat((1/s).toFixed(2))),f&&(s=parseFloat(f[1]),m=parseFloat((1/s).toFixed(2)))}}if(!s&&!m){var v=(e.navigator.appVersion.match(/android/gi),e.navigator.appVersion.match(/iphone/gi)),h=e.devicePixelRatio;s=v?h>=3&&(!s||s>=3)?3:h>=2&&(!s||s>=2)?2:1:1,m=1/s}if(a.setAttribute("data-dpr",s),!o)if(o=n.createElement("meta"),o.setAttribute("name","viewport"),o.setAttribute("content","initial-scale="+m+", maximum-scale="+m+", minimum-scale="+m+", user-scalable=no"),a.firstElementChild)a.firstElementChild.appendChild(o);else{var x=n.createElement("div");x.appendChild(o),n.write(x.innerHTML)}e.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(r,300)},!1),e.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(r,300))},!1),"complete"===n.readyState?n.body.style.fontSize=12*s+"px":n.addEventListener("DOMContentLoaded",function(e){n.body.style.fontSize=12*s+"px"},!1),r(),u.dpr=e.dpr=s,u.refreshRem=r,u.rem2px=function(e){var t=parseFloat(e)*this.rem;return"string"==typeof e&&e.match(/rem$/)&&(t+="px"),t},u.px2rem=function(e){var t=parseFloat(e)/this.rem;return"string"==typeof e&&e.match(/px$/)&&(t+="rem"),t}}(window,window.lib||(window.lib={}))},{}]},{},[1]);
        //# sourceMappingURL=flexible.js.map
    </script>
    <link rel="stylesheet" href="/assets/stylesheets/customer/customer.css"/>
    <link rel="stylesheet" href="/assets/stylesheets/customer/shops.css"/>
</head>
<body>
<div class="sans shop trailingSpace">
    <header class="navigation navigationWithToolbar">
        <div class="navigationBar">
            <h1 class="pageHeading">店铺收藏</h1>
        </div>
        <nav class="toolbar trisected clearfix"><a href="{{route('favoriteIndex')}}" class="toolDrawer ">我收藏的</a><a href="{{route('myBuyedShop')}}" class="toolDrawer toolDrawerOpen">我买过的</a><a href="{{route('myBrowseShop')}}" class="toolDrawer">最近浏览</a></nav>
    </header>
    <section class="shopList">
        {{--<h2 class="shopListClassifier">今天</h2>--}}
        @foreach($orders as $order)
        <div style="background-image:url('{{\Dajiayao\Library\Util\ImageUtil::getRuleImgSize($order->shop->banner,750,246)}}')" class="shopBanner">
            <div class="shopBio">
                <a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}" class="shopBrand" style="background-image:url('{{$order->shop->thumbnail}}')"></a>
                <a href="{{Config::get('app.shop_base_url').$order->shop->short_id}}" class="shopName">{{$order->shop->name}}</a>
                <div class="shopFavor" data-shopshortid="{{$order->shop->short_id}}"><span class=@if($order['favorite']) {{"yyicon-favorite"}} @else {{"yyicon-favorite-outline"}} @endif></span></div>
            </div></div>
        @endforeach

        {{--<h2 class="shopListClassifier">昨天</h2><a class="shopBanner">--}}
            {{--<div class="shopBio">--}}
                {{--<div class="shopBrand"></div>--}}
                {{--<div class="shopName">星巴克丫摇微店</div>--}}
                {{--<div class="shopFavor"><span class="yyicon-favorite-outline"></span></div>--}}
            {{--</div></a>--}}
        {{--<a class="shopBanner">--}}
            {{--<div class="shopBio">--}}
                {{--<div class="shopBrand"></div>--}}
                {{--<div class="shopName">星巴克丫摇微店</div>--}}
                {{--<div class="shopFavor"><span class="yyicon-favorite-outline"></span></div>--}}
            {{--</div></a>--}}
    </section>
</div>
<script src="/assets/scripts/vendor.js?0616"></script>
<script src="/assets/scripts/customer/shops.js?0616"></script>

</body>
</html>