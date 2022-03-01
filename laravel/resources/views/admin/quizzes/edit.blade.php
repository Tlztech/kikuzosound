@extends('admin.layouts.app')
@section('content')
<?php
    $lib_type=[
        1 => [
            "input_name" => "stethoscope",
            "setting_label" => "iPax・選択肢設定",
            "input_label" => "iPax",
            "explanation_label" => "この聴診器を解答説明する",
            "add_lib_btn_label"=>"iPaxを追加"
        ],
        0 => [
            "input_name" => "auscultation",
            "setting_label" => "聴診音・選択肢設定",
            "input_label" => "聴診音",
            "explanation_label" => "この聴診音を解答説明する",
            "add_lib_btn_label"=>"聴診音を追加"
        ],
        2 => [
            "input_name" => "palpation",
            "setting_label" => "触診・選択肢設定",
            "input_label" => "触診",
            "explanation_label" => "この触診を解答説明する",
            "add_lib_btn_label"=>"触診を追加"
        ],
        3 => [
            "input_name" => "ecg",
            "setting_label" => "心電図・選択肢設定",
            "input_label" => "心電図",
            "explanation_label" => "この心電図を解答説明する",
            "add_lib_btn_label"=>"心電図を追加"
        ],
        4 => [
            "input_name" => "examination",
            "setting_label" => "視診・選択肢設定",
            "input_label" => "視診",
            "explanation_label" => "この視診を解答説明する",
            "add_lib_btn_label"=>"視診を追加"
        ],
        5 => [
            "input_name" => "xray",
            "setting_label" => "レントゲン・選択肢設定",
            "input_label" => "レントゲン",
            "explanation_label" => "このレントゲンを解答説明する",
            "add_lib_btn_label"=>"レントゲンを追加"
        ],
        6 => [
            "input_name" => "echo",
            "setting_label" => "心エコー選択肢設定",
            "input_label" => "心エコー",
            "explanation_label" => "この心エコーを解答説明する",
            "add_lib_btn_label"=>"心エコーを追加"
        ],
    ];
?>
<div class="page-header">
    <h1>クイズ編集</h1>
</div>

