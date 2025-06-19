<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
</head>
<body>
  <h2>Reset Kata Sandi</h2>

  <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <input type="hidden" name="token" value="{{ $token }}">
      <label>Email</label><br>
      <input type="email" name="email" required><br><br>

      <label>Password Baru</label><br>
      <input type="password" name="password" required><br><br>

      <label>Konfirmasi Password</label><br>
      <input type="password" name="password_confirmation" required><br><br>

      <button type="submit">Reset Password</button>
  </form>

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

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
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
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
      </div>

      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" name="password" required>
      </div>

      <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>
      </div>

      <button type="submit" class="btn">Reset Password</button>
    </form>
  </div>

</body>
</html>
