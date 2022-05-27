<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\File\FileStoreRequest;
use App\Http\Resources\FileResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DiskController extends Controller
{
    public function index()
    {
        return FileResource::collection(File::withTrashed()->with('link')->get());
    }

    public function store(FileStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['user_id'];
        $files = [];
        if ($user = User::findOrFail($id)) {
           $dataRequest = $this->getDataRequest($request, $user->id);

            foreach ($dataRequest['files'] as $file) {
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

               $files[] = File::create([
                    'user_id' => $dataRequest['user']->id,
                    'src' => $dataForDB['src'],
                    'ext' => $dataForDB['ext'],
                    'title' => $dataForDB['title'],
                    'size' => $dataForDB['size'],
                    'type' => $dataForDB['type'],
                    'folder' => $dataRequest['folder']
                ]);
            }

            return FileResource::collection($files);
        }
        else return response()->json(['message' => 'Пользователь не найден'], 404);

    }

    public function show($file)
    {
        return new FileResource(File::with('link')->findOrFail($file));
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    private function getDataRequest($request, $user)
    {
        $folder = Carbon::now()->toDateString();
        $folderResize = public_path('storage/files/' . $user. '/' . $folder . '/resize');
        $folderOriginal = public_path('/files/' . $user . '/' . $folder);
        $files = $request->file('files');

        return $folderOriginal;

        return [
            'folder' => $folder,
            'folderResize' => $folderResize,
            'folderOriginal' => $folderOriginal,
            'files' => $files
        ];
    }

    private function getDataForDB($user, $file)
    {
        $mime = $file->getMimeType();
        $title = strstr($file->getClientOriginalName(), '.', true);
        $size = $file->getSize();
        $size = round($size / 1024);
        $ext = $file->getClientOriginalExtension();
        $src = $user . Carbon::now()->timestamp . $title . '.' . $ext; //Carbon::now()->timestamp

        $type = $this->getType($mime);

        return [
            'src' => $src,
            'ext' => $ext,
            'title' => $title,
            'size' => $size,
            'type' => $type,
        ];
    }
}
