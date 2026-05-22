@extends('user.profile')
@section('user_profile_component')
<link rel="stylesheet" href="{{ @asset('css/series_list.css') }}">
<h2 class="text-center">Danh sách series</h2>
<!-- The floating button -->
@if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="container d-flex justify-content-end">
    <a class="btn btn-success" href="">
        Thêm series mới
    </a>
</div>
<div class="d-flex flex-column">
    <!-- Series sẽ ở trong 1 cái box -->
  
</div>
@endsection