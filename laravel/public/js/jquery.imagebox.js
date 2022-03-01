;(function($){
  'use strict';
  /**
   * 指定されたimgを拡大する
   */
  var methods = {
    init : function( options ) {
      return this.each(function() {
        //console.log($(this).attr('src'));
        $(this).on('click', methods.show);
      });
    },
    show: function(e) {
      //console.log($(this).attr('src'));
      var $img = $('<img/>').attr('src', $(this).attr('src')).css({
				'display': 'block',
				'position': 'fixed',
				// 'margin': '60px auto',
        'margin': 'auto',
				'max-width': $( window ).width() * 0.8,
				'top': '0',
				'left': '0',
				'right': '0',
				'bottom': '0'
			});
      var $bg = $('<div/>').css({
				// 'position': 'absolute',
        'position': 'fixed',
				'top': '0',
				'left': '0',
				'right': '0',
				'bottom': '0',
        // クイズ解答選択画面で上手くbodyの高さを取得できないので修正
				// 'height': $('body').height(),
        'height': '100%',
				'width': '100%',
				'z-index': '10000',
				'background-color': 'rgba(0,0,0,0.8)',
				'vertical-align': 'middle',
				'text-align': 'center'
			});
      var $close = $('<button type="button" class="close" aria-hidden="true">×</button>').css({
        'display': 'block',
        'position': 'fixed',
        'right': '0',
        'color': '#fff',
        'width': '60px',
        'height': '60px',
        'text-align': 'center',
        'font-size': '35px',
        'border':'0px',
      }).on('click', function(e){
        $bg.remove();
        $(this).remove();
        $(document.body).css({
         'overflow-x': 'auto',
         'overflow-y': 'auto',
        });
      });
      $(document.body).css({
       'overflow-x': 'hidden',
       'overflow-y': 'hidden',
      });
      $bg.append($img);
      $bg.prepend($close);
      $('body').prepend($bg);
    }
  };

  // このプラグインをエクスポート
  $.fn.imagebox = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.imagebox' );
    }
  };
})(jQuery);
