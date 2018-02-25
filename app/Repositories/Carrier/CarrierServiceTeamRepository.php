<?php

namespace App\Repositories\Carrier;

use App\Criteria\Carrier\CarrierServiceTeamSelectCriteria;
use App\Models\CarrierServiceTeam;
use Illuminate\Database\Eloquent\Builder;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\RepositoryInterface;

class CarrierServiceTeamRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'team_name',
        'remark',
        'status'
    ];



    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierServiceTeam::class;
    }

    public function allServiceTeams()
    {
        return $this->model->all();
    }

    public function getTree($data,$parent_id = 0){
        $res = array();
        foreach ($data as $v) {
            if ($v['parent_id'] == $parent_id) {
                $res[] = $v;
                $child = $this->getTree($data,$v['id']);
                $res = array_merge($res,$child);
            }
        }
        return $res;
    }
}
