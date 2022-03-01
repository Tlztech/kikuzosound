@extends('layouts.app')

@section('title', 'My Page')

@section('breadcrumb')
{!! Breadcrumbs::render('mypage') !!}
@endsection

@section('content')
<?php
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID');
  $university_id = DB::table("onetime_keys")->where("customer_id", $user_id)->first()->university_id;
  if($user_id==null) {
    $user_id=0;
  }
?>

<style>
  td {
    text-align: center;
  }
</style>

<!-- メインコンテンツ（左） -->
<div id="container" class="mypage">
	<!-- お問い合わせ -->
	<div class="container_inner clearfix">
		<div class="contents" id="mypage-contents">
			<div class="sp-mypage-menu-container">
				<ul class="select_tag pc_none append_list sp-mypage-menu">
					<li><a href="#recommend">@lang('mypage_menus.li1')</a></li>
					<li><a href="#bookmarks">@lang('mypage_menus.li2')</a></li>
					<li><a href="#analysis">@lang('mypage_menus.li3')</a></li>
				</ul>
			</div>
			<!-- Recommend Ausculaide Content -->
			<span class="anchor" id="recommend"></span>
			<div id="recommend">
        @if(env('APP_ENV') === 'local' || env('APP_ENV') === 'development' )
          <h1 class="sup-text">@lang('mypage.r1')</h1>
        @endif
				<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
					<label style="padding-right: 40px; background-color: #fef9f5;"> @lang('mypage.recommend_content')</label>
				</h2>
        <div class="contents_box sp_mTB20 mTB20" >
          <ul class="sound_list accordion">
            <?php $type_strings = [0 => trans('list.tag-stetho'), 1 => trans('list.tag-aus'), 2 => trans('list.tag-palp'), 3 => trans('list.tag-ecg'), 4 => trans('list.tag-ins') , 5 => trans('list.tag-xray'), 6 => trans('list.tag-echo')]; ?>
            <li class="sound_box aus-sound_box rec-ipax-tag" data-id="{{ 0 }}">
                <p class="tag" id="{{ 0 }}" data-favo="{{ 0 }}" data-lib_type="{{1}}"><span>{{$type_strings[1]}}</span></p>
            </li>
          </ul>
					<div class="pTB20" style="height:auto;">
          <iframe
                        id="recommended_ausculaide"
                        class=""
                        frameborder="0"
                        data-size="1"
                        src=""
                        style="width:100%;"
                        >
                      </iframe>
					</div>
				</div> 
			</div>
			<!-- Recommend Library -->
			<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
				<label style="padding-right: 40px;background: #fef9f5;">@lang('mypage.recommend_lib')</label>
			</h2>
      <div id="recommended_library_contents"> 
      <div class="contents_box sp_mTB20 mTB20">
					<div class="contents_box_inner pTB20">
            <div class="loading-wrapper">
                <div class="loading">
                    <div class="sk-fading-circle">
                        <div class="sk-circle1 sk-circle"></div>
                        <div class="sk-circle2 sk-circle"></div>
                        <div class="sk-circle3 sk-circle"></div>
                        <div class="sk-circle4 sk-circle"></div>
                        <div class="sk-circle5 sk-circle"></div>
                        <div class="sk-circle6 sk-circle"></div>
                        <div class="sk-circle7 sk-circle"></div>
                        <div class="sk-circle8 sk-circle"></div>
                        <div class="sk-circle9 sk-circle"></div>
                        <div class="sk-circle10 sk-circle"></div>
                        <div class="sk-circle11 sk-circle"></div>
                        <div class="sk-circle12 sk-circle"></div>
                    </div>
                </div>
            </div>
					</div>
				</div> 
      </div>

			<span class="anchor" id="bookmarks"></span>
			<!-- Bookmarks  -->
			<div id="bookmarks">
				<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
					<label style="padding-right: 40px; background: #fef9f5;">@lang('mypage.bookmarks')</label>
				</h2>
				@include('mypage.bookmarks',[$params, $bookmarks, $bookmarkCount, $auth])
			<span class="anchor" id="analysis"></span>
			<div id="analysis">
				<!-- Exam Report  -->
				<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
					<label style="padding-right: 40px;background: #fef9f5;">@lang('mypage.exam_report')</label>
				</h2>
				<div class="contents_box sp_mTB20 mTB20">
					<div class="contents_box_inner pTB20">
            @include('mypage.exam_report')
						
					</div>
				</div> 

				<!-- Score Ranking -->
				<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
					<label style="padding-right: 40px;background: #fef9f5;">@lang('mypage.rate_by_content')</label>
				</h2>
				<div class="contents_box sp_mTB20 mTB20">
					<div class="contents_box_inner pTB20">
            @include('mypage.rate_by_content')
						
					</div>
				</div> 
				
				<!-- Usage Status -->
				<h2 class="sub_title" style="background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;">　
					<label style="padding-right: 40px;background: #fef9f5;">@lang('mypage.usage_status')</label>
					</h2>
				<div class="contents_box sp_mTB20 mTB20">
					<div class="contents_box_inner pTB20" style="height:450px;">
            @include('mypage.usage_status')
						
					</div>
				</div> 
			</div>
			<div class="home-sort-wrapper" style="">
            	<a class="home-sort-btn" href="#recommend">@lang('mypage.go_back_btn')</a>
        	</div>

		</div>
		
		<!-- サイドコンテンツ（右） -->
		<div class="side_column">
	    @include('layouts.mypage_menus')
	    </div>
