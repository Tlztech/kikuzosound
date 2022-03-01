@if(Session::has('messages'))
<div class="alert alert-success">
  <p></p>
  <ul>
    @foreach (session('messages') as $message)
      <li>{{ $message }}</li>
    @endforeach
  </ul>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
  <p>エラーが発生しました</p>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif