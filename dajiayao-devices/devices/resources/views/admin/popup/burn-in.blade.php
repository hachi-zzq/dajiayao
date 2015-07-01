<!-- Modal 烧号 -->
<div class="" id="burn-in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; top: 10px">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">设备烧号</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="panel panel-white">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">厂商</label>
                                            <div class="col-sm-10">
                                                <select id="select-man" class="form-control m-b-sm">
                                                    @foreach($manufacturers as $man)
                                                        <option class="manufacturer_id" value="{{$man->id}}">{{$man->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">型号</label>
                                            <div class="col-sm-10">
                                                <select id="select-model" class="form-control m-b-sm">
                                                    @foreach($manufacturers[0]->models as $model)
                                                        <option class="model-id" value="{{$model->id}}">{{$model->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">厂商SN</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="manufacturer_sn" value="{{$manufacturers[0]->models[0]->manufacturer_sn}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">生产日期</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control date-picker" id="manufacturer_create" value="{{$manufacturers[0]->models[0]->getShortDate()}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">电池寿命</label>
                                            <div class="col-sm-10">
                                                <div class="input-group m-b-sm">
                                                    <input type="text" class="form-control" id="battery_lifetime" aria-describedby="basic-addon2" value="{{$manufacturers[0]->models[0]->battery_lifetime}}"/>
                                                    <span class="input-group-addon" id="basic-addon2">月</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">电量有效期</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control date-picker" id="battery_expire" value="{{$manufacturers[0]->models[0]->getBatteryExpireDate()}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-md-6 -->
                            <div class="col-md-6">
                                <div class="panel panel-white">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">SN</label>
                                            <div class="col-sm-10">
                                                <p class="form-control-static" id="sn"></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">UUID</label>
                                            <p class="form-control-static" id="uuid"></p>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Major</label>
                                            <p class="form-control-static" id="major"></p>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Minor</label>
                                            <p class="form-control-static" id="minor"></p>
                                        </div>
                                        <input type="hidden" id="hiddenWxDeviceID" value=""/>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">MAC</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">配置密码</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="device_password" value="{{$manufacturers[0]->models[0]->default_password}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btn-modify-umm">修改UMM</button>
                        <div id="modify-umm-d" style="display: none; overflow: scroll;">
                            <table class="table" style="text-align: left;">
                                <thead>
                                <tr>
                                    <th scope="row"></th>
                                    <th>微信设备ID</th>
                                    <th>备注</th>
                                    <th>UUID</th>
                                    <th>Major</th>
                                    <th>Minor</th>
                                    <th>SN</th>
                                </tr>
                                </thead>
                                <tbody id="modify-umm-content">

                                </tbody>
                            </table>
                            <div>
                                <button type="button" class="btn btn-success" id="btn-umm-ok">确定</button>
                                <button type="button" class="btn btn-default btn-umm-close">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close">关闭</button>
                <button type="button" class="btn btn-success" id="btn-burn-in">烧号完成</button>
            </div>
        </div>
    </div>
</div>