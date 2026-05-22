<div class="p-4 mt-3">
    <div class="row">
        <div class="col-sm-3 d-flex mt-2 justify-content-center">
            <div id="imagePreview" class="" style="width: 162px; height: 255px; background-color: gray;">
                <img id="previewImg" src="" alt="" class="img-fluid rounded" style="width: inherit; height: inherit;">
            </div>
        </div>

        <div class="col-sm-9 mt-2">
            <div class="container-sm card p-4">
                <form action="" method="post" id="FictionForm"  enctype="multipart/form-data">
                    @csrf

                    <h1 class="text-center">Thông tin truyện</h1>
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <input type="hidden" readonly name="fiction_id" value="">

                    <div class="mb-3 mt-3">
                        <label for="fiction_name" class="form-label">Tên truyện:</label>
                        <input type="text" class="form-control" 
                                id="fiction_name" placeholder="Nhập tên truyện" name="fiction_name" value="">
                        <div class="invalid-feedback" id="fiction_name_Error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_description" class="form-label">Mô tả truyện:</label>
                        <textarea class="form-control" id="fiction_description" name="description" rows="5" 
                                placeholder="Nhập mô tả truyện"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="series_id" class="form-label">Series truyện:</label>
                         <select name="series_id" class="form-select">
                            <option value="">Không có</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fiction_image" class="form-label">Bìa truyện <i>(Khuyến nghị ảnh có kích cỡ 108 x 170 hoặc có tý lệ tương đương)</i>:</label>
                        <input class="form-control" type="file" accept="image/*" name="image" onchange="previewImage(event)">
                        <div class="invalid-feedback" id="fiction_image_Error"></div>
                    </div>


                    <button type="submit" class="btn btn-success w-100" name="add_fiction">Sửa thông tin truyện</button>
                </form>

                <div class="container-sm d-flex justify-content-end mt-3 mb-3">
                    <a class="btn btn-danger delete-btn" style="width: fit-content; height: fit-content;" data-bs-toggle="modal" data-bs-target="#deleteModal">Xóa truyện</a>
                </div>
            </div>
        </div>

         <!-- The Modal -->
        <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận muốn xóa <strong id="delete_stuff">?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Hành động này sẽ không thể đảo ngược...hãy suy nghĩ thật kỹ
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <form action="" method="post">
                        <?php echo csrf_field() ?>
                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                    </form>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>