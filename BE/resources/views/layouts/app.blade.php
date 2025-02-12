<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    @stack('styles') <!-- Để thêm CSS tùy chỉnh nếu cần -->
</head>
<body>
    <div class="container">
        @yield('content')
        
        <!-- Nút Home -->
        <a href="{{ route('home') }}" class="btn btn-primary home-button">
            <i class="fas fa-home"></i>
        </a>

        <!-- Nút Logout -->
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger logout-button">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>

        <!-- Form ẩn để xử lý đăng xuất -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    @stack('scripts') <!-- Để thêm JS tùy chỉnh nếu cần -->
</body>
</html>

<style>
    .home-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000; /* Đảm bảo xuất hiện trên các nội dung khác */
    }
    .logout-button {
        position: fixed;
        bottom: 20px;
        right: 100px; /* Cách nút Home một khoảng */
        z-index: 1000;
    }
</style>
