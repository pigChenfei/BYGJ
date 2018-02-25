<script>
    $.fn.serializeJson = function () {
        var serializeObj = {};
        $(this.serializeArray()).each(function () {
            serializeObj[this.name] = this.value;
        });
        return serializeObj;
    };

    $.fn.overlayToggle = function(){
        $('#overlay').toggle();
    };

    $.fn.showOverlayLoading = function(){
        $('#overlay').show();
    };

    $.fn.hideOverlayLoading = function(){
        $('#overlay').hide();
    };


    $.fn.alertSuccess = function(message){
        toastr.clear();
        toastr.success(message);
    };

    $.fn.alertError = function(message){
        toastr.clear();
        toastr.error(message);
    };

    $.fn.winwinAjax = {
        ajaxSuccessCallBack:function (message) {
            $.fn.alertSuccess(message);
        },
        ajaxFailedCallBack:function (message) {
            $.fn.alertError(message)
        },
        sendUpdateAjax:function (url,data,successCallBack, failedCallBack) {
            data['_method'] = 'PATCH';
            $.ajax({
                url:url,
                data:data,
                type:'POST',
                success:function (e) {
                    if(e.success == true){
                        $.fn.winwinAjax.ajaxSuccessCallBack('更新成功');
                        successCallBack();
                    }else{
                        $.fn.winwinAjax.ajaxFailedCallBack(e.message || '更新失败');
                        failedCallBack();
                    }
                },
                error:function (xhr) {
                    $.fn.winwinAjax.ajaxFailedCallBack(xhr.responseJSON.message || xhr.statusText || '更新失败');
                    failedCallBack();
                }
            })
        },
        sendFetchAjax:function (url,data,successCallBack, failedCallBack) {
            $.ajax({
                url:url,
                data:data,
                dataType:'json',
                type:'GET',
                success:function (resp) {
                    toastr.clear();
                    if(resp.success == true){
                        successCallBack(resp);
                    }else{
                        $.fn.winwinAjax.ajaxFailedCallBack(resp.message || '获取数据失败');
                        failedCallBack(resp);
                    }
                },
                error:function (xhr) {
                    var message;
                    if(xhr.responseJSON){
                        message = xhr.responseJSON.message;
                    }
                    $.fn.winwinAjax.ajaxFailedCallBack(message || xhr.statusText || '获取数据失败');
                    failedCallBack();
                }
            })
        },
        buttonActionSendAjax:function (dom,url,data,successCallBack, failedCallBack, ajaxType,options) {
            var type  = ajaxType || 'GET';
            if(type == 'PATCH'){
                data['_method'] = 'PATCH';
            }
            dom && (dom.disabled = true);
            options = $.extend({
                dataType:'json'
            },options);
            $.ajax({
                url:url,
                data:data,
                dataType:options.dataType,
                type: type == 'GET' ? 'GET' : 'POST',
                success:function (resp) {
                    toastr.clear();
                    if(options.dataType == 'json'){
                        if(resp.success == true){
                            successCallBack(resp);
                        }else{
                            $.fn.winwinAjax.ajaxFailedCallBack(resp.message || '操作失败');
                            failedCallBack(resp);
                        }
                    }else{
                        successCallBack(resp);
                    }
                    dom && (dom.disabled = false);
                },
                error:function (xhr) {
                    var message;
                    var responseJson = $.parseJSON(xhr.responseText);
                    if(responseJson.message){
                        message = responseJson.message;
                    }
                    $.fn.winwinAjax.ajaxFailedCallBack(message || xhr.statusText);
                    failedCallBack();
                    dom && (dom.disabled = false);
                }
            })
        }
    }
</script>