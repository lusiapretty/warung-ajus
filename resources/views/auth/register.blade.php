<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 360px;
  }

  h2 {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 18px;
    font-size: 20px;
  }

  .form-group {
    margin-bottom: 12px;
    position: relative;
  }

  .form-group input {
    width: 100%;
    padding: 8px 8px 8px 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 13px;
  }

  .form-group input:focus {
    border-color: #f39c12;
  }

  .toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #aaa;
    font-size: 14px;
  }

  .btn {
    width: 100%;
    padding: 9px;
    background: linear-gradient(to right, #e74c3c, #f1c40f);
    border: none;
    color: #fff;
    font-weight: bold;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .btn:hover {
    background: linear-gradient(to right, #e67e22, #f39c12);
  }

  .text-link {
    margin-top: 10px;
    text-align: center;
    font-size: 12.5px;
  }

  .text-link a {
    color: #e74c3c;
    text-decoration: none;
  }

  .text-link a:hover {
    text-decoration: underline;
  }

  .error-message {
    color: red;
    font-size: 12px;
    margin-top: 3px;
  }
  </style>
</head>
<body>
  <div class="box">
    <h2>Daftar Akun Baru</h2>
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="text" name="email" placeholder="Email " value="{{ old('email') }}" required>
        @error('email')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
        <span class="toggle-password" id="toggle-password">
          <i class="fas fa-eye"></i>
        </span>
        @error('password')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Kata Sandi" required>
        <span class="toggle-password" id="toggle-password-confirmation">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <button type="submit" class="btn">Daftar</button>

      <div class="text-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      function setupToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        const icon = toggle.querySelector('i');

        toggle.addEventListener('click', () => {
          if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
          } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
          }
        });
      }

      setupToggle('toggle-password', 'password');
      setupToggle('toggle-password-confirmation', 'password_confirmation');
    });
  </script>
</body>
</html>
