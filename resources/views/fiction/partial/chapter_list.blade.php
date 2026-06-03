<div class="card p-4 mt-5">
    <h2 class="text-center">Mục lục chương</h2>

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0" style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif


    <div class="container d-flex justify-content-end">
        <a class="btn btn-success" style="width: fit-content" href="{{ url('/author/edit_fiction/' . $fiction->id . '/new_chapter') }}">
            Thêm chương mới
        </a>
    </div>

    <div class="d-flex flex-column">
        @forelse ($chapters as $chapter)
            <div class="card p-3 mt-3 chapter">
                <h4><a href="{{ url('author/edit_fiction/' . $chapter->fiction_id . '/edit_chapter/' . $chapter->id) }}">
                    {{ $chapter->chapter_name }}
                </a></h4>
                <p><i>Ngày đăng tải:</i> {{ $chapter->created_at }}</p>
                <p><i>Cập nhật lần cuối:</i> {{ $chapter->updated_at }}</p>
                <div>
                    <a class="btn btn-primary" href="{{ route('chapter.preview', $chapter->id) }}">Preview</a>
                    <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                           data-bs-toggle="modal"
                           data-bs-target="#deleteModal"
                           data-id="{{ $chapter->id }}"
                           data-name="{{ $chapter->chapter_name }}"
                           onclick="get_delete_id(this)">
                        Xóa chương
                    </a>
                </div>
            </div>
        @empty
            <h6 class="text-center">Chưa có chương truyện nào, bấm Thêm chương mới để thêm chương truyện</h6>
        @endforelse
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Xác nhận xóa <strong id="delete_stuff"></strong>?</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="confirm_delete_btn" onclick="confirm_delete(this)" class="btn btn-danger">Xác nhận xóa</button>
            <button type="button" class="btn btn-success" id="btn-close-modal" data-bs-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

<script>
    const BASE_URL = "{{ url('/') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const FICTION_ID = "{{ $fiction->id }}"
</script>
<script src="{{ @asset('js/delete_chapter.js') }}"></script>