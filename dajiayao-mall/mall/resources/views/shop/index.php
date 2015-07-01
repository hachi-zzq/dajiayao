<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <title>丫摇小店</title>
    <script>!function e(t,r,i){function n(o,l){if(!r[o]){if(!t[o]){var s="function"==typeof require&&require;if(!l&&s)return s(o,!0);if(a)return a(o,!0);var m=new Error("Cannot find module '"+o+"'");throw m.code="MODULE_NOT_FOUND",m}var u=r[o]={exports:{}};t[o][0].call(u.exports,function(e){var r=t[o][1][e];return n(r?r:e)},u,u.exports,e,t,r,i)}return r[o].exports}for(var a="function"==typeof require&&require,o=0;o<i.length;o++)n(i[o]);return n}({1:[function(e,t,r){"use strict";!function(e,t){function r(){var t=a.getBoundingClientRect().width;t/s>540&&(t=540*s);var r=t/10;a.style.fontSize=r+"px",u.rem=e.rem=r}var i,n=e.document,a=n.documentElement,o=n.querySelector('meta[name="viewport"]'),l=n.querySelector('meta[name="flexible"]'),s=0,m=0,u=t.flexible||(t.flexible={});if(o){console.warn("将根据已有的meta标签来设置缩放比例");var c=o.getAttribute("content").match(/initial\-scale=([\d\.]+)/);c&&(m=parseFloat(c[1]),s=parseInt(1/m))}else if(l){var d=l.getAttribute("content");if(d){var p=d.match(/initial\-dpr=([\d\.]+)/),f=d.match(/maximum\-dpr=([\d\.]+)/);p&&(s=parseFloat(p[1]),m=parseFloat((1/s).toFixed(2))),f&&(s=parseFloat(f[1]),m=parseFloat((1/s).toFixed(2)))}}if(!s&&!m){var v=(e.navigator.appVersion.match(/android/gi),e.navigator.appVersion.match(/iphone/gi)),h=e.devicePixelRatio;s=v?h>=3&&(!s||s>=3)?3:h>=2&&(!s||s>=2)?2:1:1,m=1/s}if(a.setAttribute("data-dpr",s),!o)if(o=n.createElement("meta"),o.setAttribute("name","viewport"),o.setAttribute("content","initial-scale="+m+", maximum-scale="+m+", minimum-scale="+m+", user-scalable=no"),a.firstElementChild)a.firstElementChild.appendChild(o);else{var x=n.createElement("div");x.appendChild(o),n.write(x.innerHTML)}e.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(r,300)},!1),e.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(r,300))},!1),"complete"===n.readyState?n.body.style.fontSize=12*s+"px":n.addEventListener("DOMContentLoaded",function(e){n.body.style.fontSize=12*s+"px"},!1),r(),u.dpr=e.dpr=s,u.refreshRem=r,u.rem2px=function(e){var t=parseFloat(e)*this.rem;return"string"==typeof e&&e.match(/rem$/)&&(t+="px"),t},u.px2rem=function(e){var t=parseFloat(e)/this.rem;return"string"==typeof e&&e.match(/px$/)&&(t+="rem"),t}}(window,window.lib||(window.lib={}))},{}]},{},[1]);
        //# sourceMappingURL=flexible.js.map
    </script>
    <link rel="stylesheet" href="/assets/stylesheets/shop.css?t=<?php echo $hash_file ? $hash_file['shop_css'] : ''?>"/>
    <link rel="stylesheet" href="/assets/stylesheets/app.css?t=<?php echo $hash_file ? $hash_file['app'] : ''?>"/>
</head>
<body>
<div id="app" data-shopshortid="<?php echo $shop_short_id;?>"></div>
<script type="text/template" id="tplApp">
    <div class="app">
        <component is="{{route}}" keep-alive></component>
    </div>
