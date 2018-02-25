                <td>
                    <div class="btn-group">

                        <button onclick="location.href='{{route('templates.edit',$id)}}'" class="btn btn-default btn-xs">
                            <i class="fa fa-edit">编辑</i>
                        </button>
                        <button onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(_me,'{!! route('templates.showAssignCarriersModal') !!}',{template_ids:[{!! $id !!}]},function(html){
                                $('#editAddModal').html(html).modal('show');
                                },null,'POST',{dataType:'html'})" class='btn btn-default btn-xs'>
                            <i class="fa fa-edit">分配运营商</i>
                        </button>
                    </div>
                </td>