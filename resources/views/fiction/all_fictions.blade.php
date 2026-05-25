@extends('main')

@section('content')
<link rel="stylesheet" href="{{ @asset('css/all_fiction_series.css') }}">
<style>
    #header_search_bar
    {
        display: none;
    }   
</style>
<div class="container-sm mt-3">
    {{-- Tí nữa thay link mới sau --}}
    <form class="d-flex" role="search" 
            method="get" action="https://youtu.be/dQw4w9WgXcQ?si=VkmG2L8omiNp_FW-">
        @csrf
        <input class="form-control me-2" type="search" placeholder="Tìm kiếm tên truyện" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>
    <h3 class="text-center mt-5">Danh sách truyện</h3>
    <div class="container-sm mt-3 md-3" id="fiction_series_list">
        
    </div>
</div>
@endsection