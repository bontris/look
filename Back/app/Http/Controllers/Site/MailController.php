<?php

namespace App\Http\Controllers\Site;

use Auth;

use Validator;

use Illuminate\Http\Request;

use Illuminate\Mail\Message;

use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;

class MailController extends Controller
{
	public function main (Request $request) {
        if ((strtolower($request->method()) == 'post')) {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:1,2,3',
                'mail' => 'required|email|max:64',
                'cell' => 'nullable|string|max:16',
                'name' => 'required|string|max:64',
                'text' => 'required|string|max:2048'
            ], [
                'type.in' => 'El campo no es válido.',
                'type.required' => 'El campo es requerido.',
                'mail.max' => 'El campo no es válido.',
                'mail.required' => 'El campo es requerido.',
                'name.max' => 'El campo no es válido.',
                'name.required' => 'El campo es requerido.',
                'text.max' => 'El campo no es válido.',
                'text.required' => 'El campo es requerido.',
                'mail.email' => 'El campo debe ser un correo válido.',
                'cell.max' => 'El campo no es válido.'
            ]);

            if (empty($validator->fails())) {
                Mail::raw(sprintf("NOMBRE: %s\n\nCORREO: %s\n\nTELÉFONO: %s\n\nMENSAJE: %s", trim($request->get('name')), trim($request->get('mail')), trim($request->get('cell')), trim($request->get('text'))), function ($message) use ($request) {
                    $message->to(env('APP_MAIL'), env('APP_NAME'))
                            ->from(trim($request->get('mail')), trim($request->get('name')))
                            ->subject([1 => 'Solicitud', 2 => 'Sugerencia', 3 => 'Felicitaciones'][intval($request->get('type'))]);
                });

                return response()->json(['text' => 'El mensaje fue enviado con éxito.'], 200);
            } else {
                return response()->json(['text' => 'Uno o más campos del formulario no son correctos.',
                                         'list' => array_map(function ($item) {
                    return current($item);
                }, $validator->errors()->toArray())], 400);
            }
        } else {
            return view('/site/mail', ['$request' => $request]);
        }
	}
}