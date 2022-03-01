<div class='row'>
    <div class="col-md-12">
        <table class="table">
            <tbody id="fill-in_final_quiz_choice__tbody" data-lib_type="{{$lib_type['input_name']}}" class="form-group">
                <?php $lib = $lib_type['input_name'].'_quiz_choices'?>
                    @foreach((array)old($lib) as $i => $v)
                    <tr data-key="{{$i}}">
                        <td style="width:90%">
                            <input type="hidden" class="form-control" name="{{$lib}}[{{$i}}][fill_in][lib_key]"
                                value="{{$lib_key}}" />

                            <input name="{{$lib}}[{{$i}}][fill_in][title]" 
                                    type="text" class="form-control"
                                    value="{{old( $lib.'.' . $i . '.fill_in.title')}}">
                        </td>
                        <td class="text-right">
                            <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                            <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                        </td>
                    </tr>
                    @endforeach

                    @if(count(array_filter(array_keys((array)old($lib)), 'is_int' )) < 1 && !isset($is_edit))
                        <tr data-key="0">
                            <td style="width:90%">
                                <input type="hidden" class="form-control" name="{{$lib}}[0][fill_in][lib_key]"
                                    value="{{$lib_key}}" />

                                <input name="{{$lib}}[0][fill_in][title]"
                                        type="text" class="form-control"
                                        value="">
                            </td>
                            <td class="text-right">
                                <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                                <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                            </td>
                        </tr>
                    @endif
                    @if(isset($is_edit) && empty(array_filter(array_keys((array)old($lib)), 'is_int' )))
                        @if(isset($quiz_library_choices))
                            @foreach($quiz_library_choices as $i => $c)
                                <tr data-key="{{$i}}">
                                    <td style="width:90%">
                                        <input type="hidden" class="form-control" name="{{$lib}}[{{$i}}][fill_in][lib_key]"
                                            value="{{$lib_key}}" />

                                        <input name="{{$lib}}[{{$i}}][fill_in][title]" 
                                                type="text" class="form-control"
                                                value="{{old( $lib.'.' . $i . '.fill_in.title', $c['title'])}}">
                                    </td>
                                    <td class="text-right">
                                        <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                                        <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    @if(isset($quiz_library_choices))
                        @if(count($quiz_library_choices ) < 1 && isset($is_edit))
                            <tr data-key="0">
                                <td style="width:90%">
                                    <input type="hidden" class="form-control" name="{{$lib}}[0][fill_in][lib_key]"
                                        value="{{$lib_key}}" />

                                    <input name="{{$lib}}[0][fill_in][title]"
                                            type="text" class="form-control"
                                            value="">
                                </td>
                                <td class="text-right">
                                    <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                                    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                                </td>
                            </tr>
                        @endif
                    @endif
            </tbody>
        </table>
        <button id="add_quiz_fill-in_choices＿btn" class="btn btn-success pull-right" type="button"
            data-lib_type="{{$lib_type['input_name']}}" data-lib_key="{{$lib_key}}">解答ワードを追加</button>
    </div>
</div>