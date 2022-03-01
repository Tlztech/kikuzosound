@extends('admin.layouts.app')

@section('content')

<!-- 聴診音種別・タイトル -->
<div class="page-header">
  <h2>
    {{$stetho_sound->title}}
  </h2>
</div><!-- END: 聴診音種別・タイトル -->

<!-- 説明 -->
<div class="well">
  <strong>説明:</strong><br/><br/>
  <p>
    {!!$stetho_sound->description!!}
  </p>
</div><!-- END: 説明 -->

<!-- 加工方法・正常/異常・代表疾患 -->
<div class="row">
  <div class="col-md-3">
    <div class="well">
      <?php $normal_strings = [ 0 => '異常', 1 => '正常' ]; ?>
      <strong>正常/異常: </strong>
      {{$normal_strings[$stetho_sound->is_normal] or '指定なし'}}
    </div>
  </div>
</div><!-- END: 加工方法・正常/異常・代表疾患 -->

<div class="well library-file">
    <div class="row stetho_sound_images">
      <div style="" class="col-md-6 text-center">
        <strong>説明画像 (JP):</strong><br/><br/>
        @if($stetho_sound->images->count())
          <div class="img_slide" style="width: 365px; display: block; margin-left: auto; margin-right: auto;">
            <div class="img_slide_inner">
              <ul class="bxslider_ja_{{$stetho_sound->id}}">
                @foreach($stetho_sound->images as $image)
                  @if($image->lang == "ja")
                    <li>
                      <div class="slider-container" style="background-color: #f5f5f5;">
                      @if (strpos($image['image_path'], 'mp4'))
                        <video id="library_video" controls controlslist="nodownload" disablepictureinpicture style="width: 100%;">
                          <source src="{{ $image->image_path.'?_='.session('version') }}" type="video/mp4">
                        </video>
                      @else
                      <img loading="lazy" src="{{ $image->image_path.'?_='.session('version') }}" style="cursor: pointer; width:100%; object-position: center;"  />
                      @endif
                      </div>
                    </li>
                  @endif
                <!-- TODO: 画面説明　-->
                @endforeach
              </ul>
            </div>
          </div>
          <script>
            $('.bxslider_ja_{{$stetho_sound->id}}').bxSlider();
          </script>
        @endif
      </div>

      <div style="" class="col-md-6 text-center">
        <strong>説明画像 (EN):</strong><br/><br/>
        @if($stetho_sound->images->count())
          <div class="img_slide"  style="width: 365px; display: block; margin-left: auto; margin-right: auto;">
            <div class="img_slide_inner">
              <ul class="bxslider_en_{{$stetho_sound->id}}">
                @foreach($stetho_sound->images as $image)
                  @if($image->lang == "en")
                    <li>
                      <div class="slider-container" style="background-color: #f5f5f5;">
                      @if (strpos($image['image_path'], 'mp4'))
                        <video id="library_video" controls controlslist="nodownload" disablepictureinpicture style="width: 100%;">
                          <source src="{{ $image->image_path.'?_='.session('version') }}" type="video/mp4">
                        </video>
                      @else
                      <img loading="lazy" src="{{ $image->image_path.'?_='.session('version') }}" style="cursor: pointer; width:100%; object-position: center;"  />
                      @endif
                      </div>
                    </li>
                  @endif
                <!-- TODO: 画面説明　-->
                @endforeach
              </ul>
            </div>
          </div>
          <script>
            $('.bxslider_en_{{$stetho_sound->id}}').bxSlider();
          </script>
        @endif
      </div>
    </div>
</div>

<div class="well library-file">
    <div class="row stetho_sound_images">
      <div style="" class="col-md-6 text-center">
        <strong>身体画像(前面):</strong><br/><br/>
        <img src="{{ $stetho_sound->body_image.'?_='.session('version') }}" class="body-img-center" />
      </div>
      <div style="" class="col-md-6 text-center">
        <strong>身体画像(背面):</strong><br/><br/>
        <img src="{{ $stetho_sound->body_image_back.'?_='.session('version') }}" class="body-img-center" />
      </div>
    </div>
</div>

<!-- 説明 -->
<div class="well">
    <strong>画像説明 (JP):</strong><br/><br/>
    <p>
      {!!$stetho_sound->image_description!!}
    </p>
</div><!-- END: 説明 -->

<!-- 説明 -->
<div class="well">
    <strong>画像説明 (EN):</strong><br/><br/>
    <p>
      {!!$stetho_sound->image_description_en!!}
    </p>
</div><!-- END: 説明 -->

<?php 
  $config = json_decode($stetho_sound->configuration);
  $s = json_decode($stetho_sound->sound_path);
