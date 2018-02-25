<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Repositories\Admin\TemplateRepository;
use App\DataTables\Admin\TemplateDataTable;
use App\Http\Requests\Admin\CreateTemplateRequest;
use App\Models\Def\Template;
use App\Models\CarrierTemplate;
use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Flash;

class TemplateController extends AppBaseController
{

    public $templateRepos;

    public function __construct(TemplateRepository $templateRepos)
    {
        $this->templateRepos = $templateRepos;
    }

    public function index(TemplateDataTable $templatedatatables)
    {
        // $templates =Template::all();
        return $templatedatatables->render('Admin.template.index');
    }

    public function create()
    {
        return view('Admin.template.create');
    }

    public function store(CreateTemplateRequest $request)
    {
        $input = $request->all();
        if ($request->get('id')) {
            return $this->update($request->get('id'), $input);
        }
        try {
            $temptemplate = Template::where([
                'type' => $input['type'],
                'value' => $input['value']
            ])->first();
            if ($temptemplate) {
                Flash::error('此模板已存在');
                
                return redirect()->back();
            }
            $template = new Template();
            $template->fill($input);
            $template->save();
        } catch (\Exception $e) {
            Flash::error('模板添加失败');
            
            return redirect()->back();
        }
        
        Flash::success('模板添加成功');
        
        return redirect(route('templates.index'));
    }

    public function edit($id)
    {
        $template = Template::where('id', $id)->first();
        if (empty($template)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.template.edit', compact('template'));
    }

    public function update($id, $input)
    {
        try {
            $this->templateRepos->update($input, $id);
        } catch (\Exception $e) {
            Flash::error('模板修改失败');
            
            return redirect()->back();
        }
        Flash::success('模板修改成功');
        
        return redirect(route('templates.index'));
    }

    public function showAssignCarriersModal(Request $request)
    {
        $this->validate($request, [
            'template_ids' => 'required|array',
            'template_ids.*' => 'integer'
        ], [], [
            'template_ids' => '模版'
        ]);
        $templates = Template::whereIn('id', $request->get('template_ids'))->get();
        if ($templates->count() == 1) {
            $selectedCarriers = CarrierTemplate::whereIn('template_id', [
                $templates->first()->id
            ])->with('carrier')->get();
        } else {
            $selectedCarriers = Collection::make([]);
        }
        $allCarriers = Carrier::all();
        return view('Admin.template.assign_carriers')->with('templates', $templates)
            ->with('allCarriers', $allCarriers)
            ->with('selectedCarriers', $selectedCarriers);
    }

    public function updateCarriersTemplates(Request $request)
    {
        $this->validate($request, [
            'template_ids' => 'required|array',
            'template_ids.*' => 'integer',
            'carrier_ids' => 'array',
            'carrier_ids.*' => 'integer'
        ]);
        
        foreach ($request->get('template_ids') as $id) {
            \DB::delete('delete from inf_carrier_template where template_id=' . $id);
        }
        
        $insertGameCarrierIds = $request->get('carrier_ids') ?: [];
        
        try {
            \DB::transaction(function () use ($insertGameCarrierIds, $request) {
                
                if ($insertGameCarrierIds) {
                    foreach ($request->get('template_ids') as $templateId) {
                        // 从性能上面考虑, 不得已用DB操作
                        \DB::table('inf_carrier_template')->insert(array_map(function ($element) use ($templateId) {
                            return [
                                'carrier_id' => $element,
                                'template_id' => $templateId,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }, $insertGameCarrierIds));
                    }
                }
            });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            throw $e;
            return $this->sendErrorResponse($e->getMessage());
        }
        // dd($oldGameCarriers,$carrierIds,$deleteGameCarrierIds,$insertGameCarrierIds);
    }
}

