<?php
namespace App\Http\Controllers\Agent;

use App\Http\Requests\Agent;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use App\Repositories\Agent\AgentUserRepository;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Response;
use App\Models\CarrierAgentUser;
use App\Models\CarrierAgentLevel;
use App\Models\AgentNews\AgentNewsRelation;

class AgentAccountCenterController extends AppBaseController
{

    /** @var  CarrierAgentUserRepository */
    private $agentUserRepository;

    public function __construct(AgentUserRepository $agentUserRepo)
    {
        $this->agentUserRepository = $agentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param CarrierAgentUserDataTable $carrierAgentUserDataTable
     * @return Response
     */
    public function index()
    {
        // 代理用户个人信息
        $agentAccountCenter = CarrierAgentUser::where([
            'id' => \WinwinAuth::agentUser()->id
        ])->with('agentBankCard.bankType')->first();
        // dd($agentAccountCenter->agentBankCard);
        if (empty($agentAccountCenter)) {
            return $this->renderNotFoundPage();
        }
        if ($agentAccountCenter['agent_level_id']) {
            // 运营商代理等级信息
            $carrierAgentLevel = CarrierAgentLevel::where([
                'id' => $agentAccountCenter['agent_level_id']
            ])->first();
            // 代理名称
            $carrierAgentLevelType = CarrierAgentLevel::typeMeta()[$carrierAgentLevel->type];
        } else {
            $carrierAgentLevel = null;
            $carrierAgentLevelType = null;
        }
        
        return view(\WinwinAuth::agentUser()->template_agent_admin . '.agent_account_center.index')->with([
            'agentAccountCenter' => $agentAccountCenter,
            'carrierAgentLevel' => $carrierAgentLevel,
            'carrierAgentLevelType' => $carrierAgentLevelType
        ]);
    }

    /*
     * 更新代理信息
     */
    public function agentInformationUpdate(Agent\UpdateCarrierAgentUserRequest $request, CarrierAgentUserRepository $agentUserRepository)
    {
        $input = $request->all();
        $id = \WinwinAuth::agentUser()->id;
        $agentUser = CarrierAgentUser::where('id', $id)->first();
        if (empty($agentUser)) {
            return $this->renderNotFoundPage();
        }
        
        $result = $agentUserRepository->update($input, $id);
        if ($result) {
            return $this->sendSuccessResponse(route('agentAccountCenters.index'));
        } else {
            return $this->sendErrorResponse('信息保存失败', 404);
        }
    }

    /**
     * *
     * 邮箱验证码修改密码
     * add by tlt
     */
    public function modifyPassword(Request $request)
    {
        $email = $request->input('email', '');
        $verification_code = $request->input('code', '');
        $password = $request->input('password', '');
        $type = $request->input('type', '');
        if ($email != \WinwinAuth::agentUser()->email) { // 邮箱验证码不正确
            return $this->sendErrorResponse('该邮箱与当前登录账号邮箱不一致', 500);
        }
        // 从redis 中查询游戏验证码
        $redis = Redis::connection();
        $code = $redis->get($email . 'code');
        if ($code != $verification_code) {
            return $this->sendErrorResponse('邮箱验证码错误', 500);
        }
        // 保存密码
        if ($type == 'denglu') {
            \WinwinAuth::agentUser()->password = bcrypt($password);
        } elseif ($type == 'qukuan') {
            \WinwinAuth::agentUser()->pay_password = bcrypt($password);
        }
        \WinwinAuth::agentUser()->save();
        // 清空redis中的验证码
        $redis->set($email . 'code', null);
        return $this->sendSuccessResponse();
    }

    public function bindEmail(Request $request)
    {
        $emailConf = CarrierPasswordRecoverySiteConf::first();
        if (empty($emailConf) || empty($emailConf->is_open_email_send_function) || empty($emailConf->smtp_username)) {
            return $this->sendErrorResponse('对不起，该运营商未开启配置邮箱验证功能，请联系运营商', 500);
        }
        $verifiCode = createRand(10); // 生成随机码
        $email = $request->input('email', ''); // 收件人邮箱
        $payerInfo = CarrierAgentUser::where('email', $email)->first();

        if (empty($email)) {
            return $this->sendErrorResponse('邮箱不能为空', 500);
        }
        if (! empty($payerInfo)) {
            return $this->sendErrorResponse('邮箱已存在', 500);
        }
        if ($request->has('type') && $request->get('type') == 'player'){
            return $this->sendResponse($email);
        }
        $url = url('/homes.bindEmail?bindEmail=' . $verifiCode);

        try {
            // 邮件配置
            $backup = Mail::getSwiftMailer();
            $transport = \Swift_SmtpTransport::newInstance($emailConf->smtp_server, $emailConf->smtp_service_port, $emailConf->smtp_encryption);
            $transport->setUsername($emailConf->smtp_username);
            $transport->setPassword($emailConf->smtp_password);
            $gmail = new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
            // 发送邮件
            Mail::send('email.bindEmail', [
                'code' => $url
            ], function ($message) use ($email, $emailConf) {
                $message->from($emailConf->smtp_username, $emailConf->mail_sender)
                    ->to($email)
                    ->subject('绑定邮箱');
            });
            // 重置原邮件配置
            Mail::setSwiftMailer($backup);

            // 保存邮件验证码
            $redis = Redis::connection();
            $redis->set($verifiCode, \WinwinAuth::agentUser()->username.'-'.\WinwinAuth::agentUser()->carrier_id.'-'.$email.'-'.'agent');
            return $this->sendResponse($email);
        } catch (\Exception $e) {
            \WLog::error('======>邮件发送失败:' . $e->getMessage());
            return $this->sendErrorResponse('发送失败，请重试');
        }
    }
}
