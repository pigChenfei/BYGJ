<?php

namespace App\Repositories\Carrier;

//use App\Criteria\Carrier\CarrierServiceTeamSelectCriteria;
use App\Models\RolesModel\Permission;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

class CarrierPermissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_id',
        'name',
        'display_name',
        'description'
    ];

//    public function boot()
//    {
//        $this->pushCriteria(new CarrierServiceTeamSelectCriteria());
//    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Permission::class;
    }

    public function allPermissions($id)
    {
        return $this->model->where([
            ['group_id', '=', $id],
        ])->get();
    }

}
