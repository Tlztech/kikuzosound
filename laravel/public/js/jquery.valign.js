;(function($){
  'use strict';
  /**
   * CSSで対応不可能な縦の文字位置を調整する。
   * Float指定された要素内のY座標を揃えます
   */
  var methods = {
    init : function( options ) {
      var settings = $.extend(true, {
        targetClasses: [
          '.quiz_title', '.textbox .title'  // 項目内でY軸を揃えたいセレクタ
        ],
        middleAlignmentClasses: [
          '.quiz_title'                     // 項目内で縦中央に揃えたいセレクタ
        ],
        deviceWidth: 600,
        minCols: 2,
        maxCols: 3
      }, options);

      // Windowサイズを変更したら描画するイベントを登録
      // resizeイベントが大量に発行されるため200ミリ秒で制限
      var timer = false;
      var $this = $(this);
      $(window).resize(function() {
        if ( timer !== false ) {
          clearTimeout(timer);
        }
        timer = setTimeout(function() {
          methods.render( $this, settings );
        }, 200);
      });

      methods.render( $(this), settings);
    },
    // 描画する
    render : function( $this, settings ) {
      // 列数（PCの場合3列、600xp未満のSPの場合2列となる）
      var colsByRow = $(window).width() < settings.deviceWidth ? settings.minCols : settings.maxCols;
      // 1行をまとめて選択できるセレクタを作成する
      var $rows = createRows( $this, colsByRow );
      // 1行毎にY座標を調整する
      $.each($rows, function(_, $cols) {
        // 一旦高さをAutoに戻す
        $cols.find( settings.targetClasses.join(',') ).height( 'auto' );
        // 指定された対象クラスについて
        $.each( settings.targetClasses, function( _, targetSelector ) {
          // 1行の中で最大の高さを取得する
          var maxHeight = getMaxHeightInColumns( $cols, targetSelector );
          // その高さを他の列の要素にも設定する
          $cols.find(targetSelector).height( maxHeight );
          // 指定があれば上下中央とする
          if ( $.inArray( targetSelector, settings.middleAlignmentClasses ) > -1 ) {
            setMiddleInVertical( $cols, targetSelector );
          }
        });
      });
    }
  };

  /** プライベート */

  /**
   * 1行をまとめて取得するセレクタを作成する
   *
   * @param $items 対象の要素
   * @param colsByRow classNameで指定された要素配列を何列で1行とするか
   * @return 行の配列 
   */
  function createRows( $items, colsByRow ) {
    var rows = [];
    for ( var i = 0; i < $items.length; i += colsByRow ) {
      var cols = [];
      for ( var colNum = i; colNum < colsByRow+i; colNum++ ) {
        cols.push($items.eq(colNum));
      }
      // jQueryオブジェクトの配列を、配列のjQueryオブジェクトに変換する
      rows.push(cols.reduce($.merge));
    }
    return rows;
  }

  /**
   * 1行の中で最大の高さを取得する
   *
   * @param $cols 1行の要素
   * @return int 最大の高さ
   */
  function getMaxHeightInColumns( $cols, targetSelector ) {
    if ( $cols.length === 0 ) {
      return 0;
    }
    // 高い順に並べる
    var $sortedCols = $cols.sort(function(a,b){
      var h1 = $(a).find(targetSelector).height();
      var h2 = $(b).find(targetSelector).height();
      return h2-h1;
    });
    // 最大の高さを取得する
    var maxHeight = $sortedCols.eq(0).find(targetSelector).height();
    return maxHeight;
  }

  /**
   * $cols 内の targetSelector 要素内を縦中央に揃える
   *
   * @param $cols 1行の要素
   * @param targetSelector 縦中央に揃えたいセレクタ
   */
  function setMiddleInVertical( $cols, targetSelector ) {
    $cols.find(targetSelector).css({
      'display'        : 'table-cell',
      'vertical-align' : 'middle',
      // table-cell の場合、width:100% が効果なし。
      // width:100% と同じ効果にするために親要素より大きい幅をpx指定ことで対応する。（CSS仕様）
      'width'          : '400px'
    });
  }

  // このプラグインをエクスポート
  $.fn.valign = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.valign' );
    }
  };
})(jQuery);