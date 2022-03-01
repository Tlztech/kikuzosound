const SITEURL = window.location.protocol + "//" + window.location.hostname;
$(document).ready(function () {
    var current_elm
    var vid_open = false
    var prev_log = {log_id : null};
    var startLibrarLog = function (data, event ) {
        $.ajaxSetup({
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });
        $.ajax({
            url: "./ajaxcreateLibraryLog",
            type: "post",                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     data: { 
                "library_id": data.new_lib.attr('data-id'),
                "prev_id": data.lib_id,
                'lib_name' : data.lib_name,
                "prev_log" : prev_log.log_id,
                "click" : event
            },
            cache: false,
            dataType: "text"
        })
            .done(function (res) { 
                prev_log =  JSON.parse(res)
                if(event == 'end'){
                    prev_log.log_id = null
                }
            })
            .fail(function (jqxhr, status, error) { });
    }
    
    var toggleAccordion = function ($elm) {
        current_elm = $elm
        $library_id = $elm.closest(".sound_box");
        if (vid_open == true) {
            // console.log('vid_opened')
            $elm.toggleClass("open_active");

            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_title .name").animate({ color: '#000000' }, 300);
            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_accordion_open").removeClass("open_active");

            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_box_inner").removeClass("sound_conts_open", 1000);

            $elm.closest(".sound_box").find(".sound_box_inner").toggleClass("sound_conts_open", 1000);
            $elm.toggleClass("open_active");


            if ($elm.hasClass('open bro 1')) {
                $elm.find('.name').animate({ color: '#CCCCCC' }, 300);
            } else {
                $elm.find('.name').animate({ color: '#000000' }, 300);
            }
            vid_open = false
        } else {
            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_title .name").animate({ color: '#000000' }, 300);
            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_accordion_open").removeClass("open_active");

            $elm.closest(".sound_box").siblings(".sound_box").find(".sound_box_inner").removeClass("sound_conts_open", 1000);

            $elm.closest(".sound_box").find(".sound_box_inner").toggleClass("sound_conts_open", 1000);
            $elm.toggleClass("open_active");

            if ($elm.hasClass('open_active')) {
                $elm.find('.name').animate({ color: '#CCCCCC' }, 300);
            } else {
                $elm.find('.name').animate({ color: '#000000' }, 300);
                //reset adio,videos when close
                $('video,audio').each(function () {
                    $(this).get(0).currentTime = 0;
                    $(this).get(0).pause();
                });
            }
        }

        //open aus acordion
        if ($elm.hasClass('ausculaide')) {
            setupDataBodyFunction($elm, $elm.attr("data-result"))
        }
      
        let text = $library_id.find(".tag").text().trim()
        console.log(text)
        if( !prev_log.log_id )
            startLibrarLog(
                { 'lib_id' : null, 
                    'new_lib' : $library_id,
                    'lib_name': text 
                }, 'start');
        else if( prev_log.sendlib_id != $library_id.attr('data-id') ) 
            startLibrarLog(
                { 'lib_id' : prev_log.sendlib_id, 
                    'new_lib' : $library_id,
                    'lib_name': text 
                }, 'end_start');
        else if( prev_log.sendlib_id == $library_id.attr('data-id') )
            startLibrarLog(
                { 'lib_id' : null, 
                    'new_lib' : $library_id,
                    'lib_name': text 
                }, 'end');
        if( !prev_log.log_id )
            startLibrarLog({ 'lib_id' : null, 'new_lib' : $library_id }, 'start');
        else if( prev_log.sendlib_id != $library_id.attr('data-id') ) 
            startLibrarLog({ 'lib_id' : prev_log.sendlib_id, 'new_lib' : $library_id }, 'end_start');
        else if( prev_log.sendlib_id == $library_id.attr('data-id') )
            startLibrarLog({ 'lib_id' : null, 'new_lib' : $library_id }, 'end');
    }


    var toggleVideo = function ($elm) {
        $elm.closest(".sound_box").siblings(".sound_box").find(".sound_title .name").animate({ color: '#000000' }, 300);
        $elm.closest(".sound_box").siblings(".sound_box").find(".sound_box_inner").removeClass("sound_conts_open", 1000);
        $elm.closest(".sound_box").find(".sound_box_inner").toggleClass("sound_conts_open", 1000);

    }

    let isFront = false
    function setupDataBodyFunction(e, sound ) {
        sound = JSON.parse(sound)
        let isOpen = $("#aus_" + sound.id).hasClass("open_active")
        let url = null
        localStorage.setItem("body", "front");
        // on load load body 
        resetAus(sound);///reset storage and defaults
        if (isOpen) {
            // $(".bodyFrame").hide();
            // $("#body-"+sound.id).show();
            $("#ausculaide_app")
            // .detach()
            .appendTo('#body-' + sound.id);
            //resetPrevFrameSound(localStorage.getItem("sthetho_id"));
            localStorage.setItem("type", sound.type);
            last_stethoId = localStorage.getItem("sthetho_id");
            if (last_stethoId != sound.id) { // set previous content to default
                localStorage.removeItem("slider_" + last_stethoId); // set to default slider value
                localStorage.removeItem("playrate_" + last_stethoId); // set default playrate
            }

            localStorage.setItem("sthetho_id", sound.id);
            if (sound.type === 2) {//heart
                if (!$("#heart_icon_" + sound.id).attr("disabled")) {
                    $("#heart_icon_" + sound.id).removeClass("btn-heart-grey-icon");
                    $("#heart_icon_" + sound.id).addClass("btn-heart-icon");
                    localStorage.setItem("heart", 1);
                } else {
                    localStorage.setItem("heart", 0);
                }
            }
            if (sound.type === 1) {//lungs
                if (!$("#lung_icon_" + sound.id).attr("disabled")) {
                    $("#lung_icon_" + sound.id).removeClass("btn-lung-grey-icon");
                    $("#lung_icon_" + sound.id).addClass("btn-lung-icon");
                    localStorage.setItem("lung", 1);
                } else {
                    localStorage.setItem("lung", 0);
                }
            }
        } else {
            var stethoId = localStorage.getItem("sthetho_id");
            localStorage.removeItem("slider_" + stethoId); // set to default slider value
            localStorage.removeItem("playrate_" + stethoId); // set default playrate
            //resetPrevFrameSound(localStorage.getItem("sthetho_id"));
            localStorage.setItem("sthetho_id", "");
        }
        loadBodyFunction(sound, isOpen)
        $("#bswitch_" + sound.id).click((e) => {
            handleClickSwitchBody(e, sound)
        })

        // when click heart icon
        $('.btn-heart').off().on('click', function (event) {
            $(".btn-points").each(function (index) {
                $(this).removeClass("btn-point-active");
            });
            event.stopPropagation();
            var body = localStorage.getItem("body");
            var pulse = localStorage.getItem("pulse");
            if (body == "front") {
                var className = $(this).attr("class");
                if (className.includes("btn-heart-icon")) {
                    if (sound.type !== 2) {//disabled if not heart type
                        $(this).removeClass("btn-heart-icon");
                        $(this).addClass("btn-heart-grey-icon");

                        localStorage.setItem("heart", 0);
                    }
                    if (pulse == 1) {
                        $("#pulse_icon_" + sound.id).removeClass("btn-pulse-icon");
                        $("#pulse_icon_" + sound.id).addClass("btn-pulse-grey-icon");
                        localStorage.setItem("pulse", 0);
                    }
                } else {
                    $(this).removeClass("btn-heart-grey-icon");
                    $(this).addClass("btn-heart-icon");
                    localStorage.setItem("heart", 1);
                }
                loadBodyFunction(sound, isOpen)
            }
        });

        $(".pulse_btn_close").on('click', function (event) {
            if ($("#pulse_checkbox_" + sound.id).is(":checked")) {
                localStorage.setItem("pulse_prompt", 1)
            }
            $("#pulseModal_" + sound.id).hide();
        });

        // when click pulse icon
        $(".btn-pulse").off().on('click', function (event) {
            if (localStorage.getItem("pulse_prompt") != 1 && localStorage.getItem("pulse") == 0) {
                $("#pulseModal_" + sound.id).show();
            }

            $(".btn-points").each(function (index) {
                $(this).removeClass("btn-point-active");
            });
            if ($("#heart_icon_" + sound.id).attr("disabled")) { // check if heart is disabled
                return 0;
            }
            var body = localStorage.getItem("body");
            var id = localStorage.getItem("sthetho_id");
            const heart = localStorage.getItem("heart");
            if (body == "front") {
                var className = $(this).attr("class");
                if (className.includes("btn-pulse-icon")) {
                    $(this).removeClass("btn-pulse-icon");
                    $(this).addClass("btn-pulse-grey-icon");
                    localStorage.setItem("pulse", 0);
                } else {
                    $(this).removeClass("btn-pulse-grey-icon");
                    $(this).addClass("btn-pulse-icon");
                    localStorage.setItem("pulse", 1);

                    // turn on heart icon also
                    $("#heart_icon_" + sound.id).removeClass("btn-heart-grey-icon");
                    $("#heart_icon_" + sound.id).addClass("btn-heart-icon");
                    localStorage.setItem("heart", 1);
                }
            }
            loadBodyFunction(sound, isOpen)
        });

        // when click lung icon
        $(".btn-lung").off().on('click', function (event) {
            $(".btn-points").each(function (index) {
                $(this).removeClass("btn-point-active");
            });
            if (sound.type !== 1) { // check if type is not lungs and heart is disabled
                if ($("#heart_icon_" + sound.id).attr("disabled")) {
                    return 0;
                }
            }
            var className = $(this).attr("class");
            if (className.includes("btn-lung-icon")) {
                if (sound.type !== 1) {//disabled if not lungs
                    $(this).removeClass("btn-lung-icon");
                    $(this).addClass("btn-lung-grey-icon");
                    localStorage.setItem("lung", 0);
                }
            } else {
                $(this).removeClass("btn-lung-grey-icon");
                $(this).addClass("btn-lung-icon");
                localStorage.setItem("lung", 1);
            }
            loadBodyFunction(sound, isOpen)
        });
        //dummy reload icon outside aus frame for fullscreen
        $(".btn-reload-icon").off().on('click', function (event) {
            $(".bodyFrame").contents().find("#btn-case-top").click();
        });
    }

    function resetAus(sound) {
        console.log("reset")
        $("#heart_icon_" + sound.id).removeClass("btn-heart-icon btn-heart-grey-icon").addClass("btn-heart-grey-icon");
        $("#pulse_icon_" + sound.id).removeClass("btn-pulse-icon btn-pulse-grey-icon").addClass("btn-pulse-grey-icon");
        $("#lung_icon_" + sound.id).removeClass("btn-lung-icon btn-lung-grey-icon").addClass("btn-lung-grey-icon");

        $(".btn-points").each(function (index) {
            $(this).removeClass("btn-point-active");
        });
        localStorage.setItem("heart", 0);
        localStorage.setItem("pulse", 0);
        localStorage.setItem("lung", 0);
        localStorage.setItem("body", "front");
        localStorage.setItem("type", "");
    }
    $( ".audio_slider" ).slider();

    // function resetPrevFrameSound(prev_active_id) {
    //     $("#body-" + prev_active_id).attr("src", "")
    // }

    function loadBodyFunction(sound, isOpen, is_exam_quiz) {
        const body = localStorage.getItem("body");
        const heart = localStorage.getItem("heart");
        const pulse = localStorage.getItem("pulse");
        const lung = localStorage.getItem("lung");
        let url = ""
        let case_type = "heart";

        if (isOpen) {

            // heart front
            const heartFActive = body === "front" && sound.type === 2 && heart == 1 && (lung == 0 || !lung) && (pulse == 0 || !pulse)
            const heartFAndLungsActive = body === "front" && sound.type === 2 && heart == 1 && lung == 1 && (pulse == 0 || !pulse)
            const heartFAndPulseActive = body === "front" && sound.type === 2 && heart == 1 && pulse == 1 && (lung == 0 || !lung)
            const heartFPulseLungsActive = body === "front" && sound.type === 2 && heart == 1 && pulse == 1 && lung == 1

            // lungs front
            const lungsFActive = body === "front" && sound.type === 1 && lung == 1 && (heart == 0 || !heart) && (pulse == 0 || !pulse)
            const lungsAndHeartFActive = body === "front" && sound.type === 1 && lung == 1 && heart == 1 && (pulse == 0 || !pulse)

            // lungs back
            const lungsBActive = body === "back" && sound.type === 1 && lung == 1 && (heart == 0 || !heart) && (pulse == 0 || !pulse)


            if (heartFActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart"
                $('.audio_slider').slider('enable');
                case_type = "heart";
            }

            if (heartFAndPulseActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart_pulse"
                $('.audio_slider').slider('enable');
                case_type = "heart_pulse";
            }

            if (heartFAndLungsActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart_lungs"
                $('.audio_slider').slider('disable');
                case_type = "heart_lungs";
            }

            if (heartFPulseLungsActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart_pulse_lungs"
                $('.audio_slider').slider('disable');
                case_type = "heart_pulse_lungs";
            }


            if (lungsFActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=lungs"
                $('.audio_slider').slider('disable');
                case_type = "lungs";
                $('.btn-points').prop("disabled", true);
            }


            if (lungsAndHeartFActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart_lungs"
                $('.audio_slider').slider('disable');
                case_type = "heart_lungs";
            }


            if (lungsBActive) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=lungs_back"
                $('.audio_slider').slider('disable');
                case_type = "lungs_back";
            }

            if (
                !heartFActive &&
                !heartFAndLungsActive &&
                !heartFAndPulseActive &&
                !heartFPulseLungsActive &&
                !lungsFActive &&
                !lungsAndHeartFActive &&
                !lungsBActive
            ) {
                // url = SITEURL + "/ausculaide_app?id="+sound.id+"&case=heart"
                $('.audio_slider').slider('enable');
                case_type = "heart";
            }

            if(is_exam_quiz){
                $('.audio_slider').slider('enable');
            }

            $("#body-" + sound.id).data("case", case_type)
            //loadAus(sound.id, is_exam_quiz);
            loadAjaxAusculaide(sound,case_type);
            console.log("open")
        }else{
            stopAllSound();
        }
        setTimeout(function () {
            setAusSize();
        }, 200);
        //$("#body-"+sound.id).attr("src", url)
        //$("#body-" + sound.id).data("case", case_type)
        //$("#ausculaide_load_btn_" + sound.id).click();
        setSpeedSlider();//set aus audio speed slider
        if (sound.type == 2) {
            $('.btn-points').prop("disabled", false);
        } else {
            $('.btn-points').prop("disabled", true);
        }

        if ($("#heart_icon_" + sound.id).attr("disabled")) {
            $('.btn-points').prop("disabled", true);
            $('.audio_slider').slider('disable');
        }
    }

    function loadAjaxAusculaide(sound,case_type){
        console.log("hey")
        var url =  SITEURL + "/ajax_ausculaide_app"
        $.ajax({
            data:{
                "id": sound.id,
                "case": case_type,
            },
            url: url,
            type: 'GET',
            success: function(res) {

                // get the ajax response data

                // update modal content here
                $('#body-'+sound.id).html();
                $('#body-'+sound.id).html(res);
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
    }

    //aus audio speed slider
    function setSpeedSlider() {
        var stethoId = localStorage.getItem("sthetho_id");
        var speed = localStorage.getItem("slider_" + stethoId);
        var labels = [48, 54, 60, 72, 84, 96, 108, 120];
        $(".audio_slider").slider({
            min: 0,
            max: 7,
            value: speed ? speed : 2,
            step: 1
        })
            .slider("pips", {
                rest: "label",
                labels: labels
            });
    }

    function handleClickSwitchBody(e, sound, exam_quiz) {

        let isOpen = exam_quiz ? true : $("#aus_" + sound.id).hasClass("open_active");
        if (isOpen) {
            if (!isFront) {
                // keep heart icon active state
                if (localStorage.getItem("type") == 1) { // lung type
                    if (localStorage.getItem("heart") == 1) {
                        localStorage.setItem("is_heart", 1); // keep heart state
                    } else {
                        localStorage.removeItem("is_heart");
                    }
                }

                if (!$("#lung_icon_" + sound.id).attr("disabled")) {
                    $("#lung_icon_" + sound.id).removeClass("btn-lung-grey-icon");
                    $("#lung_icon_" + sound.id).addClass("btn-lung-icon");
                    localStorage.setItem("lung", 1);
                } else {
                    localStorage.setItem("lung", 0);
                }
                $("#heart_icon_" + sound.id).removeClass("btn-heart-icon")
                $("#heart_icon_" + sound.id).addClass("btn-heart-grey-icon");
                localStorage.setItem("heart", 0);
            } else {
                if (exam_quiz) {//set default in exam quiz
                    localStorage.setItem("lung", 1);
                    localStorage.setItem("heart", 1);
                    localStorage.setItem("pulse", 0);
                }
            }
            localStorage.setItem("body", isFront ? "front" : "back");
            isFront = !isFront
            loadBodyFunction(sound, isOpen);
            // turn on heart icon automatically
            if (localStorage.getItem("is_heart") == 1) {
                $("#heart_icon_" + sound.id).trigger("click");       
            }
        }
    }


    // $('.bodyFrame').on('load', function() {
    //     var fulscreen_mode = localStorage.getItem("fullscreen");
    //     if(fulscreen_mode==1){
    //         $('.bodyFrame').contents().find('.points').addClass("__fullscreen");
    //     }
    //     //reload click
    //     $('.bodyFrame').contents().find('#btn-case-top').click(function() {
    //         setSpeedSlider();
    //     });

    // });
    $('#btn-case-top').click(function () {
        setSpeedSlider();
    });

    function getSoundSpeed(value) {
        switch (value) {
            case 0:
                return 0.8
            case 1:
                return 0.9
            case 2:
                return 1.0
            case 3:
                return 1.2
            case 4:
                return 1.4
            case 5:
                return 1.6
            case 6:
                return 1.8
            case 7:
                return 2.0
            default:
                return 1.0
        }
    }

    //Aus Fullscreen Mode
    FullScreenHelper.on(function () {
        var id = localStorage.getItem("sthetho_id");
        if (FullScreenHelper.state()) {//fullscreen mode
            console.log($(".bodymap-front").height())
            localStorage.setItem("fullscreen", 1);
            //set class for fullscreen mode
            $("#aus-img_wrapper_" + id).addClass("div_fullscreen");
            $("#body-" + id).addClass("aus_fullscreen_img");
            $(".aus-fullscreen-off").attr("style", "display:unset");
            $("#bswitch_" + id).addClass("__fullscreen");
            $('.bodyFrame').contents().find('.points').addClass("__fullscreen");
            $(".btn-reload-icon").addClass("btn-reload--fullscreen");
            setTimeout(function () {
                setAusSize(true);
                setFullscreenSwitch();
                setStethoPosition("zoom_in");
                $("#zoom_adjustment").data("width",$(".bodymap-front").width());
                $("#zoom_adjustment").data("height",$(".bodymap-front").height());
            }, 500);
        } else {//off fullscreen mode
            $("#aus-img_wrapper_" + id).removeClass("div_fullscreen");
            $("#body-" + id).removeClass("aus_fullscreen_img");
            $(".aus-fullscreen-off").attr("style", "display:none");
            $("#bswitch_" + id).removeClass("__fullscreen");
            $(".exit-fullscreen-overlay").hide();
            $('.bodyFrame').contents().find('.points').removeClass("__fullscreen");
            $(".btn-reload-icon").removeClass("btn-reload--fullscreen");
            var frame = document.getElementById("body-" + id);
            frame.style.width = "";
            frame.style.height = "";
            setTimeout(function () {
                setAusSize(true);
                setFullscreenSwitch();
                setStethoPosition("zoom_out");
            }, 500);
            localStorage.setItem("fullscreen", 0);
        }

    });

    //fix scope positon when zoom in/out
    function setStethoPosition(state){
    var scope = $('#stethoscope');
     var pos = scope.position();
     var adjust_point = {};
     if(state=="zoom_in"){
        adjust_point = getAnimationZoomPoints({cx:pos.left, cy:pos.top, r:0, type:""})
     }else{
        adjust_point = getAnimationZoomOutPoints({cx:pos.left, cy:pos.top})
     }
     var x = adjust_point.cx;
	 var y= adjust_point.cy;
     scope.css({top: y, left: x});
    }

    // when click fullscreen
    $(".btn-fullscreen-on").click(function () {
        var id = localStorage.getItem("sthetho_id");
        $("#aus-img_wrapper_" + id).addClass("div_fullscreen");
        $("#body-" + id).addClass("aus_fullscreen_img");
        $("#bswitch_" + id).addClass("__fullscreen");
        $('.bodyFrame').contents().find('.points').addClass("__fullscreen");
        var selector = "#aus-fullscreen-wrapper_" + id;
        FullScreenHelper.request(selector);
    });

    // when click fullscreen
    $(".aus-fullscreen-off").click(function () {
        $(".exit-fullscreen-overlay").show();
    });

    $(".fullscreen-btn-ok").click(function () {
        FullScreenHelper.exit();
        $(".exit-fullscreen-overlay").hide();
    });

    $(".fullscreen-btn-cancel").click(function () {
        $(".exit-fullscreen-overlay").hide();
    });

    $(window).on('resize', function () {
        setAusSize();
        $( "#stethoscope" ).draggable({});
    });
    function setAusSize(is_zoom = false) {
        var id = localStorage.getItem("sthetho_id");
        var fulscreen_mode = localStorage.getItem("fullscreen");
        var body_frame = $(".bodymap-frame.type-cardio");
        var body_image = $(".bodymap-front");
        var ausculaide_app_wrapper = $(".main_wrapper.ausculaide");
        var frame_width = $("#body-" + id).width();
        
        if (is_zoom && fulscreen_mode == 1) {
            var screen_width = window.screen.width;
            var screen_height = window.screen.height;
            if (screen_width > screen_height) {//landscape
                frame_width = screen_height;
                var zoom_adjustment = frame_width + Number((frame_width / 100) * 14);
                frame_width = zoom_adjustment
            } else {//portrait
                frame_width = screen_width;
            }
            $("#body-" + id).width(frame_width);
            $("#body-" + id).height(screen_height);
        }

        var prcent_x = (1 / 500) * 100;

        var prcent_body_x = (frame_width / 500) * 100;        
        var prcent_frame_y = (450 / 500) * 100;
        
        var body_x = Number((500 / 100) * prcent_body_x);
        var body_y = Number((400 / 100) * prcent_body_x);

        var body_size = Number((frame_width / 100) * prcent_x);
        $("#body-" + id).attr("data-size", body_size)

        body_frame.width(body_x).height(body_y);
        body_image.width(body_x).height(body_y);
        ausculaide_app_wrapper.width(body_x).height(body_y);
        // var doc = document.getElementById("ausculaide_app");
        // doc.style.zoom = body_size;
        $('.footer.ausculaide').css('zoom', body_size);

        //stetho size
        var stetho_percnt_x = ((64 / 500) * 100);
        var stetho_percnt_y = ((64 / 400) * 100);
        $("#stethoscope").width(Number((body_image.width() / 100) * stetho_percnt_x));
        $("#stethoscope").height(Number((body_image.height() / 100) * stetho_percnt_y));

        if (is_zoom) {
            var reload_icon = document.getElementById("reload_icon_" + id);
            reload_icon.style.zoom = body_size;
            if (fulscreen_mode == 1) {
                $('#btn-case-top').addClass("__fullscreen");
            } else {
                $('#btn-case-top').removeClass("__fullscreen");
            }
        }
        $('.bodyFrame').height(Number((frame_width / 100) * prcent_frame_y))

    }


    function setFullscreenSwitch() {
        var fulscreen_mode = localStorage.getItem("fullscreen");
        var id = localStorage.getItem("sthetho_id");
        var bodymap = $("#body-" + id);
        var body_switch_width = $("#bswitch_" + id).width();
        var frame_width = $("#body-" + id).width();

        var prcent_x = (1 / 520) * 100;

        var zoom = Number((frame_width / 100) * prcent_x);

        var switch_btn = document.getElementById("bswitch_" + id);
        if (fulscreen_mode != 1) {
            switch_btn.style.top = '';
            switch_btn.style.right = '';
            switch_btn.style.width = '';
            switch_btn.style.height = '';
            switch_btn.style.backgroundSize = '';
        } else {
            switch_btn.style.top = (bodymap[0].offsetTop + 10) + 'px';
            switch_btn.style.right = (bodymap[0].offsetLeft + (body_switch_width + 20)) + 'px';
            switch_btn.style.width = Number(body_switch_width * zoom) + 'px';
            switch_btn.style.height = Number(body_switch_width * zoom) + 'px';
            switch_btn.style.backgroundSize = Number(body_switch_width * zoom) + 'px';
        }

    }

    //quiz exam slider
    var lib_type = $('.content_title').data('lib_type');
    $('#exam_quiz.bxslider').bxSlider({
        slideWidth: 258,
        adaptiveHeight: true,
        infiniteLoop: (lib_type == 1) ? false : true,
        touchEnabled: (lib_type == 1) ? false : true,
        oneToOneTouch: (lib_type == 1) ? false : true,
        // 要素に対してbxsliderの準備が整うと呼ばれる
        onSliderLoad: function (currentIndex) {
            var currentSlide = $('.bxslider>li').eq(currentIndex)
            if (lib_type == 1) {
                var sound = $('.aus-img_wrapper').data("result");
                var title = $('.aus-img_wrapper').data("title");
                $("#ausculaide_app").detach().appendTo('#body-' + sound.id);
                setQuizExamAusculaide(sound);
                $("#sl_image_title").data("id", sound.id).text(title);
            }
            // 0件の場合スライド機能をOFFにする
            if (this.getSlideCount() == 1) {
                // jquery.bxslider.js によって生成された操作UI要素を削除する
                this.find('.bx-prev').remove();
                this.find('.bx-next').remove();
                this.closest('.bx-wrapper').find('.bx-pager').remove();
                // Bxsliderの下の余白を削減する
                this.closest('.bx-wrapper').css('margin-bottom', '30px');
            }
            if (this.getSlideCount() == 0) {
                $('.bx-wrapper .bx-prev').remove();
                $('.bx-wrapper .bx-next').remove();
            }
        },
        /**
         * ※引数$slideElement：スライドするjQuery要素
         * ※引数oldIndex：前のスライドの要素のインデックス（遷移前）
         * ※引数newIndex：先のスライドの要素インデックス（遷移後）
         */
        onSlideAfter: function ($slideElement, oldIndex, newIndex) {
            // 先のスライドのタイトルを取得する
            var oldSlide = $('.bxslider>li').eq(oldIndex)

            var prev_slide = oldSlide.find('.aus-img_wrapper').data("result");
            if (lib_type == 1) {
                var sound = $slideElement.find('.aus-img_wrapper').data("result");
                var title = $slideElement.find('.aus-img_wrapper').data("title");
                //$("#body-"+prev_slide.id).attr("src", '/body-images/ui/bodymap/body.jpg');//unload prev slide ausculaide
                $("#btn-case-top").click();
                $("#ausculaide_app").detach().appendTo('#body-' + sound.id);
                setQuizExamAusculaide(sound);
                $("#sl_image_title").data("id", sound.id).text(title);
            }

            var image_title = $slideElement.find("#sl_image_title").text();
            var titleElement = $slideElement.closest(".img_slide").find("#image_title");
            titleElement.text(image_title);
            if (image_title) {
                titleElement.show();
                $("#image_title").text(image_title);
            } else {
                titleElement.hide();
            }
        }
    });
    //set exam quiz local storage
    function setQuizExamAusculaide(sound) {
        localStorage.setItem("sthetho_id", sound.id);
        localStorage.setItem("body", "front");
        if (sound.type == 1) {//lung type
            localStorage.setItem("lung", 1);
            localStorage.setItem("heart", 1);
            localStorage.setItem("pulse", 0);
            localStorage.setItem("type", "lung");
        } else {//heart type
            localStorage.setItem("heart", 1);
            localStorage.setItem("pulse", 1);
            localStorage.setItem("lung", 1);
            localStorage.setItem("type", "heart");
        }

        setAusSize();

        loadBodyFunction(sound, true, true);
    }

    //exam quiz switch btn
    $(".quiz-btn-switch-body").click(function (e) {
        var id = $(this).data("id");
        var sound = $('#aus-img_wrapper_' + id).data("result");
        handleClickSwitchBody(e, sound, true)
    })

    //when clicking on clickable div to show carousel
    $(".accordion .sound_accordion_open").on("click", function (e) {
        // $('.vid_open').css('display', 'none')
        // var audio = $('.open_active').siblings('.audio').find('play-pauseZ').children('playZ:first')
        // $('.open_active').siblings('.audio').find('.audiojs').each(function(idx, el){
        //     var name  = $(el).attr('id').replace("_wrapper", "");
        //     console.log(name)
        //     audiojs.instances[name].pause();
        // });
        for (var prop in audiojs.instances) {
            audiojs.instances[prop].pause()
        }
        // audio.trigger('click');
        $('.bx-viewport ').css('display', 'block')
        $('.bx-controls').css('display', 'block')
        $('.bxslider').css('display', 'block')
        toggleAccordion($(this));
    });

    //when clicking on play for video
    $(".play_vid").on("click", function (e) {
        if (!$(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").hasClass('open_active')) {
            $('.open_active').removeClass('open_active')
            $('.sound_conts_open').removeClass('sound_conts_open')
        }

        $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").find('.name').animate({ color: '#CCCCCC' }, 300)
        if (!$(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").hasClass('open_active')) {
            $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").parent().addClass('sound_conts_open', 1000)
        }
        $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").addClass('open_active')
        $('.vid_open').css('display', 'block')
        // toggleVideo($(this));
        // if(vid_open){
        //     //do nothing
        //     toggleAccordion(current_elm);
        //     $('.vid_open').css('display', 'block')
        //     toggleVideo($(this));

        // }
        // else{
        //     vid_open = true
        //     if (current_elm == null) {
        //         $('.bxslider').css('display', 'none')
        //         $('.bx-viewport').css('display', 'none')
        //         $('.bx-controls').css('display', 'none')
        //         $('.vid_open').css('display', 'block')
        //         toggleVideo($(this));
        //     }
        //     else{
        //         if (current_elm.hasClass('open_active')) {
        //             // toggleAccordion(current_elm);
        //             $('.bxslider').css('display', 'none')
        //             $('.bx-viewport').css('display', 'none')
        //             $('.bx-controls').css('display', 'none')
        //             $('.vid_open').css('display', 'block')
        //             toggleVideo($(this));
        //         } else {
        //             // $('.open_active').removeClass('open_active')
        //             // $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").addClass('open_active')
        //             $('.bxslider').css('display', 'none')
        //             $('.bx-viewport').css('display', 'none')
        //             $('.bx-controls').css('display', 'none')
        //             $('.vid_open').css('display', 'block')
        //             toggleVideo($(this));
        //         }
        //     }

        // }

    });

    //close video by clicking pause
    $(".pauseZ_vid").on("click", function (e) {
        // vid_open = false
        // $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").removeClass("open_active");
        // $('.vid_open').css('display', 'none')
        // $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").find('.name').animate({ color: '#000000' }, 300)
        // $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").parent().removeClass('sound_conts_open', 1000)
        // $(this).parent().parent().parent().prevAll("div.sound_accordion_open:first").siblings('.accordion_conts').find('.sound_conts_inner').find(".sound_title .name").animate({ color: '#000000' }, 300);
        // var has_mp4 = $('.vid_open').css('display')
        // if (has_mp4 == 'none') {
        //     //do nothing if mp3
        // } else {
        //     // if (current_elm != null) {
        //     //     current_elm.removeClass("open_active");
        //     // }
        //     // toggleVideo($(this));
        //     $('.vid_open').css('display', 'none')
        // }
    });

    //spメニュー
    $(function spmenu() {
        $("#gnavi_sp_btn").on("click", function () {
            $("#gnavi_sp .menu_box").slideToggle();
            $(this).toggleClass("gnavi_sp_btn_open");
            $("#overlay").fadeToggle();
        });
    });

    //通常アコーディオン
    $(function accordion() {
        $(".accordion_btn").on("click", function () {
            $(this).next(".accordion_moreconts").slideToggle();
            $(this).toggleClass("accordion_more");
        });
    });

    //通常アコーディオン
    $(function accordion2() {
        $(".accordion_btn2").on("click", function () {
            $(this).next(".accordion_moreconts").slideToggle();
        });
    });

    /*
        // 購入種類　最初に来た時の設定
        var radiochecked = $('input[name="kind"]:checked').val();
        if(radiochecked == 'rent1' || radiochecked == 'rent2' || radiochecked == 'rent3' || radiochecked == 'rent4') {  // レンタルの場合
            $(".rental").css('display','block');    // 表示
        }

        // 購入種類　ラジオボタンが変更された場合
        $('input[name="kind"]:radio').change( function() {
            var radio = $(this).val();
            if(radio == 'buy') {    // 購入の場合
                $(".rental").css('display','none'); // 非表示
            } else {    // レンタルの場合
                $(".rental").css('display','block');    // 表示
            }
        });
    */

    // タブメニュー
    $("#tabMenu li a").on("click", function () {
        $(".tabClass").hide(); // 一旦全て隠す
        $(".table-ul li a").css('color', '#666666'); // 一旦全て文字黒色
        $(".table-ul li a").css('font-weight', 'normal'); // 一旦全てノーマル
        $(this).css('color', '#f9f8ef'); // 対象の文字ボールド
        $(this).css('font-weight', 'bold'); // 対象の文字白色
        $($(this).attr("href")).fadeToggle(); // 対象を表示
        return false;
    });

    // タブメニュー
    $("#tabMenu2 li a").on("click", function () {
        $(".tabClass2").hide(); // 一旦全て隠す
        $(".table-ul2 li a").css('color', '#666666'); // 一旦全て文字黒色
        $(".table-ul2 li a").css('font-weight', 'normal'); // 一旦全てノーマル
        $(this).css('color', '#f9f8ef'); // 対象の文字ボールド
        $(this).css('font-weight', 'bold'); // 対象の文字白色
        $($(this).attr("href")).fadeToggle(); // 対象を表示
        return false;
    });

    /* ビデオ開始 */
    var v = null; // オブジェクト初期化

    // トグル
    $(".video-ul li a").on("click", function () {
        // 一旦全て隠す
        $(this).parent().parent().next().find(".videoWrap").hide();
        // 対象を表示
        $($(this).attr("href")).fadeToggle();

        // アコーディオン　オープン
        $(this).parent().parent().next().css('display', 'block');

        var vid = $(this).data("vid"); // ビデオタグ取得
        $("html,body").animate({ scrollTop: $(this).offset().top }); // スクロール

        // 動画再生していたら止める
        if (v !== null) {
            // v.pause(); // 一時停止
        }

        v = document.getElementById(vid); // ビデオオブジェクト
        // v.play(); // 再生

        return false;
    });

    // 閉じる
    $(".videoClose").on("click", function () {
        // v.pause(); // 一時停止
        $(this).parent().parent().parent().slideToggle(); // アコーディオン　クローズ
    });
    /* ビデオ終了 */

    // telemedica 20180207
    // ビデオのログ(プレイ)
    $("#video1").on("play", function () {
        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var data = {
            screen_code: 'VIDEO',
            event_code: 'PLAY',
            body: {
                stetho_sound: {
                    id: "null",
                    type: "null",
                    title: "null"
                }
            }
        };

        $.ajax({
            url: "/log",
            type: "post",
            data: data,
            dataType: "json"
        })
            .done(function (res) { })
            .fail(function (jqxhr, status, error) { });
    });

    // telemedica 20180207
    // ビデオのログ(ストップ)
    $("#video1").on("pause", function () {
        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var data = {
            screen_code: 'VIDEO',
            event_code: 'PAUSE',
            body: {
                stetho_sound: {
                    id: "null",
                    type: "null",
                    title: "null"
                }
            }
        };

        $.ajax({
            url: "/log",
            type: "post",
            data: data,
            dataType: "json"
        })
            .done(function (res) { })
            .fail(function (jqxhr, status, error) { });
    });

    /*
        // telemedica 20180207
        // 何故かjqueryだと動作しなかったのでJSで
        var video = document.getElementById('video1');

        // telemedica 20180207
        // ビデオのログ(終了)
        // 何故かjqueryだと動作しなかったのでJSで
        video.addEventListener('ended', function() {
            // Ajax初期値設定
            $.ajaxSetup({
                // 普通のheaderでは駄目だった
                // getならこのajaxSetup自体を丸々なくせば動作する
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                }
            });

            var data = {
                screen_code: 'VIDEO',
                event_code: 'ENDED',
                body: {
                    stetho_sound: {
                        id: "null",
                        type: "null",
                        title: "null" 
                    }
                }
            };

            $.ajax({
                url:"/log",
                type:"post",
                data:data,
                dataType:"json"
            })
            .done(function(res){
            })
            .fail(function(jqxhr, status, error){
            });
        }, false);
    */

    // telemedica 20180920
    // 外部リンク
    $(".outlink").on("click", function () {
        var href = $(this).attr("href");

        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        $.ajax({
            url: "./ajaxoutlink",
            type: "post",
            data: { "href": href },
            cache: false,
            dataType: "text"
        })
            .done(function (res) { })
            .fail(function (jqxhr, status, error) { });
    });

    // telemedica 20171031
    // お気に入りの登録・削除ボタン
    $(".FavButton").on("click", function () {
        if (!confirm($(this).data('confirm'))) { // キャンセルの時の処理
            return false;
        }

        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var aid = $(this).data('aid'); // アカウントid
        var pid = 1; // パックid(当面1固定。パック分、ボタン追加になる?)
        var id = $(this).data('id'); // 聴診音id
        var favo = $(this).data('favo'); // お気に入り状態
        let trans = $(this).data('trans');
        var type = $(this).data('type'); //type of sound
        var lib_type = $(this).data('lib_type');
        switch (lib_type) {
            case 0:
                imgurl = "url(../img/tag-stetho.png)";
                break;
            case 1:
                imgurl = "url(../img/tag-aus.png)";
                break;
            case 2:
                imgurl = "url(../img/tag-palp.png)";
                break;
            case 3:
                imgurl = "url(../img/tag-ecg.png)";
                break;
            case 4:
                imgurl = "url(../img/tag-ins.png)";
                break;
            case 5:
                imgurl = "url(../img/tag-xray.png)";
                break;
            case 6:
                imgurl = "url(../img/tag-echo.png)";
                break;
            default:
                imgurl = "url(../img/tag.png)";
                break;
        }
        $.ajax({
            url: "/ajaxfavo",
            type: "post",
            data: { "aid": aid, "pid": pid, "id": id, "favo": favo },
            dataType: "text",
            context: this // コールバック関数にはcontextでこのオブジェクトを渡す
            // http://kihon-no-ki.com/jquery-ajax-pass-value-to-callback-use-context
        })
            .done(function (res) {
                var pt = location.pathname; // URLのパス名
                var disp; // 0:登録 1:削除 2:DB操作失敗 3:登録数オーバー

                if (res == 0) {
                    disp = trans ? trans[0] : "「お気に入り」に登録"
                } else if (res == 1) {
                    disp = trans ? trans[1] : "「お気に入り」から削除"
                    imgurl = "url(../img/tag-favo.png)"; // タグのイメージ
                } else if (res == 2) {
                    alert("データベースへのアクセスが正常に行われませんでした。");
                } else if (res == 3) {
                    alert(trans[2]);
                }

                if (res == 0 || res == 1) { // 正常だった場合
                    if (pt.match(/home/)) { // URLのパス名にhomeを含んでいた場合
                        location.reload(); // 再表示
                    } else {
                        var tmp = "#" + id; // タグのid
                        console.log(imgurl)
                        $(tmp).css('background-image', imgurl); // イメージ変更

                        if (res == 1) {
                            $(this).css('background', '#eeeeee'); // ボタン色
                            $(this).css('border-color', '#eeeeee'); // ボタン枠
                            $(this).css('color', '#000000'); // ボタン文字
                            $(this).css('background-image', 'url(../img/no-delete.png)');
                            $(this).css('background-repeat', 'no-repeat');
                            $(this).css('background-position', '8% 46%');
                        } else {
                            $(this).css('background', '#47B8E8'); // ボタン色
                            $(this).css('border-color', '#47B8E8'); // ボタン枠
                            $(this).css('color', '#ffffff'); // ボタン文字
                            $(this).css('background-image', 'none');
                        }
                        $(this).text(disp); // ボタン表示切替(削除->登録or登録->削除)

                        // 下記はdata-の書き換え。動作しなかった。
                        // AjaxFavorite.phpに経緯記述あり
                        // $(this).dataset.favo = res;
                    }
                }
            })
            .fail(function (jqxhr, status, error) { });
    });


    // telemedica 20171120
    // 簡単登録のオリジナルセットを削除
    $(".DeleteSetButton").on("click", function () {
        if (!confirm('別に保存したお気に入りを削除します。')) { // キャンセルの時の処理
            return false;
        }

        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var aid = $(this).data('aid'); // アカウントid
        var fpid = $(this).data('fpid'); // 登録パックid

        $.ajax({
            url: "/ajaxdeleteset",
            type: "post",
            data: { "aid": aid, "fpid": fpid },
            dataType: "text",
            context: this // コールバック関数にはcontextでこのオブジェクトを渡す
            // http://kihon-no-ki.com/jquery-ajax-pass-value-to-callback-use-context
        })
            .done(function (res) {
                if (res == 2) {
                    alert("データベースへのアクセスが正常に行われませんでした。");
                }

                if (res == 0 || res == 1) { // 正常だった場合
                    location.reload(); // 再表示
                }
            })
            .fail(function (jqxhr, status, error) { });
    });

    // telemedica 20171120
    // 簡単登録にオリジナルセットを保存
    $("#OriginalSetButton").on("click", function () {
        var title = $("#b_title").val(); // タイトル
        var max = 10; // 最大文字数(サーバでは別チェックも有)
        var length = title.length; // タイトル文字数
        var tags = "<p style='color:red;font-size:12px;font-weight:bold;margin:1em 0em 0em 0em;text-align:center;'>";
        var tage = "</p>";

        if (title == "") { // 未入力の場合
            $("#b_title_error").html(tags + "別に保存するボタン名を入力して下さい。" + tage);

            return false;
        } else if (length > max) { // 最大文字数を超えている場合
            $("#b_title_error").html(tags + "ボタン名は" + max + "文字以下にして下さい。" + tage);

            return false;
        }

        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var aid = $(this).data('aid'); // アカウントid
        var fpid = $(this).data('setfpid'); // 登録パックid

        $.ajax({
            url: "/ajaxsaveset",
            type: "post",
            data: { "aid": aid, "fpid": fpid, "title": title, "max": max },
            dataType: "text",
            context: this // コールバック関数にはcontextでこのオブジェクトを渡す
            // http://kihon-no-ki.com/jquery-ajax-pass-value-to-callback-use-context
        })
            .done(function (res) {
                if (res == 2) {
                    alert("データベースへのアクセスが正常に行われませんでした。");
                } else if (res == 3) {
                    alert("登録数を超えています。登録できません。");
                }

                if (res == 0 || res == 1) { // 正常だった場合
                    location.reload(); // 再表示
                }
            })
            .fail(function (jqxhr, status, error) { });
    });

    // telemedica 20171120
    // 簡単登録にオリジナルセットを保存
    // エンターキーのクリックでも処理実行
    $("#b_title").keypress(function (e) {
        if (e.which == 13) {
            $("#OriginalSetButton").click();
        }
    });

    // telemedica 20171120
    // お気に入りの初期設定用のセット
    $(".InitSetButton").on("click", function () {
        var register = $(this).data('register'); // 登録数

        if (register == 0) { // 登録数が0の場合
            mes = '別に保存したお気に入りを表示します。'
        } else { // 登録数が0以外(何件か登録されている)の場合
            mes = '今、表示している「お気に入り」を削除し、別に保存した「お気に入り」を表示します。'
        }

        if (!confirm(mes)) { // キャンセルの時の処理
            return false;
        }

        // Ajax初期値設定
        $.ajaxSetup({
            // 普通のheaderでは駄目だった
            // getならこのajaxSetup自体を丸々なくせば動作する
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });

        var aid = $(this).data('aid'); // アカウントid
        var set = $(this).data('set'); // セットid
        var fpid = $(this).data('fpid'); // 保存セットパックid

        $.ajax({
            url: "/ajaxinitset",
            type: "post",
            data: { "aid": aid, "set": set, "fpid": fpid },
            dataType: "text",
            context: this // コールバック関数にはcontextでこのオブジェクトを渡す
            // http://kihon-no-ki.com/jquery-ajax-pass-value-to-callback-use-context
        })
            .done(function (res) {
                if (res == 2) {
                    alert("データベースへのアクセスが正常に行われませんでした。");
                }

                if (res == 0 || res == 1) { // 正常だった場合
                    location.reload(); // 再表示
                }
            })
            .fail(function (jqxhr, status, error) { });
    });

    localStorage.removeItem("ssid");

    $(window).focus(function() { // page back to focus
        console.log("focus");
        var ssid = localStorage.getItem("ssid");
        if (ssid) {
          var audio = $("#ssid_" + ssid)[0];
          if (audio) {
            audio.play();

            var vid = document.getElementById("stetho_sound_video[" + ssid + "]");
            if (vid) {
              vid.play();
            }
          }
        }
    });
    
    $(window).blur(function() { // when change browser tab or browser is minimized
        console.log("blur");
        var ssid = localStorage.getItem("ssid");
        if (ssid) {
          var audio = $("#ssid_" + ssid)[0];
          if (audio) {
            audio.pause();
            console.log("audio: " + ssid + " paused")

            var vid = document.getElementById("stetho_sound_video[" + ssid + "]");
            if (vid) {
              vid.pause();
              console.log("video: " + ssid + " paused")
            }
          }
          
        }
    });

});

/* カレンダー */
jQuery(function (t) {
    /* 日本語化 */
    t.datepicker.regional.ja = {
        closeText: "閉じる",
        prevText: "<前",
        nextText: "次>",
        currentText: "今日",
        monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        monthNamesShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        dayNames: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
        dayNamesShort: ["日", "月", "火", "水", "木", "金", "土"],
        dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
        weekHeader: "週",
        dateFormat: "yy/mm/dd",
        firstDay: 0,
        isRTL: !1,
        showMonthAfterYear: !0,
        yearSuffix: "年"
    }, t.datepicker.setDefaults(t.datepicker.regional.ja)

    /* フォームにある日付 */
    /* 初期値はPHP側で必ず設定される */
    var pre_year = $("#calendar").parent().find(".year").val();
    var pre_month = $("#calendar").parent().find(".month").val();
    var pre_date = $("#calendar").parent().find(".date").val();

    /* 最初に来た時の最終日の調整 */
    /* 4,6,9,11月は30日、2月は28日で閏年は29日 */
    if (pre_month == '04' || pre_month == '06' || pre_month == '09' || pre_month == '11') {
        $("#calendar").parent().find(".date").children('option[value=31]').remove();
    } else if (pre_month == '02') {
        $("#calendar").parent().find(".date").children('option[value=30]').remove();
        $("#calendar").parent().find(".date").children('option[value=31]').remove();
        var lastday = formSetLastDay(Number(pre_year), Number(pre_month));
        if (lastday == 28) {
            $("#calendar").parent().find(".date").children('option[value=29]').remove();
        }
    }

    /* selectの日のオプションを作成 */
    function formSetDay() {
        var year = $("#calendar").parent().find(".year").val();
        var month = $("#calendar").parent().find(".month").val();

        var lastday = formSetLastDay(year, Number(month));
        var option = '';

        for (var i = 1; i <= lastday; i++) {
            var date = $("#calendar").parent().find(".date").val();
            var day = String(i);
            // 下記の0パディングはいらないかも
            if (i < 10) { day = "0" + String(i); }

            if (i == Number(date)) {
                option += '<option value="' + day + '" selected="selected">' + day + '</option>\n';
            } else {
                option += '<option value="' + day + '">' + day + '</option>\n';
            }
        }
        $("#calendar").parent().find(".date").html(option);
    }

    /* 年と月からその月の最終日を求める */
    function formSetLastDay(year, month) {
        var lastday = new Array('', 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
            lastday[2] = 29;
        }
        return lastday[month];
    }

    /* 年と月が変更された場合 */
    $("#calendar").parent().find(".year").change(function () {
        formSetDay();
    });
    $("#calendar").parent().find(".month").change(function () {
        formSetDay();
    });

    /* フォーム初期化 */
    $("#calendar").parent().find(".year").val(pre_year);
    $("#calendar").parent().find(".month").val(pre_month);
    $("#calendar").parent().find(".date").val(pre_date);

    /* カレンダー初期化 */
    $("#calendar").datepicker("setDate", pre_year + "/" + pre_month + "/" + pre_date);

    /* jquery UI カレンダー機能 */
    /* maxDateを変更する場合は
     * register_form.blade.php
     * register_form_confirm.blade.php
     * のyearも変更する必要有 */
    $("#calendar").datepicker({
        showOn: "button",
        buttonImageOnly: true,
        buttonImage: "./img/calendar.png",
        minDate: new Date(2018, 1 - 1, 1) // 2018年1月1日～
        ,
        maxDate: new Date(2022, 12 - 1, 31) // 2022年12月31日
        ,
        beforeShow: function (input, inst) { // 開く前に日付を上書き
            var year = $(this).parent().find(".year").val();
            var month = $(this).parent().find(".month").val();
            var date = $(this).parent().find(".date").val();
            $(this).datepicker("setDate", year + "/" + month + "/" + date);
        },
        onSelect: function (dateText, inst) { // カレンダー確定時にフォームに反映
            var dates = dateText.split('/');
            $(this).parent().find(".year").val(dates[0]);
            $(this).parent().find(".month").val(dates[1]);
            $(this).parent().find(".date").val(dates[2]);
        }
    });
});