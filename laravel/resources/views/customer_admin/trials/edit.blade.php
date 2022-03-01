@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">登録の代行</h2>
        <p style="margin:0px 0px 10px;text-align:center;">
            ・メールアドレス以外の情報は次の画面で編集して下さい<br><br>
            ・メールアドレスを間違えて入力した場合は、次の画面で修正せずに
            <a href="{{route('customer_admin_trials')}}">
                こちら
            </a>
            から削除して下さい<br>
            === メールアドレスを修正すると、修正後のメアドに認証メールが送信されてしまいます!! ===
        </p>
        @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form id="f_company" class="form" method="post" action="{{route('customer_admin_trials_register_form')}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name must">メールアドレス</p>
                        <input name="mail" type="text" maxlength="200" value="">
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
<script type="text/javascript">
</script>
@endsection
