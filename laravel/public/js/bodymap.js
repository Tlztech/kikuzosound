$(document).ready(function(){   
    let is_muted = 0.0000000000001; //lowest  volume like as muted 
    //init aus audio
    let aus_sound = {
      'a_sound_path': new Audio(),
      'p_sound_path': new Audio(),
      't_sound_path': new Audio(),
      'm_sound_path': new Audio(),

      'h1_sound_path': new Audio(),
      'h2_sound_path': new Audio(),
      'h3_sound_path': new Audio(),
      'h4_sound_path': new Audio(),

      'pa_sound_path': new Audio(),
      'pp_sound_path': new Audio(),
      'pt_sound_path': new Audio(),
      'pm_sound_path': new Audio(),

      'tr1_sound_path': new Audio(),
      'tr2_sound_path': new Audio(),

      'br1_sound_path': new Audio(),
      'br2_sound_path': new Audio(),
      'br3_sound_path': new Audio(),
      'br4_sound_path': new Audio(),

      've1_sound_path': new Audio(),
      've2_sound_path': new Audio(),
      've3_sound_path': new Audio(),
      've4_sound_path': new Audio(),
      've5_sound_path': new Audio(),
      've6_sound_path': new Audio(),

      've7_sound_path': new Audio(),
      've8_sound_path': new Audio(),
      've9_sound_path': new Audio(),
      've10_sound_path': new Audio(),
      've11_sound_path': new Audio(),
      've12_sound_path': new Audio(),
    };

    $(".multiple_quiz__btns").click(function(){
      if ($(this).attr("data-lib") != 1) {
        var id = localStorage.getItem("sthetho_id"); 
        stopSound(id);
      }
    });

    $(".quiz_choice_btn, .quiz_end, .observation_btn__save").click(function(){
      var id = localStorage.getItem("sthetho_id"); 
      stopSound(id);
    });

    function setStethoscopePosition(id) {
      // localStorage.setItem("sound_active", 0);
      // var bodymap = $("#bodymap_" +id);
      // var position = bodymap.position();

      // var d = document.getElementById("stethoscope_" +id);
      // d.style.left = position.left+ 'px';
      // d.style.top = position.top + 'px';
      // initAusSounds(id);
    }

  
    function hideStethoScope() {
      var id = localStorage.getItem("sthetho_id"); 

      grey_out_icons();
      disableAPTMs(true);
      $('.audio_slider').slider( 'disable');

      $(".stethoscope").attr("style", "display:none");
      localStorage.removeItem("sthetho_id");
      localStorage.removeItem("body");
      localStorage.removeItem("type");
      localStorage.removeItem("sound_active");
      localStorage.removeItem("heart");
      localStorage.removeItem("pulse");
      localStorage.removeItem("lung");
      localStorage.removeItem("fullscreen");

      stopSound(id);
    }

    function grey_out_icons() {
      var id = localStorage.getItem("sthetho_id");
      $("#heart_icon_" + id).removeClass("btn-heart-icon btn-heart-grey-icon").addClass("btn-heart-grey-icon");
      $("#pulse_icon_" + id).removeClass("btn-pulse-icon btn-pulse-grey-icon").addClass("btn-pulse-grey-icon");
      $("#lung_icon_" + id).removeClass("btn-lung-icon btn-lung-grey-icon").addClass("btn-lung-grey-icon");
      localStorage.setItem("heart", 0);
      localStorage.setItem("pulse", 0);
      localStorage.setItem("lung", 0);
    }

    $(".aus, .aus_home").click(function(){
      var id = $(this).data('id')
      if ($(this).attr('class').includes("open_active")) {
        setStethoscopePosition(id); // initialize the position of stethoscope
        setSpeedSlider(id); // set sound speed
        $("#stethoscope_" +id).show();
        localStorage.setItem("body", "front");
        localStorage.setItem("sthetho_id", id);
        var type = $("#stethoscope_" +id).data("type") == 1 ? "lung" : "heart";
        localStorage.setItem("type", type);

        grey_out_icons();

        if (type == "heart") {
          if (checkHeart() == 1) {
            disableAPTMs(false);
            localStorage.setItem("heart", 1);
            $("#heart_icon_" + id).removeClass("btn-heart-grey-icon").addClass("btn-heart-icon");
            $('.audio_slider').slider( 'enable'); // enable speed function
          } else {
            disableAPTMs(true);
            $('.audio_slider').slider( 'disable'); // disable speed function
          }
        } else {
          if (checkLung() == 1) {
            localStorage.setItem("lung", 1);
            $("#lung_icon_" + id).removeClass("btn-lung-grey-icon").addClass("btn-lung-icon");
          }
          disableAPTMs(true);
          $('.audio_slider').slider( 'disable'); // disable speed function
        }

        $("#body_switch_" + id).attr('data-body', "front");
        $("#bodymap_" +id).attr('src', $("#body_switch_" + id).data("front_body"));
      } else {
        hideStethoScope();
      }
    });

    function disableAPTMs(isDisabled) {
      var id = localStorage.getItem("sthetho_id");
      var sound = $(".sound_" + id)[0];

      $(".btn-point_"+ id).each(function( index ) {
        $( this ).prop('disabled', isDisabled);
      });

      if (isDisabled) {
        // reset selected aptm
        $(".btn-point_" + id).each(function( index ) {
          $( this ).removeClass("btn-point-active");
        });
      }
    }
    // when click switch body
    $(".btn-switch-body, .quiz-btn-switch-body").click(function(){
      var type = localStorage.getItem("type");

      var frontBodyImage = $(this).data('front_body');
      var backBodyImage = $(this).data('back_body');
      var id = localStorage.getItem("sthetho_id");

      var body = $(this).attr('data-body');

      var className = $(this).attr("class");

      if (type == "lung") {
        if(body == "front") {
          // Switch to Back Body
          localStorage.setItem("body", "back");
          $("#bodymap_" +id).attr('src', backBodyImage);
          $(this).attr('data-body', "back");

          if (className.includes("btn-switch-body")) {
            grey_out_icons();
            
            if (checkLung() == 1) {
              $("#lung_icon_" + id).removeClass("btn-lung-icon btn-lung-grey-icon").addClass("btn-lung-icon"); 
              localStorage.setItem("lung", 1);   
            }

            $(".btn-point_"+ id).each(function( index ) {
              $( this ).removeClass("btn-point-active");
            });
          }

        } else {
           // Switch to Front Body
          localStorage.setItem("body", "front");
          $("#bodymap_" +id).attr('src', frontBodyImage);
          $(this).attr('data-body', "front");

          if (className.includes("btn-switch-body")) {
            grey_out_icons();
            $("#heart_icon_" + id).removeClass("btn-heart-icon btn-heart-grey-icon").addClass("btn-heart-icon");
            localStorage.setItem("heart", 1);
          }
        }

        stopSound(id);
        // reset stethoscope position
        setStethoscopePosition(id);
      }
    });

    function checkHeart (){
      var id = localStorage.getItem("sthetho_id");
      var heart = 0;
      var a = aus_sound["a_sound_path"].src;
      var p = aus_sound["p_sound_path"].src;
      var t = aus_sound["t_sound_path"].src;
      var m = aus_sound["m_sound_path"].src;

      var h1 = aus_sound["h1_sound_path"].src;
      var h2 = aus_sound["h2_sound_path"].src;
      var h3 = aus_sound["h3_sound_path"].src;
      var h4 = aus_sound["h4_sound_path"].src;

      if (a || p || t || m || h1 || h2 || h3 || h4) {
        heart = 1;
      } else {
        heart = 0;
      }
      return heart;
    }

    function checkPulse (){
      var id = localStorage.getItem("sthetho_id");
      var pulse = 0;
      var pa = aus_sound["pa_sound_path"].src;
      var pp = aus_sound["pp_sound_path"].src;
      var pt = aus_sound["pt_sound_path"].src;
      var pm = aus_sound["pm_sound_path"].src;

      if (pa || pp || pt || pm) {
        pulse = 1;
      } else {
        pulse = 0;
      }

      return pulse;
    }

    function checkLung () {
      var id = localStorage.getItem("sthetho_id");
      var lung = 0;

      var tr1 = aus_sound["tr1_sound_path"].src;
      var tr2 = aus_sound["tr2_sound_path"].src;
      var br1 = aus_sound["br1_sound_path"].src;
      var br2 = aus_sound["br2_sound_path"].src;
      var br3 = aus_sound["br3_sound_path"].src;
      var br4 = aus_sound["br4_sound_path"].src;

      if (tr1 || tr2 || br1 || br2 || br3 || br4) {
        lung = 1;
      } else {
        for (var i = 1; i <= 12; i++) {
          var ve = aus_sound["ve"+i+"_sound_path"].src;
          if (ve) {
            return 1;
          }
        }
        lung = 0
      }

      return lung;
    }

    

    // set default speed to 60
    function setSpeedSliderDefault () {
      var id = localStorage.getItem("sthetho_id");
      var volspeed = 1.0; // reset to 60
      setPlaybackRate(id,volspeed);
      setSpeedSlider(id);
    }

    // when click reload icon
    $(".btn-reload-icon").click(function(){
      var id = localStorage.getItem("sthetho_id");
      $(".btn-point_"+ id).each(function( index ) {
        $( this ).removeClass("btn-point-active");
      });
      stopSound(id);
      // reset stethoscope position
      setStethoscopePosition(id);
    });

    // when point click manually
    $(".btn-point").click(function(){
      playSound();
      var body = localStorage.getItem("body");
      var heart = localStorage.getItem("heart");
      var pulse = localStorage.getItem("pulse");
      var lung = localStorage.getItem("lung");

      if ((body == "front" && heart == 1) || (body == "front" && pulse == 1)) {
        var point = $(this).data('point');
        // var src = $(this).data('src');
        var id = localStorage.getItem("sthetho_id");
        var config = $("#stethoscope_" +id).data('config');
        var me = this;

        if (config) { // check is points are set
          var adjustment = getPositionAdjustments(config[point]['x'],config[point]['y']);
          $("#stethoscope_" +id).animate({ // move the stethoscope to selected point
            left: (adjustment.x) + 'px',
            top: (adjustment.y) + 'px'
          }, {
            duration: 300,
            complete: function() { 
              if (config[point]['x'] && config[point]['y']) { // play sound if that point is set
                //remove color to current active point
                $(".btn-point").removeClass("btn-point-active");

                var volume = 1.0; // set to max vol.
                var playback_rate = null;
                if (heart == 1) {
                  var arrayOfHearts = [
                    {point:"a", vol: 0},
                    {point:"p", vol: 0},
                    {point:"t", vol: 0},
                    {point:"m", vol: 0},
                    {point:"h1", vol: 0},
                    {point:"h2", vol: 0},
                    {point:"h3", vol: 0},
                    {point:"h4", vol: 0}
                  ];
                  point = $(me).data('point');
                  PlayHeartSound(point,volume,arrayOfHearts);
                }

                if (pulse == 1) {
                  var arrayOfPulse = [
                    {point:"a", vol: 0},
                    {point:"p", vol: 0},
                    {point:"t", vol: 0},
                    {point:"m", vol: 0}
                  ];
                  point = "p" + $(me).data('point');
                  playPulseSound(point,volume,arrayOfPulse);
                }
                
                $(me).addClass("btn-point-active");
                onStethoscopeMove();
              }
            }
          });
        }
      }
    });

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

      var vol_p = radius_p/radius;
      return vol_p;
    }

    // drag trigger event for stethoscope
    $( ".stethoscope" ).draggable({
      drag: function() {
        onStethoscopeMove();
      },
      start: function() {
        playSound();
      }
    });

    function bubbleSort(a, par) {
      var swapped;
      do {
          swapped = false;
          for (var i = 0; i < a.length - 1; i++) {
              if (a[i][par] < a[i + 1][par]) {
                  var temp = a[i];
                  a[i] = a[i + 1];
                  a[i + 1] = temp;
                  swapped = true;
              }
          }
      } while (swapped);
    }

    function onStethoscopeMove() {
      var id = localStorage.getItem("sthetho_id");
      var stethoscope = $("#stethoscope_" +id);
      var position = stethoscope.position(); // get stethoscope position
      var playback_rate = null; 
      var config = $("#stethoscope_" +id).data('config');
      var body = localStorage.getItem("body");
      var heart = localStorage.getItem("heart");
      var pulse = localStorage.getItem("pulse");
      var lung = localStorage.getItem("lung");
      var type = localStorage.getItem("type");

      // stethoscope position
      var positionLeft = position.left;
      var positionTop = position.top;

      if (type == "heart") {
        if (heart == 1 && pulse == 1 && lung == 1) { // heart + pulse + lung
          setSpeedSliderDefault();
          $('.audio_slider').slider( 'disable');
          disableAPTMs(false);
        } else if (heart == 1 && lung == 1) { // heart + lung
          setSpeedSliderDefault();
          $('.audio_slider').slider( 'disable');
          disableAPTMs(false);
        } else if (heart == 1 && pulse == 1) { // heart + pulse
          $('.audio_slider').slider( 'enable');
          disableAPTMs(false);
        } else if (pulse == 1 && lung == 1) { // pulse + lung
          $('.audio_slider').slider( 'disable');
          disableAPTMs(false);
        } else if (heart == 1) { // heart
          $('.audio_slider').slider( 'enable');
          disableAPTMs(false);
        } else if (pulse == 1) { // pulse
          $('.audio_slider').slider( 'enable');
          disableAPTMs(false);
        } else if (lung == 1) { // lung
          $('.audio_slider').slider( 'disable');
          disableAPTMs(true);
        } else {
          setSpeedSliderDefault();
          $('.audio_slider').slider( 'disable');
          disableAPTMs(true);
        }
      } else {
        setSpeedSliderDefault();
        $('.audio_slider').slider( 'disable');
        disableAPTMs(true);
      }

      if (body == "front" && heart == 1 && !(heart == 1 && pulse == 1) && !(heart == 1 && pulse == 1 && lung == 1)) {
        if (config) { // check if config is set
          var a = getPositionAdjustments(
            config.a ? config.a.x : 0, 
            config.a ? config.a.y : 0, 
            config.a ? config.a.r : 0);
          var a_left = a.x;
          var a_top = a.y;
          var a_radius = a.r;

          var p = getPositionAdjustments(
            config.p ? config.p.x : 0, 
            config.p ? config.p.y : 0, 
            config.p ? config.p.r : 0);
          var p_left = p.x;
          var p_top = p.y;
          var p_radius = p.r;

          var t = getPositionAdjustments(
            config.t ? config.t.x : 0, 
            config.t ? config.t.y : 0, 
            config.t ? config.t.r : 0);
          var t_left = t.x;
          var t_top = t.y;
          var t_radius = t.r;

          var m = getPositionAdjustments(
            config.m ? config.m.x : 0, 
            config.m ? config.m.y : 0, 
            config.m ? config.m.r : 0);
          var m_left = m.x;
          var m_top = m.y;
          var m_radius = m.r;

          var h1 = getPositionAdjustments(
            config.h1 ? config.h1.x : 0, 
            config.h1 ? config.h1.y : 0,
            config.h1 ? config.h1.r : 0);
          var h1_left = h1.x;
          var h1_top = h1.y;
          var h1_radius = h1.r;

          var h2 = getPositionAdjustments(
            config.h2 ? config.h2.x : 0, 
            config.h2 ? config.h2.y : 0,
            config.h2 ? config.h2.r : 0);
          var h2_left = h2.x;
          var h2_top = h2.y;
          var h2_radius = h2.r;

          var h3 = getPositionAdjustments(
            config.h3 ? config.h3.x : 0, 
            config.h3 ? config.h3.y : 0,
            config.h3 ? config.h3.r : 0);
          var h3_left = h3.x;
          var h3_top = h3.y;
          var h3_radius = h3.r;

          var h4 = getPositionAdjustments(
            config.h4 ? config.h4.x : 0, 
            config.h4 ? config.h4.y : 0,
            config.h4 ? config.h4.r : 0);
          var h4_left = h4.x;
          var h4_top = h4.y;
          var h4_radius = h4.r;

          var a=0,p=1,t=2,m=3;
          var h1=4,h2=5,h3=6,h4=7;
          var arrayOfHearts = [
            {point:"a", vol: 0},
            {point:"p", vol: 0},
            {point:"t", vol: 0},
            {point:"m", vol: 0},
            {point:"h1", vol: 0},
            {point:"h2", vol: 0},
            {point:"h3", vol: 0},
            {point:"h4", vol: 0}
          ];

          // A
          if(((positionLeft+a_radius) >= a_left && (positionLeft-a_radius) <= a_left) &&
            ((positionTop+a_radius) >= a_top && (positionTop-a_radius) <= a_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, a_left, a_top, a_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[a].vol = is_muted;
            } else {
              arrayOfHearts[a].vol = vol_p;
            }
          }

          // P
          if(((positionLeft+p_radius) >= p_left && (positionLeft-p_radius) <= p_left) &&
            ((positionTop+p_radius) >= p_top && (positionTop-p_radius) <= p_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, p_left, p_top, p_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[p].vol = is_muted;
            } else {
              arrayOfHearts[p].vol = vol_p;
            }
          }

          // T
          if(((positionLeft+t_radius) >= t_left && (positionLeft-t_radius) <= t_left) &&
            ((positionTop+t_radius) >= t_top && (positionTop-t_radius) <= t_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, t_left, t_top, t_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[t].vol = is_muted;
            } else {
              arrayOfHearts[t].vol = vol_p;
            }
          } 

          // M
          if(((positionLeft+m_radius) >= m_left && (positionLeft-m_radius) <= m_left) &&
            ((positionTop+m_radius) >= m_top && (positionTop-m_radius) <= m_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, m_left, m_top, m_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[m].vol = is_muted;
            } else {
              arrayOfHearts[m].vol = vol_p;
            }
          }
          // h1
          if(((positionLeft+h1_radius) >= h1_left && (positionLeft-h1_radius) <= h1_left) &&
            ((positionTop+h1_radius) >= h1_top && (positionTop-h1_radius) <= h1_top) && 
            (positionLeft || positionTop)) {

              var vol_p = getVolumeByRadius(position, h1_left, h1_top, h1_radius)

              if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
                arrayOfHearts[h1].vol = is_muted;
              } else {
                arrayOfHearts[h1].vol = vol_p;
              }
          }
          // h2
          if(((positionLeft+h2_radius) >= h2_left && (positionLeft-h2_radius) <= h2_left) &&
            ((positionTop+h2_radius) >= h2_top && (positionTop-h2_radius) <= h2_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, h2_left, h2_top, h2_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[h2].vol = is_muted;
            } else {
              arrayOfHearts[h2].vol = vol_p;
            }
          }
          // h3
          if(((positionLeft+h3_radius) >= h3_left && (positionLeft-h3_radius) <= h3_left) &&
            ((positionTop+h3_radius) >= h3_top && (positionTop-h3_radius) <= h3_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, h3_left, h3_top, h3_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[h3].vol = is_muted;
            } else {
              arrayOfHearts[h3].vol = vol_p;
            }
          }
          // h4
          if(((positionLeft+h4_radius) >= h4_left && (positionLeft-h4_radius) <= h4_left) &&
            ((positionTop+h4_radius) >= h4_top && (positionTop-h4_radius) <= h4_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, h4_left, h4_top, h4_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfHearts[h4].vol = is_muted;
            } else {
              arrayOfHearts[h4].vol = vol_p;
            }
          }

          // get who's point is the highest volume
          bubbleSort(arrayOfHearts, "vol");
          console.log("heart",arrayOfHearts)
          // reset aptm active button
          $(".btn-point_"+ id).each(function( index ) {
            $( this ).removeClass("btn-point-active");
          });

          if (arrayOfHearts[0].vol > 0) {
            if(type != "lung") {
              // change active color of aptm sounds
              $("#point_" + arrayOfHearts[0].point + "_" + id).addClass("btn-point-active");
            }
            // play the sound of that point
            PlayHeartSound(arrayOfHearts[0].point, arrayOfHearts[0].vol, arrayOfHearts);
          }else{
            muteHeartSounds(arrayOfHearts);
          }

        }
      } else {
        var a=0,p=1,t=2,m=3;
        var h1=4,h2=5,h3=6,h4=7;
        var arrayOfHearts = [
          {point:"a", vol: 0},
          {point:"p", vol: 0},
          {point:"t", vol: 0},
          {point:"m", vol: 0},
          {point:"h1", vol: 0},
          {point:"h2", vol: 0},
          {point:"h3", vol: 0},
          {point:"h4", vol: 0}
        ];
        muteHeartSounds(arrayOfHearts);
      }


      if (body == "front" && pulse == 1) {

        if (config) { // check if config is set
          var a = getPositionAdjustments(
            config.a ? config.a.x : 0, 
            config.a ? config.a.y : 0, 
            config.a ? config.a.r : 0);
          var a_left = a.x;
          var a_top = a.y;
          var a_radius = a.r;

          var p = getPositionAdjustments(
            config.p ? config.p.x : 0, 
            config.p ? config.p.y : 0, 
            config.p ? config.p.r : 0);
          var p_left = p.x;
          var p_top = p.y;
          var p_radius = p.r;

          var t = getPositionAdjustments(
            config.t ? config.t.x : 0, 
            config.t ? config.t.y : 0, 
            config.t ? config.t.r : 0);
          var t_left = t.x;
          var t_top = t.y;
          var t_radius = t.r;

          var m = getPositionAdjustments(
            config.m ? config.m.x : 0, 
            config.m ? config.m.y : 0, 
            config.m ? config.m.r : 0);
          var m_left = m.x;
          var m_top = m.y;
          var m_radius = m.r;

          var a=0,p=1,t=2,m=3;
          var arrayOfPulse = [
            {point:"a", vol: 0},
            {point:"p", vol: 0},
            {point:"t", vol: 0},
            {point:"m", vol: 0}
          ];

          // A
          if(((positionLeft+a_radius) >= a_left && (positionLeft-a_radius) <= a_left) &&
            ((positionTop+a_radius) >= a_top && (positionTop-a_radius) <= a_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, a_left, a_top, a_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfPulse[a].vol = is_muted;
            } else {
              arrayOfPulse[a].vol = vol_p;
            }
          }

          // P
          if(((positionLeft+p_radius) >= p_left && (positionLeft-p_radius) <= p_left) &&
            ((positionTop+p_radius) >= p_top && (positionTop-p_radius) <= p_top) && 
            (positionLeft || positionTop)) {
            
            var vol_p = getVolumeByRadius(position, p_left, p_top, p_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfPulse[p].vol = is_muted;
            } else {
              arrayOfPulse[p].vol = vol_p;
            }
          }

          // T
          if(((positionLeft+t_radius) >= t_left && (positionLeft-t_radius) <= t_left) &&
            ((positionTop+t_radius) >= t_top && (positionTop-t_radius) <= t_top) && 
            (positionLeft || positionTop)) {
            
            var vol_p = getVolumeByRadius(position, t_left, t_top, t_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfPulse[t].vol = is_muted;
            } else {
              arrayOfPulse[t].vol = vol_p;
            }
          } 

          // M
          if(((positionLeft+m_radius) >= m_left && (positionLeft-m_radius) <= m_left) &&
            ((positionTop+m_radius) >= m_top && (positionTop-m_radius) <= m_top) && 
            (positionLeft || positionTop)) {
              
            var vol_p = getVolumeByRadius(position, m_left, m_top, m_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfPulse[m].vol = is_muted;
            } else {
              arrayOfPulse[m].vol = vol_p;
            }
          }

          // get who's point is the highest volume
          bubbleSort(arrayOfPulse, "vol");
          console.log(arrayOfPulse)

          // reset aptm active button
          $(".btn-point_"+ id).each(function( index ) {
            $( this ).removeClass("btn-point-active");
          });

          if (arrayOfPulse[0].vol > 0) {
            // change active color of aptm sounds
            $("#point_" + arrayOfPulse[0].point + "_" + id).addClass("btn-point-active");
            // play the sound of that point
            playPulseSound("p"+arrayOfPulse[0].point, arrayOfPulse[0].vol, arrayOfPulse);
          }else{
            mutePulseSounds(arrayOfPulse);
          }
        }
        
      } else {
        var a=0,p=1,t=2,m=3;
        var arrayOfPulse = [
          {point:"a", vol: 0},
          {point:"p", vol: 0},
          {point:"t", vol: 0},
          {point:"m", vol: 0}
        ];
        mutePulseSounds(arrayOfPulse);
      }

      if (body == "front" && lung == 1) {

        if (config) { // check if config is set
          var tr1 = getPositionAdjustments(
            config.tr1 ? config.tr1.x : 0, 
            config.tr1 ? config.tr1.y : 0, 
            config.tr1 ? config.tr1.r : 0
          );
          var tr1_left = tr1.x;
          var tr1_top = tr1.y;
          var tr1_radius = tr1.r;

          var tr2 = getPositionAdjustments(
            config.tr2 ? config.tr2.x : 0, 
            config.tr2 ? config.tr2.y : 0, 
            config.tr2 ? config.tr2.r : 0
          );
          var tr2_left = tr2.x;
          var tr2_top = tr2.y;
          var tr2_radius = tr2.r;

          var br1 = getPositionAdjustments(
            config.br1 ? config.br1.x : 0, 
            config.br1 ? config.br1.y : 0, 
            config.br1 ? config.br1.r : 0
          );
          var br1_left = br1.x;
          var br1_top = br1.y;
          var br1_radius = br1.r;

          var br2 = getPositionAdjustments(
            config.br2 ? config.br2.x : 0, 
            config.br2 ? config.br2.y : 0, 
            config.br2 ? config.br2.r : 0
          );
          var br2_left = br2.x;
          var br2_top = br2.y;
          var br2_radius = br2.r;

          var tr1=0,tr2=1,br1=2,br2=3;
          var arrayOfLung = [
            {point:"tr1", vol: 0},
            {point:"tr2", vol: 0},
            {point:"br1", vol: 0},
            {point:"br2", vol: 0},
            {point:"ve1", vol: 0},
            {point:"ve2", vol: 0},
            {point:"ve3", vol: 0},
            {point:"ve4", vol: 0},
            {point:"ve5", vol: 0},
            {point:"ve6", vol: 0}
          ];

          // tr1
          if(((positionLeft+tr1_radius) >= tr1_left && (positionLeft-tr1_radius) <= tr1_left) &&
            ((positionTop+tr1_radius) >= tr1_top && (positionTop-tr1_radius) <= tr1_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, tr1_left, tr1_top, tr1_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfLung[tr1].vol = is_muted;
            } else {
              arrayOfLung[tr1].vol = vol_p;
            }
          }

          // tr2
          if(((positionLeft+tr2_radius) >= tr2_left && (positionLeft-tr2_radius) <= tr2_left) &&
            ((positionTop+tr2_radius) >= tr2_top && (positionTop-tr2_radius) <= tr2_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, tr2_left, tr2_top, tr2_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfLung[tr2].vol = is_muted;
            } else {
              arrayOfLung[tr2].vol = vol_p;
            }
          }

          // br1
          if(((positionLeft+br1_radius) >= br1_left && (positionLeft-br1_radius) <= br1_left) &&
            ((positionTop+br1_radius) >= br1_top && (positionTop-br1_radius) <= br1_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, br1_left, br1_top, br1_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfLung[br1].vol = is_muted;
            } else {
              arrayOfLung[br1].vol = vol_p;
            }
          }

          // br2
          if(((positionLeft+br2_radius) >= br2_left && (positionLeft-br2_radius) <= br2_left) &&
            ((positionTop+br2_radius) >= br2_top && (positionTop-br2_radius) <= br2_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, br2_left, br2_top, br2_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfLung[br2].vol = is_muted;
            } else {
              arrayOfLung[br2].vol = vol_p;
            }
          }

          for (var i = 1; i <= 6; i++) {
            var ve = getPositionAdjustments(
              config["ve" + i] ? config["ve" + i].x : 0, 
              config["ve" + i] ? config["ve" + i].y : 0, 
              config["ve" + i] ? config["ve" + i].r : 0
            );
            var ve_left = ve.x;
            var ve_top = ve.y;
            var ve_radius = ve.r;
            // ve 1-6
            if(((positionLeft+ve_radius) >= ve_left && (positionLeft-ve_radius) <= ve_left) &&
              ((positionTop+ve_radius) >= ve_top && (positionTop-ve_radius) <= ve_top) && 
              (positionLeft || positionTop)) {

              var vol_p = getVolumeByRadius(position, ve_left, ve_top, ve_radius)

              if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
                arrayOfLung[3+i].vol = is_muted;
              } else {
                arrayOfLung[3+i].vol = vol_p;
              }
            }
          }

          // get who's point is the highest volume
          bubbleSort(arrayOfLung, "vol");
          console.log("lung",arrayOfLung)

          if (arrayOfLung[0].vol > 0) {
            // play the sound of that point
            playLungSound(arrayOfLung[0].point, arrayOfLung[0].vol, arrayOfLung);
          }else{
            muteLungSounds(arrayOfLung);
          }
        }

      } else {
        var tr1=0,tr2=1,br1=2,br2=3;
        var arrayOfLung = [
          {point:"tr1", vol: 0},
          {point:"tr2", vol: 0},
          {point:"br1", vol: 0},
          {point:"br2", vol: 0},
          {point:"ve1", vol: 0},
          {point:"ve2", vol: 0},
          {point:"ve3", vol: 0},
          {point:"ve4", vol: 0},
          {point:"ve5", vol: 0},
          {point:"ve6", vol: 0}
        ];
        muteLungSounds(arrayOfLung);
      }

      if (body == "back" && lung == 1) {
        console.log("back body");

        var br3 = getPositionAdjustments(
          config.br3 ? config.br3.x : 0, 
          config.br3 ? config.br3.y : 0, 
          config.br3 ? config.br3.r : 0
        );
        var br3_left = br3.x;
        var br3_top = br3.y;
        var br3_radius = br3.r;

        var br4 = getPositionAdjustments(
          config.br4 ? config.br4.x : 0, 
          config.br4 ? config.br4.y : 0, 
          config.br4 ? config.br4.r : 0
        );
        var br4_left = br4.x;
        var br4_top = br4.y;
        var br4_radius = br4.r;

        var br3=0,br4=1;
        var arrayOfLung = [
          {point:"br3", vol: 0},
          {point:"br4", vol: 0},
          {point:"ve7", vol: 0},
          {point:"ve8", vol: 0},
          {point:"ve9", vol: 0},
          {point:"ve10", vol: 0},
          {point:"ve11", vol: 0},
          {point:"ve12", vol: 0}
        ];

        // br3
        if(((positionLeft+br3_radius) >= br3_left && (positionLeft-br3_radius) <= br3_left) &&
          ((positionTop+br3_radius) >= br3_top && (positionTop-br3_radius) <= br3_top) && 
          (positionLeft || positionTop)) {

          var vol_p = getVolumeByRadius(position, br3_left, br3_top, br3_radius)

          if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
            arrayOfLung[br3].vol = is_muted;
          } else {
            arrayOfLung[br3].vol = vol_p;
          }
        }

        // br4
        if(((positionLeft+br4_radius) >= br4_left && (positionLeft-br4_radius) <= br4_left) &&
          ((positionTop+br4_radius) >= br4_top && (positionTop-br4_radius) <= br4_top) && 
          (positionLeft || positionTop)) {

          var vol_p = getVolumeByRadius(position, br4_left, br4_top, br4_radius)

          if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
            arrayOfLung[br4].vol = is_muted;
          } else {
            arrayOfLung[br4].vol = vol_p;
          }

        }

        for (var i = 7; i <= 12; i++) {
          var ve = getPositionAdjustments(
            config["ve" + i] ? config["ve" + i].x : 0, 
            config["ve" + i] ? config["ve" + i].y : 0, 
            config["ve" + i] ? config["ve" + i].r : 0
          );
          var ve_left = ve.x;
          var ve_top = ve.y;
          var ve_radius = ve.r;
          // ve 7-12
          if(((positionLeft+ve_radius) >= ve_left && (positionLeft-ve_radius) <= ve_left) &&
            ((positionTop+ve_radius) >= ve_top && (positionTop-ve_radius) <= ve_top) && 
            (positionLeft || positionTop)) {

            var vol_p = getVolumeByRadius(position, ve_left, ve_top, ve_radius)

            if (!(vol_p <= 1.0 && vol_p >= 0.0)) {
              arrayOfLung[1+(i-6)].vol = is_muted;
            } else {
              arrayOfLung[1+(i-6)].vol = vol_p;
            }
          }
        }

        // get who's point is the highest volume
        bubbleSort(arrayOfLung, "vol");
        console.log(arrayOfLung)

        if (arrayOfLung[0].vol > 0) {
          // play the sound of that point
          playLungSound(arrayOfLung[0].point, arrayOfLung[0].vol, arrayOfLung);
        }else{
          muteLungSounds(arrayOfLung);
        }

      } else {
        var br3=0,br4=1;
        var arrayOfLung = [
          {point:"br3", vol: 0},
          {point:"br4", vol: 0},
          {point:"ve7", vol: 0},
          {point:"ve8", vol: 0},
          {point:"ve9", vol: 0},
          {point:"ve10", vol: 0},
          {point:"ve11", vol: 0},
          {point:"ve12", vol: 0}
        ];
        muteLungSounds(arrayOfLung);
      }
    }

    // move stethoscope when click somewhere in body image
    $(".body-map").click(function(e){
      playSound();
      var isSmallScreen = (window.innerWidth <= 502) ? 1 : 0;
      var id = localStorage.getItem("sthetho_id");
      if (id) {
        var elm = $(this);
        var xPos = e.pageX - elm.offset().left;
        var yPos = e.pageY - elm.offset().top;

        var s_xPos = xPos-16;
        var s_yPos = yPos-16;
        var l_xPos = xPos-26;
        var l_yPos = yPos-26;

        $("#stethoscope_" + id).animate({ // move the stethoscope to selected point
          left: (isSmallScreen ? s_xPos : l_xPos),
          top: (isSmallScreen ? s_yPos : l_yPos)
        }, {
          duration: 300,
          complete: function() { 
            onStethoscopeMove(); // call this function to play the sound
          }
        });
      }
    });

    function getPositionAdjustments(x,y,r=0){
      var id = localStorage.getItem("sthetho_id");
      var fulscreen_mode = localStorage.getItem("fullscreen");
      var body_img_width = $("#bodymap_"+id).width();
      var body_img_height = $("#bodymap_"+id).height();

      var isSmallScreen = (body_img_width<300)?true:false;
      //set adjustment for small devices
      var prcent_x = (x / 500) * 100;
      var prcent_y = (y / 400) * 100;
      var percent_r = (r / 500) * 100;
      if(fulscreen_mode!=1){
        return {
          x: isSmallScreen ? Number((250 / 100) * prcent_x) : Number(x),
          y: isSmallScreen ? Number((200 / 100) * prcent_y) : Number(y),
          r: isSmallScreen ? Number(r)/2 : Number(r)
        }
      }else{
        var body_position = $("#bodymap_"+id).position();

        
        return {
          x: Number((body_img_width / 100) * prcent_x)+body_position.left ,
          y: Number((body_img_height / 100) * prcent_y)+body_position.top,
          r: isSmallScreen ? Number(r)/2 : Number((body_img_width / 100) * percent_r)
        }
      }

    }

    function adjustFullScreenMode(){
      var fulscreen_mode = localStorage.getItem("fullscreen");
      var id = localStorage.getItem("sthetho_id");
      var body_img_width = $("#bodymap_"+id).width();
      var body_img_height = $("#bodymap_"+id).height();
      var isSmallScreen = (body_img_width<300)?true:false;

      var stetho_percnt_x = ((70 / 500) * 100);
      var stetho_percnt_y = ((70 / 400) * 100);

      var switch_percnt_x = ((36 / 400) * 100);


      if(fulscreen_mode!=1){
        stetho_percnt_x = isSmallScreen?"86px":"70px";
        stetho_percnt_y = isSmallScreen?"86px":"70px";
        $("#stethoscope_"+id).width(stetho_percnt_x);
        $("#stethoscope_"+id).height(stetho_percnt_y);

        $("#body_switch_"+id).width("");
        $("#body_switch_"+id).height("");
        $("#body_switch_"+id).css("background-size","");

        $("#reload_icon_"+id).width("");
        $("#reload_icon_"+id).height("");
        $("#reload_icon_"+id).css("background-size","");
      }else{
        $("#stethoscope_"+id).width(Number((body_img_width / 100) * stetho_percnt_x));
        $("#stethoscope_"+id).height(Number((body_img_height / 100) * stetho_percnt_y));

        //switch size adjustment
        var switch_size = Number(((body_img_width / 100) * switch_percnt_x));
        $("#body_switch_"+id).width(switch_size+2);
        $("#body_switch_"+id).height(switch_size+2);
        $("#body_switch_"+id).css("background-size", (switch_size-5)+"px " + (switch_size-5)+ "px");

        $("#reload_icon_"+id).width(switch_size);
        $("#reload_icon_"+id).height(switch_size);
        $("#reload_icon_"+id).css("background-size", (switch_size-15)+"px " + (switch_size-15) + "px");
      }
    }

    function setFullscreenPositions(){
      var fulscreen_mode = localStorage.getItem("fullscreen");
      var id = localStorage.getItem("sthetho_id");
      var bodymap = $("#bodymap_" +id);
      var switch_btn_width= $("#body_switch_"+id).width();
      var position = bodymap.position();
      var switch_btn = document.getElementById("body_switch_" +id);
      if(fulscreen_mode!=1){
        switch_btn.style.top = '';
        switch_btn.style.right = '';
      }else{
        switch_btn.style.top = position.top + 'px';
        switch_btn.style.right = position.left + 'px';
      }

    }

    //set sound src
    function initAusSounds(id){
      var sounds = $("#sound_"+id).data("sounds");
      for (var key in aus_sound) {
        if(sounds[key]){
          aus_sound[key].preload = "none";
          aus_sound[key].src = sounds[key];
        }
        aus_sound[key].volume = is_muted;
      }
    };

    function checkAllSounds(){
      for (var key in aus_sound) {
        console.log(key + " : " + aus_sound[key].volume);
      }
    };

    $.initSounds = function(id){
      var sounds = $("#sound_"+id).data("sounds");
      for (var key in aus_sound) {
        if(sounds[key]){
          aus_sound[key].preload = "none";
          aus_sound[key].src = sounds[key];
        }
        aus_sound[key].volume = is_muted;
      }
    };

    //IOS Start Sound
    $(".body-map").on("touchstart", function(){
      playSound();
    });
    $(".stethoscope").on("touchstart", function(){
      playSound();
    });
    $(".btn-point ").on("touchstart", function(){
      playSound();
    });

    //Play Sound
    function playSound(){
      var sound_active = localStorage.getItem("sound_active");
      //play all point sound together in muted mode
      if(sound_active!=1){
        for (var key in aus_sound) {
          if(aus_sound[key].src){
            aus_sound[key].volume.is_muted;
            aus_sound[key].play();
          }
        }
      }

      localStorage.setItem("sound_active", 1);
    }

    //Play HeartSound
    function PlayHeartSound(point,volume, arrayOfHearts){
      // heart
      console.log("point: " + point);
      console.log("volume: " + volume);
      var sound_key= point+"_sound_path";
      aus_sound[sound_key].volume = volume;
      //mute sounds for not active points 
      for (var key in arrayOfHearts) {
        if(arrayOfHearts[key].point !== point){
          var sound_key= arrayOfHearts[key].point+"_sound_path";
          aus_sound[sound_key].volume = is_muted;
        }
      }
    }

    function playPulseSound(point,volume,arrayOfPulse) {
      // pulse
      console.log("point: " + point);
      console.log("volume: " + volume);
      var sound_key = point+"_sound_path";
      aus_sound[sound_key].volume = volume;
      //mute sounds for not active points 
      for (var key in arrayOfPulse) {
        var pulse_point = "p"+arrayOfPulse[key].point
        if( pulse_point !== point){
          var sound_key= pulse_point+"_sound_path";
          aus_sound[sound_key].volume = is_muted;
        }
      }
    }

    //Play Lung Sound
    function playLungSound(point,volume,arrayOfLung){
      //lung
      console.log("point: " + point);
      console.log("volume: " + volume);
      var sound_key= point+"_sound_path";
      aus_sound[sound_key].volume = volume;
      //mute sounds for not active points 
      for (var key in arrayOfLung) {
        if(arrayOfLung[key].point !== point){
          var sound_key= arrayOfLung[key].point+"_sound_path";
          aus_sound[sound_key].volume = is_muted;
        }
      }
    }

    // set PlaybackRate
    function setPlaybackRate(id,playback_rate){
      for (var key in aus_sound) {
        if(aus_sound[key].src){
          aus_sound[key].playbackRate = playback_rate ;
        }
      }
    }

    //Stop Playing Sound
    function stopSound(id){
      // stop sound
      for (var key in aus_sound) {
        if(aus_sound[key].src){
          aus_sound[key].pause();
          aus_sound[key].currentTime = 0;
          aus_sound[key].volume = is_muted ;
        }
      }
    }

    //Stop Playing Sound
    function muteHeartSounds(arrayOfHearts){
      for (var key in arrayOfHearts) {
          var sound_key= arrayOfHearts[key].point+"_sound_path";
          aus_sound[sound_key].volume = is_muted;
      }
    }

    function mutePulseSounds(arrayOfPulse){
      
      for (var key in arrayOfPulse) {
        var pulse_point = "p"+arrayOfPulse[key].point
        var sound_key= pulse_point+"_sound_path";
        aus_sound[sound_key].volume = is_muted;
      }
    }

    function muteLungSounds(arrayOfLung){
      for (var key in arrayOfLung) {
        var sound_key= arrayOfLung[key].point+"_sound_path";
        aus_sound[sound_key].volume = is_muted;
      }
    }

    function setSpeedSlider(id){
      var labels = [48,54,60,72,84,96,108,120];
      $("#controller_slider__"+id+" .audio_slider").slider({
            min: 0,
            max: 7,
            value: 2,
            step:1
        })
        .slider("pips", {
            rest: "label",
            labels: labels
      }); 
    }

    //Adjust Sound PlatbackRate
    $( ".audio_slider" ).slider({
      slide:
      function( event, ui ) {
        var id = localStorage.getItem("sthetho_id");
        var playRate = getSoundSpeed(ui.value);
        setPlaybackRate(id,playRate);
      },
      change: function( event, ui ) {
        var id = localStorage.getItem("sthetho_id");
        var playRate = getSoundSpeed(ui.value);
        setPlaybackRate(id,playRate);
      }
    });
    function getSoundSpeed(value){
      switch(value){
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
          localStorage.setItem("fullscreen", 1);
          //set class for fullscreen mode
          $("#aus-img_wrapper_"+id).addClass("div_fullscreen");
          $("#bodymap_"+id).addClass("aus_fullscreen_img");
          $(".aus-fullscreen-off").attr("style", "display:unset");
          $("#body_switch_"+id).addClass("__fullscreen");

          //set refresh button postion on fullscreen
          $(".btn-reload-icon").addClass("btn-reload--fullscreen");
          //setBodyFullscreen();
          //init stethoscope again
          setTimeout(function(){
            adjustFullScreenMode();
            setFullscreenPositions();
            setStethoscopePosition(id);
            stopSound(id);
          }, 500);
      } else {//off fullscreen mode
          $("#aus-img_wrapper_"+id).removeClass("div_fullscreen");
          $("#bodymap_"+id).removeClass("aus_fullscreen_img");
          $(".aus-fullscreen-off").attr("style", "display:none");
          $(".btn-reload-icon").removeClass("btn-reload--fullscreen");
          $("#body_switch_"+id).removeClass("__fullscreen");
          $(".exit-fullscreen-overlay").hide();
          setTimeout(function(){
             // setBodyFullscreen();
              adjustFullScreenMode();
              setFullscreenPositions();
              setStethoscopePosition(id);
              stopSound(id);
          }, 500);
          localStorage.setItem("fullscreen", 0);
      }
      
    });
    // when click fullscreen
    $(".btn-fullscreen-on").click(function(){
      var id = localStorage.getItem("sthetho_id");
      $("#aus-img_wrapper_"+id).addClass("div_fullscreen");
      $("#bodymap_"+id).addClass("aus_fullscreen_img");
      $("#body_switch_"+id).addClass("__fullscreen");
      var selector = "#aus-fullscreen-wrapper_"+id;
      FullScreenHelper.request(selector);
    });
    // when click fullscreen
    $(".aus-fullscreen-off").click(function(){
      $(".exit-fullscreen-overlay").show();
    });

    $(".fullscreen-btn-ok").click(function(){
      FullScreenHelper.exit();
      $(".exit-fullscreen-overlay").hide();
    });

    $(".fullscreen-btn-cancel").click(function(){
      $(".exit-fullscreen-overlay").hide();
    });


});