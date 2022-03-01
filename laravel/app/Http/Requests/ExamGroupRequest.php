<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ExamGroupRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'exam_group_name' => 'required|unique:universities,name',
        ];
    }
    public function messages() {
        return [
            'exam_group_name.unique' => 'Examグループ名はすでに存在します。',
            'exam_group_name.required' => 'Examグループ名を入力してください'
        ];
    }
}
