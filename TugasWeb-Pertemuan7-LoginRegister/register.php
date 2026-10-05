<?php

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi field kosong
    if (empty($nama) || empty($email) || empty($password)) {

        $error = "Semua field wajib diisi!";

    // Validasi email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid!";

    // Validasi password
    } elseif (strlen($password) < 6) {

        $error = "Password minimal 6 karakter!";

    } else {

        // Membaca file users.json
        $file = 'users.json';

        if (file_exists($file)) {

            $json = file_get_contents($file);
            $users = json_decode($json, true);

            if (!is_array($users)) {
                $users = [];
            }

        } else {

            $users = [];
        }

        // Cek email duplikat
        foreach ($users as $user) {

            if (isset($user['email']) && $user['email'] === $email) {

                $error = "Email sudah terdaftar!";
                break;
            }
        }

        // Jika tidak ada error
        if (empty($error)) {

            // Hash password
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Data user baru
            $newUser = [
                'id' => count($users) + 1,
                'nama' => $nama,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Masukkan user
            $users[] = $newUser;

            // Simpan ke users.json
            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT)
            );

            // Redirect ke login
            header("Location: login.php?msg=registered");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrasi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>📝 Registrasi</h1>

        <p class="subtitle">
            Buat akun baru
        </p>

        <?php if (!empty($error)): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Nama Lengkap</label>

            <input
                type="text"
                name="nama"
                placeholder="Masukkan nama lengkap"
                value="<?= htmlspecialchars($nama ?? '') ?>"
                required
            >

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                value="<?= htmlspecialchars($email ?? '') ?>"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Minimal 6 karakter"
                required
            >

            <button type="submit">
                Daftar
            </button>

        </form>

        <p class="bottom-text">

            Sudah punya akun?

            <a href="login.php">
                Login di sini
            </a>

        </p>

    </div>

</div>

</body>

</html>     