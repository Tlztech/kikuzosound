$(window).on('load', function (e) {
    // 職種　最初の来た時の設定
    var kindchecked = $('[name="kind"]').val();
    setDisp(kindchecked);

    function setDisp(kind) {
        switch (kind) {
            case "1":
                $(".li_id_1").css('display', 'block');
                $(".li_rowitem_1").css('display', 'block');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'none');
                $(".li_caremane_1").css('display', 'none');

                $("#group_name").text($("#group_name").data("employer_field"));
                break;
            case "2":
                $(".li_id_1").css('display', 'block');
                $(".li_rowitem_1").css('display', 'none');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'none');
                $(".li_caremane_1").css('display', 'none');

                $("#group_name").text($("#group_name").data("employer_field"));
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
                $(".li_id_1").css('display', 'none');
                $(".li_rowitem_1").css('display', 'none');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'none');
                $(".li_caremane_1").css('display', 'block');

                $("#group_name").text($("#group_name").data("employment_name"));
                break;
            case "4":
            case "13":
            case "14":
            case "16":
            case "18":
            case "21":
            case "22":
            case "24":
                $(".li_id_1").css('display', 'block');
                $(".li_rowitem_1").css('display', 'none');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'none');
                $(".li_caremane_1").css('display', 'block');

                $("#group_name").text($("#group_name").data("employment_name"));
                break;
            case "5":
            case "6":
                $(".li_id_1").css('display', 'block');
                $(".li_rowitem_1").css('display', 'none');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'block');
                $(".li_caremane_1").css('display', 'none');

                $("#group_name").text($("#group_name").data("schoolname"))
                break;
            case "19":
            case "20":
                $(".li_id_1").css('display', 'none');
                $(".li_rowitem_1").css('display', 'none');
                $(".li_group_1").css('display', 'block');
                $(".li_school_1").css('display', 'none');
                $(".li_caremane_1").css('display', 'none');

                $("#group_name").text($("#group_name").data("employment_name"));
                break;
            default:
                break;
        }
    }
});
