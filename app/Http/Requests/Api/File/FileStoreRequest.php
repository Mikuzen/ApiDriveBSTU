<?php

namespace App\Http\Requests\Api\File;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
{
    private $allMimes = ['jpeg', 'jpg', 'png', 'gif', 'bmp',
        'mpeg', 'mp3', 'mp4', 'avi', 'wav',
        'zip', 'pdf', 'doc', 'docx', 'rar'];


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'files' => 'required',
            'files.*' => 'file|mimes:.' . implode(',', $this->allMimes)
        ];
    }
}