</script>
<script type="text/template" id="tplIndex">
    <div v-attr="class: &quot;sans shop&quot; + ((promotions &amp;&amp; promotions.length) ? &quot; promoting&quot; : &quot;&quot;) + (scrolled ? &quot; retracted&quot; : &quot;&quot;)">
        <header class="shopSign">
            <div v-style="background-image:&quot;url(&quot; + shop.banner + &quot;)&quot;" class="shopBanner">
                <div class="shopBio">
                    <div v-style="background-image:&quot;url(&quot; + shop.avatar + &quot;)&quot;" class="shopBrand"></div>
                    <div v-text="shop.name" class="shopName"></div>
                    <div class="shopBadges">
                        <template v-if="isInstant">
                            <div class="shopType shopTypeInstant"><span class="yyicon-ios-bolt"></span>1小时快送<span class="yyicon-arrow-right"></span></div>
                        </template>
                        <template v-if="!isInstant">
                            <div class="shopType"><span class="yyicon-truck"></span>普通配送</div>
                        </template>
                    </div>
                    <div v-on="click: favor" class="shopFavor"><span v-attr="class:(favorite ? &quot;yyicon-favorite&quot; : &quot;yyicon-favorite-outline&quot;)"></span></div>
                </div>
                <div class="shopOverlay">
                    <div class="overlayLinks"><a v-repeat="promotions" v-attr="class: &quot;overlayLink&quot; + (!$index ? &quot; overlayLinkHilite&quot; : &quot;&quot;), href: link, title: title"><img v-attr="src: image, alt: title" class="overlayImage"></a></div>
                </div>
            </div>
        </header>
        <section class="shopwindow">
            <div style="padding-bottom:1em;line-height:1.25em;text-align:center;color:#999">您是第<span v-text="visitorCount" style="padding:0 .25em;color:#555"></span>位客人</div>
            <div class="products clearfix">
                <yao-shelf data-products="{{items}}"></yao-shelf>
            </div>
            <div class="watermark"></div>
        </section>
        <yao-shoppingbasket data-cart="{{items | gt &quot;amount&quot; 0}}"></yao-shoppingbasket>
    </div>
</script>
<script type="text/template" id="tplShoppingBasket">
    <section v-style="display:(cart.length ? &quot;block&quot; : &quot;none&quot;)" class="shoppingBasket">
        <div class="shoppingBanner">
            <p class="shoppingSum"><span class="shoppingCount"><span v-text="cart.length" class="emphases">0</span> 种商品</span><span class="shoppingCost">共 <span class="emphases"><span class="currencySymbol">¥</span><span v-text="cost | currencyfigure" class="costFigure"></span></span></span></p>
            <p class="shoppingHint">关注 丫摇小店 跟踪订单</p>
            <button type="button" v-attr="class:&quot;button basketConfirm&quot; + (pending ? &quot; pending&quot; : &quot;&quot;)" v-on="click: settle">去结算</button>
        </div>
    </section>
</script>
<script type="text/template" id="tplShelf">
    <yao-shelfcell v-repeat="products"></yao-shelfcell>
</script>
<script type="text/template" id="tplShelfCell">
    <div class="product{{amount &gt; 0 ? ' productSelected' : ''}}">
        <div class="productBrief">
            <h2 v-text="title" class="briefName"></h2>
            <p v-text="spec" class="briefSpec"></p><img v-attr="src:image, alt:title + &quot; &quot; + spec" class="briefImage">
        </div>
        <div class="price"><span class="currencySymbol">分享价 ¥</span><span v-text="price | currencyfigure"></span></div>
        <div class="productControls">
            <yao-amount data-amount="{{amount}}"></yao-amount>
        </div>
        <yao-salesrecords data-sales="{{sales}}" data-buyers="{{buyers}}"></yao-salesrecords>
    </div>
</script>
<script type="text/template" id="tplAmount">
    <div class="amount"><span class="amountControls"><span v-attr="class: &quot;amountBuy&quot; + (bought ? &quot; amountBought&quot; : &quot;&quot;)" v-on="click: buy">购买</span><span v-attr="class: &quot;amountReduce&quot; + (amount ? &quot;&quot; : &quot; amountDisabled&quot;)" v-on="click: decrease"><span class="yyicon-remove"></span></span><span v-text="amount" class="amountFigure"></span><span v-on="click: increase" class="amountIncrease"><span class="yyicon-add"></span></span></span></div>
</script>
<script type="text/template" id="tplSalesRecords">
    <div class="productStats"><span class="statsAmount">已售 <span v-text="sales"></span> 件</span>
        <template v-if="buyers.length"><span class="statsAvatars"><span v-repeat="buyers" v-style="background-image:&quot;url(&quot; + avatar + &quot;)&quot;" class="statsAvatar"></span></span></template>
    </div>
