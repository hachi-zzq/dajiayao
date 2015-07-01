<!-- Modal 分配应用 -->
<div class="" id="alloc-app-batch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">批量分配应用</h4>
            </div>
            <div class="modal-body">
                @include('admin.popup.allocapp-common')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-close">关闭</button>
                <button type="button" class="btn btn-success" id="btn-alloc-batch">分配</button>
            </div>
        </div>
    </div>
</div>