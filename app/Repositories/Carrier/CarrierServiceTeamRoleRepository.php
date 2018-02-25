<?php

namespace App\Repositories\Carrier;

//use App\Criteria\Carrier\CarrierServiceTeamSelectCriteria;
use App\Models\CarrierServiceTeamRole;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

class CarrierServiceTeamRoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'permission_id',
        'team_id'
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
        return CarrierServiceTeamRole::class;
    }
}
