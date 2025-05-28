<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;

class HjhTaskService extends AbstractService {
    use Singleton;

    public function create($userId, $image, $format, $workflowId, $workflowName, $createTaskNo = "") {
        
    }
}