<?php

namespace App\Http\Requests;

class StethoSoundRequest extends Request
{
    protected $dates = ['updated_at'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            // // Laravel5.1ではmp3のmimesバリデーションが意図しない動作をするため検証対象外とした
            // //'sound_file'      => 'required|mimes:mp3',
            'sound_file' => 'required',
            'sound_path' => 'max:255',
            // // 'title' => 'required|max:255',
            'title_en' => 'required|max:255',
            'type' => 'required|in:1,2,3,9',
            // // 'area' => 'required|max:255',
            'area_en' => 'required|max:255',
            'conversion_type' => 'required|in:0,1,2',
            'is_normal' => 'required|boolean',
            // // 'disease' => 'required|max:255',
            'disease_en' => 'required|max:255',
            'sub_description' => 'max:30',
            // // 'sub_description_en' => 'max:255',
            // // 'description' => 'required|max:1000',
            'description_en' => 'required|max:1000',
            'disp_order' => 'min:1|max:6|regex:/^[-0-9]{1,6}$/',
            'status' => 'required|in:0,1,2,3',
            // 'image_files' => 'required',
            // 'image_files.*' => 'mimes:jpeg,png,jpg,gif,svg'
        ];

        // 新規追加の場合は音声ファイルが必須
        // if ('PUT' == $this->method() || 'PATCH' == $this->method()) {
        //     $rules['sound_file'] = '';
        // }

        return $rules;
    }
    public function messages()
    {
        return [
            'user_id.required' => 'user id required。',
            'sound_file.required' => '聴診音ファイルは必須です。',
            // 'sound_file.mimes' => 'MP3 か MP4 ファイルのみアップロードできます。',
            'title_en.required'      => 'タイトルは必須です。',
            'title_en.max' => 'タイトルは255文字以内で入力してください。',
            'type.required' => '聴診音タイプが必要です。',
            'type.in' => '聴診音のタイプは、肺の音、心臓の音、腸の音などでなければなりません。',
            'area_en.required'    => '聴診部位は必須です。',
            'area_en.max' => '聴診部位は255文字以下にする必要があります。',
            'conversion_type.required' => '加工方法が必要です。',
            'conversion_type.in' => '処理方法は、オリジナル、処理音、または人工音でなければなりません。',
            'disease_en.required'   => '代表疾患は必須です。',
            'disease_en.max' => '代表疾患は255文字以下にする必要があります。',
            'description_en.required'   => '音の説明は必須です。',
            'sub_description.max' => '音源・提供者等は３０文字までで入力してください。',
            'status.required' => 'ステータスが必要です。',
            'status.in' => 'ステータスは「監視中」、「監視中」、「現在開いている」または「公開（新規）」である必要があります。',
            // 'image_files.*.mimes' => '画像は .jpg .gif .png のみアップロードできます。',
            // 'image_files.required' => '画像ファイルは必須です。',
            'description_en.max' => '説明は1000文字を超えないように設定してください。',
            'sub_description.max' => '音源・提供者等は３０文字までで入力してください。'

        ];
    }

    // 検証後に呼ばれる
    // 検証エラー時にユーザがアップロードしたファイルを再度アップロードしなくて良いようにするため。
    public function after($validator)
    {       
        parent::after($validator);

        if ($this->hasFile('sound_file')) {
            $sound_file = $this->file('sound_file');
            $sound_ext = $sound_file->getClientOriginalExtension();

            $sound_check=in_array(strtolower($sound_ext),['mp3','mp4']);
            if (!$sound_check) {
                $validator->errors()->add('sound_file', 'MP3 か MP4 ファイルのみアップロードできます。');
            }
        }

        if ($this->hasFile('image_files')) {
            $allowedfileExtension=['jpeg','png','jpg','gif','svg'];
            foreach ($this->file('image_files') as $image) {
                $file_image = $image;
                $file_extension = $file_image->getClientOriginalExtension();
                $check=in_array(strtolower($file_extension),$allowedfileExtension);
                if (!$check) {
                    $validator->errors()->add('image_files', '画像は .jpg .gif .png のみアップロードできます。');
                }
            }
        }
        return redirect()->back()->withErrors($validator);
        exit;
        if (count($validator->invalid()) > 0) {
            // 聴診音ファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
            $this->setSessionAndMoveSingleFile('sound_file', '/tmp/stetho_sounds/', 'sound_path');

            // 検証エラーで画像ファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
            if ($this->hasFile('image_files')) {
                $images = [];
                $files = $this->file('image_files');
                $paths = $this->input('image_paths');
                $ids = $this->input('image_ids');

                \Session::forget('stetho_sound_images');

                $count = count($files);
                for ($i = 0; $i < $count; ++$i) {
                    $file = $files[$i];
                    $id = $ids[$i];
                    $path = $paths[$i];

                    if (!is_null($file)) {
                        $path = $this->moveFile($file, '/tmp/stetho_sound_images/');
                    }
                    $images[] = [
                        'id' => $id,
                        'image_path' => $path,
                    ];
                }
                \Session::set('stetho_sound_images', $images);
            }
        }
    }
}