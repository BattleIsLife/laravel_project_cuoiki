@extends('main')
@section('content')

<div class="container-fluid">
    <ul class="nav nav-tabs justify-content-center" id="fictionEditTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" 
                    id="info-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#edit_fiction_info" 
                    type="button" 
                    role="tab" 
                    aria-controls="edit_fiction_info" 
                    aria-selected="false">
                Thông tin truyện
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" 
                    id="chapters-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#edit_chapter_list" 
                    type="button" 
                    role="tab" 
                    aria-controls="edit_chapter_list" 
                    aria-selected="true">
                Mục lục chương
            </button>
        </li>
    </ul>

    <div class="tab-content id="fictionEditTabsContent">
        
        <div class="tab-pane fade" 
             id="edit_fiction_info" 
             role="tabpanel" 
             aria-labelledby="info-tab">
            @include('fiction.partial.edit_fiction_info')
        </div>

        <div class="tab-pane fade show active" 
             id="edit_chapter_list" 
             role="tabpanel" 
             aria-labelledby="chapters-tab">
            @include('fiction.partial.chapter_list')
        </div>
        
    </div>
</div>
<script src="{{ @asset('js/edit_fiction.js') }}"></script>
@endsection