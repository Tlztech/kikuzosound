<div class='row'>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>正解</th>
                    <th>選択肢</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="{{$lib_type['input_name']}}_quiz_choice__tbody" data-lib_type="{{$lib_type['input_name']}}" class="form-group">
                <?php $lib = $lib_type['input_name'].'_quiz_choices'?>
                @for ($i = 0; $i < count(array_filter(array_keys((array)old($lib)), 'is_int' )); $i++) <tr>
                    <td>
                        <input type="radio" name="{{$lib}}_correct_index" value="{{old( $lib.'_correct_index')}}"
                            @if(old( $lib.'_correct_index')==$i) checked @endif />
                        <input type="hidden" class="form-control" name="{{$lib}}[{{$i}}][lib_key]"
                            value="{{$lib_key}}" />
                    </td>
                    <td>
                        <input type="text" name="{{$lib}}[{{$i}}][title]" class="form-control"
                            value="{{old( $lib.'.' . $i . '.title')}}" placeholder="JP" required/>
                    </td>
                    <td>
                        <input type="text" name="{{$lib}}[{{$i}}][title_en]" class="form-control"
                            value="{{old( $lib.'.' . $i . '.title_en')}}" placeholder="EN" required/>
                    </td>
                    <td class="text-right">
                        <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                        <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                    </td>
                    </tr>
                    @endfor
                    @if(isset($is_edit) && empty(array_filter(array_keys((array)old($lib)), 'is_int' )))
                        @foreach($quiz_library_choices as $i => $c)
                            @if(!$c->is_fill_in)
                            <tr>
                                <td>
                                    <input type="radio" name="{{$lib}}_correct_index" value="{{$i}}" @if($c->is_correct) checked
                                    @endif />
                                    <input type="hidden" class="form-control" name="{{$lib}}[{{$i}}][lib_key]"
                                        value="{{$lib_key}}" />
                                </td>
                                <td>
                                    <input type="text" name="{{$lib}}[{{$i}}][title]" class="form-control" value="{{$c->title}}"
                                        placeholder="JP" required/>
                                </td>
                                <td>
                                    <input type="text" name="{{$lib}}[{{$i}}][title_en]" class="form-control"
                                        value="{{$c->title_en}}" placeholder="EN" required/>
                                </td>
                                <td class="text-right">
                                    <a id="remove_quiz_choice__btn" class="btn btn-danger">削除</a>
                                    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
            </tbody>
        </table>
        <button id="add_quiz_choices＿btn" class="btn btn-success pull-right" type="button"
            data-lib_type="{{$lib_type['input_name']}}" data-lib_key="{{$lib_key}}">選択肢を追加</button>
    </div>
</div>