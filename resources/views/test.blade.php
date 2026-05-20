<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ @asset('css/bootstrap-5.3.8/bootstrap.min.css') }}">
    <title>Document</title>
</head>
<body>
    <h1 class="text-center">Thử nghiệm với blade</h1>
    <p>User id: {{ $user->id }}, Username: {{ $user->username }}</p>
    <p>Fictions:</p>
    @foreach ($user->fictions as $fiction)
        <p>Mã fiction: {{ $fiction->id }}, tên fiction: {{ $fiction->fiction_name }}, mô tả: {{ $fiction->description}}, ngày tạo: {{ $fiction->created_at }}, ngày chỉnh sửa: {{ $fiction->updated_at}}</p>
    @endforeach
</body>

<script src="{{ @asset('js/bootstrap-5.3.8/bootstrap.min.js') }}"></script>
</html>