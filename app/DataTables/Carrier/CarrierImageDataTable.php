<?php

namespace App\DataTables\Carrier;

use App\Models\Image\CarrierImage;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierImageDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_images.datatables_actions')
            ->addColumn('image_review',function(CarrierImage $image){
                return '<div style="width:150px;height:80px;background: url('.$image->imageAsset().') no-repeat;background-size:cover"></div>';
            })
            ->addColumn('image_url',function(CarrierImage $image){
                return $image->imageAsset();
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierImages = CarrierImage::with('imageCategory')->orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierImages);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            //->addAction(['width' => '10%'])
            ->addAction(['width' => '10%','title' => '操作'])
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
                'buttons' => [
                ],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                        var api = this.api();
                　　    var startIndex= api.context[0]._iDisplayStart;
                    　　api.column(0).nodes().each(function(cell, i) {
                    　　　　cell.innerHTML = startIndex + i + 1;
                    　　});
                    }']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['data' => 'id'],
            '图片地址' => ['name' => 'image_path', 'data' => 'image_url'],
            '预览图'      => ['data' => 'image_review'],
            '所属类别' => ['name' => 'image_category', 'data' => 'image_category.category_name'],
            '图片大小' => ['name' => 'image_size', 'data' => 'image_size','render' => 'function(){
                return this.image_size / 1000 + "KB";
            }'],
            '备注' => ['name' => 'remark', 'data' => 'remark']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierImages';
    }
}
