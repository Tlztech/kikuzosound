<div class="row">
    <table class="table">
        <tbody id="{{$lib_type['input_name']}}__tbody" data-lib_type="{{$lib_type['input_name']}}">
            <?php $lib = $lib_type['input_name'];?>
            @for ($i = 0; $i < count((array)old($lib)); $i++) <tr>
                <td>{{old( $lib.'.' . $i . '.title')}}</td>
                <td>
                    <input type="text" class="form-control" name="{{$lib}}[{{$i}}][description]" placeholder="JP"
                        value="{{old( $lib.'.' . $i . '.description')}}" />
                </td>
                <td>
                    <input type="text" class="form-control" name="{{$lib}}[{{$i}}][description_en]" placeholder="EN"
                        value="{{old( $lib.'.' . $i . '.description_en')}}" />
                </td>
                <td class="text-right" style="white-space: nowrap;">
                    <input id="title__input" type="hidden" name="{{$lib}}[{{$i}}][title]"
                        value="{{old( $lib.'.' . $i . '.title')}}" />
                    <input id="id__input" type="hidden" name="{{$lib}}[{{$i}}][id]"
                        value="{{old( $lib.'.' . $i . '.id')}}" />
                    <a class="btn btn-default select_stetho_sound_description_btn"
                        data-lib_type="{{$lib_type['input_name']}}"
                        data-id="{{old( $lib.'.' . $i . '.id')}}">{{$lib_type['explanation_label']}}</a>
                    <a id="remove_stetho_sound＿btn" class="btn btn-danger" data-lib_type="{{$lib_type['input_name']}}" data-id="{{old( $lib.'.' . $i . '.id')}}"
                        data-title="{{old( $lib.'.' . $i . '.title')}}">削除</a>
                    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                </td>
                </tr>
            @endfor
            @if(isset($is_edit) && empty(old($lib)))
            @foreach($quiz_library as $i => $s) 
            <tr>
                <td>{{$s->title_en}}</td>
                <td>
                    <input type="text" class="form-control" name="{{$lib}}[{{$i}}][description]" placeholder="JP"
                        value="{{ $s->pivot->description }}" />
                </td>
                <td>
                    <input type="text" class="form-control" name="{{$lib}}[{{$i}}][description_en]" placeholder="EN"
                        value="{{ $s->pivot->description_en }}" />
                </td>
                <td class="text-right" style="white-space: nowrap;">
                    <input id="title__input" type="hidden" name="{{$lib}}[{{$i}}][title]"
                        value="{{$s->title}}" />
                    <input id="id__input" type="hidden" name="{{$lib}}[{{$i}}][id]"
                        value="{{$s->id}}" />
                    <a class="btn btn-default select_stetho_sound_description_btn"
                        data-lib_type="{{$lib_type['input_name']}}"
                        data-id="{{$s->id}}">{{$lib_type['explanation_label']}}</a>
                    <a id="remove_stetho_sound＿btn" class="btn btn-danger" data-lib_type="{{$lib_type['input_name']}}" data-id="{{$s->id}}"
                        data-title="{{$s->title}}">削除</a>
                    <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                </td>
                </tr>
            @endforeach
            @endif

        </tbody>
    </table>
</div>
<div class="row lib_selection_wrapper">
    <div class="col-md-12">
        <button id="add_stetho_sound＿btn" class="btn btn-success pull-right add_btn_{{$lib_type['input_name']}}"
            type="button" data-lib_type="{{$lib_type['input_name']}}"
            data-lib_explanation="{{$lib_type['explanation_label']}}" @if(!($stetho_sounds->count()>0)) disabled @endif>{{$lib_type['add_lib_btn_label']}}</button>

        <label class="col-md-5 pull-right">
            <select id="{{$lib_type['input_name']}}__selector" class="form-control">
                @foreach($stetho_sounds as $key => $stetho_sound)
                    <?php $alreadyAdded = 0; ?>
                    @if(isset($is_edit) && empty(old($lib)))
                        @foreach($quiz_library as $i => $s) 
                            @if($s->id == $stetho_sound->id)
                                <?php $alreadyAdded = 1; ?>
                            @endif
                        @endforeach
                    @endif

                    @if (!$alreadyAdded)
                        <option id="sound_add__opt" value="{{$stetho_sound->id}}"
                            data-description="{{$stetho_sound->description}}">
                            {{$stetho_sound->title_en}}</option>
                    @endif
                @endforeach
            </select>
        </label>
    </div>

</div>