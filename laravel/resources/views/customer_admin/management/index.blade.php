@extends('customer_admin.layouts.app')
@section('content')
<!-- Customer Management -->
<div id="container">
  <div class="container_inner clearfix">
    <!--*********************************** .contents ***********************************-->
    <div class="contents">
      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">選択したアカウントの有効期限を設定します</h4>
            </div>
            <form method="POST" action="{{url('customer_admin/customer_admin_expiration')}}">
            {{ csrf_field() }}
            <div class="modal-body">

              <input id="input_selected_id_expiration" name="selected_id" value="" hidden/>
              <input id="selected_all_flg_expiration" name="selected_all" value="false" hidden/>
              <label class="management-create">
                  <input type="radio" id="is_has_expiry" name="is_has_expiry" value='1' class="yes" @if(old('is_has_expiry')==1) checked @endif>あり</input>
                  <span class="checkmark" style="margin-top: 10px;"></span>
              </label>
              <input type="datetime-local" id="expiration_date_input" name="expiration_date" required @if(old('is_has_expiry')==0) disabled @endif>
              <label class="management-create">
                  <input type="radio" id="is_has_expiry" name="is_has_expiry"  class="no" value='0' @if(old('is_has_expiry')==0) checked @endif>なし</input>
                  <span class="checkmark" style="margin-top: 10px;"></span>
              </label>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">保存する</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <div class="customer-top">
        <div style="margin-top:5px; margin-right:20px;">
          <label>Show</label>
          <select id="showEntries" name="showEntries" style="
            background: #fff;
            width: 60px;
            background-image: url(../img/black_down_arrow.png);
            background-size: 12px;
            display: inline;
            background-position: 45px;
            background-repeat: no-repeat;
            border: 1px solid #ccc;
            font-size: 18px;
          ">
            <?php 
              $showEntries = isset($_COOKIE["showEntries"]) ? $_COOKIE["showEntries"] : 10;
            ?>

            <option value="10" <?php if ($showEntries == 10) echo "selected"; ?>>10</option>
            <option value="25" <?php if ($showEntries == 25) echo "selected"; ?>>25</option>
            <option value="50" <?php if ($showEntries == 50) echo "selected"; ?>>50</option>
            <option value="100" <?php if ($showEntries == 100) echo "selected"; ?>>100</option>
            <option value="200" <?php if ($showEntries == 200) echo "selected"; ?>>200</option>
            <option value="300" <?php if ($showEntries == 300) echo "selected"; ?>>300</option>
            <option value="500" <?php if ($showEntries == 500) echo "selected"; ?>>500</option>
            <option value="1000" <?php if ($showEntries == 1000) echo "selected"; ?>>1000</option>
          </select>
          <label>Entries</label>
        </div>
        <a href="{{url('customer_admin/new_onetime_issue')}}"><button class="customer-admin">新規発行</button></a>
        <form method="POST" action="{{url('customer_admin')}}">
        {{ csrf_field() }}
          <input id="input_selected_id" name="selected_id" value="" hidden/>
          <input id="selected_all_flg" name="selected_all" value="false" hidden/>
          <input name="button_type" value="csv" hidden/>
          <button id="csv_download_btn" class="customer-admin" disabled="true">CSVダウンロード</button>
        </form>
        <form method="POST" action="{{url('customer_admin')}}">
          {{ csrf_field() }}
            <input id="input_selected_id_x" name="selected_id" value="" hidden/>
            <input id="selected_all_flg_x" name="selected_all" value="false" hidden/>
            <input name="button_type" value="xlsx" hidden/>
            <button id="xlsx_download_btn" class="customer-admin" disabled="true">XLSXダウンロード</button>
        </form>
        <button id="expiration_btn" class="customer-admin" disabled="true" data-toggle="modal" data-target="#myModal">利用期限</button>
        <form method="GET" action="{{url('customer_admin')}}">
          <input name="search_key" type="customer-input" placeholder="検索文字を入力" value="{{$_GET['search_key'] or ''}}"/>
          <button type="submit" class="search-input">
          検索
          </button>
          <input name="reset" type="button" value="解除" onClick="window.location ='/customer_admin'" class="search-input submit-btn"/>
        </form>
        <form method="POST" action="{{url('customer_admin/delete_invalid')}}" onsubmit="if(confirm('無効なライセンスキーをすべて削除しますか？')) { return true } else {return false };">
          {{ csrf_field() }}
            <button class="customer-admin btn btn-danger">無効削除</button>
        </form>
      </div>
      <div class="customer-bottom">
        <h2>ライセンスキー 一覧</h2>
        
        <form action="{{url('customer_admin/bulk_delete_license')}}" method="POST" style="display: inline;"
          onsubmit="if(confirm('選択した未登録のライセンスキーを削除しますか？')) { return true } else {return false };">
          {{ csrf_field() }}
          <input id="input_selected_id_unregistered" name="selected_id" value="" hidden/>
          <button class="btn btn-danger " id="bulk_delete_unregistered" style= "display:none;" type="submit">未登録のライセンスを一度に削除する</button> 
        </form><br/><br/>
        <table data-toggle="table" id="eventsTable">
          <thead>
            <tr>
              <th data-checkbox="true" ></th>
              <th data-field="id" data-visible="false"></th>
              <th data-field="status" data-visible="false"></th>
              <th data-sortable="true">代理店 </th>
              <th data-sortable="true">Examグループ名</th>
              <th data-sortable="true">ライセンスキー</th>
              <th data-sortable="true">ステータス </th>
              <th data-sortable="true">利用期限 </th>
              <th data-sortable="true">作成日 </th>
              <th data-sortable="true">更新日 </th>
              <th data-sortable="true">法人名 </th>
              <th data-sortable="true">ユーザーID </th>
              <th data-sortable="true">お客様名 </th>
              <th data-sortable="true">登録者 email</th>
              <th data-sortable="true">univ分析を無効にする</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          @if($result)
            @foreach($result as $OneTimeKey)
              <tr>
              <td></td>
              <td>{{ $OneTimeKey->id }}</td>
              <td>{{ $OneTimeKey->status }}</td>
              <td>
                <input type="text" value="{{ $OneTimeKey->agency }}" style="text-align:center;" readonly>
              </td>
              <td>
                <?php
                  if($OneTimeKey->exam_group != null) {
                    ?>
                      <input type="text" value="{{ $OneTimeKey->exam_group }}" style="text-align:center;" readonly>
                    <?php
                  } else {
                    echo "----";
                  }
                ?>
              </td>
              <td>
                <input type="text" value="{{ $OneTimeKey->onetime_key }}" style="text-align:center;" readonly>
              </td>
              <td>
                <?php
                  if ($OneTimeKey->status == 0 && $OneTimeKey->is_expired == 1) { // invalid
                    ?>
                      <input type="text" value="無効" style="text-align:center;" readonly>
                    <?php
                  }else if (!$OneTimeKey->is_expired && $OneTimeKey->status == 0) { // issued
                    ?>
                      <input type="text" value="発行済" style="text-align:center;" readonly>
                    <?php
                  } else if ($OneTimeKey->status == 1) { // used
                    ?>
                      <input type="text" value="使用済" style="text-align:center;" readonly>
                    <?php
                  } else if ($OneTimeKey->status == 2) { // changed
                    ?>
                      <input type="text" value="変更済" style="text-align:center;" readonly>
                    <?php
                  } else if ($OneTimeKey->status == 3) { // stopped
                    ?>
                      <input type="text" value="停止中" style="text-align:center;" readonly>
                    <?php
                  }
                ?>
              </td>
              <td>
                <input type="text" value="{{ $OneTimeKey->expiration_date ? $OneTimeKey->expiration_date : '----'  }}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ $OneTimeKey->created_at }}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ ($OneTimeKey->customer_id) ? $OneTimeKey->updated_at : '----' }}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ ($OneTimeKey->customer_id) ? $OneTimeKey->company : '----'}}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ ($OneTimeKey->customer_id) ? $OneTimeKey->user : '----'}}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ ($OneTimeKey->customer_id) ? $OneTimeKey->name : '----' }}" style="text-align:center;" readonly>
              </td>
              <td>
                <input type="text" value="{{ ($OneTimeKey->customer_id) ? $OneTimeKey->email : '----' }}" style="text-align:center;" readonly>
              </td>
              <td>
                @if($OneTimeKey->customer_id)
                   
                    <div class="switch_wrapper" data-id="{{$OneTimeKey->customer_id}}">
                        <input id="switch_{{$OneTimeKey->customer_id}}" 
                        type="checkbox" class="switch btn-sm univ_disabled" 
                        data-toggle="switchbutton" 
                        data-onstyle="outline-danger"
                        data-offstyle="outline-success"
                        data-customer_id="{{$OneTimeKey->customer_id}}" 
                        data-size="sm" 
                        data-onlabel="Disabled" 
                        data-offlabel="Active"
                        {{$OneTimeKey->disable_analytics? 'checked' : ''}}
                        />
                    </div>
                @else
                  ----
                @endif
              </td>
              <td>
                @if($OneTimeKey->status != 0)
                  <form action="{{url('customer_admin/update_license')."/".$OneTimeKey->id}}" method="GET" style="display: inline;"
                    onsubmit="if(confirm('この顧客の新しいライセンスキーを本当に作成しますか？')) { return true } else {return false };">
                    <button class="btn btn-danger " type="submit">変更</button>
                  </form>
                  <a class="btn btn-warning" href="{{url('customer_admin/browser_reset_history')."/".$OneTimeKey->id}}">履歴</a>
                  <a class="btn btn-success" href="{{url('customer_admin/onetime_key/edit')."/".$OneTimeKey->id}}">編集</a>
                  <form action="{{url('customer_admin/stop_license')."/".$OneTimeKey->id}}" method="GET" style="display: inline;"
                    onsubmit="if(confirm('この顧客のこのライセンスキーを停止してもよろしいですか？')) { return true } else {return false };">
                  <button class="btn" style="background-color: dimgrey; color:white;">停止</button>
                  </form>
                @endif
                @if($OneTimeKey->status == 0)
                  <form action="{{url('customer_admin/delete_license')."/".$OneTimeKey->id}}" method="GET" style="display: inline;"
                    onsubmit="if(confirm('この無効なライセンスキーを削除しますか？')) { return true } else {return false };">
                    <button class="btn btn-danger " type="submit">削除する</button>
                  </form>
                @endif
              </td>
              </tr>
            @endforeach
          @else
            No data
          @endif
          </tbody>
        </table>
        <div class="for-pagination">{!! $result->render() !!}</div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
  //custom script
  $( document ).ready(function() {
    var date = new Date();
    $('input[type="datetime-local"]').attr({
       "min" : date.toISOString().slice(0, 10) + "T00:00",
       "value" : date.toISOString().slice(0, 10) + "T00:00"
    });
    $("#is_has_expiry.yes").click(function() {
        $("#expiration_date_input").attr("disabled", false);    
    });
    $("#is_has_expiry.no").click(function() {
        $("#expiration_date_input").attr("disabled", true);        
    });
    $('.univ_disabled').change(function(el) {
      var customer_id = el.target.dataset.customer_id;
      console.log("sdsad",el.target.checked);
      var msg = el.target.checked? "Disable user to univ-admin analytics?" : "Enable user to univ-admin analytics?";
      if (confirm(msg)) {
        $.ajaxSetup({

            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });
        var data = {
            customer_id :customer_id,
            disabled : el.target.checked?1:0
        };
        $.ajax({
            url: "/customer_admin/accounts/disableAnalytics",
            type: "post",
            data: data,
            dataType: "json"
        })
        .done(function (res) { 
          console.log("res",res);
          el.target.switchButton(el.target.checked?'on':'off', true);
        })
        .fail(function (jqxhr, status, error) { 
          el.target.switchButton(el.target.checked?'off':'on', true);
        });
          
      }else {
          el.target.switchButton(el.target.checked?'off':'on', true);
      }

    });
    //reset checkbox value when redirect back
    if (window.performance && window.performance.navigation.type == window.performance.navigation.TYPE_BACK_FORWARD) {
      setTimeout(function() {
        $("input[type=checkbox]").each(function() {
          $(this).attr('checked', false);
        });
      }, 100);

    }

    var $table = $('#eventsTable')
    var checkedAll_flg = false;

    /**
    * get all selected id from table
    */
    function getIdSelections() {
      return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
      })
    }

    /**
    * get all selected unregistered license id from table
    */
    function getUnregisteredIdSelections() {
      return $.map($table.bootstrapTable('getSelections'), function (row) {
        if(parseInt(row.status)==0){
          return row.id
        }
      })
    }
    /**
    * ajax call for download csv api
    */

    /**
    * on select/unselect data on table
    */
    $table.on('check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table',
      function (e,row) {
        if(e.type=="check-all"){
          checkedAll_flg = true;
        }else{
          checkedAll_flg=false;
        }
        selections = JSON.stringify(getIdSelections());
        $('#selected_all_flg').val(checkedAll_flg);
        $('#input_selected_id').val(selections);
        $('#selected_all_flg_x').val(checkedAll_flg);
        $('#input_selected_id_x').val(selections);
        $('#selected_all_flg_expiration').val(checkedAll_flg);
        $('#input_selected_id_expiration').val(selections);
        
        if(getIdSelections().length>0){
          $('#csv_download_btn').prop( "disabled", false );
          $('#xlsx_download_btn').prop( "disabled", false );
          $('#expiration_btn').prop( "disabled", false );
        }else{
          $('#csv_download_btn').prop( "disabled", true );
          $('#xlsx_download_btn').prop( "disabled", true );
          $('#expiration_btn').prop( "disabled", true );
        }

        unregistered_license_selections=JSON.stringify(getUnregisteredIdSelections());
        console.log("test",unregistered_license_selections);
        $('#input_selected_id_unregistered').val(unregistered_license_selections);
        if(getUnregisteredIdSelections().length>1){
          $('#bulk_delete_unregistered').show();
        }else{
          $('#bulk_delete_unregistered').hide();
        }
    })

    $('#showEntries').on('change', function() {
      var CookieDate = new Date;
      CookieDate.setFullYear(CookieDate.getFullYear() +10);
      document.cookie = 'showEntries='+this.value+'; expires=' + CookieDate.toGMTString() + ';';
      window.location.reload();
    });
  });
</script>
@endsection
