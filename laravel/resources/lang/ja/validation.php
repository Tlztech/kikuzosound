<?php  // resources/lang/ja/validation.php
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは正しいURLではありません。',
    'after'                => ':attributeは:date以降の日付にしてください。',
    'alpha'                => ':attributeは英字のみにしてください。',
    'alpha_dash'           => ':attributeは英数字とハイフンのみにしてください。',
    'alpha_num'            => ':attributeは英数字のみにしてください。',
    'array'                => ':attributeは配列にしてください。',
    'before'               => ':attributeは:date以前の日付にしてください。',
    'between'              => [
        'numeric' => ':attributeは:min〜:maxまでにしてください。',
        'file'    => ':attributeは:min〜:max KBまでのファイルにしてください。',
        'string'  => ':attributeは:min〜:max文字にしてください。',
        'array'   => ':attributeは:min〜:max個までにしてください。',
    ],
    'boolean'              => ':attributeはtrueかfalseにしてください。',
    'confirmed'            => ':attributeは確認用項目と一致していません。',
    'date'                 => ':attributeは正しい日付ではありません。',
    'date_format'          => ':attributeは":format"書式と一致していません。',
    'different'            => ':attributeは:otherと違うものにしてください。',
    'digits'               => ':attributeは:digits桁にしてください',
    'digits_between'       => ':attributeは:min〜:max桁にしてください。',
    'email'                => ':attributeを正しいメールアドレスにしてください。',
    'filled'               => ':attributeは必須です。',
    'exists'               => '選択された:attributeは正しくありません。',
    'image'                => ':attributeは画像にしてください。',
    'in'                   => '選択された:attributeは正しくありません。',
    'integer'              => ':attributeは整数にしてください。',
    'ip'                   => ':attributeを正しいIPアドレスにしてください。',
    'max'                  => [
        'numeric' => ':attributeは:max以下にしてください。',
        'file'    => ':attributeは:max KB以下のファイルにしてください。.',
        'string'  => ':attributeは:max文字以下にしてください。',
        'array'   => ':attributeは:max個以下にしてください。',
    ],
    'mimes'                => ':attributeは:valuesタイプのファイルにしてください。',
    'min'                  => [
        'numeric' => ':attributeは:min以上にしてください。',
        'file'    => ':attributeは:min KB以上のファイルにしてください。.',
        'string'  => ':attributeは:min文字以上にしてください。',
        'array'   => ':attributeは:min個以上にしてください。',
    ],
    'not_in'               => '選択された:attributeは正しくありません。',
    'numeric'              => ':attributeは数字にしてください。',
    'regex'                => ':attributeの書式が正しくありません。',
    'required'             => ':attributeは必須です。',
    'required_if'          => ':otherが:valueの時、:attributeは必須です。',
    'required_with'        => ':valuesが存在する時、:attributeは必須です。',
    'required_with_all'    => ':valuesが存在する時、:attributeは必須です。',
    'required_without'     => ':valuesが存在しない時、:attributeは必須です。',
    'required_without_all' => ':valuesが存在しない時、:attributeは必須です。',
    'same'                 => ':attributeと:otherは一致していません。',
    'size'                 => [
        'numeric' => ':attributeは:sizeにしてください。',
        'file'    => ':attributeは:size KBにしてください。.',
        'string'  => ':attribute:size文字にしてください。',
        'array'   => ':attributeは:size個にしてください。',
    ],
    'string'               => ':attributeは文字列にしてください。',
    'timezone'             => ':attributeは正しいタイムゾーンをしていしてください。',
    'unique'               => ':attributeは既に存在します。',
    'url'                  => ':attributeを正しい書式にしてください。',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        'title' => 'タイトル',
        'password' => 'パスワード',
        'title_color' => 'タイトルの色',
        'description' => '説明',
        'bg_img' => 'アイコン',
        'icon_path' => 'アイコン',
        'quiz_order_type' => '出題形式',
        'max_quiz_count' => '出題数',
        'quizzes' => 'クイズ',
        'name' => '氏名',
        'email' => 'メールアドレス',
        'enabled' => '有効/無効',
        // クイズ追加/編集
        'question' => '設問',
        'stetho_sounds' => '聴診音',
        'image' => 'イラスト',
        'limit_seconds' => '制限時間',
        'quiz_choices' => '選択肢',
        // 聴診音
        'sound_file' => '聴診音ファイル',
        'sound_path' => '聴診音ファイル',
        'type' => '音声種別',
        'area' => '発生場所',
        'conversion_type' => '加工方法',
        'is_normal' => '正常/異常',
        'disease' => '代表疾患',
        'description' => '説明',
        'sub_description' => '日本語だけの場合は30文字までです',
        'sub_description_en' => '日本語だけの場合は30文字までです',
        'disp_order' => '表示順',
        'status' => 'ステータス'
    ],

    //r-mail-form validation

    'mail_required'     => 'メールアドレスを入力してください。',
    'password_required' => 'パスワードを入力してください。',
    'group_required'    => '勤務先/学校名を入力してください。',
    'group_max'         => '勤務先/学校名は50文字以内で入力してください',
    'name1_required'    => '姓を入力してください。',
    'name1_max'         => '姓は50文字以内で入力してください',
    'name2_required'    => '名を入力してください。    ',
    'name2_max'         => '名は50文字以内で入力してください',
    'kana1_required'    => '姓(カナ)を入力してください。',
    'kana1_max'         => '姓(平仮名)は50文字以内で入力してください',
    'kana2_required'    => '名(カナ)を入力してください。',
    'kana2_max'         => '名(平仮名)は50文字以内で入力してください',
    'mail_email'        => '正しいメールアドレスを入力してください。',
    'mail_max'          => 'メールは200文字以内で入力してください',
    'password_regex'    => 'パスワードは半角英数字の5文字以上30文字以下で入力して下さい',
    'gender_required'   => '性別を選択して下さい。',
    'tel_min'           => '電話番号は9～15桁の数字で入力してください。',
    'tel_max'           => '電話番号は9～15桁の数字で入力してください。',
    'tel_regex'         => '電話番号は9～15桁の数字で入力してください。',
    'tel_integer'       => '電話番号は9～15桁の数字で入力してください。',
    'tel_digits_between'       => '電話番号は9～15桁の数字で入力してください。',


    //contact-form validation

    'facility_name_required' => '施設名を入力してください。',
    'facility_name_length' => '施設名は50文字以内で入力してください',
    'name_required' => 'お名前を入力してください。',
    'name_length' => 'お名前は50文字以内で入力してください',
    'email_required' => 'メールアドレスを入力してください。',
    'phone_required' => '電話番号を入力してください。',
    'question_max' => 'お問合わせ内容は500文字以内で入力してください。',
    'question_regex' => 'お問合わせ内容にHTMLを入力することはできません',


    //application-form validation

    'affiliation_required' => 'ご所属を入力してください',
    'application_max' => 'ご所属は100文字以内で入力してください',
    'zip_required' => '郵便番号を入力してください',
    'address_required' => 'ご住所を入力してください',
    'address_max' => 'ご住所は200文字以内で入力してください',
    'dealer_max' => '販売会社名は100文字以内です',
    'area_max' => '支店/営業所は100文字以内です',
    'agree_required' => '購入する場合はチェックをしてください',
    'kind_max' => 'クーポンコードは100文字以内です',

];
