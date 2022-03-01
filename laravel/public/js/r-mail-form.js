$(document).ready(function(){
/*
    // 送信前のチェック
    $('#r-mail-form').submit(function(){
    });
*/

    // 性別(男性)
    $("#g_male").on("click", function() {
        $('input[name="gender"]').attr('checked', false);   // 全て外す
        $('input[value="male"]').prop('checked', true); // 男性
    });

    // 性別(女性)
    $("#g_female").on("click", function() {
        $('input[name="gender"]').attr('checked', false);   // 全て外す
        $('input[value="female"]').prop('checked', true);   // 女性
    });

    $("#i_none").on("click", function() {
        $('input[name="identification"]').attr('checked', false);   // 全て外す
        $('input[value="none"]').prop('checked', true); // 今は選択しない
        setIdRadio("none");
    });

    $("#i_image").on("click", function() {
        $('input[name="identification"]').attr('checked', false);   // 全て外す
        $('input[value="image"]').prop('checked', true); // 画像
        setIdRadio("image");
    });

    $("#i_tel").on("click", function() {
        $('input[name="identification"]').attr('checked', false);   // 全て外す
        $('input[value="tel"]').prop('checked', true); // 電話
        setIdRadio("tel");
    });

    // 職種　最初の来た時の設定
    var kindchecked = $('[name="kind"]').val();
    setKind(kindchecked);

    // 職種　セレクタが変更された場合
    $('[name="kind"]').change( function() {;
        var kind = $(this).val();
        setKind(kind);
    });

    // 本人確認方法　最初に来た時の設定
    var radiochecked = $('input[name="identification"]:checked').val();
    setIdRadio(radiochecked);

    // 本人確認方法　ラジオボタンが変更された場合
    $('input[name="identification"]:radio').change( function() {
        var radio = $(this).val();
        setIdRadio(radio);
    });

    // アップロードする画像の表示
    $('#show-file').on('change', function(e) {
        var file = e.target.files[0];   // ファイル

        // ファイルのブラウザ上でのURLを取得
        var blobUrl = window.URL.createObjectURL(file);

        $('#file-preview').css('width','300px');   // 表示サイズを設定
        $('#file-preview').attr('src', blobUrl);    // img要素に表示

        $("#noimage").text(''); // エラー表示を削除
    });

    /* ローカル関数 */
    /* 職種 */
    function setKind(kind) {
        var current = $('input[name="identification"]:checked').val();

        switch(kind) {
            case "1":
                $('input[name="caremane[]"]').prop("checked",false);

                $(".li_id_1").css('display','block');
                $(".li_id_2").css('display','block');
                $(".li_rowitem_1").css('display','block');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','none');
                $(".li_caremane_1").css('display','none');

                $("#group_name").text($("#group_name").data("employer_field"));
                $("#image_text").text('ご自身の医師免許証の画像を添付してください。');
                break;
            case "2":
                $('input[name="caremane[]"]').prop("checked",false);

                if(current == "tel") {
                    $('input[name="identification"]').val(['none']);
                }

                $(".li_id_1").css('display','block');
                $(".li_id_2").css('display','none');
                $(".li_rowitem_1").css('display','none');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','none');
                $(".li_caremane_1").css('display','none');

                $("#group_name").text($("#group_name").data("employer_field"));
                $("#image_text").text('ご自身の医療従事者免許証・学生証の画像を添付してください。');
                break;
            case "3":
            case "7":
            case "8":
            case "9":
            case "10":
            case "11":
            case "12":
            case "15":
            case "17":
            case "23":
            case "25":
            case "26":
                $(".li_id_1").css('display','none');
                $(".li_id_2").css('display','none');
                $(".li_rowitem_1").css('display','none');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','none');
                $(".li_caremane_1").css('display','block');

                $("#group_name").text($("#group_name").data("employment_name"));
                $("#image_text").text('ご自身の医療従事者免許証・学生証の画像を添付してください。');
                break;
            case "4":
            case "13":
            case "14":
            case "16":
            case "18":
            case "21":
            case "22":
            case "24":
                if(current == "tel") {
                    $('input[name="identification"]').val(['none']);
                }

                $(".li_id_1").css('display','block');
                $(".li_id_2").css('display','none');
                $(".li_rowitem_1").css('display','none');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','none');
                $(".li_caremane_1").css('display','block');

                $("#group_name").text($("#group_name").data("employment_name"));
                $("#image_text").text('ご自身の医療従事者免許証の画像を添付してください。');
                break;
            case "5":
            case "6":
                $('input[name="caremane[]"]').prop("checked",false);

                if(current == "tel") {
                    $('input[name="identification"]').val(['none']);
                }

                $(".li_id_1").css('display','block');
                $(".li_id_2").css('display','none');
                $(".li_rowitem_1").css('display','none');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','block');
                $(".li_caremane_1").css('display','none');

                $("#group_name").text($("#group_name").data("schoolname"));
                $("#image_text").text('ご自身の学生証の画像を添付してください。');
                break;
            case "19":
            case "20":
                $('input[name="caremane[]"]').prop("checked",false);

                $(".li_id_1").css('display','none');
                $(".li_id_2").css('display','none');
                $(".li_rowitem_1").css('display','none');
                $(".li_group_1").css('display','block');
                $(".li_school_1").css('display','none');
                $(".li_caremane_1").css('display','none');

                $("#group_name").text($("#group_name").data("employment_name"));
                $("#image_text").text('ご自身の医療従事者免許証・学生証の画像を添付してください。');
                break;
            default:
                break;
        }
    }

    /* 本人確認方法 */
    function setIdRadio(radio) {
        if(radio == 'none') {    // 今は選択しない
            $(".valuenone").css('display','block'); // 表示
            $(".valueimage").css('display','none'); // 非表示
            $(".valuetel").css('display','none'); // 非表示
        } else if(radio == 'image') {    // 画像による確認
            $(".valuenone").css('display','none'); // 非表示
            $(".valueimage").css('display','block'); // 表示
            $(".valuetel").css('display','none'); // 非表示
        } else if(radio == 'tel') { // 電話による確認
            $(".valuenone").css('display','none'); // 表示
            $(".valueimage").css('display','none'); // 非表示
            $(".valuetel").css('display','block'); // 表示
        }
    }
});
