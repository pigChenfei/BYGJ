<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierWebSiteConfRequest;
use App\Http\Requests\Carrier\UpdateCarrierWebSiteConfRequest;
use App\Models\Conf\CarrierWebBannerConf;
use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Image\CarrierImageCategory;
use App\Models\Def\Template;
use App\Models\Carrier;
use App\Models\CarrierTemplate;
use App\Repositories\Carrier\CarrierWebSiteConfRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierWebSiteConfController extends AppBaseController
{
    /** @var  CarrierWebSiteConfRepository */
    private $carrierWebSiteConfRepository;

    public function __construct(CarrierWebSiteConfRepository $carrierWebSiteConfRepo)
    {
        $this->carrierWebSiteConfRepository = $carrierWebSiteConfRepo;
    }

    /**
     * Display a listing of the CarrierWebSiteConf.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $conf = CarrierWebSiteConf::with('bannerImages')->first();
        $templates =CarrierTemplate::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with('templates')->get();

        if(!$conf){
            $conf = new CarrierWebSiteConf();
            $conf->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $conf->site_title = '';
            $conf->save();
        }
        if($conf->activity_image_resolution){
            list($width,$height) = explode('*',$conf->activity_image_resolution);
            $conf->activity_image_resolution_width = $width;
            $conf->activity_image_resolution_height = $height;
        }
        return view('Carrier.carrier_web_site_confs.index')
            ->with('carrierWebSiteConf', $conf)
            ->with('templates',$templates)
            ->with('pctemplate',\WinwinAuth::currentWebCarrier()->template)
            ->with('template_mobile',\WinwinAuth::currentWebCarrier()->template_mobile)
            ->with('template_agent',\WinwinAuth::currentWebCarrier()->template_agent);
    }

    /**
     * Show the form for creating a new CarrierWebSiteConf.
     *
     * @return Response
     */
    public function create()
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Store a newly created CarrierWebSiteConf in storage.
     *
     * @param CreateCarrierWebSiteConfRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierWebSiteConfRequest $request)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Display the specified CarrierWebSiteConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Show the form for editing the specified CarrierWebSiteConf.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return $this->sendSuccessResponse([]);
    }

    /**
     * Update the specified CarrierWebSiteConf in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierWebSiteConfRequest $request
     *
     * @return Response
     */
    public function update($id = null,UpdateCarrierWebSiteConfRequest $request)
    {
        $input = $request->all();
        $conf = CarrierWebSiteConf::first();
        $carrierWebSiteConf = $this->carrierWebSiteConfRepository->findWithoutFail($conf->id);
        if (empty($carrierWebSiteConf)) {
            return $this->sendNotFoundResponse();
        }
        //以下是需要保存为文件name对应的table中的字段
        $file_fields = [
            'common_questions' => 'common_question_file_path',
            'privacy_policy' => 'privacy_policy_file_path',
            'rule_clause' => 'rule_clause_file_path',
            'commission_policy' => 'commission_policy_file_path',
            'jointly_operated_agreement' => 'jointly_operated_agreement_file_path',
            'with_draw_comment' => 'with_draw_comment_file_path',
            'contact_us' => 'contact_us_file_path',
            'about_us' => 'about_us_file_path',
            'duty' => 'duty_file_path',
            'agent_pattern' => 'agent_pattern_file_path',
            'agent_index' => 'agent_index_file_path',
            'mobile_about' => 'mobile_about_file_path',
            'mobile_contact' => 'mobile_contact_file_path',
//            'online_service' => 'online_service_file_path',//总后台配置
        ];

        $update_type = $request->get('update_type');
        if ($update_type == 'base_info'){

            $template = $request->get('template');
            $template_mobile =$request->get('template_mobile');
            $template_agent =$request->get('template_agent');

            $currcarrier =Carrier::where('id',\WinwinAuth::carrierUser()->carrier_id)->first();
            $currcarrier->template=$template;
            $currcarrier->template_mobile=$template_mobile;
            $currcarrier->template_agent=$template_agent;
            $currcarrier->save();
        }

        //如果是文件字段,则需要将数据保存到文件中
        $fileContent = $request->get($update_type);
        if(in_array($update_type,array_keys($file_fields))){
            $filedName = $file_fields[$update_type];
            if($conf->$filedName){
                $fileName = $conf->$filedName;
            }else{
                $fileName = \WinwinAuth::carrierUser()->carrier_id.'/webConf/'.md5('Carrier'.\WinwinAuth::carrierUser()->carrier_id.strlen($fileContent).time());
            }
            \Storage::disk('carrier')->put($fileName,$fileContent);
            $input[$file_fields[$update_type]] = $fileName;
        }
        //处理活动页面图片分辨率字段
        $input['activity_image_resolution'] = $request->get('activity_image_resolution_width',0).'*'.$request->get('activity_image_resolution_height',0);
        try{
            \DB::transaction(function () use ($input,$conf,$request,$update_type){
                $this->carrierWebSiteConfRepository->update($input, $conf->id);
                if($update_type == 'content_info'){ //如果是更新网站基本信息, 则对网站Banner图片进行处理,因为网站Banner图片是属于网站基本信息模块
                    CarrierWebBannerConf::whereNotNull('carrier_id')->delete();
                    if($bannerImagesData = $request->get('bannerImagesData')){
                        $bannerImageArray = json_decode($bannerImagesData,true);
                        $bannerImageArray = array_filter($bannerImageArray,function ($element){
                            return $element['selectedImageValue'] && $element['selectedBannerPages'] && is_array($element['selectedBannerPages']);
                        });
                        foreach ($bannerImageArray as $bannerImage){
                            foreach ($bannerImage['selectedBannerPages'] as $bannerPage){
                                $carrierBanner = new CarrierWebBannerConf();
                                $carrierBanner->banner_image_id = $bannerImage['selectedImageValue'];
                                $carrierBanner->banner_belong_page = $bannerPage;
                                $carrierBanner->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                                $carrierBanner->save();
                            }
                        }
                    }
                }
            });
            return $this->sendSuccessResponse(route('carrierWebSiteConfs.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierWebSiteConf from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return $this->sendSuccessResponse([]);
    }
}