</div>
<script type="text/javascript" src="/js/jquery.sss_portal.audio.js?v=1.2.0.20190610"></script>
<script type="text/javascript">
setUpAudioJs();
function setUpAudioJs(){
  audiojs.events.ready(function() {
  /****************************************************************/
  /* 試聴音登録 */
  //return false;   // 聴診音を準備させない(要は音を出させない)
  // ここで制御せず「jquery.sss_portal.audio.js」で制御に変更
  /****************************************************************/
    var audios = document.getElementsByTagName('audio');
    if (audios.length > 0) {
      var a1 = audiojs.create(audios, {
        css: false,
        createPlayer: {
          markup: false,
          playPauseClass: 'play-pauseZ',
          scrubberClass: 'scrubberZ',
          progressClass: 'progressZ',
          loaderClass: 'loadedZ',
          timeClass: 'timeZ',
          durationClass: 'durationZ',
          playedClass: 'playedZ',
          errorMessageClass: 'error-messageZ',
          playingClass: 'playingZ',
          loadingClass: 'loadingZ',
          errorClass: 'errorZ'
        }
      });
    }

    $({}).sss_portal.audio({
      audioJsObj: a1
    });
  });
}

</script>
<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.7"></script>
<!-- <script type="text/javascript" src="/js/bodymap.js"></script> -->
<script type="text/javascript">
$(document).ready(function(){
  $('.sound_box img').not('img.body-map').imagebox();
});
</script>

