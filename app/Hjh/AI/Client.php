<?php
namespace App\Hjh\AI;

use Illuminate\Support\Facades\Http;

class Client
{
	public static function getCallbackHttp() {
		return Http::withoutVerifying()->retry(3)->timeout(10);
	}
}
