@extends('admin.layouts.app')

@section('content')

<!-- 聴診音種別・タイトル -->
<div class="page-header">
  <?php $type_strings = [ 1 => '肺音', 2 => '心音', 3 => '腸音', 9 => 'その他' ]; ?>
  <h2>
    <span class="label label-default">
      {{$type_strings[$stetho_sound->type] or '指定なし'}}
    </span>
    &nbsp; {{$stetho_sound->title?$stetho_sound->title:$stetho_sound->title_en}}
  </h2>
</div><!-- END: 聴診音種別・タイトル -->

<!-- ID・プレイヤー -->
<div class="well">
  <!-- ID
  <div class="col-md-1">
    ID: {{$stetho_sound->id}}
  </div>
  -->
  <!-- プレイヤー -->
  <strong>音源ファイル:</strong><br/><br/>
  @if($stetho_sound->sound_path)
  <audio src="{{ $stetho_sound->sound_path.'?_='.session('version') }}" controls preload></audio>
  @else
  聴診音ファイルが指定されていません。
  @endif
</div><!-- END: ID・聴診音種別・タイトル・プレイヤー -->

<!-- 説明 -->
<div class="well">
  <strong>説明文:</strong><br/><br/>
  {!! $stetho_sound->description !!}
  <br/><br/>
  <strong>音源提供者等:</strong><br/><br/>
  {!! $stetho_sound->sub_description !!}
</div><!-- END: 説明 -->

<!-- 加工方法・正常/異常・代表疾患 -->
<div class="row">
  <!-- 加工方法 -->
  <div class="col-md-3">
    <div class="well">
      <?php $conversion_type_strings = [ 0 => '採取オリジナル', 1 => '加工音', 2 => '人工音' ]; ?>
      <strong>加工方法: </strong>
      {{$conversion_type_strings[$stetho_sound->conversion_type] or '指定なし'}}
    </div>
  </div>
  <!-- 正常/異常 -->
  <div class="col-md-3">
    <div class="well">
      <?php $normal_strings = [ 0 => '異常', 1 => '正常' ]; ?>
      <strong>正常/異常: </strong>
      {{$normal_strings[$stetho_sound->is_normal] or '指定なし'}}
    </div>
  </div>
  <!-- 聴診部位 -->
  <div class="col-md-3">
    <div class="well">
      <strong>聴診部位: </strong>
      {{$stetho_sound->area}}
    </div>
  </div>
  <!-- 代表疾患 -->
  <div class="col-md-3">
    <div class="well">
      <strong>代表疾患: </strong>
      {{$stetho_sound->disease}}
    </div>
  </div>
</div><!-- END: 加工方法・正常/異常・代表疾患 -->

<div class="well">
  <strong>説明画像：</strong><br />
  <div class="row stetho_sound_images">
    @forelse($stetho_sound->images()->get() as $image)
      <div style="" class="col-md-3 text-center">
        <img src="{{ $image->image_path.'?_='.session('version') }}" class="img-responsive center-block" />
        <p>{{ $image->title}}</p>
      </div>
    @empty
      <br/><p class="col-md-6">説明画像が指定されていません。</p>
    @endforelse
  </div>
</div>

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
