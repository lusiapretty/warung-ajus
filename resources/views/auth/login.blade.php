<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Font Awesome untuk ikon mata -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ff4e50, #f9d423);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .box {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.12);
      max-width: 400px;
      width: 100%;
      position: relative;
    }

    h2 {
      color: #e74c3c;
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 18px;
      position: relative;
    }
.form-group input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  outline: none;
  box-sizing: border-box;
  font-size: 14px;
  font-family: 'Poppins', sans-serif;
  transition: border-color 0.3s, box-shadow 0.3s;
  margin-bottom: 12px;
}

    .form-group input:focus {
      border-color: #f39c12;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: linear-gradient(to right, #e74c3c, #f1c40f);
      border: none;
      color: #fff;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }

    .btn:hover {
      background: linear-gradient(to right, #e67e22, #f39c12);
    }

    .text-link {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }

    .text-link a {
      color: #e74c3c;
      text-decoration: none;
    }

    .text-link a:hover {
      text-decoration: underline;
    }

    .forgot {
      text-align: right;
      font-size: 13px;
      margin-bottom: 10px;
    }

    .forgot a {
      color: #f39c12;
      text-decoration: none;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .eye-icon {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>

    @if ($errors->any())
      <div class="alert-danger">
        <ul style="margin:0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="form-group">
        <input type="text" name="email" placeholder="Email" required autofocus value="{{ old('email') }}">
      </div>

      <div class="form-group">
        <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
        <span class="eye-icon" id="toggle-password">
          <i class="fa fa-eye" id="eye-icon"></i>
        </span>
      </div>

      <div class="forgot">
        <a href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
      </div>

      <button type="submit" class="btn">Masuk</button>

      <div class="text-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('toggle-password');
      const eyeIcon = document.getElementById('eye-icon');

      togglePassword.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash');
        } else {
          passwordInput.type = 'password';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye');
        }
      });
    });
  </script>
</body>
</html>
