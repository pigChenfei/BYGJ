<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/1
 * Time: 下午1:21
 */

namespace App\Services;

use Illuminate\Support\Facades\Lang;

class DataTableScriptService
{

    /**
     * 创建编辑/新增模态Div 如果需要弹框则在index.blade.php中必须添加
     * @return string
     */
    public function createEditOrAddModal()
    {
        return '<div class="modal fade" id="editAddModal" role="dialog" aria-labelledby="myModalLabel"></div>';
    }

    /**
     * 创建按钮打开编辑/新增模态框点击事件
     * @param $addFormUrl
     * @return string
     */
    public function addOrEditModalShowEventScript($addFormUrl,$callBackScript = null)
    {
        return '$(\'#overlay\').show();var _me = this;this.disabled = true;$(\'#editAddModal\').load(\'' . $addFormUrl . '\',null,function(){ _me.disabled = false ;$(\'#overlay\').hide();'.($callBackScript ? $callBackScript : '' ).'$(\'#editAddModal\').modal(\'show\');})';
    }


    /**
     * 编辑表单 保存按钮点击事件
     * @param $editSubmitServerUrl
     * @param null $callBackScript
     * @return string
     */
    public function editFormSubmitActionScript($editSubmitServerUrl, $callBackScript = null)
    {
        return $this->ajaxSubmitScript($editSubmitServerUrl,'编辑',$callBackScript.'
            $(\'#editAddModal\').modal(\'hide\');
            if(window.LaravelDataTables){
                window.LaravelDataTables[\'dataTableBuilder\'].ajax.reload();
            };
        ');
    }


    /**
     * 编辑表单 保存,取消按钮脚本事件
     * @param $editSubmitServerUrl
     * @param null $callBackScript
     * @return string
     */
    public function editFormSubmitAndCancelButtonsScript($editSubmitServerUrl, $callBackScript = null)
    {
        return '<button class="btn btn-primary" onclick="' . $this->editFormSubmitActionScript($editSubmitServerUrl,$callBackScript) . '">' . Lang::get('common.save') . '</button>
                    <a onclick="$(this).parents(\'.modal\').modal(\'hide\')" class="btn btn-default">' . Lang::get('common.cancel') . '</a>';
    }


    /**
     * 新增表单 保存按钮点击事件
     * @param $addSubmitServerUrl
     * @param null $callBackScript
     * @return string
     */
    public function addFormSubmitActionScript($addSubmitServerUrl, $callBackScript = null)
    {
        return $this->ajaxSubmitScript($addSubmitServerUrl,'新增',$callBackScript.'
            $(\'#editAddModal\').modal(\'hide\');
            if(window.LaravelDataTables){
                window.LaravelDataTables[\'dataTableBuilder\'].ajax.reload();
            };
        ');
    }


    /**
     *生成表单Ajax请求
     * @param $serverUrl
     * @param $actionType
     * @param null $successCallBackScript 请求成功回调函数;
     * @return string
     */
    public function ajaxSubmitScript($serverUrl, $actionType, $successCallBackScript = null){
        return '   
                        var _me = this;
                        if (this.tagName == \'FORM\'){
                            _me = $(this).find(\'*[type=submit]\')[0]
                        }
                        _me.disabled = true;
                        var _originText = $(_me).text();
                        var form = $(_me).parents(\'form\');
                        $(_me).text(\'提交中...\');
                        $.ajax({
                            url:\'' . $serverUrl . '\',
                            data:form.serialize(),
                            type:\'POST\',
                            success:function(e){
                                toastr.clear();
                                if(e.success == true){
                                    toastr.success(\''.$actionType.'成功\');
                                    '.($successCallBackScript ?: '').'
                                }else{
                                    toastr.error(e.message || \''.$actionType.'失败\', \'出错啦!\')
                                }
                                _me.disabled = false;
                                $(_me).text(_originText);
                            },
                            error:function(xhr){
                                toastr.clear();
                                var message = xhr.responseJSON.message || xhr.statusText
                                toastr.error(message || \''.$actionType.'失败\', \'出错啦!\');
                                _me.disabled = false;
                                $(_me).text(_originText);
                            }
                        });';

    }


    /**
     * 新增表单 保存,取消按钮点击事件
     * @param $addSubmitServerUrl
     * @return string
     */
    public function addFormSubmitAndCancelButtonsScript($addSubmitServerUrl,$callBackScript = null)
    {
        return '<button class="btn btn-primary" onclick="' . $this->addFormSubmitActionScript($addSubmitServerUrl,$callBackScript) . '">' . Lang::get('common.save') . '</button>
                <a onclick="$(\'#editAddModal\').modal(\'hide\')" class="btn btn-default">' . Lang::get('common.cancel') . '</a>';
    }

    /**
     * 删除按钮点击事件
     * @param $deleteServerUrl
     * @param string $confirmTitle
     * @param string $confirmContent
     * @return string
     */
    public function createDeleteButtonScript($deleteServerUrl, $confirmTitle = '确认删除?', $confirmContent = '点击确定删除当前选中项')
    {
        return '<button type="button" class="btn btn-danger btn-xs" data-shown-callback="" data-comfirm-callback="
                $.ajax({
                    url:\'' . $deleteServerUrl . '\',
                    type:\'POST\',
                    data:{_method:\'DELETE\'},
                    success:function(e){
                        toastr.clear();
                        if(e.success == true){
                             toastr.success(\'删除成功\');
                             if(window.LaravelDataTables){
                                        window.LaravelDataTables[\'dataTableBuilder\'].ajax.reload();
                             }
                        }else{
                            toastr.error(e.message || \'删除失败\', \'出错啦!\')
                        }
                    },
                    error:function(xhr){
                        toastr.clear();
                        toastr.error(xhr.responseJSON.message || \'删除失败\', \'出错啦!\')
                    }
                })
            " data-title="' . $confirmTitle . '" data-content="' . $confirmContent . '" data-toggle="modal" data-target="#myModal" onclick="">
                <i class="fa fa-trash">' . Lang::get('common.delete') . '</i>
            </button>';
    }
}