?>
<strong>設定:</strong><br/><br/>
<div class="row">
  <!-- 加工方法 -->
  <div class="col-md-6">
    <div class="well">
      <b>A:</b> {{ !empty($config->a->x)? $config->a->x : '0' }}px, {{ !empty($config->a->y)? $config->a->y : '0' }}px, {{ !empty($config->a->r)? $config->a->r : '0' }}r
      <br>
      <audio src="{{ !empty($s->a_sound_path)? $s->a_sound_path.'?_='.session('version') : '' }}" controls preload></audio>
    </div>
  </div>
  <!-- 正常/異常 -->
  <div class="col-md-6">
    <div class="well">
      <b>P:</b> {{ !empty($config->p->x)? $config->p->x : '0' }}px, {{ !empty($config->p->y)? $config->p->y : '0' }}px, {{ !empty($config->p->r)? $config->p->r : '0' }}r
      <br>
      <audio src="{{ !empty($s->p_sound_path)? $s->p_sound_path.'?_='.session('version') : '' }}" controls preload></audio>
    </div>
  </div>
  <!-- 聴診部位 -->
  <div class="col-md-6">
    <div class="well">
      <b>T:</b> {{ !empty($config->t->x)? $config->t->x : '0' }}px, {{ !empty($config->t->y)? $config->t->y : '0' }}px, {{ !empty($config->t->r)? $config->t->r : '0' }}r
      <br>
      <audio src="{{ !empty($s->t_sound_path)? $s->t_sound_path.'?_='.session('version') : '' }}" controls preload></audio>
    </div>
  </div>
  <!-- 代表疾患 -->
  <div class="col-md-6">
    <div class="well">
      <b>M:</b> {{ !empty($config->m->x)? $config->m->x : '0' }}px, {{ !empty($config->m->y)? $config->m->y : '0' }}px, {{ !empty($config->m->r)? $config->m->r : '0' }}r
      <br>
      <audio src="{{ !empty($s->m_sound_path)? $s->m_sound_path.'?_='.session('version') : '' }}" controls preload></audio>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-3">
      <div class="well">
        <strong>座標: </strong>
        {{$stetho_sound->coordinate}}
      </div>
    </div>
</div><!-- END: 加工方法・正常/異常・代表疾患 -->

<!-- 監修コメント -->
<hr />
<h4>監修コメント</h4>
<div class="well">
  <input type="hidden" id="stetho_sound_id" value="{{ $stetho_sound->id }}"/>
  <div id="comments_area">
  @foreach($stetho_sound->comments()->get() as $comment)
      <div class="comment_item" style="padding-bottom: 1em; margin-bottom: 1em; border-bottom: 1px solid gray;" data-id="{{$comment->id}}">
        <p>
          <div style="float:left;">{{ $comment->created_at}}&nbsp;&nbsp;<strong>{{ $comment->user->name}}</strong></div>
          @if(\Auth::user()->isAdmin() || (!\Auth::user()->isAdmin() && \Auth::user()->id == $comment->user_id))
          <div style="float:right;"><a class="btn btn-sm btn-default edit_comment__btn">編集</a>&nbsp;<a class="btn btn-sm btn-danger remove_comment__btn">削除</a></div>
          @endif
          <div style="clear: both;"></div>
        </p>
        <p class="comment_text" data-id="{{$comment->id}}">{{ $comment->text }}</p>
        <textarea name="comment" class="form-control" style="display: none;">{{ $comment->text }}</textarea>
        <button type="button" class="btn btn-sm btn-primary update_comment__btn" style="margin-top: 4px; display: none;">更新</button>
      </div>
  @endforeach
  </div>
  <textarea id="comments__textarea" name="comment" class="form-control"></textarea><br/>
  <button type="button" id="add_comments__btn" class="btn btn-primary" disabled>コメント保存</button>
  <span>※コメントはすぐに保存されます</span>
</div><!-- END: 監修コメント -->
<hr /><!-- END: 監修コメント -->

<!-- ステータス -->
<div class="well">
  <strong>ステータス:</strong>&nbsp;&nbsp;
    <form action="{{ route('admin.stetho_sounds.update_status', $stetho_sound->id) }}" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <?php $public = ($stetho_sound->status == 2 && Auth::user()->role != App\User::$ROLE_ADMIN) ? 'disabled' : ''; ?>
        <label class="radio-inline">
          <input type="radio" name="status" id="status" value="0" @if($stetho_sound->status == 0) checked @endif {{ $public }}> 監修中
        </label>
        <label class="radio-inline">
          <input type="radio" name="status" id="status" value="1" @if($stetho_sound->status == 1) checked @endif {{ $public }}> 監修済
        </label>
        <?php $disabled = (Auth::user()->role != App\User::$ROLE_ADMIN) ? 'disabled' : ''; ?>
        <label class="radio-inline">
          <input type="radio" name="status" id="status" value="2" @if($stetho_sound->status == 2) checked @endif {{ $disabled }}> 公開中
        </label>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" class="btn btn-primary" value="監視状況更新" {{ $public }}/>
      </div>
    </form>
</div><!-- END: ステータス -->

<script type="text/javascript" src="/js/jquery.imagebox.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.stetho_sound_images img').imagebox();
});
</script>

@endsection
