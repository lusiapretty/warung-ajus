<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
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
      padding: 10px 38px 10px 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
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
    }

    .info-text {
      font-size: 12px;
      color: #555;
      margin-bottom: 6px;
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

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 4px;
    }

    @media (max-width: 480px) {
  .box {
    padding: 30px 20px;
    margin: 20px;
    border-radius: 10px;
  }

  h2 {
    font-size: 22px;
    margin-bottom: 24px;
  }

  .form-group input {
    font-size: 13px;
    padding: 10px 36px 10px 10px;
  }

  .btn {
    font-size: 14px;
    padding: 10px;
  }

  .info-text {
    font-size: 12px;
  }

  .alert-danger {
    font-size: 13px;
  }

  .error-message {
    font-size: 12px;
  }
}

  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Kata Sandi</h2>

    @if ($errors->any())
      <div class="alert-danger">
        <ul style="margin:0; padding-left:18px;">
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="form-group">
        <input type="email" name="email" placeholder="Masukkan Email Anda" value="{{ old('email') }}" required>
      </div>

      <div class="form-group">
        <div class="info-text">Password minimal 6 karakter dan 1 huruf besar.</div>
        <input type="password" name="password" id="password" placeholder="Password Baru" required>
        <span class="toggle-password" id="toggle-password">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <div class="form-group">
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required>
        <span class="toggle-password" id="toggle-password-confirmation">
          <i class="fas fa-eye"></i>
        </span>
      </div>

      <button type="submit" class="btn">Reset Password</button>
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
