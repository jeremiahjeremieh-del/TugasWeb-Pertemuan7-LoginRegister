<?php

session_start();

$error = '';
$success = '';

// Pesan dari proses registrasi/logout
if (isset($_GET['msg'])) {

    if ($_GET['msg'] === 'registered') {
        $success = "Registrasi berhasil! Silakan login.";
    }

    if ($_GET['msg'] === 'logged_out') {
        $success = "Anda berhasil logout.";
    }
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi
    if (empty($email) || empty($password)) {

        $error = "Email dan password wajib diisi!";

    } else {

        $file = 'users.json';

        // Membaca users.json
        if (file_exists($file)) {

            $json = file_get_contents($file);
            $users = json_decode($json, true);

            if (!is_array($users)) {
                $users = [];
            }

        } else {

            $users = [];
        }

        $foundUser = null;

        // Cari user berdasarkan email
        foreach ($users as $user) {

            if (
                isset($user['email']) &&
                $user['email'] === $email
            ) {

                $foundUser = $user;
                break;
            }
        }

        // Cek password
        if (
            $foundUser !== null &&
            password_verify(
                $password,
                $foundUser['password']
            )
        ) {

            // Membuat session
            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];

            // Masuk dashboard
            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Email atau password salah!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>🔐 Login</h1>

        <p class="subtitle">
            Silakan masuk ke akun Anda
        </p>

        <?php if (!empty($error)): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <?php if (!empty($success)): ?>

            <div class="alert success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

        <p class="bottom-text">

            Belum punya akun?

            <a href="register.php">
                Daftar sekarang
            </a>

        </p>

    </div>

</div>

</body>

</html>