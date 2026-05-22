<div class="card p-4 mt-5">
    <h2 class="text-center">Mục lục chương</h2>

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
        <a class="btn btn-success" style="width: fit-content" href="">
            Thêm chương mới
        </a>
    </div>

    <div class="d-flex flex-column">
        
    </div>
</div>