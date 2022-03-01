@if (count($errors) > 0)
<div class="alert alert-danger">
  <p>{{$title or '入力に誤りがあります。'}}</p>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif