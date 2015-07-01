<!-- Modal 烧号 -->
<div class="" id="apply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; top: 10px">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">申请微信设备</h4>
            </div>
            <form method="post" action="{{route('adminApplyWxDevice')}}">
            <div class="modal-body">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                            </ul>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="panel panel-white">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">公众号</label>
                                                <div class="col-sm-10">
                                                    <select id="select-man" name="wx_mp_id" class="form-control m-b-sm">
                                                        @foreach($mps as $mp)
                                                                <option class="manufacturer_id" value="{{$mp}}">{{\Dajiayao\Model\WeixinMp::find($mp)->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">数量</label>
                                                <div class="col-sm-10">
                                                        <input type="text" name="sum" class="form-control" id="manufacturer_sn" value=" "/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div><!-- col-md-6 -->
                            </div>

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-close">关闭</button>
                    <button type="submit" class="btn btn-success" id="btn-burn-in">申请</button>
                </div>
            </div>
                </form>

        </div>
    </div>
</div>

