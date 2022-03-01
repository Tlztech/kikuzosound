<table>
  <tbody>
    @foreach($stetho_sounds as $stetho_sound)
    <tr>
      <td>{{$stetho_sound->id}}</td>
      <td>{{$stetho_sound->user_id}}</td>
      <td>{{$stetho_sound->sound_path}}</td>
      <td>{{$stetho_sound->title}}</td>
      <td>{{$stetho_sound->type}}</td>
      <td>{{$stetho_sound->area}}</td>
      <td>{{$stetho_sound->conversion_type}}</td>
      <td>{{$stetho_sound->is_normal}}</td>
      <td>{{$stetho_sound->disease}}</td>
      <td>{{$stetho_sound->description}}</td>
      <td>{{$stetho_sound->status}}</td>
      <td>{{$stetho_sound->is_public}}</td>
      <td>{{$stetho_sound->disp_order}}</td>
    </tr>
    @endforeach
  </tbody>
</table>