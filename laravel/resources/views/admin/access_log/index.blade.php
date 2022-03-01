@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">Access Log</h1>
</div>

<div class="row">
  <div class="col-md-11">
    <a href="{{route('access_log_csv')}}" class="btn btn-info" target="_parent">CSVダウンロード</a>
    <a href="{{route('access_log_excel')}}" class="btn btn-info" target="_parent">XLSXダウンロード</a>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>date/time</th>
          <th>IP address</th>
          <th>domain</th>
          <th>japaniese school name</th>
          <th>user agent</th>
          <th>session ID</th>
          <th>user ID</th>
          <th>Referrer</th>
        </tr>
      </thead>

      <tbody>
        @foreach($accessLog as $log)
        <tr>
          <td>{{$log->created_at}}</td>
          <td>{{$log->ip_addr}}</td>
          <td>{{$log->domain}}</td>
          <td>{{$log->school_name}}</td>
          <td>{{$log->user_agent}}</td>
          <td>{{$log->session_id}}</td>
          <td>{{$log->user_id}}</td>
          <td>{{$log->referrer}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {!! $accessLog->render() !!}
  </div>
</div>
@endsection
