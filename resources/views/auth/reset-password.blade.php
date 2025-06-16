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
</body>
</html>
