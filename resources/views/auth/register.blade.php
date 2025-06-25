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
      margin-bottom: 16px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper input {
      width: 100%;
      padding: 10px 36px 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
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

    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 4px;
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
  </style>
</head>
<body>
  <div class="box">
    <h2>Daftar Akun Baru</h2>
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <div class="input-wrapper">
          <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
        </div>
        @error('name')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>
        @error('email')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div style="font-size: 12px; color: #555; margin-bottom: 4px;">
          Password minimal 6 karakter dan mengandung huruf besar.
        </div>

        <div class="input-wrapper">
          <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
          <span class="toggle-password" id="toggle-password">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        @error('password')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Kata Sandi" required>
          <span class="toggle-password" id="toggle-password-confirmation">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        @error('password_confirmation')
          <div class="error-message">{{ $message }}</div>
        @enderror
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

      const form = document.querySelector('form');
      const passwordInput = document.getElementById('password');
      const confirmInput = document.getElementById('password_confirmation');

      form.addEventListener('submit', function (e) {
        document.querySelectorAll('.js-password-error').forEach(el => el.remove());

        const password = passwordInput.value;
        const confirm = confirmInput.value;
        let isValid = true;

        if (password.length < 6) {
          const error = document.createElement('div');
          error.className = 'error-message js-password-error';
          error.textContent = 'Password minimal 6 karakter.';
          passwordInput.parentNode.parentNode.appendChild(error);
          isValid = false;
        }

        if (password !== confirm) {
          const error = document.createElement('div');
          error.className = 'error-message js-password-error';
          error.textContent = 'Ulangi kata sandi tidak cocok.';
          confirmInput.parentNode.parentNode.appendChild(error);
          isValid = false;
        }

        if (!isValid) e.preventDefault();
      });
    });
  </script>
</body>
</html>