<div class="row">
    <div class="col-sm-12">
        @include('admin.common.form_errors')
        <form class="form-horizontal" action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" name="updated_at" value="{{old('updated_at',$quiz->updated_at)}}" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">全体設定</h3>
                                </div>
                                <div class="panel-body">
                                    {{-- 強制更新 --}}
                                    @if($errors->has("is_force_update"))
                                    <div class="form-group has-error">
                                        <label for="is_force_update-field" class="col-sm-2 control-label">強制更新</label>
                                        <div class="col-sm-10">
                                            <input type="checkbox" name="is_force_update" checked
                                                style="margin-top: 10px;" />
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group @if($errors->has('title')) has-error @endif">
                                        <label for="title-field" class="col-sm-2 control-label">タイトル (JP)</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="title-field" name="title" class="form-control"
                                                value="{{ old('title',$quiz->title) }}" />
                                            @if($errors->has("title"))
                                            <span class="help-block">{{ $errors->first("title") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('title_en')) has-error @endif">
                                        <label for="title-field" class="col-sm-2 control-label">タイトル (EN)</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="title-field" name="title_en" class="form-control"
                                                value="{{ old('title_en',$quiz->title_en) }}" />
                                            @if($errors->has("title_en"))
                                            <span class="help-block">{{ $errors->first("title_en") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('question')) has-error @endif">
                                        <label for="question-field" class="col-sm-2 control-label">設問 (JP)</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="question-field" name="question" class="form-control"
                                                value="{{ old('question',$quiz->question) }}" />
                                            @if($errors->has("question"))
                                            <span class="help-block">{{ $errors->first("question") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('question_en')) has-error @endif">
                                        <label for="question-field" class="col-sm-2 control-label">設問 (EN)</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="question-field" name="question_en"
                                                class="form-control"
                                                value="{{ old('question_en',$quiz->question_en) }}" />
                                            @if($errors->has("question_en"))
                                            <span class="help-block">{{ $errors->first("question_en") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div
                                        class="form-group @if($errors->has('image') || $errors->has('image_path')) has-error @endif">
                                        <label for="image-field" class="col-sm-2 control-label">イラスト</label>
                                        <div class="col-sm-10">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <?php
                                                            $quiz_image_path = \Session::has('quiz_image_path') ? \Session::get('quiz_image_path') : old('image_path', $quiz->image_path);
                                                            \Session::forget('quiz_image_path');
                                                        ?>
                                                        <input type="file" class="well well-sm" id="quiz_image-field"
                                                            name="image" accept=".jpg,.png,image/jpeg,image/png"
                                                            value="" />
                                                        <img src="{{ $quiz_image_path.'?v='.session('version') }}"
                                                            id="quiz_image-img"
                                                            onerror='$("#image_path-field").val("");'
                                                            style="height:220px;@if(empty($quiz_image_path))display:none;@endif" />
                                                        <input id="image_path-field" type="hidden" name="image_path"
                                                            value="@if(!empty($quiz_image_path)){{ $quiz_image_path }}@endif" />
                                                        @if($errors->has("image"))
                                                        <span class="help-block">{{ $errors->first("image") }}</span>
                                                        @endif
                                                        @if($errors->has("image_path"))
                                                        <span
                                                            class="help-block">{{ $errors->first("image_path") }}</span>
                                                        @endif
                                                        <br /><input id="quiz_image_remove-btn" type="button"
                                                            value="画像を削除" class="btn btn-default"
                                                            style="@if(empty($quiz_image_path))display:none;@endif" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div
                                            class="@if($errors->has('case_age')||$errors->has('case_gender')) has-error @endif">
                                            <label for="case_age-field" class="col-sm-2 control-label">症例</label>
                                            <div class="col-sm-5">
                                                <input type="number" id="case_age-field" name="case_age"
                                                    class="form-control" value="{{ old('case_age',$quiz->case_age) }}"
                                                    placeholder="歳" />
                                                @if($errors->has("case_age"))
                                                <span class="help-block">{{ $errors->first("case_age") }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <?php $case_gender = [ 0 => '男性', 1 => '女性']; ?>
                                            <select class="form-control" name="case_gender" id="case_gender-field">
                                                {{old('case_gender')}}
                                                @foreach($case_gender as $key => $gender)
                                                <option value="{{$key}}" @if($key==old('case_gender',$quiz->
                                                    case_gender)) selected
                                                    @endif>{{$gender}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('case')) has-error @endif">
                                        <label for="case-field" class="col-sm-2 control-label">現症例 (JP)</label>
                                        <div class="col-sm-10">
                                            <textarea id="description-field" name="case"
                                                class="form-control">{{ old("case",$quiz->case) }}</textarea>
                                            @if($errors->has("case"))
                                            <span class="help-block">{{ $errors->first("case") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('case_en')) has-error @endif">
                                        <label for="case_en-field" class="col-sm-2 control-label">現症例 (EN)</label>
                                        <div class="col-sm-10">
                                            <textarea id="description-en-field" name="case_en"
                                                class="form-control">{{ old("case_en",$quiz->case_en) }}</textarea>
                                            @if($errors->has("case_en"))
                                            <span class="help-block">{{ $errors->first("case_en") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group @if($errors->has('limit_seconds')) has-error @endif">
                                        <label for="limit_seconds-field" class="col-sm-2 control-label">制限時間</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="limit_seconds-field" name="limit_seconds"
                                                min="0"
                                                class="form-control"
                                                value="{{ old('limit_seconds',$quiz->limit_seconds) }}"
                                                placeholder="30" />
                                            <p style="color:#B8B7B7;font-family:inherit;">※無制限を指定する場合は0を入力して下さい</p>
                                            @if($errors->has("limit_seconds"))
                                            <span class="help-block">{{ $errors->first("limit_seconds") }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- クイズ形式 -->
                                    <div class="form-group @if($errors->has('is_optional')) has-error @endif">
                                        <label for="is_optional-field" class="col-sm-2 control-label">クイズ形式</label>
                                            <div class="col-sm-10">
                                                <div class="radio">
                                                    <label class="col-sm-2">
                                                        <input type="radio"  name="is_optional" value='1'
                                                        {{ old("is_optional",$quiz->is_optional?1:0)==1 ? "checked" : "" }} />選択式
                                                    </label>
                                                    <label class="col-sm-2">
                                                        <input type="radio"  name="is_optional" value='0'
                                                        {{ old("is_optional",!$quiz->is_optional?0:1)==0 ? "checked" : "" }} />文章式
                                                    </label>

                                                </div>
                                                @if($errors->has("is_optional"))
                                                <span class="help-block">{{ $errors->first("is_optional") }}</span>
                                                @endif
                                            </div>
                                    </div>
                                    <!-- グループ属性 -->
                                    <?php
                                        $old_exam_groups = $quiz->exam_groups()->get()->pluck("id")->toArray();
                                        ?>
                                    </div>
                                        <div class="form-group @if($errors->has('exam_group')) has-error @endif">
                                            <label for="exam_group-field" class="col-sm-2 control-label">グループ属性</label>
                                            <div class="col-sm-10">
                                                <div class="radio">
                                                    <label class="col-sm-2">
                                                        <input type="radio" id="" name="group_attr" value='0'
                                                            {{ old("group_attr",$old_exam_groups?0:"")==0 ? "checked" : "" }} />あり
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" name="exam_group[]" id="exam_group-field" multiple>
                                                            @foreach($exam_groups as $exam_group)
                                                            <option value="{{$exam_group->id}}" @if(old("exam_group",$old_exam_groups)){{ (in_array($exam_group->id, old("exam_group",$old_exam_groups)) ? "selected":"") }}@endif>{{$exam_group->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <p style="color:#B8B7B7;font-family:inherit;white-space:nowrap;">グループ属性の削除の際は”Ctrl+F”検索で該当箇所の特定が出来ます。</p>
                                                    </div>
                                                    <label class="col-sm-2">
                                                        <input type="radio" id="" name="group_attr" value='1'
                                                            {{ old("group_attr",!$old_exam_groups?1:"")==1 ? "checked" : "" }} />なし
                                                    </label>

                                                </div>
                                                @if($errors->has("exam_group"))
                                                <span class="help-block">{{ $errors->first("exam_group") }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab_menu_wrapper">
                            <ul class="nav nav-tabs">
                                <li id="answer_otional-tab" class="nav-item "><a class="nav-link">選択式</a></li>
                                <li id="answer_fillin-tab" class="nav-item "><a class="nav-link">文章式</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active">
                                    @foreach($lib_type as $lib_key => $lib)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">{{$lib["setting_label"]}}</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label class="atoms-Label  ">{{$lib["input_label"]}}</label>
                                                        </div>
                                                        <div class="col-md-10">
                                                            @include('admin.common.lib_selection', ['stetho_sounds' =>
                                                            $stetho_sounds->where("lib_type",$lib_key), 'lib_type' =>
                                                            $lib ,'lib_key' =>$lib_key,'is_edit' => true, 'quiz_library'
                                                            =>
                                                            $quiz->stetho_sounds()->where("lib_type",$lib_key)->get()])
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <?php $error_choices = $lib['input_name'].'_quiz_choices'; ?>
                                                    <div
                                                        class="row answer_optional @if($errors->has($error_choices)) has-error @endif">
                                                        <div class="col-md-2">
                                                            <label class="atoms-Label">選択肢</label>
                                                        </div>
                                                        <div class="col-md-10">
                                                            @include('admin.common.quiz_choice',['lib_type' =>
                                                            $lib,'lib_key' =>$lib_key, 'is_edit' => true,
                                                            'quiz_library_choices' =>
                                                            $quiz->quiz_choices()->whereNull('is_fill_in')->where("lib_type",$lib_key)->orderBy('disp_order','ASC')->get()])
                                                            @if($errors->has($error_choices))
                                                            <span
                                                                class="help-block">{{ $errors->first($error_choices) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row answer_fill_in" style="display:none;">
                                                        <div class="col-md-12">
                                                            <?php $error = $lib['input_name'].'_quiz_choices_fill_in'; ?>
                                                            <div
                                                                class="row form-group @if($errors->has($error)) has-error @endif">
                                                                <div class="col-md-2"><label
                                                                        for="{{$lib['input_name']}}_quiz_choices[fill_in][lib_key]-field"
                                                                        class="control-label">ワード登録</label>
                                                                </div>
                                                                <input type="hidden" class="form-control"
                                                                    name="{{$lib['input_name']}}_quiz_choices[fill_in][lib_key]"
                                                                    value="{{$lib_key}}" />
                                                                <div class="col-md-10">
                                                                    <?php $fill_in =  $quiz->quiz_choices()->where('is_fill_in', 1)->where('lib_type', $lib_key)->first();?>
                                                                    <input type="text"
                                                                        name="{{$lib['input_name']}}_quiz_choices[fill_in][title]"
                                                                        class="form-control"
                                                                        value="{{old($lib['input_name'].'_quiz_choices.fill_in.title',$fill_in?$fill_in->title:'')}}">
                                                                    <span class="atoms-Span ">＊回答式はカンマ区切りで設定</span>
                                                                    @if($errors->has($error))
                                                                    <span
                                                                        class="help-block">{{ $errors->first($error) }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">解答説明</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="{{$lib['input_name']}}_description-field"
                                                    class="col-sm-2 control-label">解答説明</label>
                                                <div class="col-sm-10">
                                                    <select name="{{$lib['input_name']}}_description"
                                                        class="form-control">
                                                        <option id="{{$lib['input_name']}}_none__opt"></option>
                                                        <?php $desc_column = [
                                                            0 => "description_stetho_sound_id",
                                                            1 => "description_stethoscope_id",
                                                            2 => "description_palpation_id",
                                                            3 => "description_ecg_id",
                                                            4 => "description_inspection_id",
                                                            5 => "description_xray_id",
                                                            6 => "description_echo_id",
                                                        ]; ?>
                                                        <?php $d_sound=$quiz->libray_description($desc_column[$lib_key])->first(); $s_id=is_null($d_sound)? "" : $d_sound->id; ?>
                                                        @foreach($stetho_sounds->where("lib_type",$lib_key) as
                                                        $stetho_sound)
                                                        <option
                                                            id="{{$lib['input_name']}}_description_{{$stetho_sound->id}}__opt"
                                                            value="{{$stetho_sound->id}}"
                                                            @if(old($lib['input_name']."_description",$s_id)==$stetho_sound->
                                                            id) selected @endif>
                                                            {{$stetho_sound->title_en}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br /><br />
                                    @endforeach
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">解答登録</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row answer_optional">
                                                <div class="col-md-2">
                                                    <label class="atoms-Label  ">選択肢</label>
                                                </div>
                                                <div class="col-md-10">
                                                    @include('admin.common.quiz_choice',['lib_type' =>
                                                    array("input_name"=>"final_answer"),'lib_key' =>"final_answer",
                                                    'is_edit' => true, 'quiz_library_choices' =>
                                                    $quiz->quiz_choices()->whereNull('is_fill_in')->where("lib_type",NULL)->orderBy('disp_order','ASC')->get()])
                                                    @if($errors->has('final_answer_quiz_choices'))
                                                    <span
                                                        class="help-block">{{ $errors->first('final_answer_quiz_choices') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row answer_fill_in" style="display:none;">
                                                <div class="col-md-12">
                                                    <div
                                                        class="row form-group @if($errors->has('final_answer_choices_fill_in')) has-error @endif">
                                                        <div class="col-md-2"><label
                                                                for="final_answer_quiz_choices[fill_in][lib_key]-field"
                                                                class="control-label">解答ワード登録</label>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <?php 
                                                                $fill_final_answers = $quiz->quiz_choices()->where('is_fill_in', 1)->whereNull('lib_type')->get();
                                                            ?>
                                                            @include('admin.common.quiz_fill-in_final_choice',['lib_type' =>
                                                                array("input_name"=>"fill_final_answer"),'lib_key' =>"final_answer",
                                                                'is_edit' => true, 'quiz_library_choices' =>  $fill_final_answers])
                                                            <span class="atoms-Span ">＊回答式はカンマ区切りで設定</span>
                                                            @if($errors->has('final_answer_choices_fill_in'))
                                                            <span
                                                                class="help-block">{{ $errors->first('final_answer_choices_fill_in') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="col-sm-offset-5 col-sm-7">
                                <a class="btn btn-default" href="{{ route('admin.quizzes.index') }}">キャンセル</a>
                                <button class="btn btn-primary" type="submit">保存</button>
                            </div>
                        </div>
        </form>
    </div>
</div>
</div>

@endsection