</script>
<script type="text/template" id="tplOrder">
    <div class="sans shop trailingSpace">
        <section>
            <h2 class="sectionHeading">商品清单</h2>
            <div class="list">
                <yao-orderproduct v-repeat="items | gt &quot;amount&quot; 0"></yao-orderproduct><span v-on="click: this.$dispatch(&quot;route&quot;, &quot;yao-index&quot;)" class="trailingOption"><span class="yyicon-arrow-left"></span>返回修改</span>
            </div>
        </section>
        <section>
            <h2 class="sectionHeading">收货地址</h2>
            <div class="list">
                <template v-if="!selectedAddress">
                    <div v-on="click: !this.pending &amp;&amp; this.$dispatch(&quot;route&quot;, &quot;yao-newaddress&quot;)" class="item addAddress depth"><span class="yyicon-plus-circled"></span>添加收货地址<span class="yyicon-arrow-right"></span></div>
                </template>
                <template v-if="selectedAddress">
                    <div v-on="click: !this.pending &amp;&amp; this.$dispatch(&quot;route&quot;, &quot;yao-addresses&quot;)" class="item defaultAddress depth"><span class="yyicon-location-outline"></span>
                        <h3 v-text="selectedAddress.receiver + &quot; &quot; + selectedAddress.mobile" class="dualLineUpper"></h3>
                        <p v-text="[selectedAddress.province, selectedAddress.city, selectedAddress.county, selectedAddress.address].join(&quot; &quot;)" class="dualLineLower minor"></p><span class="yyicon-arrow-right"></span>
                    </div>
                </template>
            </div>
        </section>
        <section>
            <h2 class="sectionHeading">结算</h2>
            <div class="list">
                <div class="item singleLine">
                    <label class="label">邮费</label>
                    <div class="formText">
                        <template v-if="postage === 0">包邮</template>
                        <template v-if="postage !== 0"><span class="emphases orderCostValue"><span class="currencySymbol">¥</span><span v-text="postage" class="orderCostFigure"></span></span></template>
                    </div>
                </div>
                <div class="item singleLine">
                    <label class="label"><span class="checkbox">
                  <input type="checkbox" v-model="isAnonymous"><span class="checkboxUnchecked yyicon-checkbox-outline-blank"></span><span class="checkboxChecked yyicon-checkbox-outline"></span></span>匿名购买</label>
                    <div class="formText orderSummarizing"><span class="orderProductCount">共<span v-text="items | gt &quot;amount&quot; 0 | count" class="emphases orderProductCountFigure"></span>种商品</span><span class="orderCost">总计<span class="emphases orderCostValue"><span class="currencySymbol">¥</span><span v-text="grandTotal | currencyfigure" class="orderCostFigure"></span></span></span></div>
                </div>
            </div>
        </section>
        <section>
            <h2 class="sectionHeading">选择支付方式</h2>
            <div class="list">
                <div class="item singleLine">微信支付<span class="yyicon-checkmark"></span></div>
            </div>
        </section>
        <section class="trunk">
            <p class="followHint"><span class="yyicon-alert"></span>关注公众账号 丫摇小店 跟踪物流信息</p>
            <button type="button" v-attr="class:&quot;button fullLineButton&quot; + (pending ? &quot; pending&quot; : &quot;&quot;)" v-on="click: pay">去支付</button>
        </section>
    </div>
</script>
<script type="text/template" id="tplOrderProduct">
    <div class="item">
        <h3 v-text="title" class="dualLineUpper orderProductName"></h3>
        <p v-text="spec" class="dualLineLower orderProductDetail"></p><img v-attr="src:image, alt:title + &quot; &quot; + spec" class="orderProductImage">
        <div class="orderProductPrice">
            <div class="emphases dualLineUpper"><span class="currencySymbol">￥</span><span v-text="price | currencyfigure" class="orderUnitPrice"></span></div>
            <div class="dualLineLower"><span class="orderProductMultiply">×</span><span v-text="amount"></span></div>
        </div>
    </div>
</script>
<script type="text/template" id="tplAddresses">
    <div class="sans shop trailingSpace clearfix">
        <div class="list isolated">
            <div v-repeat="addresses" v-on="click: this.$dispatch(&quot;selectAddress&quot;, this.$data)" class="item depth">
                <div v-text="receiver + &quot; &quot; + mobile" class="dualLineUpper"></div>
                <div v-text="[province, city, county, address].join(&quot; &quot;)" class="trivial dualLineLower"></div>
                <template v-if="id === selectedAddress.id"><span class="yyicon-checkmark"></span></template>
            </div>
        </div>
        <div class="list isolated">
            <div v-on="click: this.$dispatch(&quot;route&quot;, &quot;yao-newaddress&quot;)" class="item addAddress depth"><span class="yyicon-plus-circled"></span>添加收货地址<span class="yyicon-arrow-right"></span></div>
        </div>
        <div class="trunk isolated">
            <button type="button" v-on="click: this.$dispatch(&quot;route&quot;, &quot;yao-order&quot;)" class="button fullLineButton secondaryButton">确定</button>
        </div>
    </div>
