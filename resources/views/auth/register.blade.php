<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: url('../img/hero-bg.jpg') no-repeat center center fixed;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
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
      transition: border-color 0.3s ease;
    }

    .input-wrapper input.error {
      border-color: #e74c3c;
      background-color: #fdf2f2;
    }

    .input-wrapper input.valid {
      border-color: #27ae60;
      background-color: #f8fff8;
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
      color: #e74c3c;
      font-size: 12px;
      margin-top: 4px;
      display: none;
    }

    .error-message.show {
      display: block;
    }

    .success-message {
      color: #27ae60;
      font-size: 12px;
      margin-top: 4px;
      display: none;
    }

    .success-message.show {
      display: block;
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

    .btn:disabled {
      background: #bdc3c7;
      cursor: not-allowed;
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

    .password-hint {
      font-size: 12px;
      color: #555;
      margin-bottom: 4px;
    }

    @media (max-width: 480px) {
      body {
        padding: 20px;
        align-items: flex-start;
      }

      .box {
        padding: 30px 20px;
        margin-top: 160px;
        border-radius: 12px;
      }

      h2 {
        font-size: 22px;
      }

      .input-wrapper input {
        font-size: 13px;
        padding: 10px 34px 10px 10px;
      }

      .toggle-password {
        font-size: 13px;
      }

      .btn {
        font-size: 14px;
        padding: 10px;
      }

      .text-link {
        font-size: 12px;
      }

      .error-message {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Daftar Akun Baru</h2>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
      @csrf

      <div class="form-group">
        <div class="input-wrapper">
          <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
        </div>
        <div class="error-message" id="nama-error">Nama lengkap harus diisi.</div>
        @error('nama')
          <div class="error-message show">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>
        <div class="error-message" id="email-error">Email harus diisi dengan format yang benar.</div>
        @error('email')
          <div class="error-message show">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div class="password-hint">
          Password minimal 6 karakter dan mengandung huruf kapital.
        </div>

        <div class="input-wrapper">
          <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
          <span class="toggle-password" id="toggle-password">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="error-message" id="password-error">Password harus diisi (minimal 6 karakter dan mengandung huruf kapital).</div>
        @error('password')
          <div class="error-message show">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Kata Sandi" required>
          <span class="toggle-password" id="toggle-password-confirmation">
            <i class="fas fa-eye"></i>
          </span>
        </div>
        <div class="error-message" id="password-confirmation-error">Ulangi kata sandi harus diisi dan sesuai dengan kata sandi.</div>
        <div class="success-message" id="password-confirmation-success">Password sudah sesuai.</div>
        @error('password_confirmation')
          <div class="error-message show">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn" id="submitBtn">Daftar</button>

      <div class="text-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Setup password toggle functionality
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

      // Get form elements
      const form = document.getElementById('registerForm');
      const namaInput = document.getElementById('nama');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const confirmInput = document.getElementById('password_confirmation');
      const submitBtn = document.getElementById('submitBtn');

      // Error message elements
      const namaError = document.getElementById('nama-error');
      const emailError = document.getElementById('email-error');
      const passwordError = document.getElementById('password-error');
      const confirmError = document.getElementById('password-confirmation-error');
      const confirmSuccess = document.getElementById('password-confirmation-success');

      const touched = {
        nama: false,
        email: false,
        password: false,
        confirm: false
      }

      // Validation functions
      function validateNama() {
        const value = namaInput.value.trim();
        if (!touched.nama) {
          return true;
        }

        if (value === '') {
          showError(namaInput, namaError, 'Nama lengkap harus diisi.');
          return false;
        } else {
          hideError(namaInput, namaError);
          return true;
        }
      }

      function validateEmail() {
        const value = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!touched.email) {
          return true;
        }
        
        if (value === '') {
          showError(emailInput, emailError, 'Email harus diisi.');
          return false;
        } else if (!emailRegex.test(value)) {
          showError(emailInput, emailError, 'Format email tidak valid.');
          return false;
        } else {
          hideError(emailInput, emailError);
          return true;
        }
      }

      function validatePassword() {
        const value = passwordInput.value;
        const hasUpperCase = /[A-Z]/.test(value);
        if (!touched.password) {
          return true;
        }
        
        if (value === '') {
          showError(passwordInput, passwordError, 'Password harus diisi.');
          return false;
        } else if (value.length < 6) {
          showError(passwordInput, passwordError, 'Password minimal 6 karakter.');
          return false;
        } else if (!hasUpperCase) {
          showError(passwordInput, passwordError, 'Password harus mengandung minimal 1 huruf kapital.');
          return false;
        } else {
          hideError(passwordInput, passwordError);
          return true;
        }
      }

      function validatePasswordConfirmation() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        if (!touched.confirm) {
          return true;
        }
        
        if (confirm === '') {
          showError(confirmInput, confirmError, 'Ulangi kata sandi harus diisi.');
          hideSuccess(confirmSuccess);
          return false;
        } else if (password !== confirm) {
          showError(confirmInput, confirmError, 'Password tidak sesuai.');
          hideSuccess(confirmSuccess);
          return false;
        } else {
          hideError(confirmInput, confirmError);
          showSuccess(confirmSuccess, 'Password sudah sesuai.');
          return true;
        }
      }

      function showError(input, errorElement, message) {
        input.classList.add('error');
        input.classList.remove('valid');
        errorElement.textContent = message;
        errorElement.classList.add('show');
      }

      function hideError(input, errorElement) {
        input.classList.remove('error');
        input.classList.add('valid');
        errorElement.classList.remove('show');
      }

      function showSuccess(successElement, message) {
        successElement.textContent = message;
        successElement.classList.add('show');
      }

      function hideSuccess(successElement) {
        successElement.classList.remove('show');
      }

      function updateSubmitButton() {
        const isValid = validateNama() && validateEmail() && validatePassword() && validatePasswordConfirmation();
        submitBtn.disabled = !isValid;
      }

      // Real-time validation
      namaInput.addEventListener('blur', () => {
        touched.nama = true;
        validateNama();
        updateSubmitButton();
      });
      namaInput.addEventListener('input', () => {
        if (namaInput.value.trim() !== '') {
          validateNama();
        }
        updateSubmitButton();
      });

      emailInput.addEventListener('blur', () => {
        touched.email = true;
        validateEmail();
        updateSubmitButton();
      });
      emailInput.addEventListener('input', () => {
        if (emailInput.value.trim() !== '') {
          validateEmail();
        }
        updateSubmitButton();
      });

      passwordInput.addEventListener('blur', () => {
        touched.password = true;
        validatePassword();
        updateSubmitButton();
      });
      passwordInput.addEventListener('input', () => {
        if (!touched.password) touched.password = true; // SET touched saat user mulai mengetik
        validatePassword();
        // Re-validate confirmation when password changes
        if (confirmInput.value !== '') {
          validatePasswordConfirmation();
        }
        updateSubmitButton();
      });

      confirmInput.addEventListener('blur', () => {
        touched.confirm = true;
        validatePasswordConfirmation();
        updateSubmitButton();
      });
      confirmInput.addEventListener('input', () => {
        if (!touched.confirm) touched.confirm = true;
        validatePasswordConfirmation();
        updateSubmitButton();
      });

      // Form submission validation
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        
        const isNamaValid = validateNama();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmValid = validatePasswordConfirmation();

        if (isNamaValid && isEmailValid && isPasswordValid && isConfirmValid) {
          // All validations passed, submit the form
          this.submit();
        }
      });

      // Initial validation state
      updateSubmitButton();
    });
  </script>
</body>
</html>