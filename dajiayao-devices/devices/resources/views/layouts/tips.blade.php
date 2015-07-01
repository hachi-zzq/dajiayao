@if(Session::get("success_tips"))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ Session::get("success_tips") }}</strong>
    </div>
@elseif(Session::get("error_tips"))
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ Session::get("error_tips") }}</strong>
    </div>
@endif