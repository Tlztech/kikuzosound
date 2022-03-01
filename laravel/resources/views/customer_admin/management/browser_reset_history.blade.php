@extends('customer_admin.layouts.app')
@section('content')
<!-- Customer Management -->
<div id="container">
  <div class="container_inner clearfix">
    <!--*********************************** .contents ***********************************-->
    <div class="contents">
      
      <div class="customer-bottom">
        <h2>ブラウザのリセット履歴</h2>
        <table data-toggle="table" id="eventsTable">
            <thead>
              <tr>
                <th data-sortable="true">顧客名 </th>
                <th data-sortable="true">Eメール</th>
                <th data-sortable="true">以前のライセンスキー </th>
                <th data-sortable="true">日付 </th>
              </tr>
            </thead>
            <tbody>
            @if($browserResetHistory)
              @foreach($browserResetHistory as $history)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $history->prev_onetime_key }}</td>
                    <td>{{ $history->created_at }}</td>
                </tr>
              @endforeach
            @else
              <center>
                <h3>ブラウザのリセット履歴が見つかりません</h3>
              </center>
            @endif 
            </tbody>
        </table>
        <br><br>
        <div class="contents_box_inner pTB20">
          <p class="contact_submit mB20">
              <a href="{{url('customer_admin')}}" class="btn btn-info " style="cursor:pointer;">戻る</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection