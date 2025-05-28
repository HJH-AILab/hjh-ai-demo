<?php
namespace App\Http\Services;

use App\Http\Services\Draw\Task;
use App\Models\HjhDrawTask;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhTaskService extends AbstractService {
    use Singleton;

    public function create($userId, $params) {
        $insertData = [
            'user_id' => $userId,
            'task_no' => $this->get($params, 'create_task_no', 0),
            'workflow_id' => $this->get($params, 'workflow_id', 0),
            'workflow_name' => $this->get($params, 'workflow_name', ''),
            'name' => $this->get($params, 'name', ''),
            'type' => $this->get($params, 'type', ''),
            'order_id' => $this->get($params, 'order_id', 0),
            'user_parameter' => json_encode($params),
            'result_detail' => '{}',
            'images' => '',
            'task_status' => Task::TASK_STATUS_CREATE, // 1:创建
            'task_desc' => $this->get($params, 'task_desc', ''),
            'add_time' => Carbon::now()->timestamp,
            'status' => Task::ST_OK,
            'created_at' => Carbon::now(),
        ];

        $drawTask = HjhDrawTask::create($insertData);
        return $drawTask;
    }

    function get($arr, $key, $default = '') {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}