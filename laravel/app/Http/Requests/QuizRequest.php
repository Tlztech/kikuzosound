<?php

namespace App\Http\Requests;

class QuizRequest extends Request
{
    protected $dates = ['updated_at'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'title_en' => 'required|max:10000',
            'question' => 'required|max:1000',
            'question_en' => 'required|max:10000',
            // 'case_age' => 'required|max:3',
            // 'case' => 'required|max:10000',
            // 'case_en' => 'required|max:10000',
            'stetho_sounds' => 'array|max:6',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'image_path' => 'max:255',
            'limit_seconds' => 'integer',
            // 'quiz_choices' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title_en.required'      => 'タイトル(EN)は必須です',
            'title_en.max' => 'タイトルは10000文字以内で入力してください。(EN)',
            'title.required'      => 'タイトル(JP)は必須です。',
            'title.max' => 'タイトルは10000文字以内で入力してください。(JP)',
            'case_age.required' => 'ケース欄は必須です',
            'case_age.max' => '年齢フィールドを3桁以内で入力してください。',
            'case.required' => '現在のケース（JP）は必須です',
            'case.max' => '現在のケース（JP）を10,000文字以内で入力してください。',
            'case_en.required' => '現在のケース（EN）が必要です',
            'case_en.max' => '現在のケース（EN）を10,000文字以内で入力してください。',
            'question_en.required'    => '設問(EN)は必須です。',
            'question_en.max' => '設問は10000文字以内で入力してください。(EN)',
            'question.required'    => '設問(JP)は必須です。',
            'question.max' => '設問は10000文字以内で入力してください。(JP)',
            'image.mimes' => 'アイコンは .jpg .gif .pngのみ設定できます。',
            'quiz_choices.required' => '選択肢は必須です。'
        ];
    }

    // 検証後に呼ばれる
    // 検証エラー時にユーザがアップロードしたファイルを再度アップロードしなくて良いようにするため。
    public function after($validator)
    {
        parent::after($validator);
        $lib_count=0;
        $optional = $this->input('is_optional');
        //stethoscope choices
        if (!empty($this->input('stethoscope', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('stethoscope_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('stethoscope_quiz_choices', 'iPaxの選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('stethoscope_quiz_choices_fill_in', 'オースカレイドの入力欄は必須です。');
            // }
        }
        //auscultation choices
        if (!empty($this->input('auscultation', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('auscultation_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('auscultation_quiz_choices', '聴診音の選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('auscultation_quiz_choices_fill_in', '聴診音の入力欄は必須です。');
            // }
        }
        //palpation choices
        if (!empty($this->input('palpation', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('palpation_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('palpation_quiz_choices', '触診の選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('palpation_quiz_choices_fill_in', '触診の入力欄は必須です。');
            // }
        }
        //ecg choices
        if (!empty($this->input('ecg', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('ecg_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('ecg_quiz_choices', '心電図の選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('ecg_quiz_choices_fill_in', '心電図の入力欄は必須です。');
            // }
        }
        //examination choices
        if (!empty($this->input('examination', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('examination_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('examination_quiz_choices', '視診の選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('examination_quiz_choices_fill_in', '視診の入力欄は必須です。');
            // }
        }
        //xray choices
        if (!empty($this->input('xray', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('xray_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('xray_quiz_choices', 'レントゲンの選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('xray_quiz_choices_fill_in', 'レントゲンの入力欄は必須です。');
            // }
        }
        //echo choices
        if (!empty($this->input('echo', []))) {
            $lib_count +=1;
            $stetho_choices = $this->input('echo_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if ($optional && empty($choices)) {
                $validator->errors()->add('echo_quiz_choices', '心エコーの選択肢は必須です。');
            }
            // if (empty($stetho_choices['fill_in']['title'])) {
            //     $validator->errors()->add('echo_quiz_choices_fill_in', '心エコーの入力欄は必須です。');
            // }
        }

        if(!$optional){ //fill-in only validations
            //final choice
            $stetho_choices = $this->input('fill_final_answer_quiz_choices', []);
            $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
            if(count(array_filter($stetho_choices))==1){//validate if only 1 choice
                $f_key = array_keys($stetho_choices)[0]; //get the first key
                if (empty($stetho_choices[$f_key]['fill_in']['title'])) {
                    $validator->errors()->add('final_answer_choices_fill_in', '最終解答の入力が必須です。');
                }
            }
            if (empty($choices)) {
                $validator->errors()->add('final_answer_choices_fill_in', '最終解答の入力が必須です。');
            }
        }else{ //optional only validations
            //no contents validation
            if (empty($this->input('echo', []))
                && empty($this->input('xray', []))
                && empty($this->input('examination', []))
                && empty($this->input('ecg', []))
                && empty($this->input('palpation', []))
                && empty($this->input('auscultation', []))
                && empty($this->input('stethoscope', []))) {
                $validator->errors()->add('library', '少なくとも1つのライブラリを入力してください。');
            }

            //Final choices
            if ($lib_count>1) {
                $stetho_choices = $this->input('final_answer_quiz_choices', []);
                $choices = count(array_filter(array_keys($stetho_choices), 'is_int'));
                if (empty($choices)) {
                    $validator->errors()->add('final_answer_quiz_choices', 'ケーススタディには最終解答の選択肢が必須です。');
                }
            }
        }

        if (count($validator->invalid()) > 0) {
            // イラストファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
            $this->setSessionAndMoveSingleFile('image', '/tmp/quizzes/', 'quiz_image_path');
        }
    }
}
