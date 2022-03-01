@if ($breadcrumbs)
<div class="breadcrumb">
  <ul class="breadcrumb_inner clearfix">
    @foreach ($breadcrumbs as $breadcrumb)
      @if (!$breadcrumb->last)
        <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a><span>></span></li>
      @else
        <li class="active">{{ $breadcrumb->title }}</li>
      @endif
    @endforeach
  </ul>
</div>
@endif
