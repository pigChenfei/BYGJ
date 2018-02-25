<?php

namespace App\Models\Conf;

use App\Entities\CacheConstantPrefixDefine;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Carrier;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CarrierWebSiteConf
 *
 * @package App\Models\Carrier\Conf
 * @version March 13, 2017, 9:55 am UTC
 * @property int $id
 * @property int $carrier_id 所属运营商
 * @property string $site_title 网站标题
 * @property string $site_key_words 网站关键词
 * @property string $site_description 网站描述
 * @property string $site_javascript 网站js
 * @property string $site_notice 网站公告
 * @property string $site_footer_comment 网站底部说明
 * @property string $common_question_file_path 常见问题文件目录
 * @property string $contact_us_file_path 联系我们
 * @property string $about_us_file_path 关于我们
 * @property string $duty_file_path 关于我们
 * @property string $privacy_policy_file_path 隐私政策文件目录
 * @property string $rule_clause_file_path 规则条款文件目录
 * @property string $agent_pattern_file_path 合营模式文件目录
 * @property string $agent_index_file_path 贷款首页文件目录
 * @property string $mobile_about_file_path 手机端关于我们文件目录
 * @property string $mobile_contact_file_path 手机端联系我们文件目录
 * @property string $online_service_file_path 手机端联系我们文件目录
 * @property string $with_draw_comment_file_path 提款说明文件目录
 * @property string $net_bank_deposit_comment 网银存款说明
 * @property string $atm_deposit_comment ATM存款说明
 * @property string $third_part_deposit_comment 第三方存款说明
 * @property string $commission_policy_file_path 佣金政策文件目录
 * @property string $jointly_operated_agreement_file_path 合营协议文件目录
 * @property string $activity_image_resolution 活动图片分辨率 按照*分隔  例如 1024*768
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conf\CarrierWebBannerConf[] $bannerImages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereActivityImageResolution($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereAtmDepositComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereCommissionPolicyFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereCommonQuestionFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereContactUsFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereJointlyOperatedAgreementFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereNetBankDepositComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf wherePrivacyPolicyFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereRuleClauseFilePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteFooterComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteJavascript($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteKeyWords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteNotice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereSiteTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereThirdPartDepositComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebSiteConf whereWithDrawCommentFilePath($value)
 */
class CarrierWebSiteConf extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        static::updated(function (CarrierWebSiteConf $carrierWebSiteConf){
            CarrierInfoCacheHelper::clearCarrierWebsiteConf($carrierWebSiteConf->carrier);
        });
    }

    public $table = 'conf_carrier_web_site';

    /**
     *首页id
     */
    const SITE_INDEX_PAGE_ID = 1;
    /**
     *真人娱乐页面id
     */
    const SITE_FOR_ENJOY_PAGE_ID = 2;
    /**
     *彩票页面id
     */
    const SITE_LOTTERY_PAGE_ID = 3;
    /**
     *电子游戏页面id
     */
    const SITE_ELECTRONIC_GAME_PAGE_ID = 4;
    /**
     *体育游戏页面id
     */
    const SITE_SPORTS_GAME_PAGE_ID = 5;
    /**
     *优惠活动页面id
     */
    const SITE_PREFERENTIAL_ACTIVITIES_PAGE_ID = 6;
    /**
     *帮助页id
     */
    const SITE_HELP_PAGE_ID = 7;
    /**
     *合营代理页id
     */
    const SITE_JOINTLY_OPERATED_PAGE_ID = 8;

    public static function sitePages() {
        return [
            self::SITE_INDEX_PAGE_ID => '首页',
            self::SITE_FOR_ENJOY_PAGE_ID => '真人娱乐页',
            self::SITE_LOTTERY_PAGE_ID => '彩票页面',
            self::SITE_ELECTRONIC_GAME_PAGE_ID => '电子游戏页',
            self::SITE_SPORTS_GAME_PAGE_ID => '体育游戏页',
            self::SITE_PREFERENTIAL_ACTIVITIES_PAGE_ID => '优惠活动页',
            self::SITE_HELP_PAGE_ID => '帮助页',
            self::SITE_JOINTLY_OPERATED_PAGE_ID => '合营代理页'
        ];
    }

    /**
     *
     */
    const CREATED_AT = 'created_at';
    /**
     *
     */
    const UPDATED_AT = 'updated_at';


    /**
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @var array
     */
    public $fillable = [
        'carrier_id',
        'site_title',
        'site_key_words',
        'site_description',
        'site_javascript',
        'site_notice',
        'site_footer_comment',
        'common_question_file_path',
        'contact_us_file_path',
        'about_us_file_path',
        'duty_file_path',
        'privacy_policy_file_path',
        'rule_clause_file_path',
        'with_draw_comment_file_path',
        'net_bank_deposit_comment',
        'atm_deposit_comment',
        'third_part_deposit_comment',
        'commission_policy_file_path',
        'jointly_operated_agreement_file_path',
        'activity_image_resolution',
        'agent_pattern_file_path',
        'agent_index_file_path',
        'mobile_about_file_path',
        'mobile_contact_file_path',
        'online_service_file_path',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'site_title' => 'string',
        'site_key_words' => 'string',
        'site_description' => 'string',
        'site_javascript' => 'string',
        'site_notice' => 'string',
        'site_footer_comment' => 'string',
        'common_question_file_path' => 'string',
        'contact_us_file_path' => 'string',
        'about_us_file_path' => 'string',
        'duty_file_path' => 'string',
        'privacy_policy_file_path' => 'string',
        'rule_clause_file_path' => 'string',
        'with_draw_comment_file_path' => 'string',
        'net_bank_deposit_comment' => 'string',
        'atm_deposit_comment' => 'string',
        'third_part_deposit_comment' => 'string',
        'commission_policy_file_path' => 'string',
        'jointly_operated_agreement_file_path' => 'string',
        'agent_pattern_file_path' => 'string',
        'agent_index_file_path' => 'string',
        'mobile_about_file_path' => 'string',
        'mobile_contact_file_path' => 'string',
        'online_service_file_path' => 'string',
    ];


    /**
     * 取款说明
     * @return null|string
     */
    public function with_draw_comment(){
        //如果存在这个文件目录且文件是存在的
        if($this->with_draw_comment_file_path && \Storage::disk('carrier')->exists($this->with_draw_comment_file_path)){
            //返回这个文件
            return \Storage::disk('carrier')->get($this->with_draw_comment_file_path);
        }
        return ' 为了您的取款更快到账，建议您绑定<i>工商银行、农业银行、招商银行、中国银行、建设银行</i>五大银行卡，如绑定非五大银行卡，非工作时间无法保证2小时内到账。';
    }

    /**
     * 佣金政策
     * @return null|string
     */
    public function commission_policy(){
        if($this->commission_policy_file_path && \Storage::disk('carrier')->exists($this->commission_policy_file_path)){
            return \Storage::disk('carrier')->get($this->commission_policy_file_path);
        }
        return ' <h4><span class="img-circle"></span>佣金政策</h4>
        <div class="commission-box">
            <h5>一、佣金计算</h5>
            <p>1.推广代理佣金计算</p>
            <div class="table-wrap mb-20">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">级别</th>
                        <th class="text-center">公司本月总盈利 (CNY)</th>
                        <th class="text-center">活跃玩家数最低要求</th>
                        <th class="text-center">佣金百分比</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>1-3000</td>
                        <td>3</td>
                        <td>35.00%</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>3000-5000</td>
                        <td>5</td>
                        <td>40.00%</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>5000-10000</td>
                        <td>10</td>
                        <td>45.00%</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>10000-20000</td>
                        <td>20</td>
                        <td>50.00%</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>20001-12000000</td>
                        <td>50</td>
                        <td>55.00%</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h5>二、佣金统计周期和提取周期</h5>
            <p>代理以自然月为一个统计周期，即每月1号到当月最后一天。每月10号之前完成代理佣金结算，代理可在每月10号后，申请提取佣金。佣金将在三个工作日内自动转入提款账户</p>
            <div class="mb-20"></div>
            <h5>三、佣金结算要求</h5>
            <p>1.当月至少满足有效会员5个</p>
            <p>2.账户盈利状态达到500以上</p>
            <p>3.收款人姓名必须与注册真实姓名一致</p>
        </div>';
    }

    /**
     * 合营协议
     * @return null|string
     */
    public function jointly_operated_agreement(){
        if($this->jointly_operated_agreement_file_path && \Storage::disk('carrier')->exists($this->jointly_operated_agreement_file_path)){
            return \Storage::disk('carrier')->get($this->jointly_operated_agreement_file_path);
        }
        return ' <h4><span class="img-circle"></span>合作协议</h4>
        <div class="commission-box">
            <h5>一、注册规约</h5>
            <p>1. 为有效防止非诚信合作商滥用博赢国际所提供的代理优惠制度，公司审查部门将严格审核每位代理商注册时提供的个人资料（包括姓名，邮件及电话等）若经审核发现代理商有任何不良营利企图，或与其他代理商、会员进行合谋套利等不诚信行为，博赢国际将关闭该合作代理商之账户并收回该代理商的所有佣金与优惠。</p>
            <div class="mb-20"></div>
            <h5>二、权责条款</h5>
            <p>(1). 合作伙伴需尽其责任积极销售及推广博赢国际以求双方利润最大化。合作伙伴必须在不违反法律的前提下，进行正面宣传、销售及推广乐宝博。由此产生的宣传、销售及推广时所产生的费用需由合作伙伴自行承担。</p>
            <p>(2). 合作伙伴需尽其责任积极销售及推广博赢国际以求双方利润最大化。合作伙伴必须在不违反法律的前提下，进行正面宣传、销售及推广乐宝博。由此产生的宣传、销售及推广时所产生的费用需由合作伙伴自行承担。</p>
            <p>2.合作伙伴的权利和义务</p>
            <p>(1). 合作伙伴需尽其责任积极销售及推广博赢国际以求双方利润最大化。合作伙伴必须在不违反法律的前提下，进行正面宣传、销售及推广乐宝博。由此产生的宣传、销售及推广时所产生的费用需由合作伙伴自行承担。</p>
            <p>(2). 合作伙伴需尽其责任积极销售及推广博赢国际以求双方利润最大化。合作伙伴必须在不违反法律的前提下，进行正面宣传、销售及推广乐宝博。由此产生的宣传、销售及推广时所产生的费用需由合作伙伴自行承担。</p>
            <p>(3) 同一IP/同一姓名/同一收款账号的会员只能是一个合作商的下线，合作商自已不能成为自已及其他合作商的下线会员。同一IP/同一姓名/同一收款账号只能申请一个合作伙伴账号。</p>
            <div class="mb-20"></div>
            <h5>三、协议期限和终止</h5>
            <p>1.当月至少满足有效会员5个</p>
            <p>2.账户盈利状态达到500以上</p>
            <p>3.收款人姓名必须与注册真实姓名一致</p>
        </div>';
    }

    /**
     * 常见问题
     * @return null|string
     */
    public function common_question(){
        if($this->common_question_file_path && \Storage::disk('carrier')->exists($this->common_question_file_path)){
            return \Storage::disk('carrier')->get($this->common_question_file_path);
        }
//        return NULL;
        return '<div class="qa-box active"><div class="qa-head"><span class="qa"></span>'.'
	        					<span class="qa-title">你们公司合法吗?</span>'.'
	        					<span class="arrow"></span>'.'
	        				</div>'.'
	        				<div class="qa-body" style="display: block;">'.'
	        					<span>'.'
	        						本集团是一个国际性的网上赌场。是国际领先的投注及博彩公司，是世界上最著名的娱乐场所运营企业之一，凭借超越10年的博彩市场运营经验，如今已发展成为集赌场酒店、博彩、休闲、餐饮为一体的大型娱乐公司，集团总部设在菲律宾，香港、加拿大、越南及新加坡均有分部，是全世界最大的博彩公司之一。目前本娱乐场与BBIN技术合作，提供最为多样化的游戏，共同打造业内最高端的游戏平台。'.'
	        					</span>'.'
	        				</div>'.'
	        			</div>'.'
<div class="qa-box">'.'
	        				<div class="qa-head">'.'
	        					<span class="qa"></span>'.'
	        					<span class="qa-title">投注是否安全?</span>'.'
	        					<span class="arrow"></span>'.'
	        				</div>'.'
	        				<div class="qa-body">'.'
	        					<span>'.'
	        						我们采用了目前最好的加密技术（1024位RSA密钥交换和 448位blowfish）和防火墙系统保护您的安全、私隐，并保证您享受公平的游戏。 客户在本平台的所有活动均严格保密，我们不会向任何第三方透露客户资料。所有银行交易由国际金融机构在高标准的安全和机密的网络中进行。 进入玩家账户资料也必须有玩家唯一的登录ID和密码，确保客户的资金安全有保障。'.'
	        					</span>'.'
	        				</div>'.'
	        			</div>'.'
<div class="qa-box">'.'
	        				<div class="qa-head">'.'
	        					<span class="qa"></span>'.'
	        					<span class="qa-title">如何注册?</span>'.'
	        					<span class="arrow"></span>'.'
	        				</div>'.'
	        				<div class="qa-body">'.'
	        					<span>'.'
开户方式有两种： 1.请点击网站首页“注册”按钮，按照界面所规定的填写内容进行自助开户。 2.联系在线客服提供您的联系电话和取款银行卡姓名即可开出游戏账号。'.'
	        					</span>'.'
	        				</div>'.'
	        			</div>'.'
<div class="qa-box">'.'
	        				<div class="qa-head">'.'
	        					<span class="qa"></span>'.'
	        					<span class="qa-title">忘记密码怎么办?</span>'.'
	        					<span class="arrow"></span>'.'
	        				</div>'.'
	        				<div class="qa-body">'.'
	        					<span>'.'
	        						您可以联系24小时在线客服人员、通过客服人员免费电话给您协助找回您的账号密码。'.'
	        					</span>'.'
	        				</div>'.'
	        			</div>';
    }

    /**
     * 关于我们
     * @return null|string
     */
    public function about_us(){
        if($this->about_us_file_path && \Storage::disk('carrier')->exists($this->about_us_file_path)){
            return \Storage::disk('carrier')->get($this->about_us_file_path);
        }
//        return NULL;
        return '<i>谁是博赢国际？</i><p>
	        					本集团是世界领先的网络博彩集团之一，在全球差不多五十个国家均设办事处，英国、比利时、爱尔兰、美 国及阿根廷都提供投注服务。现集团基于业务多元化考虑，将线上博彩业务领域扩展至亚洲，并以"全力打造亚洲最丰富的真人视讯超 市"为己任，向博彩好爱者隆重推出。
	        				</p><i>博赢国际的好处？</i><p>只要使用一个账户，凯发娱乐便可带给您全面的激动人心的投注娱乐。而且博赢国际集团是世界上最大的博彩公司的一份子，您尽可放心 ，我们对您账户的处理是完全安全、谨慎和诚实的。</p>';
    }
    /**
     * 责任博彩
     * @return null|string
     */
    public function duty(){
        if($this->duty_file_path && \Storage::disk('carrier')->exists($this->duty_file_path)){
            return \Storage::disk('carrier')->get($this->duty_file_path);
        }
//        return NULL;
        return '<i>责任博彩</i><p >
	        					所有未满18岁之人士（或在阁下居住的管辖范围内被视为未成年人士）进行博彩活动是属于非法的行为。任何未满18岁之人士不得在 本站开户和投注， 凯发娱乐如果发现任何违背此规定的客户，将采取终止该投注账户操作的措施。
	        				</p>
	        				<i>关于博彩</i>
	        				<p>博彩只不过是一种娱乐消遣的方式，切莫过于沉迷，导致对日常生活造成负面的影响。凯发娱乐绝对有责任让顾客在一个良好的环境 下获得最佳的娱乐体验，并希望顾客可以明确地调整自己的心态，避免因沉迷博彩而影响到自己的事业和家庭，甚至别人，例如亲人 的生活。</p>
	        				<i>博赢国际提醒各位玩家：</i>	
	        				<p>
		        				博赢国际正在积极努力为玩家提供一个优质的娱乐平台。这些问题设置的目的在于，我们已经设置升级了多项安全设施来确保我们游 戏的公平公正。我们鼓励客户通过回答我们的问卷来了解客户对博彩的危害问题的状况：


								1、您会因为无聊或者不开心来进行赌博吗？<br/>

								2、您在注金投注完时，是否感觉钱已经丢掉或者说要尽快的再次下注？<br/>

								3、您会一直赌博直到您的钱都输完吗？<br/>

								4、您是否有说谎言借钱，甚至盗窃来进行博彩？<br/>

								5、您是否刻意隐瞒过您在博彩中花费的时间和资金吗？<br/>

								6、您是否不愿意花费您的赌金在其他方面呢？<br/>

								7、您是否已经对家人，朋友和爱好失去了兴趣？<br/>

								8、假如您的注金全部投注完，您是否会感到尽快赢回您输掉的资金？
	        				</p>
	        				<i>如果您的大部分答案选择为"是"，您可能已经沉迷于赌博，我们建议您:</i>
	        				<p>
	        					• 把博彩当做一种娱乐休闲项目<br/>

								• 避免连续的损失</br>

								• 对博彩有自己的认识</br>

								• 合理安排自己在博彩中的时间和精力</br>
	        				</p>
	        				<i>自我隔离：</i>
	        				<p>对于一些客户希望暂时远离博彩，我们将提供一个自我排除的功能，您可以申请六个月到五年的账户关闭。请点击网页中的"联系我们" ，我们的客服人员会为您带来更多资讯。</p>';
    }

    /**
     * 联系我们
     * @return null|string
     */
    public function contact_us(){
        if($this->contact_us_file_path && \Storage::disk('carrier')->exists($this->contact_us_file_path)){
            return \Storage::disk('carrier')->get($this->contact_us_file_path);
        }
        return '<div class="item adress">
                        <div class="infos">
                            <p class="tit">公司地址</p>
                            <p>xxx</p>
                            <p>xxx</p>
                        </div>
                    </div>
                    <div class="item mobile">
                        <div class="infos">
                            <p class="tit">联系方式</p>
                            <p>联系电话：13748574128</p>
                            <p>传&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;真：13748574128</p>
                        </div>
                    </div>
                    <div class="item email">
                        <div class="infos">
                            <p class="tit">邮箱</p>
                            <p>www.aa@qq.com</p>
                        </div>
                    </div>
                    <div class="item qq">
                        <div class="infos border0">
                            <p class="tit">联系QQ</p>
                            <p>客服1：11111</p>
                            <p>客服2：11sxx</p>
                        </div>
                    </div>';
    }

    /**
     * 隐私政策
     * @return null|string
     */
    public function privacy_policy(){
        if($this->privacy_policy_file_path && \Storage::disk('carrier')->exists($this->privacy_policy_file_path)){
            return \Storage::disk('carrier')->get($this->privacy_policy_file_path);
        }
        return NULL;
    }

    /**
     * 规则条款
     * @return null|string
     */
    public function rule_clause(){
        if($this->rule_clause_file_path && \Storage::disk('carrier')->exists($this->rule_clause_file_path)){
            return \Storage::disk('carrier')->get($this->rule_clause_file_path);
        }
        return NULL;
    }
    /**
     * 合营模式
     * @return null|string
     */
    public function agent_pattern(){
        if($this->agent_pattern_file_path && \Storage::disk('carrier')->exists($this->agent_pattern_file_path)){
            return \Storage::disk('carrier')->get($this->agent_pattern_file_path);
        }
        return NULL;
    }
    /**
     * 代理首页
     * @return null|string
     */
    public function agent_index(){
        if($this->agent_index_file_path && \Storage::disk('carrier')->exists($this->agent_index_file_path)){
            return \Storage::disk('carrier')->get($this->agent_index_file_path);
        }
        return NULL;
    }
    /**
     * 手机端关于我们
     * @return null|string
     */
    public function mobile_about(){
        if($this->mobile_about_file_path && \Storage::disk('carrier')->exists($this->mobile_about_file_path)){
            return \Storage::disk('carrier')->get($this->mobile_about_file_path);
        }
        return '<div class="content-padded">
        <h4>谁是双赢国际？</h4>
        <p>本集团是世界领先的网络博彩集团之一，在全球差不多五十个国家均设办事处，英国、比利时、爱尔兰、美 国及阿根廷等都提供投注服务。现集团基于业务多元化考虑，将线上博彩业务领域扩展至亚洲，并以"全力打造亚洲最丰富的真人视讯超 市"为己任，向博彩好爱者隆重推出。</p>

        <h4>双赢国际的好处？</h4>
        <p>只要使用一个账户，凯发娱乐便可带给您全面的激动人心的投注娱乐。而且双赢国际集团是世界上最大的博彩公司的一份子，您尽可放心 ，我们对您账户的处理是完全安全、谨慎和诚实的。</p>  
      </div>';
    }
    /**
     * 手机端联系我们
     * @return null|string
     */
    public function mobile_contact(){
        if($this->mobile_contact_file_path && \Storage::disk('carrier')->exists($this->mobile_contact_file_path)){
            return \Storage::disk('carrier')->get($this->mobile_contact_file_path);
        }
        return '<div class="kefu">
          <div class="distab">
            <div class="discell">客服时间：</div>
            <div class="discell">星期一至星期五</div>
          </div>
          <div class="distab">
            <div class="discell" style="width:3.5rem;"></div>
            <div class="discell">9:00-12:00及13:00-18:00</div>
          </div>
        </div>
        <div class="list-block">
          <ul>
            <li><a class="item-content item-link">
                <div class="item-inner">
                  <div class="item-title">联系电话：010-87376524</div>
                </div></a></li>
          </ul>
        </div>
        <div class="list-block">
          <ul>
            <li><a class="item-content item-link">
                <div class="item-inner">
                  <div class="item-title">客服邮箱：aa.bb@cc.com</div>
                </div></a></li>
          </ul>
        </div>
        <div class="list-block">
          <ul>
            <li><a class="item-content item-link">
                <div class="item-inner">
                  <div class="item-title">商务合作：aa.bb@cc.com</div>
                </div></a></li>
          </ul>
        </div>';
    }
    /**
     * 手机端联系我们
     * @return null|string
     */
    public function online_service(){
        if($this->online_service_file_path && \Storage::disk('carrier')->exists($this->online_service_file_path)){
            return \Storage::disk('carrier')->get($this->online_service_file_path);
        }
        return NULL;
    }

    public function bannerImages(){
        return $this->hasMany(CarrierWebBannerConf::class,'carrier_id','carrier_id');
    }
    public function carrier(){
        return $this->hasOne(Carrier::class,'id','carrier_id');
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

        
    ];

    
}
