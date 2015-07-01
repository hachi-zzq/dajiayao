@if(Session::get("success_tips"))
    {{Session::get("success_tips")}}
@elseif(Session::get("error_tips"))
    {{ Session::get("error_tips") }}
@endif