<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class fetchUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取网站地址';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://www.pexels.com/zh-cn/medium/above-the-fold/?id[]=3076207');
        $body = $response->getBody();
        
    }
}
