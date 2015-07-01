function accMul(arg1,arg2)
{
    var m=0,s1=arg1.toString(),s2=arg2.toString();
    try{m+=s1.split(".")[1].length}catch(e){}
    try{m+=s2.split(".")[1].length}catch(e){}
    return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
}

$( document ).ready(function() {

    // Write your custom Javascript codes here...
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#txtItemCommissionRatio, #txtItemPrice").focusout(function(){
        var ratio = $("#txtItemCommissionRatio").val();
        var price = $("#txtItemPrice").val();
        $("#txtItemCommission").val(accMul(ratio, price));
        return true;
    });

    $("#check-items-all").click(function(){
        if($(this).parent("span").attr("class") == "checked"){
            $(".check-items-single").parent("span").attr("class", "checked");
        }else{
            $(".check-items-single").parent("span").removeAttr("class", "checked");
        }
    });

    $("#btn-puton").click(function(){
        var id = 0;
        var item_ids = [];
        $(".check-items-single").each(function(i,e){
            if($(e).parent("span").hasClass("checked")){
                id = $(e).parents("tr").children().last().children(".hiddenItemId").val();
                item_ids.push(id);
            }
        });
        $.post("{{route('adminItemsShelfStatusBatch')}}", {ids:item_ids, to:1}, function(data){
            location.reload();
        });
    });
    $("#btn-putoff").click(function(){
        var id = 0;
        var item_ids = [];
        $(".check-items-single").each(function(i,e){
            if($(e).parent("span").hasClass("checked")){
                id = $(e).parents("tr").children().last().children(".hiddenItemId").val();
                item_ids.push(id);
            }
        });
        $.post("{{route('adminItemsShelfStatusBatch')}}", {ids:item_ids, to:0}, function(data){
            location.reload();
        });
    });

    $("#check-orders-all").click(function(){
        if($(this).parent("span").attr("class") == "checked"){
            $(".check-orders-single").parent("span").attr("class", "checked");
        }else{
            $(".check-orders-single").parent("span").removeAttr("class", "checked");
        }
    });

    $("#btn-order-deliver").click(function(){
        var params = {
            order_number: $("#hiddenOrderNumber").val(),
            express_id: $("#express_id").val(),
            express_num: $("#express_num").val()
        };
        $.post('/orders/deliverajax', params, function(data){
            location.reload();
        });
    });

    $("#btn-order-modify").click(function(){
        var params = {
            order_number: $("#hiddenOrderNumber").val(),
            comment: $("#order-comment").text()
        };
        $.post('/orders/updateajax', params, function(data){
            location.reload();
        });
    });
});