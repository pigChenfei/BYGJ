<?php
namespace App\Http\Controllers\Web;

use App\Exceptions\AgentAccountException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Web\AgentLoginRequest;
use App\Http\Requests\Web\CreateCarrierAgentUserRequest;
use App\Models\Carrier;
use App\Models\CarrierAgentDomain;
use App\Models\CarrierAgentUser;
use App\Models\CarrierBackUpDomain;
use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Image\CarrierImage;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Player;

// 代理前台控制器
class AgentController extends AppBaseController
{

    public $carrier;

    public $carrierWebConf;

    public function __construct()
    {
        $this->carrier = \WinwinAuth::currentWebCarrier();
        $conf = CarrierWebSiteConf::first();
        if (! $conf) {
            $conf = new CarrierWebSiteConf();
        }
        $this->carrierWebConf = $conf;
    }

    // 首页
    public function index()
    {
        // $webConf = $this->carrierWebConf->agent_index();
        $webConf = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理首页');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.home', compact('webConf'));
    }

    // 合营模式
    public function pattern()
    {
        // $webConf = $this->carrierWebConf->agent_pattern();
        $webConf = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理合营模式');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.pattern', compact('webConf'));
    }

    // 合作协议
    public function protocol()
    {
        $webConf = $this->carrierWebConf->jointly_operated_agreement();
        $image = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理合作协议');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.protocol', compact('webConf', 'image'));
    }

    // 佣金政策
    public function policy()
    {
        $webConf = $this->carrierWebConf->commission_policy();
        $image = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理佣金政策');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.policy', compact('webConf', 'image'));
    }

    // 联系我们
    public function connectUs()
    {
        $webConf = $this->carrierWebConf->contact_us();
        $image = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理联系我们');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.connectUs', compact('webConf', 'image'));
    }

    /**
     * 代理类型二级联动数据
     */
    public function dataAgentLevel(Request $request)
    {
        $data['type'] = $request->get('type');
        $data['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
        $classes = \App\Models\CarrierAgentLevel::where($data)->get();
        return $this->sendResponse($classes);
        // echo json_encode($classes);
    }

    /**
     * 注册界面
     *
     * @param $request
     * @return \View
     */
    public function registerPage()
    {
        if (\WinwinAuth::agentUser()) {
            return redirect('/agent/admin/agentAccountCenters');
        }
        // 获得代理类型下的所有代理
        $carrierAgentLevelName = \App\Models\CarrierAgentLevel::where('type', 1)->get();
        
        $agentRegisterConf = \WinwinAuth::currentWebCarrier()->dashLoginConf;
        // 判断运营商是否禁止注册
        if ($agentRegisterConf->is_allow_agent_register == 0) {
            return view('Web.agents.banRegister');
        }
        $host = \Request::capture()->getHost();
        $is_pro = false;
        $agent = CarrierAgentDomain::byWebsite($host)->first();
        $carrier = Carrier::retrieveBySiteUrl($host)->first();
        $carrierDomain = CarrierBackUpDomain::retrieveByDomainName($host)->first();
        if ($agent) {
            if ($carrier || $carrierDomain) {
                $is_pro = true;
            }
        } else {
            $is_pro = true;
        }
        $webConf = $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '代理我要加入');
            })
            ->orderBy('created_at', 'desc')
            ->first();
        return view('Web.' . $this->carrier->template_agent . '.register',
            compact('agentRegisterConf', 'carrierAgentLevelName', 'is_pro', 'webConf'));
    }

    /**
     * 验证码
     *
     * @return \Response
     */
    public function captcha()
    {
        return $this->sendResponse(\Captcha::img());
    }

    /**
     * 注册处理
     *
     * @param CreatePlayer
     * @return \Response
     */
    // CreatePlayerRequest $request
    public function register(CreateCarrierAgentUserRequest $request, CarrierAgentUserRepository $agentUserRepository)
    {
        // 判断验证码是否正确
        if (! \Captcha::check($request->get('refercode'))) {
            return $this->sendErrorResponse(
                [
                    'field' => 'refercode',
                    'message' => '验证码输入错误'
                ], 403);
        }
        
        $input = $request->all();
        $host = $request->header('host');
        $carrier = \WinwinAuth::currentWebCarrier();
        $hasExist = CarrierAgentUser::where('username', $input['username'])->where('carrier_id', $carrier->id)->first();
        if ($hasExist) {
            return $this->sendErrorResponse(
                [
                    'field' => 'username',
                    'message' => '账号已存在'
                ], 403);
        }
        // 生成随机邀请码
        $input['promotion_code'] = CarrierAgentUser::generateReferralCode();
        
        $websites = array(
            $carrier->site_url
        );
        if (count($carrier->carrierBackUpDomain) > 0) {
            foreach ($carrier->carrierBackUpDomain as $domain) {
                $websites[] = $domain->domain;
            }
        }
        if (! in_array($host, $websites)) {
            $host = $carrier->site_url . '/?ak=' . $input['promotion_code']; // 推广链接
        }
        
        if (! empty($input['promotion_url']) && ! in_array($input['promotion_url'], $websites)) {
            $a = \DB::table('inf_agent_domain')->where('website', $input['promotion_url'])->first(); // CarrierAgentDomain::byWebsite($input['promotion_url'])->first();
            $b = Carrier::retrieveBySiteUrl($input['promotion_url'])->first();
            $c = CarrierBackUpDomain::retrieveByDomainName($input['promotion_url'])->first();
            if ($a || $b || $c) {
                return $this->sendErrorResponse(
                    [
                        'field' => 'promotion_url',
                        'message' => '邀请域名已使用'
                    ], 403);
            }
        } else {
            $input['promotion_url'] = $host . '/?ak=' . $input['promotion_code'];
        }
        if (! starts_with('http://', $input['promotion_url']) && ! starts_with('https://', $input['promotion_url'])) {
            $input['promotion_url'] = 'http://' . $input['promotion_url'];
        }
        
        $input['promotion_url'] = Player::getShortUrl($input['promotion_url']);
        
        // 获取当前网址,判断是否为代理域名,通过代理域名获得父ID
        // $agent = CarrierAgentDomain::byWebsite($host)->first();
        // $carrier = Carrier::retrieveBySiteUrl($host)->first();
        // $carrierDomain = CarrierBackUpDomain::retrieveByDomainName($host)->first();
        $agent = \WinwinAuth::currentWebAgent();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        if ($agent && empty($carrier)) {
            if (! $agent->agentLevel->is_multi_agent || ! $carrier->is_multi_agent) {
                return $this->sendErrorResponse(
                    [
                        'field' => 'agent_level',
                        'message' => '该代理商不支持多级代理推广，请联系该代理'
                    ], 403);
            }
            $input['parent_id'] = \WinwinAuth::currentWebAgent()->id;
            // $input['level'] = \WinwinAuth::currentWebAgent()->level + 1;
        } else {
            if ($request->has('promotion_code')) {
                $pId = CarrierAgentUser::where('promotion_code', $request->get('promotion_code'))->first();
                if (empty($pId)) {
                    return $this->sendErrorResponse(
                        [
                            'field' => 'promotion_code',
                            'message' => '推荐代理商不存在，请重新输入'
                        ], 403);
                }
                if (! $pId->agentLevel->is_multi_agent || ! $pId->carrier->is_multi_agent) {
                    return $this->sendErrorResponse(
                        [
                            'field' => 'agent_level',
                            'message' => '该推荐代理商不支持多级推广，请联系该代理'
                        ], 403);
                }
                $input['parent_id'] = $pId->id;
                // $input['level'] = $pId->level + 1;
            } else {
                $defaultAgent = CarrierAgentUser::where('carrier_id', $carrier->id)->where('is_default', 1)->first();
                $input['parent_id'] = empty($defaultAgent) ? 0 : $defaultAgent->id;
            }
        }
        
        // 运营商ID
        $input['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
        
        // 默认取款密码000000
        $input['pay_password'] = bcrypt('000000');
        
        $input['password'] = bcrypt($request->get('password'));
        
        // 判断是否是推荐用户开户
        if ($request->get('parent_id')) {
            $input['parent_id'] = $request->get('parent_id');
            $agent = $agentUserRepository->create($input);
            if ($agent) {
                return $this->sendSuccessResponse();
            } else {
                return $this->sendErrorResponse('注册失败', 403);
            }
        }
        
        try {
            $agent = $agentUserRepository->create($input);
            // if (!\WinwinAuth::agentAuth()){
            // \WinwinAuth::agentAuth()->loginUsingId($agent->id);
            // }
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            \WLog::error('注册失败', [
                $e->getMessage()
            ]);
            return $this->sendErrorResponse('注册失败', 403);
        }
    }

    /**
     * 登录处理
     */
    public function login(AgentLoginRequest $request)
    {
        if ($request->get('has')) {
            if (! \Captcha::check($request->get('refercode'))) {
                return $this->sendErrorResponse(
                    [
                        'fields' => 'refercode',
                        'message' => '验证码输入错误'
                    ], 403);
            }
        }
        $username = $request->get('username');
        $agentUser = CarrierAgentUser::where('username', $username)->with('agentLoginConf')->first();
        
        if ($agentUser) {
            try {
                if ($agentUser->isActive()) {
                    if (\Hash::check($request->get('password'), $agentUser->password) == true) {
                        \WinwinAuth::agentAuth()->loginUsingId($agentUser->id);
                        return $this->sendSuccessResponse();
                    } else {
                        return $this->sendErrorResponse('账户或密码错误', 403);
                    }
                }
            } catch (AgentAccountException $e) {
                return $this->sendErrorResponse($e->getMessage(), 403);
            }
        } else {
            return $this->sendErrorResponse('账户错误或不存在', 404);
        }
    }

    /**
     * 退出登录
     *
     * @return \\Redirector
     */
    public function logout()
    {
        \WinwinAuth::agentAuth()->logout();
        return redirect(route('agents.index'));
    }
}
