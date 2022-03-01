<?php
$version = $_GET['asset_ver'];
?>
@extends('layouts.offline_app',[$version])

@section('content')

<div>
    <div style="display:none;" id="asset_version" data-version="{{$version}}"></div>
    <div style="display:none;" id="offline_id" data-id="{{$sound->id}}"></div>
    <div id="data-offline">
    <div class="aus-body_img offline">
            <div id="aus_{{$sound->id}}" class="offline_data open_active" data-id="{{$sound->id}}" data-result="{{ json_encode($sound) }}">
                      <span class="aus-icon-switch_wrapper">
                        <button id="" class="btn-icon aus-fullscreen-on btn-fullscreen-on zoom">
                          @lang('aus.zoom_in')
                        </button>
                        
                        <?php 
                          $sound_path = json_decode($sound->sound_path);
                          $config = json_decode($sound->configuration);
                          // aptm heart
                          $a = isset($sound_path->a_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->a_sound_path : "";
                          $p = isset($sound_path->p_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->p_sound_path : "";
                          $t = isset($sound_path->t_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->t_sound_path : "";
                          $m = isset($sound_path->m_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->m_sound_path : "";

                          $isHeart = 0;
                          if ($a || $p || $t || $m) {
                            $isHeart = 1;
                          } else {
                            // h1-h4
                            for ($i = 1; $i <= 4; $i++) {
                              $h = isset($sound_path->{"h" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"h" . $i . "_sound_path"} : "";
                              if ($h) {
                                $isHeart = 1;
                              }
                            }
                          }
                          // aptm pulse
                          $pa = isset($sound_path->pa_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->pa_sound_path : "";
                          $pp = isset($sound_path->pp_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->pp_sound_path : "";
                          $pt = isset($sound_path->pt_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->pt_sound_path : "";
                          $pm = isset($sound_path->pm_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->pm_sound_path : "";

                          $isPulse = 0;
                          if ($pa || $pp || $pt || $pm) {
                            $isPulse = 1;
                          } else {
                            // p1-p4
                            for ($i = 1; $i <= 4; $i++) {
                              $p = isset($sound_path->{"p" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"p" . $i . "_sound_path"} : "";
                              if ($p) {
                                $isPulse = 1;
                              }
                            }
                          }
                          // front lung
                          $tr1 = isset($sound_path->tr1_sound_path) && isset($config->tr1) && $config->tr1->x && $config->tr1->y && $config->tr1->r ? $sound_path->tr1_sound_path : "";
                          $tr2 = isset($sound_path->tr2_sound_path) && isset($config->tr2) && $config->tr2->x && $config->tr2->y && $config->tr2->r ? $sound_path->tr2_sound_path : "";
                          $br1 = isset($sound_path->br1_sound_path) && isset($config->br1) && $config->br1->x && $config->br1->y && $config->br1->r ? $sound_path->br1_sound_path : "";
                          $br2 = isset($sound_path->br2_sound_path) && isset($config->br2) && $config->br2->x && $config->br2->y && $config->br2->r ? $sound_path->br2_sound_path : "";

                          $isLung = 0;
                          if ($tr1 || $tr2 || $br1 || $br2) {
                            $isLung = 1;
                          } else {
                            for ($i = 1; $i <= 6; $i++) {
                              $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                              if ($ve) {
                                $isLung = 1;
                              }
                            };
                          }

                          // back lung
                          $br3 = isset($sound_path->br3_sound_path) && isset($config->br3) && $config->br3->x && $config->br3->y && $config->br3->r ? $sound_path->br3_sound_path : "";
                          $br4 = isset($sound_path->br4_sound_path) && isset($config->br4) && $config->br4->x && $config->br4->y && $config->br4->r ? $sound_path->br4_sound_path : "";
                          
                          $isLungBack = 0;
                          if ($br3 || $br4) {
                            $isLungBack = 1;
                          } else {
                            for ($i = 7; $i <= 12; $i++) {
                              $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                              if ($ve) {
                                $isLungBack = 1;
                              }
                            };
                          }

                        ?>
                        <button id="heart_icon_{{$sound->id}}" class="btn-heart btn-icon btn-heart-grey-icon" <?php echo $isHeart ? "" : "disabled"; ?> ></button>
                        @if($sound->type == 2) <!-- if heart -->
                          <button id="pulse_icon_{{$sound->id}}" class="btn-pulse btn-icon btn-pulse-grey-icon" <?php echo $isPulse ? "" : "disabled"; ?> ></button>
                        @endif
                        <button id="lung_icon_{{$sound->id}}" class="btn-lung btn-icon btn-lung-grey-icon" <?php echo $isLung ? "" : "disabled"; ?> 
                          data-lung-back="<?php echo $isLungBack ? 1 : 0; ?> "
                          data-lung-front="<?php echo $isLung ? 1 : 0; ?> "
                        >
                        </button>
                      </span>
                      <ul class="main_body_container" id="aus-fullscreen-wrapper_{{$sound->id}}">
                        <button id="btn-fullscreen-off" class="btn-icon aus-fullscreen-off" style="display:none">
                          @lang('aus.zoom_out')
                        </button>
                        <button id="bswitch_{{$sound->id}}" 
                          class="btn-switch-body <?php echo ($sound->type == 1 && $sound->body_image_back != "") ? '' : 'btn-switch-body-disabled';?>"
                          data-front_body="{{ $sound->body_image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-back_body="{{ $sound->body_image_back.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-body="front"
                          <?php echo ($sound->type == 1 && $sound->body_image_back != "")? '' : 'disabled';?>
                        >
                        </button>
                        <li>
                          <div id="aus-img_wrapper_{{$sound->id}}" class="aus-img_wrapper">
                          <!-- <iframe
                              id="body-{{ $sound->id }}"
                              class="bodyFrame"
                              frameborder="0"
                              data-size="1"
                              src="">
                          </iframe> -->
                            <div 
                              id="body-{{ $sound->id }}"
                              class="bodyFrame"
                              frameborder="0"
                              data-size="1"
                            >
                                @include('bodymap.bodymap')
                            </div>
                          </div>
                          <button id="reload_icon_{{$sound->id}}" class="btn-reload-icon"></button>
                        </li>
                        <div class="exit-fullscreen-overlay" style="display:none">
                            <div class="fullscreen-msg">
                              <div class="fullscreen-title-wrapper"> 
                                <p class="fullscreen-msg-title">@lang('aus.fullscreen_exit')</p>
                              </div>
                              <div class="fullscreen-footer-wrapper" >
                                <div data-id="{{$sound->id}}" class="fullscreen-btn-wrapper __ok"> 
                                  <span class="fullscreen-btn fullscreen-btn-ok">@lang('aus.Ok')</span>
                                </div>
                                <div class="fullscreen-btn-wrapper __cancel">
                                  <span class="fullscreen-btn fullscreen-btn-cancel">@lang('aus.Cancel')</span>
                                </div>
                              </div>
                            </div>
                        <div>
                      </ul>
                      <?php 
                        $s = json_decode($sound->sound_path);
                      ?>
                      
                      <div id="controller_slider__{{$sound->id}}" class="aus_controller_wrapper" style="display:none;"> 
                      <!-- <button id="point_a_{{$sound->id}}" data-point="a" class="btn-point btn-point_{{$sound->id}}">A</button>
                      <button id="point_p_{{$sound->id}}" data-point="p" class="btn-point btn-point_{{$sound->id}}">P</button>
                      <button id="point_t_{{$sound->id}}" data-point="t" class="btn-point btn-point_{{$sound->id}}">T</button>
                      <button id="point_m_{{$sound->id}}" data-point="m" class="btn-point btn-point_{{$sound->id}}">M</button>

                      </br> -->
                      <div class="audio_slider"></div>
                      <p class="slider_label">HR</p>
                      </div>

                      <button id="sound_{{$sound->id}}" data-sounds="{{$sound->sound_path}}"></button>
                  </div>
    
    </div>

</div>


<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script>
$(document).ready(function(){
  var offline_id = $("#offline_id").data("id");
  $(window).load(function(){
    window.opener.document.getElementById('offline_switch_'+offline_id).switchButton('on', true);
  });
  $(window).unload(function(){
    let offline_storage = localStorage.getItem('offline');
    let active_tabs = [];
    if(offline_storage){
        active_tabs = JSON.parse(offline_storage);
    }
    const del_index = active_tabs.indexOf(offline_id);
    if (del_index > -1) {
        active_tabs.splice(del_index, 1);
    }
    localStorage.setItem('offline',JSON.stringify(active_tabs));
    window.opener.document.getElementById('offline_switch_'+offline_id).switchButton('off', true);
  });
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
@endsection