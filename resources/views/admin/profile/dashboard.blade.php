@extends('admin.profile')
@section('title')
    Báo cáo thống kê & thông tin tài khoản
@endsection
@section('moderator_profile_component')
<div class="text-center">
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
</div>

{{-- Dashboard dựa trên quyền hạn --}}
{{-- Chỉ dành cho admin --}}
@if ($moderator->permission === 'admin')
    <div class="card p-3 mb-4 bg-white shadow-sm">
        <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center flex-nowrap gap-3 row-gap-0">
            
            <div class="col-auto">
                <label class="fw-bold text-secondary mb-0 text-nowrap">Bộ lọc khoảng thời gian:</label>
            </div>
            
            <div class="col-auto">
                <select name="filter" id="filterSelect" class="form-select">
                    <option value="today" {{ ($dashboard_data['current_filter'] ?? '') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="yesterday" {{ ($dashboard_data['current_filter'] ?? '') == 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
                    <option value="7_days" {{ ($dashboard_data['current_filter'] ?? '') == '7_days' ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="custom" {{ ($dashboard_data['current_filter'] ?? '') == 'custom' ? 'selected' : '' }}>Khoảng ngày tùy chỉnh</option>
                </select>
            </div>

            <div id="customDateRange" class="col-auto d-flex align-items-center gap-2 text-nowrap" 
                 style="display: {{ ($dashboard_data['current_filter'] ?? '') == 'custom' ? 'flex' : 'none' }} !important;">
                <input type="date" name="start_date" class="form-control form-control-sm" style="width: 135px;" value="{{ $dashboard_data['start_date'] ?? '' }}">
                <span class="text-secondary">đến</span>
                <input type="date" name="end_date" class="form-control form-control-sm" style="width: 135px;" value="{{ $dashboard_data['end_date'] ?? '' }}">
            </div>
            
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm px-3 text-nowrap">Áp dụng</button>
            </div>
            
        </form>
    </div>
@endif

<div class="card p-3 text-bg-light shadow-sm">
    <h2 class="text-center">Xin chào, <i>{{ $moderator->username }}</i>!!</h2>
    <h3 class="text-center">
        @if($moderator->permission === 'admin')
            {{ $dashboard_data['message'] }}
        @else
            Trong ngày hôm nay {{ now()->format('d-m-Y') }}
        @endif
    </h3>
    
    <div class="row mt-4 fs-5">
        <div class="col-sm-6 ps-4">
            <p>Tổng lượt đọc: <strong>{{ number_format($dashboard_data['totalReadsToday']) }}</strong></p>
            <p>Tổng lượt bình luận: <strong>{{ number_format($dashboard_data['totalCommentsToday']) }}</strong></p>
        </div>
        <div class="col-sm-6 ps-4">
            <p>Số chương truyện được đăng tải: <strong>{{ number_format($dashboard_data['totalChaptersToday']) }}</strong></p>
            <p>Số lượng người dùng đã đăng ký: <strong>{{ number_format($dashboard_data['totalNewUsersToday']) }}</strong></p>
        </div>
    </div>
</div>

<div class="text-center mt-2">
    <h2>Thông tin tài khoản</h2>
</div>
<div class="row">
    <div class="col-sm-6">
        <p>Tên tài khoản: <i>{{ $moderator->username }}</i></p>
    </div>

    <div class="col-sm-6">
        <p>Email: <i>{{ $moderator->email }}</i></p>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <p>Vai trò: <i>{{ $moderator->permission_name }}</i></p>
    </div>

    <div class="col-sm-6">
        <p><a href="{{ route('admin.change_info') }}">Thay đổi thông tin</a></p>
    </div>
</div>
<script src="{{ @asset('js/moderator/change_dashboard_filter.js') }}"></script>

@endsection