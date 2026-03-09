<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class RideController extends Controller
{
	public function main (Request $request, $task = null, $item = null) {
        if (isset($item)) {
            if (($item = DB::table('stops')
                           ->where('stops.hide', 0)
                           ->where('stops.hash', $item)
                           ->join('paths', function ($join) {
                               $join->on('stops.bind', 'paths.row')
                                    ->where('paths.pick', Auth::user()->id);
                           })
                           ->join('donations', 'donations.row', 'stops.pick')
                           ->select(
                                'stops.*',
                                'donations.type',
                                'donations.date',
                                'donations.name',
                                'donations.town',
                                'donations.load',
                                'donations.mail',
                                'donations.phone',
                                'donations.mobile',
                                'donations.address'
                           )->first())) {
                switch (strtolower($task)) {
                    case 'done':
                        $validator = Validator::make($request->all(), [
                            'done' => 'required|in:1,2',
                            'note' => 'nullable|max:256',
                            'spot' => 'required|array|min:2|max:2'
                        ], [
                            'done.in' => 'El campo no es válido.',
                            'spot.min' => 'El campo no es válido.',
                            'spot.max' => 'El campo no es válido.',
                            'note.max' => 'El campo no es válido.',
                            'spot.array' => 'El campo no es válido.',
                            'done.required' => 'El campo es requerido.',
                            'spot.required' => 'El campo es requerido.'
                        ]);

                        if (empty($validator->fails())) {
                            if (empty(intval($item->done))) {
                                try {
                                    DB::beginTransaction();

                                    if (DB::table('stops')
                                        ->where('row', $item->row)
                                        ->update([
                                        'date' => date('Y-m-d H:i:s'),
                                        'note' => trim($request->get('note')),
                                        'done' => (intval($request->get('done')) + 1),
                                        'spot' => ((isset(($spot = $request->post('spot'))[0]) && isset($spot[1])) ? DB::raw(sprintf("ST_GeomFromText('POINT(%s)')", implode(' ', array_map(function ($part) {
                                            return floatval($part);
                                        }, $spot)))) : null)
                                    ])) {
                                        if (DB::table('donations')
                                              ->where('row', $item->pick)
                                              ->update(['done' => intval($request->get('done'))])) {
                                            DB::commit();

                                            return response()->json([
                                                'text' => 'El registro fue actualizado correctamente.'
                                            ], 200);
                                        } else {
                                            return response()->json([
                                                'text' => 'No se pudo actualizar el registro correctamente1.'
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'text' => 'No se pudo actualizar el registro correctamente2.'
                                        ], 500);
                                    }
                                } catch (\Exception $exception) {
                                    DB::rollBack();

                                    Log::error(sprintf('Unexpected exception: %s', $exception->getMessage()));

                                    return response()->json([
                                        'text' => 'El registro no pudo ser actualizado correctamente3.','fail'=>$exception->getMessage()
                                    ], 500);
                                }
                            } else {
                                return response()->json([
                                    'text' => 'El estado del registro no es válido.'
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'text' => 'Uno o más campos del formulario no son correctos.',
                                'list' => array_map(function ($item) {
                                    return current($item);
                                }, $validator->errors()->toArray())
                            ], 400);
                        }
                    default:
                        return response()->json($item, 200);
                }
            } else {
                return response()->json([
                    'text' => 'El registro no fue encontrado.'
                ], 404);
            }
        } else {
            switch (strtolower($task)) {
                case 'load':
                    return response()->json(array_reduce(DB::table('stops')
                                                           ->where('stops.hide', 0)
                                                            ->join('paths', function ($join) {
                                                                $join->on('stops.bind', 'paths.row')
                                                                    ->where('paths.pick', Auth::user()->id);
                                                            })
                                                            ->join('donations', 'donations.row', 'stops.pick')
                                                            ->select(
                                                                'stops.*',
                                                                'donations.type',
                                                                'donations.date',
                                                                'donations.done',
                                                                'donations.name',
                                                                'donations.town',
                                                                'donations.load',
                                                                'donations.mail',
                                                                'donations.phone',
                                                                'donations.mobile',
                                                                'donations.address',
                                                                'donations.indication',
                                                                DB::raw('ST_AsText(donations.spot) AS spot')
                                                            )
                                                            ->orderBy('stops.made', 'desc')
                                                            ->get()
                                                            ->toArray(), function ($list, $item) {
                        array_push($list, [
                            'indication' => $item->indication,
                            'address' => $item->address,
                            'mobile' => $item->mobile,
                            'phone' => $item->phone,
                            'load' => floatval($item->load),
                            'spot' => preg_match('/^POINT\((\-?\d+(\.\d+)?)\s(\-?\d+(\.\d+)?)\)$/', $item->spot, $item->spot) ? [floatval($item->spot[1]), floatval($item->spot[3])] : [null, null],
                            'lock' => intval($item->lock),
                            'type' => intval($item->type),
                            'date' => intval($item->date),
                            'done' => intval($item->done),
                            'item' => intval($item->row),
                            'hash' => $item->hash,
                            'code' => $item->code,
                            'name' => $item->name,
                            'mail' => $item->mail,
                            'town' => $item->town,
                            'made' => $item->made
                        ]);
    
                        return $list;
                    }, []));
                default:
                    return view('ride', ['request' => $request]);
            }
        }
	}
}
