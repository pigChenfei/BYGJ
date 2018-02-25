<div class="modal-dialog modal-lg" style="min-width: 1280px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑素材</h4>
        </div>
        <div class="modal-body" id="modalContent">
            @include('Carrier.carrier_images.fields')
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                <button class="btn btn-primary edit-sure">保存</button>
                <a onclick="$(this).parents('.modal').modal('hide')" class="btn btn-default">取消</a>
            </div>
        </div>
    </div>
</div>
<script>
    $('.edit-sure').on('click', function () {
        var _this = $(this);
        var obj = {};
        obj.image_category =  $('#category').val();
        obj.url_type =  $('input[name=url_type]:checked').val();
        if (obj.url_type == 1){
            obj.url_link =  $('.url_link option:selected').val();
        }else{
            obj.url_link =  $('.url_link').val();
        }
        obj.image_path =  $('input[name=image_path]').val();
        obj.id =  $('input[name=id]').val();
        _this.removeClass('edit-sure');
        var _originText = _this.text();
        _this.text('提交中...');
        $.ajax({
           url:'{!! route('carrierImages.store') !!}',
           type:'post',
           data:obj,
           dataType:'json',
            success:function(data){
                toastr.clear();
                if(data.success == true){
                    toastr.success('编辑成功');

                    $('#editAddModal').modal('hide');
                    if(window.LaravelDataTables){
                        window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                    };

                }else{
                    toastr.error(e.message || '编辑失败', '出错啦!')
                }
                _this.text(_originText);
            },
            error:function (xhr) {
                toastr.clear();
                var message = xhr.responseJSON.message || xhr.statusText;
                toastr.error(message || '编辑失败', '出错啦!');
                _this.addClass('edit-sure');
                _this.text(_originText);
            }
        });
    });
</script>