; (function ($) {
  'use strict';
  /**
   * クイズ機能を実現する一連のjQueryプラグイン
   *
   */
  var methods = {
    init: function (options) {
      var settings = $.extend(true, {
        quizSelectBoxClass: '.quiz_selected'
      }, options);

      // クイズパックの選択イベントを登録
      $(settings.quizSelectBoxClass).on('click', function () {
        var url = $(this).data('quiz-pack-url');
        requestQuizStart(url, initQuizStart);
      });

      // Ajax初期値設定
      $.ajaxSetup({
        dataType: 'html',
        timeout: 10000,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
      });
    },
  };

  /** プライベート */


  /**
   * @var 制限時間のタイマーID
   */
  var quizTimerId = null;

  /**
   * クイズスタート画面を要求します
   *
   */
  function requestQuizStart(url, openedCallback) {
    var options = {
      type: 'ajax', //ajaxでページを読み込み
      items: {
        src: url
      },
      fixedContentPos: true,
      fixedBgPos: true,
      closeOnBgClick: false,  //閉じるボタンでのみ閉じる
      overflowY: 'auto', //オーバーレイのスクロール有、hiddenで無し。 
      callbacks: {
        ajaxContentAdded: openedCallback,  // DOMに追加後コールされる
        close: function () {
          clearQuizTimerId();
        }
      },
      enableEscapeKey : false
    };
    $.magnificPopup.open(options);
  }

  /**
   * クイズスタート画面を初期化します
   *
   */
  function initQuizStart() {
    // ポップアップのタイトル、コンテンツ表示部の高さを調整する
    var headHeight = $('.quiz_box').find('.quiz_head').height();
    var margin = $('.quiz_box').find('.quiz_title').css('margin');
    headHeight += parseInt(margin, 10) * 2;
    $('.quiz_box').find('.quiz_cont').css({ top: headHeight });
    // スタートボタンでクイズ解答選択画面を要求する
    $('.quiz_start').find('#quiz_start_btn').off('click');
    $('.quiz_start').find('#quiz_start_btn').on('click', function (e) {
      var quiz_type = $('input[name="quiz_type"]:checked').val();
      if ($('#is_quiz_start').is(":checked")) {
        var next_quiz_id = $(this).data('next_quiz_id');
        $("#quiz_modal_close_btn").hide();
        $(this).off('click');
        e.stopPropagation();
        e.preventDefault();
        // screen_cd=QUIZ_STARTは操作ログに前画面の情報を記録するため
        var url = "";
        if (next_quiz_id) {
          url = $(this).data('url') + '?screen_cd=QUIZ_START&quiz_type=' + quiz_type;
        }
        requstQuiz(url);
        return false;
      }
    });
  }

  /**
   * 次のクイズ解答選択画面を要求します
   *
   */
  function requstQuiz(url) {
    resetAus();
    if (!url) {
      initQuizScore(`
      <div id="quiz_score" class="quiz_cont_inner">
        <div class="score mB30">There is no available questions in this exam</div>
        <ul class="score_table">
        </ul>
        <p>The exam doesn't have an questions. (Please provide questions)</p>
        <a href="" class="close_btn_foot mTB20 finish_btn"><img src="img/finish_en.png" alt="閉じる" width="150" style="cursor: pointer;"></a>
      </div>
      `);
      return;
    }
    $.ajax({
      url: url,
      type: 'GET',
      success: function (data) {
        initQuiz(data);
      },
      error: function (xhr, textStatus, errorThrown) {
        $.error([xhr, textStatus, errorThrown]);
      }
    });
  }

  /**
   * クイズ解答選択画面を初期化します
   */
  function initQuiz(data, currentSeconds) {
    // HTMLをコンテンツ表示部に表示する
    $('.quiz_cont_scroll').html(data);
    // クイズ選択肢ボタンを初期化する
    disableQuizChoiceButtons();
    // 制限時間があれば表示してカウントダウンを開始する
    startCoundDown(currentSeconds);
    // 「解答を見て終了」が選択された場合
    $('.quiz_end').on('click', function () {
      // 画面遷移中に他のクイズ選択肢を押せないようにする
      disableQuizChoiceButtons();
      // 1つ目の選択肢のデータを使う
      var $el = $('.quiz_choice_btn').first();
      var answer = {
        start_time: $("#quiz_start_time").val(),
        quizEnd: 1,    // 「解答を見て終了」
        oldChoices: $el.data('data'),
        quizType: $(this).data('quiz_type'),
        quizChoiceId: -1,      // -1 を選択させる
        quizChoiceTitle: '',
        isCorrect: false,   // 不正解
        isCorrectNumber: $('#is_correct_number').val()
      };
      var url = $el.data('url');
      //console.log("quiz_end:"+url+":"+answer);
      // 次のクイズ解答選択画面を要求する
      requestNextQuiz(url, answer);
      // <a>クリックによるリンクイベントをキャンセルする
      return false;
    });
    // クイズ解答選択ボタンをクリックした場合、クイズ解答判定画面を要求する
    $('.quiz_choice_btn').on('click', function () {
      // 画面遷移中に他のクイズ選択肢を押せないようにする
      disableQuizChoiceButtons();
      // 選択したボタンを色反転
      $(this).css('background-color', '#ff5f80').css('color', '#fff');
      var quiz_type = $(this).data('quiz_type');
      var choice_title = "";
      if (quiz_type == 1) { //fill_in mode
        choice_title = $('#fill_in_answer').val();
      } else {
        if($(this).data('choice_id')==0){
          choice_title = "";
        }else{
          choice_title = $(this).text();
        }
      }
      var answer = {
        start_time: $("#quiz_start_time").val(),
        quizEnd: 0,    // 「解答を見て終了」ではない
        oldChoices: $(this).data('data'),
        quizType: quiz_type,
        quizChoiceId: $(this).data('choice_id'),
        quizChoiceTitle: choice_title,
        isCorrectNumber: $('#is_correct_number').val(),
        exam_id: $(this).data("exam_id")
      };
      var url = $(this).data('url');
      requestNextQuiz(url, answer);
      // <a>クリックによるリンクイベントをキャンセルする
      return false;
    });

    //multi quiz observation submit
    $('#multi_quiz_observation_btn').on('click', function () {
      disableQuizChoiceButtons();
      // 選択したボタンを色反転
      var quiz_type = $(this).data('quiz_type');
      var choice_title = "";
      var choice_id = 0;
      if (quiz_type == 1) { //fill_in mode
        choice_title = $('#fill_in_answer').val();
        choice_id = $('#fill_in_answer').data('choice_id');
      } else {
        choice_title = "";
        choice_id = $("select#multi-quiz-observation__select option").filter(":selected").val();
        if(choice_id){
          choice_title = $("select#multi-quiz-observation__select option").filter(":selected").text();
        }
      }

      var params = {
        'start_time': $("#quiz_start_time").val(),
        'old_choices': $(this).data('data'),
        'quiz_type': quiz_type,
        'old_observations': $(this).data('observations'),
        'quiz_choice_id': choice_id,
        'quiz_choice_title': choice_title,
        'exam_id': $(this).data("exam_id"),
        'content_type': $(this).data('content_type'),
      }
      var url = $(this).data('url');
      var $timerArea = $('.question_number');
      var currentSeconds = $timerArea.children('span').text();
      // if ((params.quiz_choice_id == "" || !params.quiz_choice_id) && params.quiz_type!=1) {
      //   alert("please select observation")
      // } else {
      //   requestMultiQuizContent(url, params, currentSeconds);
      // }
      requestMultiQuizContent(url, params, currentSeconds);
    });
    //multi quiz final answer submit
    $('#multi_quiz_final_answer_btn').on('click', function () {
      var quiz_type = $(this).data('quiz_type');
      var choice_title = "";
      var choice_id = 0;
      if (quiz_type == 1) { //fill_in mode
        choice_title = $('#fill_in_answer').val();
        choice_id = $('#fill_in_answer').data('choice_id');
      } else {
        choice_title = "";
        choice_id = $("select#multi-quiz-observation__select option").filter(":selected").val();
        if(choice_id){
          choice_title = $("select#multi-quiz-observation__select option").filter(":selected").text();
        }
      }
      var answer = {
        start_time: $("#quiz_start_time").val(),
        quizEnd: 0,    // 「解答を見て終了」ではない
        oldChoices: $(this).data('data'),
        quizType: quiz_type,
        oldObservations: $(this).data('observations'),
        quizChoiceId: choice_id,
        quizChoiceTitle: choice_title,
        isCorrectNumber: $('#is_correct_number').val(),
        exam_id: $(this).data("exam_id"),
        is_multiple     : true
      };
      var url = $(this).data('url');
      // if ((answer.quizChoiceId == "" || !answer.quizChoiceId) && answer.quizType!=1) {
      //   alert("please select Diagnosis")
      // } else {
        
      // }
      requestNextQuiz(url, answer);
      return false;

    });

    $('.multiple_quiz__btns').on('click', function (el) {
      var quiz_type = $(this).data('quiz_type');
      const url = $(this).data('url') + '?screen_cd=QUIZ&quiz_type=' + quiz_type;
      $(".multiple_quiz_lib__title").show();
      $(this).off('click');
      var params = {
        'old_choices': $(this).data('data'),
        'old_observations': $(this).data('observations'),
        'start_time': $("#quiz_start_time").val(),
      }
      var already_active = $(this).hasClass("active");
      var $timerArea = $('.question_number');
      var currentSeconds = $timerArea.children('span').text();
      if (!already_active) {
        requestMultiQuizContent(url, params, currentSeconds);
      }
    });
    var lib_type = $('.content_title').data('lib_type');
    if (lib_type == 1) {
      //initStheto();
    }

    //update use logs before unload
    $(window).on("beforeunload", function() { 
      resetAus();
      $.ajax({
        type: "GET",
        url: 'quizpacks/end_quiz',
        success: function(data){
          return true;
        }
      });
    })
  }

  /**
   * クイズ解答選択肢ボタンを押させないようにする
   */
  function disableQuizChoiceButtons() {
    $('.quiz_choice_btn').off('click').css('background-color', 'white').css('color', '#333');

    $('.quiz_end').off('click').css('background-color', 'white').css('color', '#333');

    $('.multi_quiz_observation_btn').off('click');
  }

  /**
   * 制限時間のタイマーIDをクリアします
   */
  function clearQuizTimerId() {
    if (quizTimerId !== null) {
      clearTimeout(quizTimerId);
    }
  }

  /**
   * 制限時間を表示しカウントダウンを開始します。
   */
  function startCoundDown(oldSeconds) {
    if ($('.question_number').length > 0) {
      var $timerArea = $('.question_number');
      var seconds = $timerArea.data('seconds');
      if (oldSeconds !== undefined) {//dont reset timer when selecting mutiple contents
        seconds = oldSeconds;
      }
      $timerArea.children('span').text(seconds);
      // 1秒毎にカウントを減らす
      quizTimerId = setInterval(function () {
        // 制限時間の表示更新
        var currentSeconds = $timerArea.children('span').text();
        currentSeconds--;
        if (currentSeconds < 0) {
          // タイムオーバイベント
          // var data = { quiz_pack: {
          //     id: $('body').find('.quiz_box').data('quiz-pack-id'),
          //     title: $('body').find('.quiz_box').data('quiz-pack-title')
          //   },
          //   quiz: {
          //     id: $('body').find('.question_box').data('quiz-id'),
          //     title: $('body').find('.question_box').data('quiz-title'),
          //     question: $('body').find('.question_box').data('quiz-question')
          //   }
          // };
          // $(window).logger('send', '/log', { screen_code: 'QUIZ', event_code: 'TIME_OVER', body: data });

          clearQuizTimerId();
          // 画面遷移中に他のクイズ選択肢を押せないようにする
          disableQuizChoiceButtons();
          // 1つ目の選択肢のデータを使う
          var $el = $('.quiz_choice_btn').first();
          var answer = {
            start_time: $("#quiz_start_time").val(),
            quizEnd: 0,    // 「解答を見て終了」ではない
            oldChoices: $el.data('data'),
            quizType: $el.data('quiz_type'),
            oldObservations: $el.data('observations'),
            quizChoiceId: -1,      // -1 を選択させる
            quizChoiceTitle: '',
            isCorrectNumber: $('#is_correct_number').val(),
            exam_id: $el.data("exam_id")
          };
          var url = $el.data('url');
          // 次のクイズ解答選択画面を要求する
          requestNextQuiz(url, answer);
        }
        else {
          $timerArea.children('span').text(currentSeconds);
        }
      }, 1000);
    }
  }

  /**
   * 次のクイズを要求します。
   * 次のクイズが存在する場合、クイズ解答選択画面になります。
   * クイズがすべて完了している場合、クイズ成績画面になります。
   */
  function requestNextQuiz(url, answer) {
    if (url == undefined) {
      initQuizScore(`
      <div id="quiz_score" class="quiz_cont_inner">
        <div class="score mB30">There is no right answer for this question</div>
        <ul class="score_table">
        </ul>
        <p>The question doesn't have an options. (Please provide options)</p>
        <a href="" class="close_btn_foot mTB20 finish_btn"><img src="img/finish_en.png" alt="閉じる" width="150" style="cursor: pointer;"></a>
      </div>
      `);
      return;
    }
    // タイマーを切る
    clearQuizTimerId();
    resetAus();
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'start_time': answer.start_time,
        'quiz_end': answer.quizEnd,
        'quiz_type': answer.quizType,
        'old_choices': answer.oldChoices,
        'old_observations': answer.oldObservations,
        'quiz_choice_id': answer.quizChoiceId,
        'quiz_choice_title': answer.quizChoiceTitle,
        'exam_id': answer.exam_id,
        'is_multiple': answer.is_multiple ? answer.is_multiple : false,
      },
      success: function (data) {
        // クイズの結果を表示します
        showQuizAnswerResult(answer, 1, function () {
          $('.quiz_cont_scroll').html(data).show();
          if ($('.quiz_cont_scroll').find('.quiz_answer_box').length > 0) {
            // クイズ画面を初期化する
            initQuiz(data);
          }
          else {
            // クイズ画面を初期化する
            initQuizScore(data);
          }
        });
      },
      error: function (xhr, textStatus, errorThrown) {
        $.error([xhr, textStatus, errorThrown]);
      }
    });
  }

  function requestMultiQuizContent(url, params, currentSeconds) {
    $('#multi_quiz_observation_btn').hide();
    resetAus();
    clearQuizTimerId();
    $.ajax({
      url: url,
      type: 'POST',
      data: params,
      success: function (data) {
        initQuiz(data, currentSeconds);
      },
      complete: function() {
        $('#multi_quiz_observation_btn').show();
      },
      error: function (xhr, textStatus, errorThrown) {
        $('#multi_quiz_observation_btn').show();
        $.error([xhr, textStatus, errorThrown]);
      }
    });
  }

  /**
   * クイズ解答判定画面を初期化します
   */
  function showQuizAnswerResult(answer, showSeconds, callback) {
    var lang = $('.quiz_cont_scroll').data('lang');
    var imgTag = (answer.isCorrect) ?
      '<img src="img/quiz_result_true' + lang + '.png" alt="正解"/>' :
      '<img src="img/quiz_result_false' + lang + '.png" alt="不正解"/>';

    var html = '' +
      '<div class="quiz_cont_inner height100">' +
      '  <p class="quiz_number"><span>' + answer.isCorrectNumber + '</span>' + ((lang == "_en") ? "Question" : "問") + '</p>' +
      '  <div class="quiz_result_box">' +
      '    <div class="quiz_result">' + imgTag + '</div>' +
      '  </div>' +
      '</div>';

    // $('.quiz_cont_scroll').html(html).show(); // dont show if correct or wrong

    // 1秒後に非表示にする
    setTimeout(function () {
      $('.quiz_cont_scroll').html(html).hide();
      callback();
    }, showSeconds);

    return html;
  }

  /**
   * クイズ成績画面を初期化します
   */
  function initQuizScore(data) {
    $('.quiz_cont_scroll').html(data);
    // 回答結果選択ボタン
    $('.answer_confirm_btn').on('click', function (e) {
      $(this).off('click');
      var quiz_type = $(this).data('quiz_type');
      e.stopPropagation();
      e.preventDefault();
      var url = $(this).data('url') + '?quiz_type=' + quiz_type;
      //console.log(url);
      var quizNumber = $(this).data('quiz_number');
      var old_choices = $(this).data('old_choices');
      requestQuizAnswerConfirm(old_choices, url, quizNumber, initQuizAnswerConfirm);
      // <a>クリックによるリンクイベントをキャンセルする
      return false;
    });
  }

  /**
   * クイズ解答確認画面を要求します
   */
  function requestQuizAnswerConfirm(old_choices, url, quizNumber, successCallback) {
    resetAus();
    $.ajax({
      url: url,
      data: {
        'old_choices': old_choices
      },
      type: 'GET',
      success: function (data) {
        // scoreのhistory画面保存
        var state = {
          content: $('.quiz_cont_scroll').html(),
          url: url
        };
        successCallback(data, quizNumber, state);
      },
      error: function (xhr, textStatus, errorThrown) {
        $.error([xhr, textStatus, errorThrown]);
      }
    });
  }

  /**
   * クイズ解答確認画面を初期化します
   */
  function initQuizAnswerConfirm(data, quizNumber, state) {
    //回答確認画面を成績画面に遷移

    $('.quiz_cont_scroll').html(data).ready(function () {
      // クイズ確認問題番号
      var lang = $('.question_title').data('lang');
      var quiz_number = quizNumber + $('.question_title').data('trans');
      if (lang == "en") {
        quiz_number = $('.question_title').data('trans') + quizNumber;
      }
      $('.question_title').text(quiz_number);
      // 戻るボタン押下
      $('.back_btn').on('click', function () {
        initQuizScore(state.content);
      });
      //initStheto();
    });
  }

  if ($.fn.sss_portal == undefined) {
    $.fn.sss_portal = {};
  }
  $.fn.sss_portal.quiz = function (method) {
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' + method + ' does not exist on jQuery.sss_portal.quiz');
    }
  };

  //===================================================
  // Stetho function
  //===================================================
  function initStheto() {
    /**
    * when point click manually
    *
    */
    $(".btn-point").click(function () {
      var point = $(this).data('point');
      var id = localStorage.getItem("sthetho_id");
      var config = $("#stethoscope_" + id).data('config');
      var src = $("#stethoscope_" + id).data('sounds')[point + "_sound_path"];

      if (config) { // check is points are set
        var adjustment = getPositionAdjustments(config[point]['x'], config[point]['y']);
        $("#stethoscope_" + id).animate({ // move the stethoscope to selected point
          left: (adjustment.x) + '%',
          top: (adjustment.y) + '%',
        }, 300);
        var sound = $("#sound_" + id)[0];
        if (config[point]['x'] && config[point]['y']) { // play sound if that point is set
          sound.volume = 1.0; // set to max vol.
          if ($("#sound_" + id).attr("data-point") != point) { // check if sound already playing
            $("#sound_" + id).attr('data-point', point);
            sound.src = src; // set sound src
            sound.play(); //call this to play the sound right away
            //console.log(sound);
          }
        } else {
          $("#sound_" + id).attr('data-point', "");
          sound.pause();
          sound.volume = 0.0;
          sound.src = "";
          sound.currentTime = 0;
        }
      }
    });
    /**
    * when sound speed button click
    *
    */
    $(".btn-vol").click(function () {
      var volspeed = $(this).data('vol');
      var id = localStorage.getItem("sthetho_id");

      $(".btn-vol").each(function (index) {
        $(this).removeClass("vol-active");
      });

      $(this).addClass("vol-active");

      var sound = $("#sound_" + id)[0];
      sound.playbackRate = volspeed;
    });
    /**
    * drag trigger event for stethoscope
    *
    */

    $('.stethoscope').bind('mousemove', function (event) {
      onStethoscopeMove();
    });

    $(".body-map").click(function (e) {
      var id = localStorage.getItem("sthetho_id");
      if (id) {
        var elm = $(this);
        var xPos = e.pageX - elm.offset().left;
        var yPos = e.pageY - elm.offset().top;

        $("#stethoscope_" + id).animate({ // move the stethoscope to selected point
          left: xPos - 16,
          top: yPos - 16,
        }, {
          duration: 300,
          complete: function () {
            onStethoscopeMove(); // call this function to play the sound
          }
        });
      }
    });

  }

  function onStethoscopeMove() {
    var id = localStorage.getItem("sthetho_id");
    var stethoscope = $("#stethoscope_" + id);
    var sound = $("#sound_" + id)[0];
    var position = stethoscope.position(); // get stethoscope position
    var config = stethoscope.data('config');
    var sounds = stethoscope.data('sounds');

    if (config) { // check if config is set

      var a_left = Number((250 / 100) * getPositionAdjustments(config.a.x, config.a.y).x);
      var a_top = Number((200 / 100) * getPositionAdjustments(config.a.x, config.a.y).y);
      var a_radius = Number(config.a.r) / 2;

      var p_left = Number((250 / 100) * getPositionAdjustments(config.p.x, config.p.y).x);
      var p_top = Number((200 / 100) * getPositionAdjustments(config.p.x, config.p.y).y);
      var p_radius = Number(config.p.r) / 2;

      var t_left = Number((250 / 100) * getPositionAdjustments(config.t.x, config.t.y).x);
      var t_top = Number((200 / 100) * getPositionAdjustments(config.t.x, config.t.y).y);
      var t_radius = Number(config.t.r) / 2;

      var m_left = Number((250 / 100) * getPositionAdjustments(config.m.x, config.m.y).x);
      var m_top = Number((200 / 100) * getPositionAdjustments(config.m.x, config.m.y).y);
      var m_radius = Number(config.m.r) / 2;

      // A
      if (((position.left + a_radius) >= a_left && (position.left - a_radius) <= a_left) &&
        ((position.top + a_radius) >= a_top && (position.top - a_radius) <= a_top)) {

        var vol_p = getVolumeByRadius(position, a_left, a_top, a_radius)

        if (vol_p <= 1.0 && vol_p >= 0.0) {
          sound.volume = vol_p;
        }

        if ($("#sound_" + id).attr("data-point") != "a") { // check if sound already playing
          $("#sound_" + id).attr('data-point', "a");
          sound.src = sounds['a_sound_path']; // set sound src
          //console.log(sound);
        }

        sound.play(); //call this to play the song right away
      }

      // P
      else if (((position.left + p_radius) >= p_left && (position.left - p_radius) <= p_left) &&
        ((position.top + p_radius) >= p_top && (position.top - p_radius) <= p_top)) {

        var vol_p = getVolumeByRadius(position, p_left, p_top, p_radius)

        if (vol_p <= 1.0 && vol_p >= 0.0) {
          sound.volume = vol_p;
        }

        if ($("#sound_" + id).attr("data-point") != "p") { // check if sound already playing
          $("#sound_" + id).attr('data-point', "p");
          sound.src = sounds['p_sound_path']; // set sound src
          //console.log(sound);
        }
        sound.play(); //call this to play the song right away
      }

      // T
      else if (((position.left + t_radius) >= t_left && (position.left - t_radius) <= t_left) &&
        ((position.top + t_radius) >= t_top && (position.top - t_radius) <= t_top)) {

        var vol_p = getVolumeByRadius(position, t_left, t_top, t_radius)

        if (vol_p <= 1.0 && vol_p >= 0.0) {
          sound.volume = vol_p;
        }

        if ($("#sound_" + id).attr("data-point") != "t") { // check if sound already playing
          $("#sound_" + id).attr('data-point', "t");
          sound.src = sounds['t_sound_path']; // set sound src
          //console.log(sound);
        }
        sound.play(); //call this to play the song right away
      }

      // M
      else if (((position.left + m_radius) >= m_left && (position.left - m_radius) <= m_left) &&
        ((position.top + m_radius) >= m_top && (position.top - m_radius) <= m_top)) {

        var vol_p = getVolumeByRadius(position, m_left, m_top, m_radius)

        if (vol_p <= 1.0 && vol_p >= 0.0) {
          sound.volume = vol_p;
        }

        if ($("#sound_" + id).attr("data-point") != "m") { // check if sound already playing
          $("#sound_" + id).attr('data-point', "m");
          sound.src = sounds['m_sound_path'];; // set sound src
          //console.log(sound);
        }
        sound.play(); //call this to play the song right away
      } else {
        if (sound) {
          sound.pause();
        }
      }
    }
  }
  /**
  * get adjustment for small body images
  *
  */
  function getPositionAdjustments(x, y) {
    return {
      x: ((x) / 500) * 100,
      y: ((y) / 400) * 100
    }
  }

  /**
  * get voulume by raduis
  *
  */
  function getVolumeByRadius(position, x, y, radius) {
    var top_r = (position.top + radius) - y;
    var left_r = (position.left + radius) - x;
    var bottom_r = Math.abs((position.top - radius) - y);
    var right_r = Math.abs((position.left - radius) - x);

    var radius_p = 0;

    if (top_r < left_r && top_r < bottom_r && top_r < right_r) {
      radius_p = top_r;
    } else if (left_r < top_r && left_r < bottom_r && left_r < right_r) {
      radius_p = left_r;
    } else if (bottom_r < top_r && bottom_r < left_r && bottom_r < right_r) {
      radius_p = bottom_r;
    } else if (right_r < top_r && right_r < left_r && right_r < bottom_r) {
      radius_p = right_r;
    } else {
      radius_p = radius;
    }

    var vol_p = radius_p / radius;

    // console.log("top: " + top_r);
    // console.log("left: " + left_r);
    // console.log("bottom: " + bottom_r);
    // console.log("right: " + bottom_r);

    return vol_p;
  }

  function resetAus(){
    var reload_btn = $("#btn-case-top");
    if($("#ausculaide_app").hasClass("aus_loaded")){
      stopAllSound();
    }

  }
})(jQuery);
