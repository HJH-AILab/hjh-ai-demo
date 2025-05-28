<?php

namespace App\Http\Controllers;

use App\Http\Services\Draw\Base;
use App\Http\Services\HjhCloudService;
use App\Models\HjhImage;
use Illuminate\Http\Request;
use Auth;
use App\Models\Image;
use App\Models\Music;
use Image as Intervention;
use Storage;

class HjhController extends Controller
{

    /**
     * 添加验证中间件
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', array(
            'except' => ['callback', 'hjh-callback']
        ));
    }

    /**
     * showWorkflowForm
     * @return void
     */
    public function showWorkflowForm()
    {
        $workflows = HjhCloudService::getInstance()->getWorkflows();
        if(!empty($workflows)) {
            $workflows = $workflows['list'];
        }
        return view('hjh.workflow', [
                'workflows' => $workflows,
                'title' => '好机绘工作流列表'
            ]);
    }

    /**
     * 显示图片修改框
     * @return void
     */
    public function showWorkflowImageForm(Request $request)
    {
        $data = $request->all();
        $workflowId = $data['workflow_id'] ?? '';
        $workflowName = $data['workflow_name'] ?? '';
        return view('hjh.workflowimage', [
                'workflow_id' => $workflowId,
                'workflow_name' => $workflowName,
                'title' => '好机绘工作流图片'
            ]);
    }

    /**
     * 上传图片
     * @return void
     */
    public function create(Request $request)
    {
        $model = new Image();
        $userId = Auth::user()->id;
        if ($request->hasFile('thumb') && $request->file('thumb')->isValid()) {

            $file = $request->file('thumb');
            $model->thumb = $file->store('thumbs');
            $imageName = uniqid(date('YmdHis')) . $file->getClientOriginalName();

            $createTaskNo = uniqid(date('YmdHis'));
            HjhCloudService::getInstance()->create(
                $userId,
                $file,
                $model->thumb,
                Base::FORMAT_SQURE,
                $request->get('workflow_id'),
                $request->get('workflow_name'),
            );
            //生成1920宽度图片
            $resource1920 = Intervention::make($file)->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb1920/' . $imageName, $resource1920);
            $model->thumb1920 = 'thumb1920/' . $imageName;

            //生成1280宽度图片
            $resource1280 = Intervention::make($file)->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb1280/' . $imageName, $resource1280);
            $model->thumb1280 = 'thumb1280/' . $imageName;

            //生成640宽度图片
            $resource640 = Intervention::make($file)->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream()->detach();
            Storage::disk('local')->put('thumb640/' . $imageName, $resource640);
            $model->thumb640 = 'thumb640/' . $imageName;
        }
        $model->desc = $request->get('desc');
        $model->lens = $request->get('lens');
        $model->size = $request->get('size');
        $model->resolution = $request->get('resolution');
        $model->aspect_ratio = $request->get('aspect_ratio');
        $model->keywords = $request->get('keywords');
        $model->released = 0;
        $model->user_id = $userId;
        $model->source = Base::SOURCE_HJH; // 来源 1-好机绘
        $model->workflow_id = $request->get('workflow_id', 0);
        $model->workflow_name = $request->get('workflow_name', '');
        $model->save();

        return redirect()->back()->withMessage('图片上传成功,审核通过后展示');
    }

    /**
     * callback
     * {
	    "task_no": "20250527175912870966",
        "data": {
            "task_no": "",
            "task_status": 1,
            "images": [],
            "source": [],
            "faceswap": [],
            "desc": "ok"
        }
    }
     * @return void
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        return json_encode(['code' => 200, 'message' => 'success', 'data' => $data]);
    }
    
}
