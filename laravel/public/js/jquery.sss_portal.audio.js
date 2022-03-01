;(function($){
  'use strict';
  var currentId;    // クリックされたカレントid
  /**
   * 3Sポータルの聴診音再生停止コントローラ 
   * 1つのaudioのみ再生させる
   * 使い方：home.blade.php 参照
   */
  var methods = {
    // 初期化
    init : function( options ) {
      var settings = $.extend(true, {
        audioJsObj: null,
        playPauseClass: 'play-pauseZ'
      }, options);

      // 再生停止のclass名
      var playPauseClass = '.' + settings.playPauseClass;
      // AudioJSが割り当てたIDの配列を取得する
      var ids = $(playPauseClass).parent().map(function(i, e){ return $(e).attr('id'); });

      loginModal(ids,settings); // ログインの処理(ログイン後に継続してplay)
      removeModal();    // モーダルの解除

      // 1つのaudioのみ再生させる
      $(playPauseClass).on('click', function(e) {
        currentId = $(this).parent().attr('id');
        for(var i = 0; i < settings.audioJsObj.length; i++) {
          if ( ids[i] != currentId && settings.audioJsObj[i].playing ) {
            settings.audioJsObj[i].pause();
            settings.audioJsObj[i].currentTime = 0;
          }
        }
      });
    },
  };

  /**************************************************************/
  /*
    1つのaudioを再生
  */
  /**************************************************************/
  function playOne(ids,currentId,settings) {
    for(var i = 0; i < settings.audioJsObj.length; i++) {
        if(ids[i] == currentId) {
            settings.audioJsObj[i].play();
        }
    }
  }

  /**************************************************************/
  /*
    モーダルの解除
  */
  /**************************************************************/
  function removeModal() {
    $(document).on("click","#close",function() {
      $('#modal').fadeOut('slow',function(){
        $('#modal').remove();   // モーダル削除

        /* modalcallは「audiojs/audio.min.js」にて設定 */
        modalcall = true;   // モーダルを表示してOK
      });
    });
  }

  /**************************************************************/
  /*
    モーダルでのログイン
  */
  /**************************************************************/
  function loginModal(ids,settings) {
    $(document).on("submit","#sample_login",function(event) {
        event.preventDefault();
        var form = $(this);

        var account = $('#sample_login [name=account]').val();

        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:form.serialize(),
            cache:false,
            dataType:"json",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        })
        .done(function(res){
            if(res.status == 1) {  // OKの場合
                if(res.type == 0) { // 0:試聴音登録者 1:3spアカウント
                    var tmp = $("#s_edit_link").attr("href");
                    var array = tmp.split('?');
                    var href = array[0]+"?edit="+res.edit;

                    var exceptmail = $("#use_sample").data('em');

                    if(account != exceptmail) {
                        $("#s_edit_link").attr("href",href);
                        $("#use_sample").css('display','block');
                    }
                }

                $(".use_sample_info").css('display','none');
                $(".reg_mail_info").css('display','none');
                $(".prvideo").css('display','none');

                $('#modal').fadeOut('slow',function(){  // フェードアウト
                    $('#modal').remove(); // モーダルを削除
                    /* modalcallは「audiojs/audio.min.js」にて設定 */
                    modalcall = true;   // モーダルを表示してOK

                    // iOS等はajaxの通信と音データの通信の2つがあるとロードできない為
                    var win = (/(windows)/i).test(navigator.userAgent);
                    if(win) {
                        playOne(ids,currentId,settings);  // カレントを再生
                    }
                });
            } else {    // NGの場合
                $('.modal_message').html("ログインできません");
            }
        })
        .fail(function(jqxhr, status, error){ // ajaxがエラーの場合
            $('.modal_message').html("通信エラーです<br>再度ログインを行って下さい");

            // エラーの場合、再表示で直るケースが多い
            var sleep = function(){ location.reload(); }
            setTimeout(sleep,2000); // 2秒間表示して再表示
        });
    });
  }

  // jQueryにssl_portalオブジェクトをエクスポート
  if ( $.fn.sss_portal == undefined ) {
    $.fn.sss_portal = {};
  }
  // このプラグインをssl_portalオブジェクトにエクスポート
  $.fn.sss_portal.audio = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.sss_portal.faq' );
    }
  };
})(jQuery);
