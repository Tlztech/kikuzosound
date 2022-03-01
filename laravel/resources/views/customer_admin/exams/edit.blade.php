@extends('customer_admin.layouts.app')
@section('content')
<!-- coupons -->
<style>
    .help-block{
        color: red;
        font-size: 12px;
    }
    .input_name{
        width:35% !important;
    }
    .mat{
        width: 100%;
    }
</style>
<div class="container">
    <div class="mat">
        <ul class="info">
                    <li></li><!-- 中央揃えの為の空き -->
                    <li><h2 class="title_m" style="margin-bottom:0px;">コンダクタ登録</h2></li>
                    <li>
                        <a href="{{route('customer_admin_exams.delete', $exam->id)}}" id="c_delete">削除</a>
                    </li>
                </ul>
        <form id="product" class="form" method="post" action="{{route('customer_admin_exams.update', $exam->id)}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">Username<br>
                            @if($errors->has("user"))
                                <span class="help-block">{{ $errors->first("user") }}</span>
                            @endif
                        </p>
                        <input type="text" name="user" value="{{$exam->user}}" required="required"  id="username">
                        <p class="invalid_user msg_err">• 半角英数字を入力してください</p>
                        <p class="min_char_user msg_err">• 6文字以上30文字以下で入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">Password(講師側) <br>
                            @if($errors->has("password"))
                                <span class="help-block">{{ $errors->first("password") }}</span>
                            @endif
                        </p>
                        <input type="text" name="password" value="" placeholder="半角英数字" id="teacher_pass">
                        &nbsp&nbsp ※何も入れない場合は、前のままのパスワードになります<br>
                        <p class="invalid_pass msg_err">• 半角英数字を混ぜてご入力ください</p>
                        <p class="min_char_err msg_err">• 6文字以上でご入力ください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">Password(受講側) <br>
                            @if($errors->has("speaker"))
                                <span class="help-block">{{ $errors->first("speaker") }}</span>
                            @endif
                        </p>
                        <input type="text" name="speaker" value="" placeholder="半角英数字" id="student_pass">&nbsp&nbsp ※何も入れない場合は、前のままのパスワードになります<br>
                        <p class="invalid_pass_2 msg_err" style="display:none;">• 半角英数字を混ぜてご入力ください</p>
                        <p class="min_char_err_2 msg_err">• 6文字以上でご入力ください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">使用施設名、等 <br>
                            @if($errors->has("unit"))
                                <span class="help-block">{{ $errors->first("unit") }}</span>
                            @endif
                        </p>
                        <input type="text" name="unit" value="{{$exam->unit}}" min="0" required="required">
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">受講側の接続最大数 <br>
                            @if($errors->has("max_connections"))
                                <span class="help-block">{{ $errors->first("max_connections") }}</span>
                            @endif
                        </p>
                         <select class="year" id="max_connections" name="max_connections">
                            @for ($i = 1; $i <= 30; $i++)
                                @if($exam->max_connections == $i)
                                <option value="{{$i}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{$i}}" >{{ $i }}</option>
                                @endif
                            @endfor
                        </select>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">使用聴診音 <span id="helpkey" class="linkbutton">（入力補助）</span>
                            @if($errors->has("disp_order"))
                                <span class="help-block">{{ $errors->first("disp_order") }}</span>
                            @endif
                        </p>
                        <input type="text" name="disp_order" id="disp_order" value="{{implode(',', $exam->sounds()->get()->pluck('id')->toArray())}}" placeholder="idをcsvで表示順に記述(例:2,45,32)" required="required">&nbsp&nbsp
                        <span style='color: #ff552e;font-size:2em;'>開発機</span>の音源を使用しています
                    </label>
                </li>
                <li id="helpinput" style="display:none;">
                    <label for="" class="">
                        <p class="input_name">使用聴診音の入力補助
※入力補助を使用すると手動入力した分は削除されます <br>
                            @if($errors->has("stetho_sound_id"))
                                <span class="help-block">{{ $errors->first("stetho_sound_id") }}</span>
                            @endif
                        </p>
                        @foreach($sounds as $sound)
                        @if(in_array($sound->id, $exam->sounds()->get()->pluck('id')->toArray()))
                            <input type="checkbox" class="exams_ss" name="stetho_sound_id[]" value="{{$sound->id}}" style="width: 100px;" checked="checked">
                            {{$sound->id}} : {{$sound->title}} <br>
                        @else
                            <input type="checkbox" class="exams_ss" name="stetho_sound_id[]" value="{{$sound->id}}" style="width: 100px;">
                            {{$sound->id}} : {{$sound->title}} <br>
                        @endif
                        @endforeach
                    </label>
                </li>
            </ul>
            <p class="contact_submit">
                <button type="submit" class="submit_btn">登録</button>
            </p>
        </form>
    </div>
</div>
@endsection
@section('page-script')
<script src="/js/customer-admin-validations/edit-exams-form.js"></script>
<script type="text/javascript">
    $(function() {
        $('#helpkey').click(function(){
            var open_key = document.getElementById('helpinput').style.display;
            if (open_key == 'none'){
                document.getElementById('helpinput').style.display = 'block';
            }else{
                document.getElementById('helpinput').style.display = 'none';
            }
        });
        var disp_data = $('#disp_order').val(); // inputに表示している順番
        var disp_order = disp_data.split(',');  // 表示している順番を配列に

        $('.exams_ss').click(function() {   // チェックボックスクリック
            var val = $(this).val();    // value取得
            var index = disp_order.indexOf(val);    // 配列の位置取得
            if(index == -1) {   // 配列にない場合
                disp_order.push(val);   // 追加
            } else {    // 配列にある場合
                disp_order.splice(index,1); // 削除
            }
            if(disp_order[0] == "") {   // 最初「,4,19」の様に,が付くので削除
                disp_order.shift(); // 先頭の要素を削除
            }
            $('#disp_order').val(disp_order);   // inputに表示
        });
    });
</script>
@endsection
