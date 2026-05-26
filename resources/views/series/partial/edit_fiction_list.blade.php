<link rel="stylesheet" href="{{ @asset('css/fiction_list.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card p-4 mt-5">
    <h2 class="text-center">Danh sách truyện thuộc series</h2>

    <div class="container d-flex justify-content-end">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newFictionInSeriesModal" style="width: fit-content" href="">
            Thêm truyện vào series
        </button>
    </div>

    <div class="d-flex flex-column" id="fiction_in_series_list">
        @if (count($fictions_in_series) == 0)
        <div class="mt-3">
            <h4 class="text-center">Series này chưa có truyện nào, bấm vào nút <i>"Thêm truyện vào series"</i> để thêm truyện vào series</h4>
        </div>
    @else
        @foreach ($fictions_in_series as $fiction)
            <div class="fiction card p-3 mt-3 ms-2 me-2">
                <img class="cover_image" src="{{ @asset('storage/' . $fiction->image_link) }}">
                <div class="fiction-info ms-3">
                    <a href="{{ @url('author/edit_fiction/' . $fiction->id) }}"><h4 class="fiction-title">{{ $fiction->fiction_name }}</h4></a>  
                    <p>Ngày đăng tải: <i>{{ $fiction->created_at }}</i></p>
                    <p>Cập nhật lần cuối: <i>{{ $fiction->updated_at }}</i></p>
                    <div>
                        <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" 
                           data-bs-toggle="modal"
                           data-bs-target="#deleteModal"
                           data-id="{{ $fiction->id }}"
                           data-name="{{ $fiction->fiction_name }}"
                           onclick="get_delete_id(this)">
                            Xóa khỏi series
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex flex-column justify-content-center mt-4">
            {{ $fictions_in_series->links('pagination::bootstrap-5') }}
        </div>

        <!-- Modal xóa truyện khỏi series -->
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
    @endif
    {{-- Modal thêm truyện vào series --}}
    <div class="modal" id="newFictionInSeriesModal">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Thêm truyện vào series <strong>{{ $series->series_name }}</strong></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ url('author/edit_series/'. $series->id . '/add_fiction') }}" method="post" id="addFictionToSeriesForm">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Chọn 1 truyện để thêm vào series</p>
                        <p><select class="form-select form-control" name="fictions" id="selectFiction">
                                <option value="">Vui lòng lựa chọn 1 truyện</option>
                                @foreach ($fictions_not_in_series as $fiction)
                                    <option value="{{ $fiction->id }}">{{ $fiction->fiction_name }}</option>
                                @endforeach
                        </select>
                        <div class="invalid-feedback" id="selectFiction_Error"></div>
                        </p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Thêm vào series</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
            const BASE_URL = "{{ url('') }}";
    </script>

    <script src="{{ @asset('js/delete_fiction_from_series.js') }}"></script>
    <script src="{{ @asset('js/add_fiction_to_series.js') }}"></script>
</div>
    </div>
</div>