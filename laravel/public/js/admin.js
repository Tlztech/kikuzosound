$(function () {
  // --------------------------------クイズパック--------------------------------
  // クイズパック追加/変更画面でランダム/固定が変更されたときのイベント
  $("#fixed_status").on('change', function () {
    // 固定(0)の場合、「固定」なので出題数を読取専用にする。
    // ランダム(1)の場合、「ランダム」なので出題数を書込可能にする。
    var is_fixed = ($("#fixed_status").val() === "0");
    $('#max_quiz_count-field').prop('readonly', is_fixed);
    // 出題数を更新する
    if (is_fixed) {
      $('#max_quiz_count-field').val($('#quizzes__tbody').children('tr').length);
    }
  });

  // クイズパックの表示順保存ボタンを押下されたときのイベント
  $('#save_order_btn').click(function () {
    // 並び替えれた後のクイズパックIDの配列を取得
    var ids = $('#sortable_tbody>tr>td:first-child').map(function (i, e) { return $(e).text(); }).get();
    var data = JSON.stringify({ "quiz_packs": ids });
    $.ajax({
      url: '/admin/quiz_packs_orders',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: data,
      type: 'PATCH',
      contentType: 'application/json',
      success: function (msg) {
        alert("クイズパックの表示順を変更しました。");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("表示順を変更に失敗しました : " + XMLHttpRequest);
      },
    });
  });

  // 追加ボタン押下イベント
  $('#add_quiz__btn').on('click', function () {
    // クイズのセレクトボックスの要素を取得
    var selector_id = '#quiz_add__selector';
    var selected_id = selector_id + ' option:selected';
    // クイズのIDとタイトルを取得する
    var selectedQuiz = {
      id: $(selected_id).val(),
      title: $(selected_id).text()
    };
    // クイズ一覧の行を作成
    var row = "";
    row += '<tr>';
    row += '  <td>' + selectedQuiz.title + '</td>';
    row += '  <td class="text-right">';
    row += '    <input type="hidden" name="quizzes[]" value="' + selectedQuiz.id + '"/>'
    row += '    <a id="remove_quiz__btn" class="btn btn-danger" data-id="' + selectedQuiz.id + '" data-title="' + selectedQuiz.title + '">削除</a>';
    row += '    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />';
    row += '  </td>';
    row += '</tr>';
    // クイズ一覧の要素を取得
    var $quiz_table = $('#quizzes__tbody');
    // クイズ一覧に行を追加する
    $quiz_table.append(row).on('click', '#remove_quiz__btn[data-id=' + selectedQuiz.id + ']', function () {
      var id = $(this).data('id');
      var title = $(this).data('title');
      // クイズ一覧から要素を削除する
      $(this).closest('tr').remove();
      // クイズのセレクトボックスに要素を戻す
      if ($(selector_id + ' option[value=' + id + ']').length == 0)
        $(selector_id).append($('<option>').val(id).text(title));
      // valueでソートする
      var $options = $(selector_id + ' option');
      $options.sort(function (a, b) {
        a = a.value;
        b = b.value;
        return a - b;
      });
      $(selector_id).html($options);
      // 追加した要素を選択する
      $(selector_id).val(id);
      // 追加ボタンを押せるようにする
      $('#add_quiz__btn').prop('disabled', false);
      // 出題数を更新する
        $('#max_quiz_count-field').val($quiz_table.children('tr').length);
    });
    // クイズのセレクトボックスから選択した要素を削除する
    $(selector_id + ' option[value=' + selectedQuiz.id + ']').remove();
    // セレクトボックスの選択肢がなくなったら追加ボタンを押せないようにする
    if ($(selector_id).children().length === 0) {
      $(selector_id).val('');
      $('#add_quiz__btn').prop('disabled', true);
    }
    // 出題数を更新する
      $('#max_quiz_count-field').val($quiz_table.children('tr').length);
  });

  // クイズパック編集画面のクイズ削除ボタンのクリックイベント
  $('#quizzes__tbody').on('click', '#remove_quiz__btn', function () {
    // TODO: 重複削除
    var id = $(this).data('id');
    var title = $(this).data('title');
    // クイズ一覧から要素を削除する
    $(this).closest('tr').remove();
    // クイズのセレクトボックスに要素を戻す
    if ($('#quiz_add__selector' + ' option[value=' + id + ']').length == 0)
      $('#quiz_add__selector').append($('<option>').val(id).text(title));
    // valueでソートする
    var $options = $('#quiz_add__selector' + ' option');
    $options.sort(function (a, b) {
      a = a.value;
      b = b.value;
      return a - b;
    });
    $('#quiz_add__selector').html($options);
    // 追加した要素を選択する
    $('#quiz_add__selector').val(id);
    // 追加ボタンを押せるようにする
    $('#add_quiz__btn').prop('disabled', false);
    // 出題数を更新する
    var is_fixed = ($("#fixed_status").val() === "0");
    if (is_fixed) {
      $('#max_quiz_count-field').val($('#quizzes__tbody').children('tr').length);
    }
  });

  // クイズパックのアイコン画像を一時的にブラウザに表示する
  $('#quiz_pack_image-file-input').on('change', function (e) {
    //console.log($(this));
    $('#quiz_pack_image-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#quiz_pack_image-img').show();
    $('#quiz_pack_image_remove-btn').show();
  });

  // ライブラリファイルの画像を一時的にブラウザに表示する
  $('#lib_image-file-input').on('change', function (e) {
    $('#lib_image_file_image-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#lib_image_file_image-img').show();
  });

  //説明ファイルの画像を一時的にブラウザに表示します
  $('#explanatory_image-file-input').on('change', function (e) {
    $('#explanatory_image_file-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#explanatory_image_file-img').show();
  });

  //説明（EN）ファイルのイメージをブラウザに一時的に表示します
  $('#explanatory_image_en-input').on('change', function (e) {
    $('#explanatory_image_file_en-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#explanatory_image_file_en-img').show();
  });

  //ブラウザでbiody画像ファイルの画像を一時的に表示します
  $('#body_image-file-input').on('change', function (e) {
    $('#body_image_file-image-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#body_image_file-image-img').show();
  });

  $('#body_image_back-file-input').on('change', function (e) {
    $('#body_image_back_file-image-img').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#body_image_back_file-image-img').show();
  });

  // ライブラリのビデオファイルをブラウザに一時的に表示します
  $('#lib_video-file-input').on('change', function (e) {
    $('#lib_video_file-video').fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#lib_video_file-video').show();
  });

  // クイズパックの画像の削除ボタンを押したら、入力要素を空にし、表示要素を削除する
  $('#quiz_pack_image_remove-btn').on('click', function (e) {
    $('#quiz_pack_image-file-input').val('');
    $('#quiz_pack_image-img').hide();
    $('input[name=icon_path]').val('');
    $(this).hide();
  });

  // 固定だったら出題数を読み取り専用にする
  $(document).ready(function () {
    var is_fixed = ($("#fixed_status").val() === "0");
    $('#max_quiz_count-field').prop('readonly', is_fixed);
    // 出題数を更新する
    if (is_fixed) {
      $('#max_quiz_count-field').val($('#quizzes__tbody').children('tr').length);
    }
  });
  // --------------------------------クイズ--------------------------------
  // クイズ追加/編集画面 回答D&D
  $("#quiz_choice__tbody").sortable({
    // 回答の表示順の変更イベント
    update: function (event, ui) {
      var $td = $('#quiz_choice__tbody>tr>td');
      $td.children('input[type="text"]').each(function (i, e) {
        $(e).attr('name', 'quiz_choices[' + i + '][title_en]');
      });
      $td.children('input[type="radio"]').each(function (i, e) {
        $(e).attr('value', i);
      });
    }
  });
  $('#quiz_choice__tbody').disableSelection();
  //library choices sortable
  $("#stethoscope_quiz_choice__tbody,#auscultation_quiz_choice__tbody,#palpation_quiz_choice__tbody,#ecg_quiz_choice__tbody,#examination_quiz_choice__tbody,#xray_quiz_choice__tbody,#echo_quiz_choice__tbody,#final_answer_quiz_choice__tbody,#fill-in_final_quiz_choice__tbody").sortable({
    update: function (event, ui) {
      var lib_type = $(this).data('lib_type');
      var $row = $('#' + lib_type + '_quiz_choice__tbody>tr');
      var $td = $('#' + lib_type + '_quiz_choice__tbody>tr>td');
      $row.each(function (row_i, row_e) {
        $(this).find('input[type="text"]').each(function (i, e) {
          if (i == 0) {
            $(e).attr('name', lib_type + '_quiz_choices[' + row_i + '][title]');
          } else {
            $(e).attr('name', lib_type + '_quiz_choices[' + row_i + '][title_en]');
          }
        });
      });
      $td.children('input[type="radio"]').each(function (i, e) {
        $(e).attr('value', i);
      });
    }
  });

  //library contents sortable
  $("#stethoscope__tbody,#auscultation__tbody,#palpation__tbody,#ecg__tbody,#examination__tbody,#xray__tbody,#echo__tbody").sortable({
    update: function (event, ui) {
      var lib_type = $(this).data('lib_type');
      var $row = $('#' + lib_type + '__tbody>tr');
      var $td = $('#' + lib_type + '__tbody>tr>td');
      $row.each(function (row_i, row_e) {
        $(this).find('input[type="text"]').each(function (i, e) {
          if (i == 0) {
            $(e).attr('name', lib_type + '[' + row_i + '][description]');
          } else {
            $(e).attr('name', lib_type + '[' + row_i + '][description_en]');
          }
        });
      });
      $td.children('#id__input').each(function (i, e) {
        $(e).attr('name', lib_type + '[' + i + '][id]');
      });
      $td.children('#title__input').each(function (i, e) {
        $(e).attr('name', lib_type + '[' + i + '][title]');
      });
    }
  });

  // クイズ追加/編集画面 回答D&D
  $("#stetho_sound__tbody").sortable({
    // 回答の表示順の変更イベント
    update: function (event, ui) {
      var $td = $('#stetho_sound__tbody>tr>td');
      $td.children('input[type="text"]').each(function (i, e) {
        $(e).attr('name', 'stetho_sounds[' + i + '][description_en]');
      });
      $td.children('#id__input').each(function (i, e) {
        $(e).attr('name', 'stetho_sounds[' + i + '][id]');
      });
      $td.children('#title__input').each(function (i, e) {
        $(e).attr('name', 'stetho_sounds[' + i + '][title]');
      });
    }
  });
  $('#stetho_sound__tbody').disableSelection();

  // クイズ追加/変更画面 回答追加ボタン押下イベント
  $('#add_quiz_choices＿btn').on('click', function (e) {
    var lib_type = e.target.dataset.lib_type;
    var lib_key = e.target.dataset.lib_key;
    var content_count = $('#' + lib_type + '__tbody tr').length;
    $tbody = $("#" + lib_type + "_quiz_choice__tbody");
    var cnt = $tbody.children('tr').length;
    var row = '';
    row += '<tr>';
    row += '  <td>';
    row += '    <input type="radio" name="' + lib_type + '_quiz_choices_correct_index" value="' + cnt + '" />';
    row += '    <input type="hidden" class="form-control" name="' + lib_type + '_quiz_choices[' + cnt + '][lib_key]" value="' + lib_key + '"/>';
    row += '  </td>';
    row += '  <td>';
    row += '    <input type="text" name="' + lib_type + '_quiz_choices[' + cnt + '][title]" class="form-control" value=""  placeholder="JP" required/>';
    row += '  </td>';
    row += '  <td>';
    row += '    <input type="text" name="' + lib_type + '_quiz_choices[' + cnt + '][title_en]" class="form-control" value="" placeholder="EN" required/>';
    row += '  </td>';
    row += '  <td class="text-right">';
    row += '    <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>';
    row += '    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />'
    row += '  </td>';
    row += '</tr>';

    $tbody.append(row);

    // 回答削除ボタン押下イベント
    $tbody.off('click', '#remove_quiz_choice__btn');
    $tbody.on('click', '#remove_quiz_choice__btn', function () {
      $(this).closest('tr').remove();
    });
  });
  //add quiz fill-in final choices
  $('#add_quiz_fill-in_choices＿btn').on('click', function (e) {
    var lib_type = e.target.dataset.lib_type;
    var lib_key = e.target.dataset.lib_key;
    var content_count = $('#' + lib_type + '__tbody tr').length;
    $tbody = $("#fill-in_final_quiz_choice__tbody");
   // var cnt = $tbody.children('tr').length;
    var last_tr = $tbody.children('tr').last();
    //console.log(last_tr);
    cnt=last_tr.data("key")+1;
    //console.log(cnt);
    var row = '';
    row += '<tr data-key="'+cnt+'">';
    row += '  <td style="width:90%">';
    row += '    <input type="hidden" class="form-control" name="' + lib_type + '_quiz_choices[' + cnt + '][fill_in][lib_key]" value="' + lib_key + '"/>';
    row += '    <input type="text" name="' + lib_type + '_quiz_choices[' + cnt + '][fill_in][title]" class="form-control" value=""/>';
    row += '  </td>';
    row += '  <td class="text-right">';
    row += '    <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>';
    row += '    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />'
    row += '  </td>';
    row += '</tr>';

    $tbody.append(row);

    // 回答削除ボタン押下イベント
    $tbody.off('click', '#remove_quiz_choice__btn');
    $tbody.on('click', '#remove_quiz_choice__btn', function () {
      $(this).closest('tr').remove();
    });
  });

  // 回答削除ボタン押下イベント
  $("tbody").off('click', '#remove_quiz_choice__btn');
  $("tbody").on('click', '#remove_quiz_choice__btn', function () {
    $(this).closest('tr').remove();
  });

  $("#quiz_choice__tbody").on('click', '#remove_quiz_choice__btn', function () {
    $(this).closest('tr').remove();
  });

  // クイズのイラストアップロードボタンでブラウザ上に表示する
  $('#quiz_image-field').on('change', function (e) {
    // input type = file の次のタグは img 
    $(this).next().fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
    $('#quiz_image_remove-btn').show();
  });

  // クイズの画像の削除ボタンを押したら、入力要素を空にし、表示要素を削除する
  $('#quiz_image_remove-btn').on('click', function (e) {
    $('#quiz_image-field').val('');
    $('#quiz_image-img').hide();
    $('input[name=image_path]').val('');
    $(this).hide();
  });

  //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  // クイズ追加と編集画面の聴診音追加ボタン押下イベント
  $('#add_stetho_sound＿btn').on('click', function (e) {
    var lib_explanation = e.target.dataset.lib_explanation;
    var lib_type = e.target.dataset.lib_type;
    var selector_id = '#' + lib_type + '__selector';
    var selected_id = selector_id + ' option:selected';
    var unique_add_btn_selector = ".add_btn_" + lib_type;
    var description = $('.col-sm-8 input[name=stetho_sound]').val();
    var selectedSound = {
      id: $(selected_id).val(),
      title: $(selected_id).text(),
      description
    };
    var row = "";
    var $sound_table = $('#' + lib_type + '__tbody');
    var cnt = $sound_table.children('tr').length;

    row += '<tr>';
    row += '  <td>' + selectedSound.title + '</td>';
    row += '  <td>';
    row += '    <input type="text" class="form-control" name="' + lib_type + '[' + cnt + '][description] value="' + selectedSound.description + '" placeholder="JP"/>';
    row += '  </td>';
    row += '  <td>';
    row += '    <input type="text" class="form-control" name="' + lib_type + '[' + cnt + '][description_en] value="' + selectedSound.description + '" placeholder="EN"/>';
    row += '  </td>';
    row += '  <td class="text-right" style="white-space: nowrap;">';
    row += '    <input id="title__input" type="hidden" name="' + lib_type + '[' + cnt + '][title]" value="' + selectedSound.title + '"/>';
    row += '    <input id="id__input" type="hidden" name="' + lib_type + '[' + cnt + '][id]" value="' + selectedSound.id + '"/>';
    row += '    <a class="btn btn-default select_stetho_sound_description_btn" data-id="' + selectedSound.id + '">' + lib_explanation + '</a>';
    row += '    <a id="remove_stetho_sound＿btn" data-lib_type="' + lib_type + '" class="btn btn-danger" data-id="' + selectedSound.id + '" data-title="' + selectedSound.title + '">削除</a>';
    row += '    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />'
    row += '  </td>';
    row += '</tr>';

    $sound_table.append(row).on('click', '#remove_stetho_sound＿btn[data-id=' + selectedSound.id + ']', function () {
      var id = $(this).data('id');
      var title = $(this).data('title');
      $(this).closest('tr').remove();
      // 超心音のセレクトボックスに要素を戻す
      if ($(selector_id + ' option[value=' + id + ']').length == 0)
        $(selector_id).append($('<option>').val(id).text(title));
      // valueでソートする
      var $options = $(selector_id + ' option');
      $options.sort(function (a, b) {
        a = a.value;
        b = b.value;
        return a - b;
      });
      $(selector_id).html($options);
      $(selector_id).val(id);
      $(unique_add_btn_selector).prop('disabled', false);
    }).on('click', '.select_stetho_sound_description_btn[data-id=' + selectedSound.id + ']', function (e) {
      // 回答説明を選択する
      $('select[name=' + lib_type + '_description]').val($(this).data('id'));
    });
    // 聴診音のセレクトボックスから選択した要素を削除する
    $(selector_id + ' option[value=' + selectedSound.id + ']').remove();
    // セレクトボックスの選択肢がなくなったら追加ボタンを押せないようにする
    if ($(selector_id).children().length === 0) {
      $(selector_id).val('');
      $(unique_add_btn_selector).prop('disabled', true);
    }
  });
  // 回答説明を選択する
  $('.select_stetho_sound_description_btn').on('click', function (e) {
    // 回答説明を選択する
    $('select[name=' + $(this).data('lib_type') + '_description]').val($(this).data('id'));
  });

  $('#remove_stetho_sound＿btn').on('click', function () {
    // TODO: 重複削除
    var id = $(this).data('id');
    var title = $(this).data('title');
    // 聴診音一覧から要素を削除する
    $(this).closest('tr').remove();
    var lib_type = $(this).data('lib_type');
    // var content_count = $('#'+lib_type+'__tbody tr').length;
    var selector_id = '#' + lib_type + '__selector';
    // if(content_count<=0){
    //   $('#'+lib_type+'_quiz_choice__tbody').empty();
    // }
    // 超心音のセレクトボックスに要素を戻す
    if ($(selector_id + ' option[value=' + id + ']').length == 0)
      $(selector_id).append($('<option>').val(id).text(title));
    // valueでソートする
    var $options = $(selector_id + ' option');
    // $options.sort(function(a, b) {
    //   a = a.value;
    //   b = b.value;
    //   return a-b;
    // });
    $(selector_id).html($options);
    // 追加した要素を選択する
    $(selector_id).val(id);
    // 追加ボタンを押せるようにする
    $('#add_stetho_sound＿btn').prop('disabled', false);
  });

  // --------------------------------コンテンツ--------------------------------
  // コンテンツ追加・編集画面の音の説明（画像）の「画像を追加ボタン」
  $('#stetho_sound_images_form #add_images__btn').on('click', function () {
    var field = $(this).attr("data-field");
    var lang = $(this).attr("data-lang");
    var row = '';
    row += '<tr>';
    row += '  <td>';
    row += '    <input type="hidden" name="image_ids[]" value="" />';
    row += '    <input type="hidden" name="image_paths[]" value="" />';
    row += '    <input type="file" id="image_files-fields" name="image_files[]" value="" accept=".jpg,.png,image/jpeg,image/png,video/mp4"/>';
    row += '    <img src="" height="220" style="display:none;" class="stetho-image">';
    row += '    <iframe class="iframe_video" src="" frameborder="0" allowfullscreen style="display:none;"></iframe>';
    row += '  </td>';
    row += '  <td>';

    if (field == "explanatory") {
      row += '    <input type="hidden" class="form-control" name="image_titles[]" value="" />';
      row += '    <input type="hidden" class="form-control" name="lang[]" value="' + lang + '" />';
    } else {
      row += '    <input type="text" class="form-control" name="image_titles[]" value="" />';
      row += '    <input type="hidden" class="form-control" name="lang[]" value="' + lang + '" />';
    }

    row += '  </td>';
    row += '  <td class="pull-right">';
    row += '    <a class="btn btn-sm btn-danger" id="remove_images__btn">削除</a>';
    row += '    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />';
    row += '  </td>';
    row += '</tr>';
    // 画像ファイルのInputフォームを追加
    $('#stetho_sound_images_form #sortable_tbody' + ((lang != undefined)? "_" + lang : "")).append(row).on('click', '#remove_images__btn', function () {
      // コンテンツ追加・編集画面の音の説明（画像）の削除
      var id = $(this).closest('tr').find('input[name="image_ids[]"]').val();
      if (id) {
        $(this).closest('tbody').append('<input type="hidden" name="remove_image_ids[]" value="' + id + '"/>');
      }
      $(this).parents('tr').remove();
    }).on('change', '#image_files-fields', function (e) {
      // 一時的に画像を表示する
      // input type = file の次のタグは img 
      if (e.target.files[0].type == "video/mp4") {
        // img
       
       
      
        $(this).next().attr('src', "").hide();
        // iframe
        $(this).next().next().attr('src', URL.createObjectURL(e.target.files[0])).show();

        //prevent autoplay
        let iframes = $("iframe.iframe_video");
        $.each(iframes, function (i, video) {                
          video.onload = function () {
            var frameContent = $(video).contents().find('body').html();
            $(video).contents().find('body').html(frameContent.replace('autoplay=""', ""));
            $(video).contents().find('video').css('width','200px');
        }

        });
      } else if (e.target.files[0].type == "image/jpeg" || e.target.files[0].type == "image/png" || e.target.files[0].type == "image/gif") {
        // img
        $(this).next().attr('src', URL.createObjectURL(e.target.files[0])).show();
        // iframe
        $(this).next().next().attr('src', "").hide();
      }
    });
  });

  $('.explanatory_file_wrapper').on('change', '#explanatory_image-file-input', function (e) {
    //console.log("change",$(this).data('wrapper'));
    var file_wrapper = '.explanatory_file_wrapper .'+$(this).data('wrapper');
    $(file_wrapper).empty();
    // 一時的に画像を表示する
    // input type = file の次のタグは img 
    $.each(e.target.files,function( index,file ) {
      var row = '';
      if (file.type == "video/mp4") {
        row += '<iframe src="'+URL.createObjectURL(file)+'" frameborder="0" allowfullscreen"></iframe>';
      } else if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/gif") {
        row += '<img src="'+URL.createObjectURL(file)+'" height="220" class="stetho-image">';
      }
      $(file_wrapper).append(row);
    });
  });

  $('#stetho_sound_images_form #sortable_tbody, #sortable_tbody_ja, #sortable_tbody_en').on('click', '#remove_images__btn', function () {
    // コンテンツ追加・編集画面の音の説明（画像）の削除
    var id = $(this).closest('tr').find('input[name="image_ids[]"]').val();
    if (id) {
      $(this).closest('tbody').append('<input type="hidden" name="remove_image_ids[]" value="' + id + '"/>');
    }
    $(this).parents('tr').remove();
  }).on('change', '#image_files-fields', function (e) {
    // 一時的に画像を表示する
    // input type = file の次のタグは img 
    if (e.target.files[0].type == "video/mp4") {
      // img
      $(this).next().attr('src', "").hide();
      // iframe
      $(this).next().next().attr('src', URL.createObjectURL(e.target.files[0])).show();
    } else if (e.target.files[0].type == "image/jpeg" || e.target.files[0].type == "image/png" || e.target.files[0].type == "image/gif") {
      // img
      $(this).next().attr('src', URL.createObjectURL(e.target.files[0])).show();
      // iframe
      $(this).next().next().attr('src', "").hide();
    }
  });

  // 聴診音, 画像ファイルを一時的に表示する
  $('#sound_file-field, #image-field, #lib_video-file-input').on('change', function (e) {
    // input type = file の次のタグは audio 
    //console.log(e.target.files[0].type)
    if (e.target.files[0].type == "video/mp4") {
      $("#is_video_show").show();
    } else if (e.target.files[0].type == "audio/mp3") {
      $("#is_video_show").hide();
      $("#is_video_show_1").prop("checked", false);
      $("#is_video_show_0").prop("checked", true);
    }
    $(this).next().fadeIn('fast').attr('src', URL.createObjectURL(e.target.files[0]));
  });

  // 監修コメントの追加ボタンを有効無効にする
  $('#comments__textarea').on('change keyup paste', function (e) {
    var len = $(this).val();
    if (len.length > 0) {
      $('#add_comments__btn').prop("disabled", false);
    }
    else {
      $('#add_comments__btn').prop("disabled", true);
    }
  });

  // 監修コメントの編集ボタン押下でコメント入力を有効にする
  var on_click_edit_comment_btn = function (e) {
    // コメント項目の要素
    var $item = $(this).closest('.comment_item');
    // コメント項目表示欄
    var $comment_text = $item.find('.comment_text');
    // コメント入力欄
    var $textarea = $item.find('textarea');
    // 更新ボタン
    var $update_btn = $item.find('.update_comment__btn');

    // 表示切替
    $comment_text.hide();
    $textarea.show();
    $update_btn.show();

    var id = $item.data('id');
    $update_btn.on('click', function (e) {
      // 多重更新防止
      $update_btn.off('click');
      $update_btn.prop('disable');
      // 更新処理
      var text = $textarea.val();
      if (text.length > 0) {
        var data = { "text": text };
        var stetho_sound_id = $('#stetho_sound_id').val();
        var url = '/admin/stetho_sounds/' + stetho_sound_id + '/comments/' + id;

        $.ajax({
          url: url,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: JSON.stringify(data),
          contentType: 'application/json',
          dataType: 'json',
          type: 'PUT',
          success: function (data) {
            //console.log(data);
            // 表示項目更新
            $comment_text.text(data.text);
            $textarea.val(data.text);
            // 表示切替
            $comment_text.show();
            $textarea.hide();
            $update_btn.hide();
          },
          error: function (xhr, statusText, errorThrown) {
            $.error(xhr.responseText);
          }
        });
      }
    });
  };

  // 監修コメントの編集ボタン押下でコメント入力を有効にする
  $('.edit_comment__btn').on('click', on_click_edit_comment_btn);

  var on_click_remove_comment_btn = function (e) {
    var ok = confirm('本当に削除しますか？');
    if (ok) {
      // 多重投稿防止
      var $item = $(this).closest('.comment_item');
      var $delete_btn = $(this).hide();
      var $edit_btn = $item.find('.edit_comment__btn').hide();
      var $comment_text = $item.find('.comment_text').show();
      var $textarea = $item.find('textarea').hide();
      var $update_btn = $item.find('.update_comment__btn').hide();

      var stetho_sound_id = $('#stetho_sound_id').val();
      var id = $item.data('id');
      var url = '/admin/stetho_sounds/' + stetho_sound_id + '/comments/' + id;

      // 暗くする
      $item.css('opacity', '0.5');
      // 削除処理
      $.ajax({
        url: url,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: 'application/json',
        dataType: 'json',
        type: 'DELETE',
        success: function (data) {
          //console.log(data);
          // コメント項目表示欄を削除
          $item.hide('fast', function () { $item.remove(); });
        },
        error: function (xhr, statusText, errorThrown) {
          $.error(xhr.responseText);
          $delete_btn.show();
          $edit_btn.show();
          $comment_text.show();
          $textarea.hide();
          $update_btn.hide();
          $item.css('opacity', '1');
        }
      });
    }
  };

  // 監修コメントの削除ボタン押下
  $('.remove_comment__btn').on('click', on_click_remove_comment_btn);

  // 監修コメント追加ボタン押下
  $('#add_comments__btn').on('click', function (e) {
    var text = $('#comments__textarea').val()
    if (text.length > 0) {
      var data = { "text": text };
      var id = $('#stetho_sound_id').val();
      $.ajax({
        url: '/admin/stetho_sounds/' + id + '/comments',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(data),
        type: 'POST',
        contentType: 'application/json',
        success: function (data) {
          //console.log(data);
          var html = '';
          html += '<div class="comment_item" style="padding-bottom: 1em; margin-bottom: 1em; border-bottom: 1px solid gray;" data-id="' + data.id + '">';
          html += '    <div style="float:left;">' + data.created_at.date.replace('.000000', '') + '&nbsp;&nbsp;<strong>' + data.name + '</strong></div>';
          html += '    <div style="float:right;"><a class="btn btn-sm btn-default edit_comment__btn">編集</a>&nbsp;<a class="btn btn-sm btn-danger remove_comment__btn">削除</a></div>';
          html += '    <div style="clear: both;"></div>';
          html += '  <p class="comment_text" data-id="' + data.id + '">' + data.text + '</p>';
          html += '  </p>';
          html += '  <textarea name="comment" class="form-control" style="display: none;">' + data.text + '</textarea>';
          html += '  <button type="button" class="btn btn-sm btn-primary update_comment__btn" style="margin-top: 4px; display: none;">更新</button>';
          html += '</div>';
          //console.log(html);
          $item = $('#comments_area').append(html).find('.comment_item:last-child');
          $item.find('.edit_comment__btn').on('click', on_click_edit_comment_btn)
          $item.find('.remove_comment__btn').on('click', on_click_remove_comment_btn);
          $('#comments__textarea').val('');
        }
      });
    }
  });

  // 回答説明を選択する
  $('#answer_fillin-tab').on('click', function (e) {
    var quiz_optional = $("input[name='is_optional']:checked").val();
    changeQuizTypeTab(quiz_optional);
  });

  // クリックしてタブを埋める
  $('#answer_otional-tab').on('click', function (e) {
    var quiz_optional = $("input[name='is_optional']:checked").val();
    changeQuizTypeTab(quiz_optional);
  });

  //on change quiz type
  $('input[type=radio][name=is_optional]').on('change', function() {
    changeQuizTypeTab($(this).val());
  });

  //quiz change active tab base on quiz type
  function changeQuizTypeTab(quiz_optional){
    quiz_optional = (quiz_optional==1)?true:false;
    if(quiz_optional){//optional
      $('#answer_otional-tab').addClass("active");
      $('.answer_fill_in').hide();
      $('.answer_optional').show();
      $('#answer_fillin-tab, #answer_fillin-tab >.nav-link').removeClass("active");
      $('#answer_fillin-tab, #answer_fillin-tab >.nav-link').addClass("disabled");
      $('#answer_otional-tab, #answer_otional-tab >.nav-link').removeClass("disabled");
    }else{//fill-in
      $('#answer_fillin-tab').addClass("active");
      $('.answer_fill_in').show();
      $('.answer_optional').hide();
      $('#answer_otional-tab, #answer_otional-tab >.nav-link').removeClass("active");
      $('#answer_otional-tab, #answer_otional-tab >.nav-link').addClass("disabled");
      $('#answer_fillin-tab, #answer_fillin-tab >.nav-link').removeClass("disabled");
    }
  }
  
  //get quiz optional value
  var quiz_optional = $("input[name='is_optional']:checked").val();
  //on load change quiz tab base on type
  if(quiz_optional!==null){
    changeQuizTypeTab(quiz_optional);
  }

});
