@extends('customer_admin.layouts.app')
@section('content')
<style>
    a {
    text-decoration: none !important;
    color: #3CF;
    white-space: pre-wrap;
}
</style>
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">
            「企業、連絡先、契約情報」の変更
        </h2>
        <div class="step">
            <p class="title_step">企業、連絡先の変更></p>
                <input type="text" name="company" id="search3" placeholder="1.会社名の一部を入力">
                <input type="button" id="search3_btn" name="search3_btn" class="search_btn" value="検索">
            <ul class="link-ul">
                <div id="a_result"></div>
            </ul>
            <br>
        </div>
        <div class="step">
            <p class="title_step">契約情報の変更・削除</p>
            <input type="text" name="search4" id="search4" placeholder="株式会社テレメディカ">
            <input type="button" id="search4_btn" name="search4_btn" class="search_btn" value="検索">
            <ul class="link-ul">
                <div id="z_result"></div>
            </ul>
            <br>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(function() {
        $('#search3_btn').click(function() {
            $.ajax('{{route('customer_admin_registrations_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search3').val()
                },
                success: function (data, status, xhr) {
                    $('#a_result').empty();
                    for (var i = 0; i < data.length; i++) {
                        var item = '<li><a href="{{url('/customer_admin/companies/edit')}}/'+data[i].id+'" >'+data[i].id+': '+data[i].company+' ['+data[i].contacts.length+'名]</a><br>';
                        for (var j = 0; j < data[i].contacts.length; j++) {
                            item += '<a href="{{url('/customer_admin/contacts/edit')}}/'+data[i].contacts[j].id+'" >    '+data[i].contacts[j].id+': '+data[i].contacts[j].name+'</a><br>';
                        }
                        item += '</li>';
                        $('#a_result').append(item);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#a_result').empty();
                    $('#a_result').append('Error' + errorMessage);
                }
            });
        });
        $('#search4_btn').click(function() {
            $.ajax('{{route('customer_admin_accounts_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search4').val()
                },
                success: function (data, status, xhr) {
                    $('#z_result').empty();
                    for (var i = 0; i < data.length; i++) {
                        var item = '<a href="{{url('/customer_admin/accounts/edit')}}/'+data[i].id+'"><li>'+data[i].user+'<li style="margin-left: 10px;">'+data[i].d_com+' &nbsp&nbsp'+data[i].d_name+'</li><li style="margin-left: 10px;">'+data[i].c_com+' &nbsp&nbsp'+data[i].c_name+'</li></li></a>';
                        $('#z_result').append(item);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#z_result').empty();
                    $('#z_result').append('Error' + errorMessage);
                }
            });
        });
    });
</script>
@endsection
