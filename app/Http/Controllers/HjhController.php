<?php

namespace App\Http\Controllers;

use App\Http\Services\Draw\Base;
use App\Http\Services\Draw\Images;
use App\Http\Services\Draw\Task;
use App\Http\Services\DrawTaskService;
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
     * 图片列表页展示
     * 
     */
    public function image()
    {
        $page = request('page');
        if (!$page) {
            $page = 1;
        }
        
        $res = Image::orderBy('id', 'desc')
            ->where('source', Task::SOURCE_HJH)
            ->Released()->simplePaginate(24, ['*'], 'page', $page);
        
        return view(
            'hjh.image',
            [
                'images' => $res,
                'title' => '好机绘AI画廊'
            ]
        );
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

            $createTaskNo = $userId . date('YmdHis') . mt_rand(1000000, 9999999);
            HjhCloudService::getInstance()->create(
                $userId,
                $file,
                $model->thumb,
                Base::FORMAT_SQURE,
                $request->get('workflow_id'),
                $request->get('workflow_name'),
                $createTaskNo
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
        $model->type = Images::TYPE_USER; // 用户上传
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
        /**
         * keepalive_timeout 600; #客户端浏览器超时时间

         * fastcgi_connect_timeout 600; #php-fpm连接超时时间（等待php执行的最长时间，超过这个会向浏览器返回504或502）
        fastcgi_send_timeout 600; #
        fastcgi_read_timeout 600;
         */
        //ini_set("max_execution_time", "300");
        set_time_limit(300);
        $data = $request->all();
        $taskNo = $data['task_no'] ?? '';
        if(empty($taskNo)) {
            return json_encode(['code' => 400, 'message' => 'task_no is required']);
        }
        $callbackData = $data['data'] ?? [];
        if(empty($callbackData)) {
            return json_encode(['code' => 400, 'message' => 'data is required']);
        }
        $drawTask = DrawTaskService::getInstance()->getDrawTaskByTaskNo($taskNo);

        $ret = DrawTaskService::getInstance()->callback($drawTask, $callbackData);
        return json_encode(['code' => 200, 'message' => 'success', 'data' => $ret]);
    }
    
}
