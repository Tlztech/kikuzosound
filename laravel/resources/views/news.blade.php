@extends('layouts.app')

@section('title', 'Information')

@section('breadcrumb')
{!! Breadcrumbs::render('news') !!}
@endsection
@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" class="news-container">
    <!-- お知らせ -->
    <div class="container_inner clearfix">
        <div class="contents">
            <div class="about mTB20">
                <div class="about_title">
                    <div class="news_title">@lang('news.title')</div>
                </div>
                <ul class="news_list">
                    @foreach($news as $new)
                    <li><time>
                    @if(config('app.locale') == 'en') {{$new->created_at->format('F d, Y')}} @else {{$new->created_at->format('Y年n月j日')}} @endif                        </time>
                        <span>
                            @if(config('app.locale') == 'en')
                            {{$new->description_en}}
                            @else
                            {{$new->description}}
                            @endif
                        </span>
                    </li>
                    @endforeach
                    </ul>
                </div>
                <div class="pager">
        <div class="pager_inner">
          {!! (new App\Http\Paginators\PagingPaginator(
                $news->appends([
                  'show'=>Input::get('show','10'),
                  'keyword'=>Input::get('keyword',''),
                  'filter'=>Input::get('filter',''),
                  'sort'=>Input::get('sort','')
                ])
            ))->render()
          !!}
        </div>
      </div>
            </div>

            <!-- サイドコンテンツ（右） -->
            <div class="side_column">
                <!-- Aboutリンクー -->
                @include('layouts.about_menus')
                <!-- 聴診専用スピーカとは？ -->
                @include('layouts.whatspeaker')
            </div>
        </div>
    </div>
    @endsection
