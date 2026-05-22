@extends('user.profile')
@section('user_profile_component')
<link rel="stylesheet" href="{{ @asset('css/fiction_list.css') }}">
<h2 class="text-center">Danh sách truyện</h2>

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

<!-- The floating button -->
<div class="container d-flex justify-content-end">
    <a class="btn btn-success" href="">
        Thêm truyện mới
    </a>
</div>
<div class="d-flex flex-column">
    <!-- Truyện sẽ ở trong 1 cái box -->
    
</div>
@endsection