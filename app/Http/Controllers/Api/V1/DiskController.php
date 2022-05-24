<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\File\FileStoreRequest;
use App\Http\Resources\FileResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\File;
use Carbon\Carbon;

class DiskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FileResource::collection(File::with('link')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileStoreRequest $request)
    {
        $filess = array();

        if (!is_null(User::find($request->input('user_id'))) ) {
            $dataRequest = $this->getDataRequest($request);

            foreach ($dataRequest['files'] as $file) {
                $dataForDB = $this->getDataForDB($dataRequest['user']->name, $file);

                if (!Storage::disk('public')->has('files/' . $dataRequest['user']->id
                    . '/' . $dataRequest['folder'] . '/resize')) {
                    Storage::disk('public')->makeDirectory('files/' . $dataRequest['user']->id
                        . '/' . $dataRequest['folder'] . '/resize');
                }

                if ($dataForDB['type'] == 'image') {
                    Image::make($file)->resize(250, 250)
                        ->save($dataRequest['folderResize'] . '/' . $dataForDB['src']);
                }

                Storage::putFileAs($dataRequest['folderOriginal'], $file, $dataForDB['src']);

                $file[] = File::create([
                    'user_id' => $dataRequest['user']->id,
                    'src' => $dataForDB['src'],
                    'ext' => $dataForDB['ext'],
                    'title' => $dataForDB['title'],
                    'size' => $dataForDB['size'],
                    'type' => $dataForDB['type'],
                    'folder' => $dataRequest['folder']
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'File successfully uploaded.',
                'files' => FileResource::collection($filess),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getDataRequest($request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $folder = Carbon::now()->toDateString();
        $folderResize = public_path('storage/files/' . $user->id . '/' . $folder . '/resize');
        $folderOriginal = 'public/files/' . $user->id . '/' . $folder;
        $files = $request->file('files');;

        return [
            'user' => $user,
            'folder' => $folder,
            'folderResize' => $folderResize,
            'folderOriginal' => $folderOriginal,
            'files' => $files,
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

    private function getType($mime) // KISS
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
