<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Kata Sandi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    }

    h2 {
      color: #e74c3c;
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 18px;
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

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Lupa Kata Sandi</h2>

    @if (session('status'))
      <div class="alert-success">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert-danger">
        <ul style="margin:0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="form-group">
        <input type="email" name="email" placeholder="Masukkan Email Anda" value="{{ old('email') }}" required>
      </div>
      <button type="submit" class="btn">Kirim Link Reset</button>

      <div class="text-link">
        <a href="{{ route('login') }}">Kembali ke Login</a>
      </div>
    </form>
  </div>
</body>
</html>
