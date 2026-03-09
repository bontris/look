<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use GuzzleHttp\Client;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DashController extends Controller
{
	public function main (Request $request, $task = null) {
		switch (strtolower($task)) {
			case 'load':
				$validator = Validator::make($request->all(), [
					'from' => 'nullable|date_format:Y-m-d',
					'stop' => 'nullable|date_format:Y-m-d'
				], [
					'from.date_format' => 'El campo no es válido.',
					'stop.date_format' => 'El campo no es válido.'
				]);


				$validator = Validator::make($request->all(), [
					'from' => 'required|date_format:Y-m-d',
					'stop' => 'required|date_format:Y-m-d'
				], [
					'from.required' => 'El campo es requerido.',
					'from.date_format' => 'El campo no es válido.',
					'stop.required' => 'El campo es requerido.',
					'stop.date_format' => 'El campo no es válido.'
				]);

				if (empty($validator->fails())) {
				} else {
					return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
											 'list' => array_map(function ($item) {
						return current($item);
					}, $validator->errors()->toArray())], 400);
				}
			default:
                $client = new Client();

                $user =  Auth::user();

                $card = null;

                if (isset($user->firm->hand)) {
                    $response = $client->request('GET', 'https://tallera.bitrix24.es/rest/1/mq4gp6t9m57evrv3/user.get.json', [
                        RequestOptions::QUERY => [
                            'ID' => $user->firm->hand->link
                        ]
                    ]);

                    if (($response->getStatusCode() == 200)) {
                        if (($data = json_decode($response->getBody(), true))) {
                            if (isset($data['result'][0])) {
                                $card = [
                                    'name' => $data['result'][0]['NAME'],
                                    'mail' => $data['result'][0]['EMAIL'],
                                    'note' => $user->firm->hand->note,
                                    'last' => $data['result'][0]['LAST_NAME'],
                                    'icon' => $data['result'][0]['PERSONAL_PHOTO'],
                                    'work' => $data['result'][0]['PERSONAL_MOBILE']
                                ];
                            }
                        }
                    }
                }

                return response()->json([
                    'card' => $card,
                    'list' => array_reduce(DB::table('cards')
                                              ->where('hide', 0)
                                              ->where('lock', 0)
                                              ->where('sort', (($user->type == 3) ? 1 : 2))
                                              ->where(function ($query) {
                                                $query->where('type', 1)
                                                      ->orWhere('type', 3);
                                              })
                                              ->orderBy('made', 'desc')
                                              ->get()
                                              ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'type' => intval($item->type),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'name' => $item->name,
                            'text' => $item->text,
                            'note' => $item->note,
                            'more' => $item->more,
                            'link' => $item->link,
                            'snap' => $item->snap
                        ]);

                        return $list;
                    }, [])
                ], 200);
		}
	}
}