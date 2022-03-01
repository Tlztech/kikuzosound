;(function($){
  'use strict';

  /**
   * 3Sポータルのユーザ操作ログ機能
   */
  var methods = {
    // 初期化
    init : function( options ) {
      this.settings = $.extend(true, {
        events: ['click']
      }, options);
      // 通常リンクはこの機能は使えない
      if ( this.closest('a').length > 0 && this.closest('a').attr('href') ) {
        $.error('A tag with href can not use in this logger plugin');
        return;
      }
      var _this = this;
      return this.each(function(_, elm){
        $(elm).on('click', function(e){
/****************************************************************/
/* 試聴音登録 */
/* ここでは処理せずログ出力するようにしたが、履歴として残す */
/*
          var flag = 0; // 認証ajaxを呼ぶ(ssidを渡す)

          // 認証NGの場合(メアド未登録 and 未ログイン and 許可ssid以外)
          if(flag == 0) {
            return; // ログ出力しない
            // 因みに正しくキャンセルされるがここでreturnしてもPLAYは止まらない
            // PLAYは「jquery.sss_portal.audio.js」で制御
          }
*/
/****************************************************************/
          var url  = _this.settings.url;
          var data = _this.settings.data($(this));
          methods.send(url, data);
        });
      });
    },
    send: function( url, data ) {
      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify(data),
        timeout : 10000,
        beforeSend: function(xhr) {
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success: function( responseData ) {
          //console.log(['log success:', data, responseData]);
        },
        error: function(xhr, status, errorThrown) {
          $.error([xhr, status, errorThrown]);
        }
      });
    }
  };

  // このプラグインをエクスポート
  $.fn.logger = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.logger' );
    }
  };
})(jQuery);
