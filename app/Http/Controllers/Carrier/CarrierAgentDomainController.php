<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentDomainDataTable;
use App\Http\Requests\Carrier;
use Illuminate\Http\Request;
use App\Http\Requests\Carrier\CreateCarrierAgentDomainRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentDomainRequest;
use App\Repositories\Carrier\CarrierAgentDomainRepository;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\CarrierAgentDomain;
use App\Models\CarrierAgentUser;
use App\Models\CarrierBackUpDomain;

class CarrierAgentDomainController extends AppBaseController
{

    /** @var  CarrierAgentDomainRepository */
    private $carrierAgentDomainRepository;

    public function __construct(CarrierAgentDomainRepository $carrierAgentDomainRepo)
    {
        $this->carrierAgentDomainRepository = $carrierAgentDomainRepo;
    }

    /**
     * Display a listing of the CarrierAgentDomain.
     *
     * @param CarrierAgentDomainDataTable $carrierAgentDomainDataTable
     * @return Response
     */
    public function index(CarrierAgentDomainDataTable $carrierAgentDomainDataTable)
    {
        $carrierid = \Auth::user()->carrier_id;
        return $carrierAgentDomainDataTable->render('Carrier.carrier_agent_domains.index');
    }

    /**
     * Show the form for creating a new CarrierAgentDomain.
     *
     * @return Response
     */
    public function create(CarrierAgentUserRepository $repository)
    {
        $carrierid = \Auth::user()->carrier_id;
        $carrierAgentUsers = $repository->allAgentUser($carrierid); // 获取代理用户数据
                                                                    // $template =\Config::get('template.template_agent_admin');
        return view('Carrier.carrier_agent_domains.create')->with('carrierAgentUsers', $carrierAgentUsers);
    }

    /**
     * Store a newly created CarrierAgentDomain in storage.
     *
     * @param CreateCarrierAgentDomainRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierAgentDomainRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $where = [
            'status' => 1,
            'carrier_id' => $input['carrier_id'],
            'id' => $input['agent_id']
        ];
        $agent = CarrierAgentUser::with('carrier')->where($where)->first();
        $carrier = $agent->carrier;
        if (is_null($carrier) || $carrier->id !== $input['carrier_id']) {
            return $this->sendErrorResponse('错误的请求');
        }
        $websites = array(
            $carrier->site_url
        );
        if (count($carrier->carrierBackUpDomain) > 0) {
            foreach ($carrier->carrierBackUpDomain as $domain) {
                $websites[] = $domain->domain;
            }
        }
        if ($input['website'] != $carrier->site_url) {
            $b = \App\Models\Carrier::where('site_url', $input['website'])->first();
            if (! in_array($input['website'], $websites)) {
                $a = CarrierAgentDomain::where('website', $input['website'])->first();
                $c = CarrierBackUpDomain::where('domain', $input['website'])->first();
            }
        }
        
        if (! empty($a) || ! empty($b) || ! empty($c)) {
            return $this->sendErrorResponse('此域名已存在');
        }
        if (! empty($agent)) {
            $input['agent_id'] = $agent['id'];
        }
        $carrierAgentUser = $this->carrierAgentDomainRepository->create($input);
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        Flash::success('Carrier Agent Domain saved successfully.');
        return redirect(route('carrierAgentDomains.index'));
    }

    /**
     * Display the specified CarrierAgentDomain.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierAgentDomain = $this->carrierAgentDomainRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDomain)) {
            Flash::error('Carrier Agent Domain not found');
            
            return redirect(route('carrierAgentDomains.index'));
        }
        
        return view('Carrier.carrier_agent_domains.show')->with('carrierAgentDomain', $carrierAgentDomain);
    }

    /**
     * Show the form for editing the specified CarrierAgentDomain.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id, CarrierAgentUserRepository $repository)
    {
        $carrierAgentDomain = $this->carrierAgentDomainRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDomain)) {
            Flash::error('Carrier Agent Domain not found');
            
            return redirect(route('carrierAgentDomains.index'));
        }
        $carrierid = \Auth::user()->carrier_id;
        $carrierAgentUsers = $repository->allAgentUser($carrierid); // 获取代理类型数据
        return view('Carrier.carrier_agent_domains.edit', [
            'carrierAgentDomain' => $carrierAgentDomain,
            'carrierAgentUsers' => $carrierAgentUsers
        ]);
    }

    /**
     * Update the specified CarrierAgentDomain in storage.
     *
     * @param int $id
     * @param UpdateCarrierAgentDomainRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentDomainRequest $request)
    {
        $carrierAgentDomains = $this->carrierAgentDomainRepository->findWithoutFail($id);
        $input = $request->all();
        if (empty($carrierAgentDomains)) {
            Flash::error('Carrier Agent User not found');
            return redirect(route('carrierAgentUsers.index'));
        }
        $agent = $carrierAgentDomains->agent;
        $carrier = $agent->carrier;
        if (is_null($carrier) || $carrier->id !== \WinwinAuth::currentWebCarrier()->id) {
            Flash::error('错误的请求');
            return redirect(route('carrierAgentUsers.index'));
        }
        if ($input['website'] != $carrierAgentDomains->website) {
            $websites = array(
                $carrier->site_url
            );
            if (count($carrier->carrierBackUpDomain) > 0) {
                foreach ($carrier->carrierBackUpDomain as $domain) {
                    $websites[] = $domain->domain;
                }
            }
            if ($input['website'] != $carrier->site_url) {
                $b = \App\Models\Carrier::where('site_url', $input['website'])->first();
                if (! in_array($input['website'], $websites)) {
                    $a = CarrierAgentDomain::where('website', $input['website'])->first();
                    $c = CarrierBackUpDomain::where('domain', $input['website'])->first();
                }
            }
            
            if (! empty($a) || ! empty($b) || ! empty($c)) {
                return $this->sendErrorResponse('此域名已存在');
            }
        }
        
        $carrierAgentDomain = $this->carrierAgentDomainRepository->update($input, $id);
        if (empty($carrierAgentDomain)) {
            Flash::error('Carrier Agent Domain not found');
            return redirect(route('carrierAgentDomains.index'));
        }
        if ($request->ajax()) {
            
            return self::sendResponse([], 'ok');
        }
        
        Flash::success('更新成功.');
        
        return redirect(route('carrierAgentDomains.index'));
    }

    /**
     * Remove the specified CarrierAgentDomain from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $carrierAgentDomain = $this->carrierAgentDomainRepository->findWithoutFail($id);
        if (empty($carrierAgentDomain)) {
            if ($request->ajax()) {
                return self::sendError('无法找到数据', 404);
            }
            Flash::error('无法找到该数据');
            return redirect(route('carrierAgentDomains.index'));
        }
        $this->carrierAgentDomainRepository->delete($id);
        if ($request->ajax()) {
            return self::sendResponse([], 'success');
        }
        Flash::success('删除成功.');
        return redirect(route('carrierAgentDomains.index'));
    }
    // public function destroy($id)
    // {
    // $carrierAgentDomain = $this->carrierAgentDomainRepository->findWithoutFail($id);
    //
    // if (empty($carrierAgentDomain)) {
    // Flash::error('Carrier Agent Domain not found');
    //
    // return redirect(route('carrierAgentDomains.index'));
    // }
    //
    // $this->carrierAgentDomainRepository->delete($id);
    //
    // Flash::success('Carrier Agent Domain deleted successfully.');
    //
    // return redirect(route('carrierAgentDomains.index'));
    // }
}
