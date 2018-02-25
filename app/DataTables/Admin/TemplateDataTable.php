<?php
namespace App\DataTables\Admin;

use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Def\Template;

class TemplateDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function ajax()
    {
        $dataTable = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Admin.template.datatables_actions')
            ->addColumn('selectCheckbox', function (Template $template) {
            return "<input type=\"checkbox\" value='" . $template->id . "' class=\"square-blue log_check_box\">";
        })
            ->addColumn('type', function (Template $template) {
            if ($template->type == 1) {
                return 'PC模板';
            } else if ($template->type == 2) {
                return '移动端模板';
            } else if ($template->type == 3) {
                return '代理前台模板';
            } else if ($template->type == 4) {
                return '代理后台模板';
            }
        });
        return $dataTable->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $templates = Template::orderBy('updated_at', 'desc');
        return $this->applyScopes($templates);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addColumn([
            'width' => '135px',
            'title' => '<input style="display: none" type="checkbox" class="square-blue selectCheckbox">',
            'data' => 'selectCheckbox',
            'searchable' => false
        ])
            ->columns($this->getColumns())
            ->addAction([
            'width' => '190px',
            'title' => '操作'
        ])
            ->ajax([
            'data' => \Config::get('datatables.ajax.data')
        ])
            ->parameters([
            'paging' => true,
            'searching' => false,
            'ordering' => false,
            'info' => true,
            'dom' => 'Bfrtipl',
            'scrollX' => false,
            'buttons' => [],
            'language' => \Config::get('datatables.language'),
            'drawCallback' => 'function(){
                    var api = this.api();
            　　    $(\'input[type="checkbox"].square-blue, input[type="radio"].square-blue\').iCheck({
                            checkboxClass: \'icheckbox_square-blue\',
                            radioClass: \'iradio_square-blue\'
                        }).show();
                }'
        ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'id' => [
                'name' => 'id',
                'data' => 'id',
                'searchable' => false
            ],
            '模板分类' => [
                'name' => 'type',
                'data' => 'type',
                'searchable' => false
            ],
            '中文名称' => [
                'name' => 'alias',
                'data' => 'alias',
                'searchable' => false
            ],
            '模板名称' => [
                'name' => 'value',
                'data' => 'value',
                'searchable' => false
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'templates';
    }
}
