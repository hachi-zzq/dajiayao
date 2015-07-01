@extends('layouts.seller.master')

@section('content')
<header class="activateProgress">
    <div class="activateBanner"><span class="activateStep activateStepHilite"><span class="activateIndex"><span class="yyicon-checkmark-empty"></span></span><span class="activateLabel">验证</span></span><span class="activateStep activateStepHilite"><span class="activateIndex">2</span><span class="activateLabel">设置店铺信息</span></span><span class="activateStep"><span class="activateIndex">3</span><span class="activateLabel">完成</span></span></div>
</header>
<h1 class="hidden">设置店铺信息</h1>
<section class="isolated activateShop activateList">
    <div class="item depth activateShopLogo">
        <div class="majorLine">店铺 Logo</div>
        <div class="minorLine trivial">建议图片尺寸 110 x 110px</div><span class="activatePreviewLogo"></span><span class="yyicon-arrow-right"></span>
    </div>
    <div class="item singleLine depth"><a href="#" class="itemLink">店铺封面<span class="activatePreviewBanner"></span><span class="yyicon-arrow-right"></span></a></div>
    <div class="item singleLine depth"><a href="#" class="itemLink">
            <label class="label">店铺位置</label>
            <div class="formText trivial">江苏省苏州市工业园区<span class="yyicon-arrow-right"></span></div></a></div>
    <div class="item singleLine">
        <label class="label">店铺名称</label>
        <div class="formText">
            <input type="text" name="name" placeholder="不超过7个汉字" class="formInput">
        </div>
    </div>
</section>
<section>
    <h2 class="activateHeading">店招预览</h2>
    <div class="trunk">
        <div class="activateShopPreview">
            <div class="activateShopPreviewPage promoting">
                <div class="shopBanner">
                    <div class="shopBio">
                        <div class="shopBrand"></div>
                        <div class="shopName">星巴克丫摇微店</div>
                        <div class="shopBadges">
                            <div class="shopType shopTypeExpress"><span class="yyicon-truck"></span>普通快递<span class="yyicon-arrow-right"></span></div>
                        </div>
                        <div class="shopFavor"><span class="yyicon-favorite-outline"></span></div>
                    </div>
                </div>
                <div class="shopOverlayHolder">广告位</div>
            </div>
        </div>
    </div>
</section>
<div class="trunk isolated">
    <input type="hidden" name="shop_id" value="{{$shop_id}}">
    <button type="submit" class="button fullLineButton">完成</button>
</div>
@stop

@section('js')
@stop