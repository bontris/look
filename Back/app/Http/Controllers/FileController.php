<?php

namespace App\Http\Controllers;

use Auth;

use User;

use Validator;

use Google\Client;

use Google\Service\Drive;

use Illuminate\Http\Request;

use GuzzleHttp\RequestOptions;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Cell\DataType;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

use Intervention\Image\ImageManagerStatic as Image;

class FileController extends Controller
{
	public function main (Request $request, $task = null, $item = null) {
        if (isset($item)) {
            switch (strtolower($task)) {
                case 'save':
                    if (($item = DB::table('files')
                                    ->where('files.hide', 0)
                                    ->where('files.hash', $item)
                                    ->first())) {
                        if (file_exists(($path = sprintf('%s/%s', storage_path('files'), $item->hash)))) {
                            return response(File::get($path))->header('Content-Type', $item->type)
                                                            ->header('Content-Disposition', sprintf('filename="%s"', $item->name));
                        }
                    }

                    return response('File not found.', 404);
                case 'open':
                    $list = [];
    
                    $client = new Client();

                    $user = Auth::user();
    
                    $client->setClientId(env('API_GOOGLE_CLIENT'));
    
                    $client->setClientSecret(env('API_GOOGLE_SECRET'));
    
                    $client->refreshToken(env('API_GOOGLE_TOKEN'));
    
                    $client->addScope(Drive::DRIVE_FILE);
    
                    $drive = new Drive($client);

                    if (($item = $drive->files->get($item))) {
                        $data = $drive->files->listFiles(['q' => sprintf('\'%s\' in parents and trashed = false', $item->id), 'fields' => 'files(id, name, size, mimeType)']);
    
                        if (isset($data)) {
                            $list = array_reduce($data->files, function ($list, $item) {
                                array_push($list, [
                                    'item' => $item->id,
                                    'size' => $item->size,
                                    'name' => $item->name,
                                    'type' => $item->mimeType
                                ]);
        
                                return $list;
                            }, $list);
                        }

                        return response()->json(['name' => $item->name, 'list' => $list, 'data'=>$item], 200);
                    } else {
                        return response()->json([
                            'text' => 'La carpeta no fue encontrada.'
                        ], 404);
                    }
                default:
                    try {
                        $client = new Client();
    
                        $client->setClientId(env('API_GOOGLE_CLIENT'));
    
                        $client->setClientSecret(env('API_GOOGLE_SECRET'));
    
                        $client->refreshToken(env('API_GOOGLE_TOKEN'));
    
                        $client->addScope(Drive::DRIVE_FILE);
                        
                        $drive = new Drive($client);

                        $data = $drive->files->get($item);
                        
                        return response($drive->files->get($item, array('alt' => 'media'))->getBody()->getContents())->header('Content-Type', $data->mimeType)
    			                                                                          ->header('Content-Disposition', sprintf('filename="%s"', $data->name));
                    } catch (Exception $exception) {
                        Log::error($exception->getMessage());

                        abort(500, 'Something went wrong.');
                    }
            }
        } else {
            switch (strtolower($task)) {
                default:
                    $list = [];
    
                    $client = new Client();

                    $user = Auth::user();
    
                    $client->setClientId(env('API_GOOGLE_CLIENT'));
    
                    $client->setClientSecret(env('API_GOOGLE_SECRET'));
    
                    $client->refreshToken(env('API_GOOGLE_TOKEN'));
    
                    $client->addScope(Drive::DRIVE_FILE);
    
                    $drive = new Drive($client);

                    if ($user->firm->disk) {
                        $data = $drive->files->listFiles(['q' => sprintf('\'%s\' in parents and trashed = false', $user->firm->disk), 'fields' => 'files(id, name, size, mimeType)']);
   
                        if (isset($data)) {
                            $list = array_reduce($data->files, function ($list, $item) {
                                array_push($list, [
                                    'item' => $item->id,
                                    'size' => $item->size,
                                    'name' => $item->name,
                                    'type' => $item->mimeType
                                ]);
        
                                return $list;
                            }, $list);
                        }
                    }
    
                    return response()->json($list, 200);
            }
        }
	}

    public function snap (Request $request, $item, $size = null) {
        if (file_exists(($path = sprintf('%s/%s', storage_path('files'), $item)))) {
            switch (strtolower($size)) {
                case 'thumb':
                    return Image::make($path)->resize(64, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->response('png');
                case 'small':
                    return Image::make($path)->resize(680, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->response('png');
                default:
                    return Image::make($path)->response('png');
            }
        } else {
            return response('Image not found.', 404);
        }
    }
}