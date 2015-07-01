$( document ).ready(function() {
    
    // Write your custom Javascript codes here...

    var mans;
    var device_id;
    var device_ids = Array();
    var wx_devices;
    $("#btn-select").click(function(){
        $.blockUI({message: $("#filter"), focusInput: false});
        $(".blockMsg ").css({"top": "10px", "width": "600px", "left": "20%", "cursor": "default"});
        $("#hiddenStatus").val($("#hiddenDeviceStatus").val());
    });

    $("#check-all").click(function(){
        if($(this).parent("span").attr("class") == "checked"){
            $(".check-item").parent("span").attr("class", "checked");
        }else{
            $(".check-item").parent("span").removeAttr("class", "checked");
        }
    });
    $("#btn-alloc-top").click(function(){
        $.blockUI({message: $("#alloc-app"), focusInput: false});
        $(".blockMsg ").css({"top": "10px", "width": "600px", "left": "20%", "cursor": "default"});
        device_id = null;
        device_ids = [];
        $(".check-item").each(function(i,e){
            if($(e).parent("span").hasClass("checked")){
                var id = $(e).parents("tr").children().last().children(".hiddenDeviceId").val();
                device_ids.push(id);
            }
        });
    });

    //单独分配
    $(".devices-alloc-app").click(function(){
        $.blockUI({message: $("#alloc-app"), focusInput: false});
        $(".blockMsg ").css({"top": "10px", "width": "600px", "left": "20%", "cursor": "default"});
        device_id = $(this).siblings("#hiddenDeviceId").val();
        device_ids = null;
    });
    $("#btn-alloc-app").click(function(){
        var app_ids = new Array();
        $("input[type=radio].apps-list-item").each(function(i, e){
            if($(e).parent("span").hasClass("checked")){
                app_ids.push($(e).val());
            }
        });
        var params = {id: device_id, ids: device_ids, app_ids: app_ids};
        $.get("/admin/devices/alloc", params, function(data){
            $.unblockUI('fast');
            if(data.result == 0){
                $("#error-tips").removeClass("alert-success").addClass("alert-danger").show().children("label").text(data.msg);
            }else{
                location.reload();
                //$("#error-tips").removeClass("alert-danger").addClass("alert-success").show().children("label").text(data.msg);
            }
        });
    });

    $("#btn-export-sn").click(function(){
        device_ids = [];
        $(".check-item").each(function(i,e){
            if($(e).parent("span").hasClass("checked")){
                var id = $(e).parents("tr").children().last().children(".hiddenDeviceId").val();
                device_ids.push(id);
            }
        });
        $.post("/genpdf", {ids:device_ids}, function(data){
            if(data.result == 1){
                $("#success-tips").show().children("a").attr("href", data.link);
            } else {
                $("#error-tips").show().children("button").siblings("label").html(data.msg);
            }
        });
        return false;
    });

    $("#select-man").change(function(){
        var man_id = $(this).val();
        $("#select-model").empty();
        $.get("/admin/manufacturersAjax", {id:man_id}, function(data){
            mans = data;
            $("#select-model").val(data.name);
            var models = data.models;
            var option = '';
            for(var i = 0; i < models.length; i++){
                option += '<option class="model-id" value="' + models[i].id + '">' + models[i].name + '</option>';
            }
            $("#select-model").append(option);
            $("#manufacturer_sn").val(models[0].manufacturer_sn);
            $("#manufacturer_create").val(models[0].generated_at);
            $("#battery_lifetime").val(models[0].battery_lifetime);
            $("#battery_expire").val(models[0].battery_outdate);
            $("device_password").val(models[0].default_password);
        });
    });

    $("#select-model").change(function(){
        var model_id = $(this).val();
        var models = mans.models;
        for(var i =0; i < models.length; i++){
            if(models[i].id == model_id) {
                $("#manufacturer_sn").val(models[i].manufacturer_sn);
                $("#manufacturer_create").val(models[i].generated_at);
                $("#battery_lifetime").val(models[i].battery_lifetime);
                $("#battery_expire").val(models[i].created_at);
                $("device_password").val(models[i].default_password);
                return;
            }
        }
    });

    $("#btn-burn-in").click(function(){
        var params = {
            id: device_id,
            model_id: $("#select-model").val(),
            man_sn: $("#manufacturer_sn").val(),
            uuid: $("#uuid").html(),
            major: $("#major").html(),
            minor: $("#minor").html(),
            password: $("#device_password").val(),
            battery_expire: $("#battery_expire").val(),
            wx_device_id: $("#hiddenWxDeviceID").val(),
            _token: $("#_token").val()
        };
        $.get("/admin/devices/burnin", params, function(data){
            $.unblockUI('fast');
            location.reload();
        });
    });

    $(".close, .btn-close").click(function(){
        $.unblockUI('fast');
    });

    $(".devices-burn-in").click(function(){
        $.blockUI({
            message: $("#burn-in"),
            focusInput: false
        });
        $(".blockMsg ").css({"top": "10px", "width": "1000px", "left": "15%", "cursor": "default", "overflow-y": "auto", "height": "653px"});
        device_id = $(this).siblings("#hiddenDeviceId").val();
        $.get("/currentAvailableDevice", {id:device_id}, function(data){
            if(data.result == 1){
                $("#manufacturer_sn").val(data.device.manufacturer_sn);
                $("#sn").text(data.device.sn);
                $("#uuid").text(data.wx_devices.uuid);
                $("#major").text(data.wx_devices.major);
                $("#minor").text(data.wx_devices.minor);
                $("#hiddenWxDeviceID").val(data.wx_devices.id);
            }
        });
    });

    $(".wx_device_apply").click(function(){
        $.blockUI({ message: $("#apply") });
        $(".blockMsg ").css({"top": "10px", "width": "1000px", "left": "15%", "cursor": "default"});
    });

    $("#btn-modify-umm").click(function(){
        $("#modify-umm-content").empty();
        $("#modify-umm-d").show();
        $.get("/admin/wxdevices", {sn:$("#sn").text()}, function(data){
            var wxdevices = data.wx_devices;
            wx_devices = data.wx_devices; // global
            var hdom = '';
            for(var i = 0; i < wxdevices.length; i++){
                var device = wxdevices[i].sn;
                var sn = '';
                if(device.length > 0){
                    for(var j = 0; j < device.length; j++){
                        sn += device[j].sn + '</br>';
                    }
                }
                hdom += '<tr>'
                    + '<td scope="row"><input name="select-wx-device-radio" type="radio" class="select-wx-device" value="' + wxdevices[i].id + '"/></td>'
                    + '<td>' + wxdevices[i].device_id + '</td>'
                    + '<td>' + wxdevices[i].comment + '</td>'
                    + '<td>' + wxdevices[i].uuid.substr(0, 10) + '...' + '</td>'
                    + '<td>' + wxdevices[i].major + '</td>'
                    + '<td>' + wxdevices[i].minor + '</td>'
                    + '<td>' + sn + '</td>'
                    + '</tr>';
            }
            $("#modify-umm-content").append(hdom);
            return false;
        });
    });
    $(".btn-umm-close").click(function(){
        $("#modify-umm-d").hide();
        $("#modify-umm-content").empty();
    });
    $("#btn-umm-ok").click(function(){
        var wx_device_id = $("input[name='select-wx-device-radio']:checked").val();
        for(var i = 0; i < wx_devices.length; i++){
            if(wx_device_id == wx_devices[i].id){
                $("#uuid").text(wx_devices[i].uuid);
                $("#major").text(wx_devices[i].major);
                $("#minor").text(wx_devices[i].minor);
                break;
            }
        }
        $("#modify-umm-d").hide();
        $("#hiddenWxDeviceID").val(wx_device_id);
        return true;
    });

    $("#check-devices-all").click(function(){
        if($(this).parent("span").attr("class") == "checked"){
            $(".check-device-item").parent("span").addClass("checked");
        }else{
            $(".check-device-item").parent("span").removeClass("checked");
        }
    });
    $("#btn-bind-page-save").click(function(){
        $(this).addClass("disabled");
        var wx_device_ids = [];
        $(".check-device-item").each(function(i,e){
            if($(e).parent("span").hasClass("checked")){
                var id = $(e).parents("tr").children().last(".hiddenWxDeviceID").val();
                wx_device_ids.push(id);
            }
        });
        $.post("/admin/wxpages/bind", {id: $("#hiddenWxPageID").val(), wx_device_ids: wx_device_ids}, function(data){
            if(data.result == 0){
                $("#btn-bind-page-save").removeClass("disabled");
                $(".alert").removeClass("alert-success").addClass("alert-danger").html(data.msg).show();
            }else{
                location.href = '/admin/wxpages';
            }
        });
    });

    $(".toggle-umm-detail").click(function(){
        $("#umm-detail-p-uuid").text("UUID:" + $(this).siblings("#hiddenDetailUuid").val());
        $("#umm-detail-p-major").text("Major:" + $(this).siblings("#hiddenDetailMajor").val());
        $("#umm-detail-p-minor").text("Minor:" + $(this).siblings("#hiddenDetailMinor").val());
        return true;
    });

    //$(".wxpage-delete").click(function(){
    //    if(confirm("确认删除吗？")){
    //        var pageid = $(this).siblings(".hiddenPageId").val();
    //        $.get("/admin/wxpages/delete/" + pageid, {}, function(data){
    //            if(data.result == 1){
    //                location.reload();
    //            }else{
    //                $("#error-tips").show().html(data.msg);
    //            }
    //        });
    //    }else{
    //        return false;
    //    }
    //    return true;
    //});
});