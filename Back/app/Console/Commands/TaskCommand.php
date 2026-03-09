<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;

use Illuminate\Support\Str;

use GuzzleHttp\RequestOptions;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

class TaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Bitrix tasks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct () {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle () {
        set_time_limit(0);

        $client = new Client([
            'request.options' => [
                'timeout' => 1000,
                'connect_timeout' => 1000
            ]
        ]);

        $left = 3;

        $wait = 1;

        $from = 0;

        try {
            do {
                $loop = false;

                $more = false;

                try {
                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/yph573l2l8dwjcwl/crm.deal.list.json', [
                        RequestOptions::QUERY => [
                            'order' => ['ID' => 'desc'],
                            'start' => $from,
                            'select' => ['ID', 'TITLE', 'CONTACT_ID', 'COMPANY_ID'],
                            'filter' => ['CATEGORY_ID' => '15']
                        ]
                    ]);

                    if (($more = ($response->getStatusCode() == 200))) {
                        if (($main = json_decode($response->getBody(), true))) {
                            foreach ($main['result'] as $deal) {
                                $left = 3;

                                $next = 0;

                                do {
                                    $loop = false;

                                    $done = false;

                                    try {
                                        $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/t3ez7rurxe35vrto/tasks.task.list.json', [
                                            RequestOptions::QUERY => [
                                                'start' => $next,
                                                'order' => ['ID' => 'asc'],
                                                'filter' => ['UF_CRM_TASK' => [sprintf('D_%d', $deal['ID'])]],
                                                'select' => ['ID', 'GUID', 'TITLE', 'DEADLINE', 'STATUS', 'PRIORITY', 'DESCRIPTION', 'CREATED_BY', 'CREATED_DATE', 'DURATION_TYPE', 'RESPONSIBLE_ID', 'TIME_SPENT_IN_LOGS'],
                                            ]
                                        ]);

                                        if (($done = ($response->getStatusCode() == 200))) {
                                            if (($data = json_decode($response->getBody(), true))) {
                                                foreach ($data['result']['tasks'] as $item) {
                                                    if (($task = DB::table('tasks')
                                                                   ->where('hide', 0)
                                                                   ->where('seek', intval($item['id']))
                                                                   ->first())) {
                                                        DB::table('tasks')->where('row', $task->row)->update([
                                                            'mark' => date('Y-m-d H:i:s'),
                                                            'done' => intval($item['status']),
                                                            'rank' => intval($item['priority']),
                                                            'name' => Str::limit(trim($item['title']), 125, '...'),
                                                            'note' => Str::limit(trim($item['description']), 1021, '...'),
                                                            'head' => intval($item['responsibleId']),
                                                            'time' => intval($item['timeSpentInLogs']),
                                                            'date' => isset($item['deadline']) ? date('Y-m-d H:i:s', strtotime($item['deadline'])) : null
                                                        ]);
                                                    } else {
                                                        $task = DB::table('tasks')->insertGetId([
                                                            'seek' => intval($item['id']),
                                                            'done' => intval($item['status']),
                                                            'rank' => intval($item['priority']),
                                                            'hash' => md5(uniqid(rand(), true)),
                                                            'skip' => intval($item['createdBy']),
                                                            'link' => intval($deal['CONTACT_ID']),
                                                            'bind' => intval($deal['COMPANY_ID']),
                                                            'head' => intval($item['responsibleId']),
                                                            'time' => intval($item['timeSpentInLogs']),
                                                            'name' => Str::limit(trim($item['title']), 125, '...'),
                                                            'note' => Str::limit(trim($item['description']), 1021, '...'),
                                                            'made' => date('Y-m-d H:i:s', strtotime($item['createdDate'])),
                                                            'date' => isset($item['deadline']) ? date('Y-m-d H:i:s', strtotime($item['deadline'])) : null
                                                        ]);
                                                    }

                                                    if (($bind = isset($task->row) ? $task->row : $task)) {
                                                        $left = 3;

                                                        $page = 0;

                                                        do {
                                                            $loop = false;

                                                            $keep = false;

                                                            try {
                                                                $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/18rfyq2y67i4hftv/task.elapseditem.getlist.json', [
                                                                    RequestOptions::QUERY => [
                                                                        'ORDER' => ['ID' => 'asc'],
                                                                        'FILTER' => ['TASK_ID' => intval($item['id'])],
                                                                        'SELECT' => ['ID', 'USER_ID', 'SECONDS', 'DATE_STOP', 'DATE_START', 'COMMENT_TEXT', 'CREATED_DATE'],
                                                                        'PARAMS' => ['NAV_PARAMS' => ['iNumPage' => $page]]
                                                                    ]
                                                                ]);

                                                                if (($keep = ($response->getStatusCode() == 200))) {
                                                                    if (($last = json_decode($response->getBody(), true))) {
                                                                        foreach ($last['result'] as $item) {
                                                                            if (($time = DB::table('times')
                                                                                           ->where('hide', 0)
                                                                                           ->where('seek', intval($item['ID']))
                                                                                           ->first())) {
                                                                                DB::table('times')->where('row', $time->row)->update([
                                                                                    'mark' => date('Y-m-d H:i:s'),
                                                                                    'load' => intval($item['SECONDS']),
                                                                                    'note' => Str::limit(trim($item['COMMENT_TEXT']), 253, '...'),
                                                                                    'stop' => date('Y-m-d H:i:s', strtotime($item['DATE_STOP'])),
                                                                                    'stop' => isset($item['DATE_STOP']) ? date('Y-m-d H:i:s', strtotime($item['DATE_STOP'])) : $time['stop'],
                                                                                    'date' => isset($item['DATE_START']) ? date('Y-m-d H:i:s', strtotime($item['DATE_START'])) : $time['date']
                                                                                ]);
                                                                            } else {
                                                                                DB::table('times')->insert([
                                                                                    'bind' => $bind,
                                                                                    'seek' => intval($item['ID']),
                                                                                    'skip' => intval($item['USER_ID']),
                                                                                    'load' => intval($item['SECONDS']),
                                                                                    'hash' => md5(uniqid(rand(), true)),
                                                                                    'note' => Str::limit(trim($item['COMMENT_TEXT']), 253, '...'),
                                                                                    'made' => date('Y-m-d H:i:s', strtotime($item['CREATED_DATE'])),
                                                                                    'stop' => isset($item['DATE_STOP']) ? date('Y-m-d H:i:s', strtotime($item['DATE_STOP'])) : null,
                                                                                    'date' => isset($item['DATE_START']) ? date('Y-m-d H:i:s', strtotime($item['DATE_START'])) : null
                                                                                ]);
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                $left = 3;

                                                                $wait = 1;
                                                            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                                                                Log::error($exception->getMessage());

                                                                if ($exception->hasResponse()) {
                                                                    $response = $exception->getResponse();

                                                                    if (in_array($response->getStatusCode(), [249, 429, 500, 502, 503, 504])) {
                                                                        if (($loop = boolval(($left = max($left - 1, 0))))) {
                                                                            sleep($wait++);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } while ((($done && isset($last['next']) && ($page= intval($last['next']))) || $loop));
                                                    }
                                                }
                                            }
                                        }

                                        $left = 3;

                                        $wait = 1;
                                    } catch (\GuzzleHttp\Exception\RequestException $exception) {
                                        Log::error($exception->getMessage());

                                        if ($exception->hasResponse()) {
                                            $response = $exception->getResponse();

                                            if (in_array($response->getStatusCode(), [249, 429, 500, 502, 503, 504])) {
                                                if (($loop = boolval(($left = max($left - 1, 0))))) {
                                                    sleep($wait++);
                                                }
                                            }
                                        }
                                    }
                                } while ((($done && isset($data['next']) && ($next = intval($data['next']))) || $loop));
                            }
                        }
                    }

                    $left = 3;

                    $wait = 1;
                } catch (\GuzzleHttp\Exception\RequestException $exception) {
                    Log::error($exception->getMessage());

                    if ($exception->hasResponse()) {
                        $response = $exception->getResponse();

                        if (in_array($response->getStatusCode(), [249, 429, 500, 502, 503, 504])) {
                            if (($loop = boolval(($left = max($left - 1, 0))))) {
                                sleep($wait++);
                            }
                        }
                    }
                }
            } while ((($more && isset($main['next']) && ($from = intval($main['next']))) || $loop));
        } catch (\Throwable $exception) {echo($exception->getMessage());
            Log::error($exception->getMessage());
        }
    }
}