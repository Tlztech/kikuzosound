<!-- お知らせ -->
        <div class="side_box notice mB20 ">
          <h3 class="side_title">@lang('news.sidetitle')</h3>
          <br>
          <!-- .notice_list -->
          <div style="margin-right: 10px;overflow-y: scroll;height:33vh;" class="notice_list side_box_inner">
            <ul>
              @foreach($news as $new)
              <li>
                <p class="time"> @if(config('app.locale') == 'en') {{$new->created_at->format('F d, Y')}} @else {{$new->created_at->format('Y年n月j日')}} @endif</p>
                <p class="text">
                  @if(config('app.locale') == 'en')
                        {{$new->description_en}}
                        @else
                        {{$new->description}}
                        @endif
                      </p>
              </li>
              @endforeach
              <!-- <li>
                <p class="time">@lang('news.li1.p1')</p>
                <p class="text">@lang('news.li1.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li2.p1')</p>
                <p class="text">@lang('news.li2.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li3.p1')</p>
                <p class="text">@lang('news.li3.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li4.p1')</p>
                <p class="text">@lang('news.li4.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li5.p1')</p>
                <p class="text">@lang('news.li5.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li6.p1')</p>
                <p class="text">@lang('news.li6.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li7.p1')</p>
                <p class="text">@lang('news.li7.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li8.p1')</p>
                <p class="text">@lang('news.li8.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li9.p1')</p>
                <p class="text">@lang('news.li9.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li10.p1')</p>
                <p class="text">@lang('news.li10.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li11.p1')</p>
                <p class="text">@lang('news.li11.p2')</p>
              </li>
              <li>
                <p class="time">@lang('news.li12.p1')</p>
                <p class="text">@lang('news.li12.p2')</p>
              </li> -->
            </ul>
          </div>
            <!-- /.notice_list -->
          <p class="link_red" ><a href="{{route('news')}}">@lang('news.pred')</a></p>
        </div>
        <!-- /.side_box notice -->

