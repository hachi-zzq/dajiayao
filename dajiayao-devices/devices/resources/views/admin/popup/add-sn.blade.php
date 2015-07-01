<!-- Modal 增加SN -->
<div class="modal fade" id="add-sn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form id="add-sn-form" action="/admin/devices/add" method="post">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">增加SN</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="name-input" name="count" class="form-control" placeholder="数量" required="">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-success">增加</button>
                </div>
            </div>
        </div>
    </form>
</div>