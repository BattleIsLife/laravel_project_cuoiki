@extends('main')
@section('title')
    Tạo bài đăng mới
@endsection
@section('content')
<div class="container-fluid p-4 mt-3">
    <div class="container-sm card p-4 mt-2">
        <form action="{{ route('admin.new_post') }}" method="post" id="PostForm">
            @csrf

            <h1 class="text-center">Thêm bài đăng mới</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

            <div class="mb-3 mt-3">
                <label for="title" class="form-label">Tiêu đề:</label>
                <input type="text" class="form-control" 
                        id="title" placeholder="Nhập tiêu đề" name="title" value="{{ old('title') }}">
                <div class="invalid-feedback" id="title_Error"></div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Nội dung bài đăng:</label>
                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Nhập nội dung bài đăng">{{ old('description') }}</textarea>
                <div class="invalid-feedback" id="description_Error"></div>
            </div>

            <button type="submit" class="btn btn-success w-100" name="add_post">Thêm bài đăng mới</button>
        </form>
    </div>
</div>

<script src="{{ @asset('js/moderator/edit_post.js') }}"></script>
@endsection
