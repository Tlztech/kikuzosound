;(function($) {
    var settings;

    var methods = {
        init : function(options){
            // 引数を設定
            // フォームにある日付
            // 初期値はPHP側で必ず設定される
            settings = $.extend(true, {
                pre_year:$("#yearSelect").val(),
                pre_month:$("#monthSelect").val(),
                pre_date:$("#daySelect").val()
            }, options);

            /* 最初に来た時の最終日の調整 */
            /* 4,6,9,11月は30日、2月は28日で閏年は29日 */
            if(settings.pre_month == '04' || settings.pre_month == '06' || settings.pre_month == '09' || settings.pre_month == '11') {
                $("#daySelect").children('option[value=31]').remove();
            } else if(settings.pre_month == '02') {
                $("#daySelect").children('option[value=30]').remove();
                $("#daySelect").children('option[value=31]').remove();
                var lastday = formSetLastDay(Number(settings.pre_year),Number(settings.pre_month));
                if(lastday == 28) {
                    $("#daySelect").children('option[value=29]').remove();
                }
            }

            // 処理
            return this.each(function(_, elm){
                $(elm).change(function(e){ // 変更した場合
                    formSetDay();   // 処理
                });
            });
        },
    };

  /* プライベート関数 */

  /**************************************************************/
  /*
    selectの日のオプションを作成
  */
  /**************************************************************/
  function formSetDay(){
    var year = $("#yearSelect").val();
    var month = $("#monthSelect").val();

    var lastday = formSetLastDay(year,Number(month));
    var option = '';

    for(var i = 1; i <= lastday; i++) {
      var date = $("#daySelect").val();
      var day = String(i);
//            if(i < 10) { day = "0" + String(i); }

      if(i == Number(date)){
        option += '<option value="' + day + '" selected="selected">' + day + '</option>\n';
      } else {
        option += '<option value="' + day + '">' + day + '</option>\n';
      }
    }
    $("#daySelect").html(option);
  }

  /**************************************************************/
  /*
    年と月からその月の最終日を求める
  */
  /**************************************************************/
  function formSetLastDay(year,month) {
    var lastday = new Array('',31,28,31,30,31,30,31,31,30,31,30,31);

    if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0){
      lastday[2] = 29;
    }
    return lastday[month];
  }

  $.fn.calendar = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.calendar' );
    }
  };
})(jQuery);

