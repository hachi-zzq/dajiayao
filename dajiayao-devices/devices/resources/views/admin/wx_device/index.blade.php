@extends('layouts.master')
@section('title')微信设备管理@stop

@section('page-title')
    <div class="page-title">
        <h3>微信设备管理</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="/admin">Home</a></li>
                <li class="active">微信设备管理</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body" style="min-width:900px;">
                    @if(Session::has('result') && Session::get('result') == true)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('msg')}}
                        </div>
                    @endif
                    @if(Session::has('result') && Session::get('result') == false)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('msg')}}
                        </div>
                    @endif
                    <div class="alert alert-danger alert-dismissible" id="error-tips" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <label style="margin-bottom: 0px;">操作失败</label>
                    </div>

                    <div role="tabpanel">
                        <div style="float:right; margin-left: 20px;">
                            <a href="{{route('adminSetUnRedirect')}}"><button type="button" data-ids="" class="btn btn-success" id="un_redirect">解除重定向</button></a>
                            <button type="button" class="btn btn-primary wx_device_apply" data-toggle="modal"><i class="fa fa-plus"></i>申请设备</button>

                        </div>

                        <form class="form-inline" style="float: left;" method="get" action="{{route('adminWxDevicesIndex')}}">
                            <div class="col-md-3" style="width: 100%;float:left;">
                                <select class="form-control" name="wx_mp_id">
                                    <option value="0">全部</option>
                                    @foreach($mps as $mp)
                                    <option value="{{$mp}}" @if($wx_mp_id== $mp) {{"selected"}} @endif >{{\Dajiayao\Model\WeixinMp::find($mp)->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control" name="bind">
                                    <option value="0" @if($bind== '0') {{"selected"}} @endif>全部</option>
                                        <option value="1" @if($bind== '1') {{"selected"}} @endif>绑定</option>
                                        <option value="-1" @if($bind== '-1') {{"selected"}} @endif>未绑定</option>
                                </select>
                                <input type="text" style="width: 250px;" name="kw" class="form-control" id="filter_kw" placeholder="关键字/设备ID/SN/UUID/Major/Minor" value="{{$kw}}"/>
                                <button type="submit"  class="btn btn-info">筛选</button>

                            </div>
                        </form>
                        


                        <!-- Tab Pane -->
                        <div class="tab-content" style="clear: both;">
                            <div role="tabpanel" class="tab-pane active" id="tab1">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="row"><input type="checkbox" class="check-mail-all" id="select-all" >设备ID</th>
                                            <th>SN</th>
                                            <th>UMM</th>
                                            <th>备注</th>
                                            <th>公众号</th>
                                            <th>配置页面数</th>
                                            @if(Auth::user()->role == \Dajiayao\User::ROLE_ADMIN)
                                            <th>重定向</th>
                                            @endif
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($wx_devices)
                                        @foreach($wx_devices as $device)
                                            <tr>
                                                <td scope="row">
                                                    <label><div class="checker">
                                                            <input type="checkbox" class="check-item" name="id[]" value="{{$device->id}}">
                                                        </div>{{$device->device_id}}
                                                    </label>
                                                </td>
                                                <td>
                                                    @foreach($device->sn as $sn)
                                                        {{$sn->sn}}
                                                        <br/>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if(!empty($device->uuid))
                                                        <a class="toggle-umm-detail" data-toggle="modal" data-target="#umm-detail" style="cursor: pointer;">{{substr($device->uuid, 28, 8)}}</a>
                                                        <input type="hidden" id="hiddenDetailUuid" value="{{$device->uuid}}"/>
                                                        <input type="hidden" id="hiddenDetailMajor" value="{{$device->major}}"/>
                                                        <input type="hidden" id="hiddenDetailMinor" value="{{$device->minor}}"/>
                                                    @endif
                                                    @include('admin.popup.umm-detail')
                                                </td>
                                                <td>{{$device->comment}}</td>
                                                <td>{{$device->mp->name}}</td>
                                                <td>
                                                    {{$device->getPageCount()}}
                                                </td>
                                                @if(Auth::user()->role == \Dajiayao\User::ROLE_ADMIN)
                                                    @if($device->redirect_url)
                                                        <td title="{{$device->redirect_url}}">
                                                            <a target="_blank" href="{{$device->redirect_url}}">
                                                                {{$device->redirect_url ? $device->redirect_name : "未设置"}}
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td>未设置</td>
                                                    @endif
                                                @endif

                                                <td>
                                                    <a href="{{route('adminGetUpdate',[$device->id])}}">
                                                        修改备注
                                                    </a>
                                                    <a href="{{route('adminGetBindPage',[$device->id])}}">
                                                        配置页面
                                                    </a>


                                                    @if(Auth::user()->role == \Dajiayao\User::ROLE_ADMIN)
                                                    <a href="{{route('adminSetRedirect',[$device->id])}}">
                                                        重定向
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                            @endif


                                        </tbody>
                                    </table>
                                     {!!$wx_devices->appends(Input::all())->render()!!}
                                </div>
                            </div><!-- Tab Pane 1 -->
                        </div>

                    </div>
                </div>
            </div><!-- panel -->
        </div><!-- col-md-12 -->
    </div><!-- Row -->


    @include('admin.popup.apply_wx_device')

@stop

@section('js')
    <script src="/themeforest/js/pages/form-elements.js"></script>
    <script>
        $(function(){
            $("#select-all").click(function(){
                if($(this).parent('span').attr('class') !== 'checked'){
                    $(".check-item").parent("span").removeAttr("class", "checked");
                    $('.check-item').removeAttr('checked');
                }else{
                    $(".check-item").parent("span").attr("class", "checked");
                    $('.check-item').attr('checked','checked');
                }

            });

            $("#un_redirect").click(function(){
                var item = $(".check-item");
                console.log(item);
                var id="";
                for(var i=0;i<item.length;i++){
                    if(item[i].checked == true){
                        id +=item[i].value+','
                    }
                }
                id = id.substr(0,id.length-1);

                if(id ==""){
                    alert("请选择要操作的设备");
                    return false;
                }
                $("#un_redirect").parent('a').attr('href','/admin/wx_devices/un_redirect?id='+id)

            })

        })
    </script>
@stop