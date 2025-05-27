<?php

namespace App\Http\Controllers;

use App\Http\Services\HjhCloudService;
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
