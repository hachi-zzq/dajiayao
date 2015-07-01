<div class="" id="filter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form class="form-horizontal" action="/admin/devices" method="get">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">筛选</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="panel panel-white">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">应用</label>
                                    <div class="col-sm-10">
                                        <select id="" name="app_id" class="form-control m-b-sm">
                                            @foreach($apps as $a)
                                                <option class="app_id" value="{{$a->id}}">{{$a->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">SN</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="sn" class="form-control" id="filter_sn" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">UUID</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="uuid" class="form-control" id="filter_uuid" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Major</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="major" class="form-control" id="filter_major" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Minor</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="minor" class="form-control" id="filter_minor" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">生成日期从</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="date_from" class="form-control date-picker" id="filter_date_from" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">到</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="date_to" class="form-control date-picker" id="filter_date_to" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close">关闭</button>
                <button type="submit" class="btn btn-success" id="btn-filter-ok">确定</button>
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <input type="hidden" name="status" id="hiddenStatus" value=""/>
            </div>
        </div>
    </form>
</div>