</script>
<script type="text/template" id="tplNewAddress">
    <div class="sans shop trailingSpace clearfix">
        <div class="list isolated">
            <div class="item singleLine">
                <label for="fieldReceiver" class="label">收货人姓名</label>
                <div class="formText">
                    <input id="fieldReceiver" type="text" name="receiver" placeholder="必填" v-model="newAddress.receiver" class="formInput">
                </div>
            </div>
            <div class="item singleLine">
                <label for="fieldMobile" class="label">手机号</label>
                <div class="formText">
                    <input id="fieldMobile" type="tel" name="mobile" maxlength="11" placeholder="必填" v-model="newAddress.mobile" class="formInput">
                </div>
            </div>
        </div>
        <div class="list isolated">
            <div class="item singleLine">
                <label for="fieldProvince" class="label">省份</label>
                <div class="formText">
                    <select id="fieldProvince" name="province" placeholder="必填" v-model="newAddress.provinceId" v-on="change: updateCities" number class="sans formSelect">
                        <option v-repeat="regions.provinces" v-text="name" v-attr="value:id, selected: id == newAddress.provinceId"></option>
                    </select>
                </div>
            </div>
            <div class="item singleLine">
                <label for="fieldCity" class="label">城市</label>
                <div class="formText">
                    <select id="fieldCity" name="city" placeholder="必填" v-model="newAddress.cityId" v-on="change: updateCounties" number class="sans formSelect">
                        <template v-if="newAddress.citiesAvailable">
                            <option v-repeat="newAddress.citiesAvailable" v-text="name" v-attr="value:id, selected: id == newAddress.cityId"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="item singleLine">
                <label for="fieldCounty" class="label">区县</label>
                <div class="formText">
                    <select id="fieldCounty" name="district" placeholder="必填" v-model="newAddress.countyId" number class="sans formSelect">
                        <template v-if="newAddress.countiesAvailable">
                            <option v-repeat="newAddress.countiesAvailable" v-text="name" v-attr="value:id, selected: id == newAddress.countyId"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="item singleLine">
                <label for="fieldAddress" class="label">详细地址</label>
                <div class="formText">
                    <input id="fieldAddress" type="text" name="detail" placeholder="必填" v-model="newAddress.address" class="formInput">
                </div>
            </div>
        </div>
        <div class="trunk isolated dualButtonSet clearfix">
            <button type="button" v-attr="class: &quot;button cancelButton &quot; + (pending ? &quot; pending&quot; : &quot;&quot;)" v-on="click: !pending &amp;&amp; this.$dispatch(&quot;route&quot;, &quot;$back&quot;)">返回</button>
            <button type="button" v-attr="class: &quot;button secondaryButton&quot; + (pending ? &quot; pending&quot; : &quot;&quot;)" v-on="click: createAddress">确定</button>
        </div>
    </div>
</script>
<script type="text/template" id="tplSuccess">
    <div class="sans shop successPage trailingSpace clearfix">
        <h1 class="successHeading"><span class="yyicon-checkmark-outline"></span>购买成功</h1>
        <p class="trivial followMessage">请关注公众账号 <span>丫摇小店</span> 跟踪物流信息</p>
        <div class="followGuide">
            <div class="followByQR">
                <h2 class="trivial followInstruction">长按识别图中二维码关注</h2>
                <div class="qr followQR"><img src="/images/qrcode.png" alt="丫摇小店二维码" class="followQRImage"></div>
            </div>

            <div class="followOr">或</div>
            <div class="followBySearch">
                <h2 class="minor followInstruction">搜索关注公众号</h2><img src="/assets/images/wechatsearch.png" alt="搜索关注公众号" class="followSearchUI">
                <p class="followSearchContent"><span class="trivial">微信公众号：</span><span class="emphases">丫摇小店</span></p>
            </div>
        </div>
        <div class="trunk">
            <button type="button" v-on="click: reload" class="button fullLineButton secondaryButton buttonReturn">返回继续购物</button>
        </div>
    </div>
</script>
<script src="/assets/scripts/vendor.js?<?php echo $hash_file ? $hash_file['vendor'] : ''?>"></script>
<script src="/assets/scripts/shop.js?<?php echo $hash_file ? $hash_file['shop'] : ''?>"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?73ca47df13fd8aadd6363fe15c5e92ea";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

</body>
</html>