@extends('customer_admin.layouts.app')
@section('content')
<!-- Customer Management -->
<div id="container">
  <div class="container_inner clearfix">
    <!--*********************************** .contents ***********************************-->
    <div class="contents">
        <div class="customer-bottom">
            <h2>ライセンスキー 新規発行</h2>
            <form action="new_onetime_issue" method="POST">
            {{ csrf_field() }}
                <ul class="contact_form">
                    <li>
                        <label for="" class="">
                            <p class="input_name must">代理店名</p>
                            {{-- <select name="agency" style="background:#fff;">
                            @foreach ($companies as $company)
                                <option value="{{$company->company}}"  @if(old('agency')==$company->company) selected @endif>{{$company->company}}</option>
                            @endforeach
                            </select> --}}
                            <input name="agency" type="text" value="{{ old('agency') }}"/>
                            @if($errors->has('agency'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;"> 代理店名は必須です。</p>
                            @endif
                        </label>
                    </li>
                    <li>
                        <label for="">
                            <p class="input_name must">Examグループ名</p>
                            <label class="management-create">
                               <input type="radio" id="is_exam_group" name="is_exam_group" value='1' class="yes" @if(old('is_exam_group')==1) checked @endif>あり</input>
                               <span class="checkmark"></span>
                            </label>
                            <select name="university_id" style="background:#fff;width:290px;background-image:url(../img/black_down_arrow.png);background-size:15px;display:inline;background-position:270px; background-repeat: no-repeat; " id="exam_name_dropdown" @if(old('is_exam_group')==0) disabled @endif>
                                <option value="" selected></option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}">{{$university->name}}</option>
                                @endforeach
                            </select>
                            <label class="management-create">
                                <input type="radio" id="is_exam_group" name="is_exam_group"  class="no" value='0' @if(old('is_exam_group')==0) checked @endif>なし</input>
                                <span class="checkmark"></span>
                            </label>
                            @if($errors->has('university_id'))
                                <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">Examグループ名は必須です。</p>
                            @endif
                        </label>
                    </li>
                    <li>
                        <label for="">
                            <p class="input_name must">利用期限</p>
                            <label class="management-create">
                               <input type="radio" id="is_has_expiry" name="is_has_expiry" value='1' class="yes" @if(old('is_has_expiry')==1) checked @endif>あり</input>
                               <span class="checkmark"></span>
                            </label>
                            <input type="datetime-local" id="expiration_date_input" name="expiration_date" style="width:290px;height: 35px;" value="{{ old('expiration_date') }}" required  @if(old('is_has_expiry')==0) disabled @endif>
                            <label class="management-create">
                                <input type="radio" id="is_has_expiry" name="is_has_expiry"  class="no" value='0' @if(old('is_has_expiry')==0) checked @endif>なし</input>
                                <span class="checkmark"></span>
                            </label>
                            @if($errors->has('university_id'))
                                <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">Examグループ名は必須です。</p>
                            @endif
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must">ライセンスキー発行数</p>
                            <input name="count" type="number" maxlength="5" value="{{ old('count') }}"/>
                            @if($errors->has('count'))
                                <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">
                                @if(old('count') > 10000)
                                    発行数は10000件以内で入力してください。
                                @else
                                    発行数は必須です。
                                @endif
                                </p>
                            @endif
                        </label>
                    </li>
                </ul>
                <div class="contents_box_inner pTB20">
                    <p class="contact_submit mB20">
                        <input class="submit_btn"  style="cursor:pointer;" type="submit" value="新規発行" ></input>
                    </p>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
@endsection
@section('page-script')

<script type="text/javascript">
    $("#is_exam_group.yes").click(function() {
        $("#exam_name_dropdown").attr("disabled", false);       
    });
    $("#is_exam_group.no").click(function() {
        $("#exam_name_dropdown").attr("disabled", true);        
    });

    $("#is_has_expiry.yes").click(function() {
        $("#expiration_date_input").attr("disabled", false);       
    });
    $("#is_has_expiry.no").click(function() {
        $("#expiration_date_input").attr("disabled", true);        
    });
    $( document ).ready(function() {
        var date = new Date();
        $('input[type="datetime-local"]').attr({
        "min" : date.toISOString().slice(0, 10) + "T00:00",
        "value" : date.toISOString().slice(0, 10) + "T00:00"
        });
    });
</script>
@endsection
