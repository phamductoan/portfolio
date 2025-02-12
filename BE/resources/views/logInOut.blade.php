<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng Nhập / Đăng Ký</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    body {
      background: #f7f7f7;
    }
    .card {
      margin: 0 auto;
      max-width: 400px;
      margin-top: 50px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .toggle-btns {
      text-align: center;
      margin-top: 20px;
    }
    .toggle-btns button {
      margin: 0 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Nút chuyển đổi giữa đăng nhập và đăng ký -->
    <div class="toggle-btns">
      <button id="btnLogin" class="btn btn-primary">Đăng Nhập</button>
      <button id="btnRegister" class="btn btn-secondary">Đăng Ký</button>
    </div>

    <!-- Form đăng nhập -->
    <div id="loginForm" class="card">
      <div class="card-body">
        <h3 class="card-title text-center">Đăng Nhập</h3>
        <form action="{{ route('login') }}" method="POST">
          @csrf
          <!-- Hiển thị lỗi nếu có -->
          @if($errors->has('email'))
            <div class="alert alert-danger">
              {{ $errors->first('email') }}
            </div>
          @endif
          <div class="form-group">
            <label for="loginEmail">Email</label>
            <input type="email" name="email" id="loginEmail" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="loginPassword">Mật khẩu</label>
            <input type="password" name="password" id="loginPassword" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
        </form>
      </div>
    </div>

    <!-- Form đăng ký (ẩn ban đầu) -->
    <div id="registerForm" class="card" style="display: none;">
      <div class="card-body">
        <h3 class="card-title text-center">Đăng Ký</h3>
        <form action="{{ route('register') }}" method="POST">
          @csrf
          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="form-group">
            <label for="registerName">Họ và tên</label>
            <input type="text" name="name" id="registerName" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <input type="email" name="email" id="registerEmail" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="registerPassword">Mật khẩu</label>
            <input type="password" name="password" id="registerPassword" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="registerPasswordConfirmation">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" id="registerPasswordConfirmation" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success btn-block">Đăng Ký</button>
        </form>
      </div>
    </div>
  </div>

  <!-- JS để chuyển đổi giữa form đăng nhập và đăng ký -->
  <script>
    document.getElementById('btnLogin').addEventListener('click', function() {
      document.getElementById('loginForm').style.display = 'block';
      document.getElementById('registerForm').style.display = 'none';
      this.classList.replace('btn-secondary', 'btn-primary');
      document.getElementById('btnRegister').classList.replace('btn-primary', 'btn-secondary');
    });
    document.getElementById('btnRegister').addEventListener('click', function() {
      document.getElementById('loginForm').style.display = 'none';
      document.getElementById('registerForm').style.display = 'block';
      this.classList.replace('btn-secondary', 'btn-primary');
      document.getElementById('btnLogin').classList.replace('btn-primary', 'btn-secondary');
    });
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
