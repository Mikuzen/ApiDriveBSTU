<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\File\FileStoreRequest;
use App\Http\Resources\FileResource;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DiskController extends Controller
{
    private $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/vnd.wap.bmp'];
    private $videoMimes = ['video/mpeg', 'video/mp4', 'video/x-msvideo'];
    private $audioMimes = ['audio/mpeg', 'video/mp4', 'video/wnd.wave'];

    public function index()
    {
        return FileResource::collection(File::withTrashed()->with('link')->get());
    }

    public function store(FileStoreRequest $request)
    {
        if (!$request->hasFile('files')) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $validated = $request->validated();
        $user = User::findOrFail($validated['user_id']);
        $filesList = [];

        if ($user) {
            $dataRequest = $this->getDataRequest($user->id);

            foreach ($request->file('files') as $file) {

                $dataForDB = $this->getDataForDB($user->name, $file);

                if (!Storage::disk('public')->has('files/' . $user->id
                    . '/' . $dataRequest['folder'] . '/resize')) {
                    Storage::disk('public')->makeDirectory('files/' . $user->id
                        . '/' . $dataRequest['folder'] . '/resize');
                }

                if ($dataForDB['type'] == 'image') {
                    Image::make($file)->resize(250, 250)
                        ->save($dataRequest['folderResize'] . '/' . $dataForDB['src']);
                }

                Storage::putFileAs($dataRequest['folderOriginal'], $file, $dataForDB['src']);

                $filesList[] = File::create([
                    'user_id' => $user->id,
                    'src' => $dataForDB['src'],
                    'ext' => $dataForDB['ext'],
                    'title' => $dataForDB['title'],
                    'size' => $dataForDB['size'],
                    'type' => $dataForDB['type'],
                    'private' => true,
                    'folder' => $dataRequest['folder']
                ]);
            }

            return FileResource::collection($filesList);
        } else return response()->json(['message' => 'Пользователь не найден или файл не загружен'], 404);
    }

    public function show($file)
    {
        return new FileResource(File::withTrashed()->with('link')->findOrFail($file));
    }

    public function destroy($id)
    {
        $file = File::withTrashed()->findOrFail($id);
        if ($file->type === 'image') {
            Storage::disk('public')
                ->delete('/files/' . $file->user_id . '/' . $file->folder . '/resize/' . $file->src);
        }

        Storage::disk('public')
            ->delete('/files/' . $file->user_id . '/' . $file->folder . '/' . $file->src);

        $file->forceDelete();

        return response()->json([], 204);
    }

    private function getDataRequest($user)
    {
        $folder = Carbon::now()->toDateString();
        $folderResize = public_path('storage/files/' . $user . '/' . $folder . '/resize');
        $folderOriginal = '/public/files/' . $user . '/' . $folder;

        return [
            'folder' => $folder,
            'folderResize' => $folderResize,
            'folderOriginal' => $folderOriginal,
        ];
    }

    private function getDataForDB($user, $file)
    {
        $mime = $file->getMimeType();
        $title = strstr($file->getClientOriginalName(), '.', true);
        $size = round($file->getSize() / 1024);
        $ext = $file->getClientOriginalExtension();
        $src = str_replace(' ','_',$user . Carbon::now()->timestamp . $title . '.' . $ext);

        $type = $this->getType($mime);

        return [
            'src' => $src,
            'ext' => $ext,
            'title' => $title,
            'size' => $size,
            'type' => $type,
        ];
    }

    private function getType($mime)
    {
        $var = 'app';

        if (in_array($mime, $this->imageMimes)) {
            $var = 'image';
        } elseif (in_array($mime, $this->videoMimes)) {
            $var = 'video';
        } elseif (in_array($mime, $this->audioMimes)) {
            $var = 'audio';
        }

        return $var;
    }
}