<script type="text/javascript">
function setUpSoundZ(){
    $('.sound_box .playZ').logger({
    url: '/log',
    data: function(elm) {
      var $sound_box = $(elm).closest('.sound_box');
      var data = {
        screen_code: 'HOME',
        event_code: 'PLAY',
        body: {
          stetho_sound: {
            id: $sound_box.data('id'),
            type: $.trim($sound_box.find('.tag').text()),
            title: $sound_box.find('.sound_title .name').attr('title')
          }
        }
      };
      return data;
    }
  });
}
(function($){
  setUpSoundZ();
  
})(jQuery);
$(window).on('load',function () {
    $("#tabBox2").hide();
    $("#tabBox3").hide();
    $("#tabBox5").hide();

    $(".accordion_moreconts").show();
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
    setmyPageAtrributes();
    window.touchPunchDelay=100;
    $("#bookmarks__tbody").sortable({
      // 回答の表示順の変更イベント
      update: function(event, ui) {
        var $li = $('#bookmarks__tbody>li');
        var arr = []
        $li.children('input[type="hidden"]').each(function(i, e){
          $(e).attr('name','favorites[' + i + '][disp_order]');
          $name = 'favorites[' + i + '][disp_order]';
          arr[i] = {
            favorites_id: $('input[name="' + $name + '"]').val(),
            disp_order: i
          }
        });

        var data = JSON.stringify({"favorites": arr});
        $.ajax({
          url : '/favorites_reorder',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data : data,
          type : 'POST',
          contentType : 'application/json',
          success: function(res){
            //if(res == 1) console.log("bookmark reordered!");
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert( "error : " + XMLHttpRequest );
          },
        });
      }
    });
  });
  function setmyPageAtrributes(){
    $(".tag").each( function() {  // タグ
      var lib_type = $(this).data('lib_type');
    
      switch(lib_type){
        case 0:
          return $(this).css('background-image', "url(../img/tag-stetho.png)"); 
        case 1:
          return $(this).css('background-image', "url(../img/tag-aus.png)"); 
        case 2:
          return $(this).css('background-image', "url(../img/tag-palp.png)"); 
        case 3:
          return $(this).css('background-image', "url(../img/tag-ecg.png)"); 
        case 4:
          return $(this).css('background-image', "url(../img/tag-ins.png)"); 
        case 5:
          return $(this).css('background-image', "url(../img/tag-xray.png)"); 
        case 6:
          return $(this).css('background-image', "url(../img/tag-echo.png)"); 
      }
    });

    $(".tag").each(function() { // タグ
      var favo = $(this).data('favo'); // お気に入り状態

      if (favo == 1) { // お気に入りに登録されている場合
        $(this).css('background-image', 'url(../img/tag-favo.png)');
      }
    });

    $(".FavButton").each(function() { // タグ
      var favo = $(this).data('favo'); // お気に入り状態

      if (favo == 1) { // お気に入りに登録されている場合
        $(this).css('background', '#eeeeee');
        $(this).css('border-color', '#eeeeee');
        $(this).css('color', '#000000');
        $(this).css('background-image', 'url(../img/no-delete.png)');
        $(this).css('background-repeat', 'no-repeat');
        $(this).css('background-position', '8% 46%');
      }
    });

    // aus video
    $(".library_video_aus").on("play", function (e) {
      var v = $(this);
      $("video").each(function( index ) {
        if ($(v).attr("src") != $(this).attr("src")) {
          $(this).get(0).pause()
        }
      });
    });

    // library video
    $(".library_video_stetho, .library_video_palpa, .library_video_inspi").on("play", function (e) {
      if($(this).attr("id") == "library_video") {
        var id = $(this).att("data-id");
        var className = $(".audiojsZ_" + id).attr("class");
        if (className.includes("playingZ")) {
          $(".pauseZ_vid_" + id).trigger("click");
        }
        var v = $(this);
        $("video").each(function( index ) {
          if ($(v).attr("src") != $(this).attr("src")) {
            $(this).get(0).pause()
          }
        });
      } else {
        var v = $(this);
        $("video").each(function( index ) {
          if ($(v).attr("src") != $(this).attr("src")) {
            $(this).get(0).pause()
          }
        });
      }
    });
  }
  </script>
  <script>
    var universityId = {{ $university_id }};
    window.addEventListener('load', async (event) =>  {
    var params = {
        'user_id'              : <?php echo $user_id; ?>,
        'base_url'             : window.location.protocol + '//' + location.host,
        'model_path'           : '/tf_model/model.json',
        'learning_data_path'   : '/api/get_learning_data',
        'recommended_data_path': '/api/get_recommended_data'
    }
    var libdata = await getRecommendWithTensorflow(params);
    console.log("lib_data",libdata);
    let ids=[];
    

    // recommended ipax
    let aus_id = null;
    let aus_id_has_group = null;
    let aus_id_no_group = null;

    let aus_id_has_group_2 = null
    let aus_id_no_group_2 = null

    for (var key in libdata) {
      if(libdata[key].lib_type == 1){
        
        if (aus_id == null) aus_id = libdata[key].lib_id;
        
        if (libdata[key].univ_ids.length > 0) { // has group
          for (var i in libdata[key].univ_ids) {
            if (libdata[key].univ_ids[i].exam_group_id == universityId) {
              
              if (aus_id_has_group == null) aus_id_has_group = libdata[key].lib_id;

              if (aus_id_has_group_2 == null && aus_id_has_group != libdata[key].lib_id) {
                aus_id_has_group_2 = libdata[key].lib_id;
              }
                
            }
          }

        } else { // not belong to any group
          if (aus_id_no_group == null) aus_id_no_group = libdata[key].lib_id; 
          //aus_id_no_group = null;

          if (aus_id_no_group_2 == null && aus_id_no_group != libdata[key].lib_id) {
            aus_id_no_group_2 = libdata[key].lib_id; 
          }
        }
        // console.log(libdata[key])
      }
    }

    if (aus_id_has_group) {
      aus_id = aus_id_has_group;
    } else if (!aus_id_has_group && aus_id_no_group) {
      aus_id = aus_id_no_group;
    } else {
      aus_id = null;
    }

    // 1 ipax dedicated item
    let rec_ipax2=null;
    if (aus_id_has_group_2) {
      ids.push(aus_id_has_group_2);
      rec_ipax2=aus_id_has_group_2;
    } else if(!aus_id_has_group_2 && aus_id_no_group_2) {
      ids.push(aus_id_no_group_2);
      rec_ipax2=aus_id_no_group_2;
    }

    if (rec_ipax2 != null) {
      sessionStorage.setItem("rec_ipax", rec_ipax2);
    }
    if (aus_id != null) {
      sessionStorage.setItem("main_rec_ipax", aus_id);
    }

    let recommendedLibCtr = 1;

    // get recommended libs

    // stetho
    for (var key in libdata) { 
      if (recommendedLibCtr <= 5) {

        if (libdata[key].univ_ids.length > 0) { // has group
          for (var i in libdata[key].univ_ids) {
            if (libdata[key].univ_ids[i].exam_group_id == universityId) {

              if (libdata[key].lib_type != 1) {
                ids.push(libdata[key].lib_id);
                recommendedLibCtr++;
              }

            }
          }
        } else { // no group
          if (libdata[key].lib_type != 1) {
            ids.push(libdata[key].lib_id);
            recommendedLibCtr++;
          }
        }
      }
    }

    // if (recommendedLibCtr <= 5) {
    //   for (var key in libdata) { 
    //     if (recommendedLibCtr <= 5) {

    //       var hasDuplicate = 0;

    //       ids.forEach(function (item, index) {
    //         if(item == libdata[key].lib_id) {
    //           hasDuplicate = 1;
    //         }
    //       });

    //       if (!hasDuplicate) {

    //         if (libdata[key].lib_type != 1) {
    //           ids.push(libdata[key].lib_id);
    //           recommendedLibCtr++;
    //         }
    //       }
    //     }
    //   }
    // }

    if (aus_id != null) {
      let rec_aus_url= window.location.protocol + '//' + location.host + "/ajax_recommended_aus?aus=" + aus_id;
      $("#recommended_ausculaide").attr("src", rec_aus_url);
    } else {
      $("#recommended_ausculaide").attr("style", "height:100px");
    }
    
    $.ajax({
            data:{
                "lib_ids": ids,
            },
            url: window.location.protocol + '//' + location.host + "/ajax_recommended",
            type: 'GET',
            success: function(res) {
                // get the ajax response data

                // update modal content here
                $('#recommended_library_contents').html();
                $('#recommended_library_contents').html(res);
                setmyPageAtrributes();
                setUpAudioJs();
                setUpSoundZ();
                
                setTimeout(function() {
                  $("#recommended_ausculaide").contents().find(".sound_box img").not('img.body-map').imagebox();
                }, 3000);
                
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
    //console.log(ids);
    });
    </script>
<script>
if ('serviceWorker' in navigator) {
  var CACHE_NAME = "aus-cache{{session('cache_version')}}";
  caches.keys().then(function(cacheNames) {
    return Promise.all(
      cacheNames.map(function(cacheName) {
        if(cacheName != CACHE_NAME) {
          return caches.delete(cacheName);
        }
      })
    );
  });
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js?cache_version='+CACHE_NAME).then(function(registration) {
      // Registration was successful
      //console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      //console.log('ServiceWorker registration failed: ', err);
    });
  });
}
</script>
<style>
.bodyFrame{
  width:100%;
  height:450px;
}
.aus_recommend .tag{
    width: 50px;
    height: 25px;
    margin-left: -10px;
    margin-top: 10px;
    text-align: center;
}
.recommended_sound_accordion_open.sound_title.accordion_active.aus{
  width:100%;
}
.recommended_sound_accordion_open.sound_title.accordion_active.aus{
  width:100%;
}
.recommended_sound_accordion_open.sound_title.accordion_active.aus{
  width:700px;
} 
.sound_list .recommended_sound_accordion_open.open_active{
  background-image:unset !important;
}
.recommended_sound_accordion_open .ausc-img-container{
  height: 700px;
}
</style>
@endsection