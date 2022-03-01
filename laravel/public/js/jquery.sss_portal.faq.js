;(function($){

  var methods = {
    init : function( options ) {
      var settings = $.extend(true, {
        keywordInputClass    : 'input.search_keyword[type=text]',
        contentClass         : '.contents_box',
        contentTitleClass    : '.title_m',
        faqClass             : '.faq_list',
        faqQClass            : '.faq_Q',
        faqAClass            : '.faq_A',
        searchEvnet          : 'change keydown keyup mousedown click mouseup'
      }, options);

      // カテゴリーとFAQのデータを取得する。
      this.contents = getContents(settings);
      // キーワード入力欄のイベントを初期化する。
      $(settings.keywordInputClass).off(settings.searchEvnet).text('');
      // キーワード入力欄の入力欄変更イベントで検索を実行する。
      var _this = this;
      $(settings.keywordInputClass).on( settings.searchEvnet, function(e) {
        var keyword = $(this).val();
        if ( keyword.length > 0 ) {
          // コンテンツを検索する
          var results = [];
          results = findContentsByKeyword( _this.contents, keyword );
          // 表示を更新する
          render( settings, results );
        }
        else {
          $(settings.contentClass).show();
          $(settings.faqClass).show();
        }
        
      });
    },
  };

  /**  プライベート関数 */

  /**
   * 絞込検索機能に必要なデータ(contents)を取得する 
   * 
   * @param settings 設定
   * @return array コンテンツの配列（1コンテンツにはFAQの配列がある）
   * [
   *   {
   *      title: "hoge",
   *      index: 0,
   *      faqs: [
   *        {
   *          index: 0,
   *          q: "hoge hoge",
   *          a: "hoge hoge"
   *        },
   *        ...
   *      ]
   *   },
   *   ...
   * ]
   * 
   */
  function getContents(settings) {
    var contents = [];
    $(settings.contentClass).each(function(content_i, content_box){
      var content = {
        index: content_i,
        title: $(content_box).find(settings.contentTitleClass).text(),
        faqs: []
      };
      $(content_box).find(settings.faqClass).each(function(faq_i, faq_list){
        content.faqs.push({
          index: faq_i,
          q: $(faq_list).find(settings.faqQClass).text(),
          a: $(faq_list).find(settings.faqAClass).text()
        });
      });
      contents.push(content);
    });
    return contents;
  }

  /**
   * contentsを探索しキーワードにマッチしたコンテンツにフラグを返す 
   *
   * @param contents 検索対象のコンテンツ配列
   * @param keyword キーワード
   * @return array ヒットしたコンテンツの配列（1コンテンツにはFAQの配列がある）
   * [
   *   {
   *      title: "hoge",
   *      index: 0,
   *      faqs: [
   *        {
   *          index: 0,
   *          q: "hoge hoge",
   *          a: "hoge hoge"
   *        },
   *        ...
   *      ]
   *   },
   *   ...
   * ]
   */
  function findContentsByKeyword( contents, keyword ) {
    // コンテンツをディープコピー
    var matchedContents = $.extend(true, [], contents);
    $.each( matchedContents, function(content_i, content) {
      content.faqs = $.grep( content.faqs, function(faq, _) {
        return faq.q.indexOf(keyword) >= 0 || faq.a.indexOf(keyword) >= 0;
      });
      matchedContents[content_i] = content;
    });
    // マッチしたFAQが0件の場合、コンテンツを取り除く
    return $.grep( matchedContents, function(content, _){
      return content.faqs.length > 0;
    });
  }

  /**
   * 表示を更新する
   *
   * @param settings 設定
   * @param contents コンテンツの配列（1コンテンツにはFAQの配列がある）
   */
  function render( settings, contents ) {
    // すべて非表示にする
    $(settings.contentClass).hide();
    $(settings.faqClass).hide();
    // データを繰り返して表示する
    $.each(contents, function(_, content) {
      var $content_box = $(settings.contentClass).eq(content.index);
      // コンテンツを表示する
      $content_box.show();
      // FAQを表示する
      $.each( content.faqs, function(_, faq){
        $content_box.find(settings.faqClass).eq(faq.index).show();
      });
    });
  }

  if ( $.fn.sss_portal == undefined ) {
    $.fn.sss_portal = {};
  }
  $.fn.sss_portal.faq = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.sss_portal.faq' );
    }
  };
})(jQuery);