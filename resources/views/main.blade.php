<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Flan-fiction | Ươm mầm sự sáng tạo")</title>

    <link rel="icon" type="image" href="{{ @asset('logo/favicon.jpeg') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ @asset('css/bootstrap-5.3.8/bootstrap.min.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ @asset('css/body.css') }}">
    <link rel="stylesheet" href="{{ @asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ @asset('css/footer.css') }}">
    <style>
         /* Custom CSS for the overlay */
        #fullscreen-loader {
            position: fixed; /* Fixed position to cover the viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            z-index: 9999; /* High z-index to be on top of other content */
            display: flex; /* Use flexbox for centering */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Header here --}}
    @include('shared.header')
    
    {{-- Nội dung ở đây --}}
    <main>
        <div>
            @yield('content')
        </div>
    </main>


    {{-- Footer here --}}
    @include('shared.footer')
</body>

<script src="{{ @asset('js/bootstrap-5.3.8/bootstrap.min.js') }}"></script>
<script>
        // Hide the loader once the window is fully loaded
        window.addEventListener('load', function () {
            const loader = document.getElementById('fullscreen-loader');
            if (loader) {
                loader.style.display = 'none';
            }
        });
</script>
</html>
