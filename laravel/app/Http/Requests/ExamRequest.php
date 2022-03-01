<?php

namespace App\Http\Requests;

class ExamRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => $this->exam ? 'required|unique:exams,user,'.$this->exam : 'required|unique:exams,user',
            'password' => $this->exam ? 'max:50' :'required|max:50',
            'speaker' => $this->exam ? 'max:50': 'required|max:50',
            'unit' => 'required',
            'disp_order' => 'required',
        ];
    }
}
