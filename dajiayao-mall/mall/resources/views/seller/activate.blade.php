@extends('layouts.seller.master')

@section('content')
<header class="activateProgress">
    <div class="activateBanner"><span class="activateStep activateStepHilite"><span class="activateIndex">1</span><span class="activateLabel">验证</span></span><span class="activateStep"><span class="activateIndex">2</span><span class="activateLabel">设置店铺信息</span></span><span class="activateStep"><span class="activateIndex">3</span><span class="activateLabel">完成</span></span></div>
</header>
<div class="activateDevice">
    <div class="activateDeviceTitle"><span class="yyicon-beacon"></span>
        <h1 class="activateDeviceHeading">开始激活您的iBeacon</h1>
    </div>
    <div class="trivial activateDeviceSN">当前设备 <span class="minor">SN:<span>{{$sn}}</span></span></div>
    <hr class="activateDeviceRule">
    <h2 class="trivial activateDeviceHint">请先验证您的手机号</h2>
</div>
<form action="{{route('sellerActivatePost')}}" method="POST" v-on="submit: check">
    <section class="list activateList activateDeviceList">
        <div class="item singleLine">
            <label class="label">手机号</label>
            <div class="formText securityCode">
                <input type="text" name="mobile" maxlength="11" v-model="mobile" class="formInput">
                <button type="button" v-on="click: getSecurityCode" v-text="delaying ? delay + &quot;秒后重试&quot; : &quot;获取验证码&quot;" class="button strokedButton securityCodeButton">获取验证码</button>
            </div>
        </div>
        <div class="item singleLine">
            <label class="label">验证码</label>
            <div class="formText">
                <input type="text" name="code" maxlength="4" v-model="securityCode" class="formInput">
            </div>
        </div>
    </section>
    <div class="trunk isolated">
        <input type="hidden" name="sn" value="{{$sn}}">
        <button type="submit" class="button fullLineButton">下一步</button>
    </div>
</form>
@stop

@section('js')
<script src="/assets/scripts/shopkeeper/activateaccount.js"></script>
@stop