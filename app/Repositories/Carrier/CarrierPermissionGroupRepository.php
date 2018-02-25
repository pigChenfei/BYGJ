<?php

namespace App\Repositories\Carrier;

//use App\Criteria\Carrier\CarrierServiceTeamSelectCriteria;
use App\Models\RolesModel\PermissionGroup;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;
class CarrierPermissionGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_name',
        'sort',
        'parent_id'
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
        return PermissionGroup::class;
    }

    public function allPermissionGroup()
    {

            return $this->model->where([
                ['parent_id', '=', '0'],
            ])->get();
    }

    public function allParentGroup($groupid)
    {
        return $this->model->where([
            ['parent_id', '=', $groupid],
        ])->get();
    }

}
