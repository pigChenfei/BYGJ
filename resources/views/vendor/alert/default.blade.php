<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">模态框标题</h4>
            </div>
            <div class="modal-body" id="modalContent">
                模态框内容
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="closeButton">取消</button>
                <button type="button" class="btn btn-primary" id="confirmButton">确定</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-danger" id="myErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">模态框标题</h4>
            </div>
            <div class="modal-body" id="modalContent">
                模态框内容
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal" id="closeButton">取消</button>
                <button type="button" class="btn btn-outline" id="confirmButton">确定</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $(function(){
            $('#myModal,#myErrorModal').on('show.bs.modal', function (e) {
                var _me = this;
                // do something...
                var btn = e.relatedTarget ? $(e.relatedTarget) : $(_me);

                window.btn = btn;

                var shown_callback = btn.data('shown-callback');
                $(_me).find('#modalTitle').html(btn.data('title'));
                $(_me).find('#modalContent').html(btn.data('content'));

                if(shown_callback){
                    eval(shown_callback);
                }

                var confirm_callback = btn.data('comfirm-callback');

                function hideModal(){
                    var dfd = $.Deferred();
                    setTimeout(function () {
                        $(_me).modal('hide');
                        dfd.resolve();
                    }, 100);
                    return dfd.promise();
                };

                $(this).find('#confirmButton').unbind().on('click',function(){
                    if(confirm_callback){
                        hideModal().then(eval(confirm_callback))
                    }
                })

            });

        })
    </script>
@endsection
