<?php

session_start();

// Jika belum login, kembali ke login
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;
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

    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="dashboard">

        <div class="dashboard-icon">
            🏠
        </div>

        <h1>
            Selamat Datang!
        </h1>

        <p class="welcome">

            Halo,

            <strong>
                <?= htmlspecialchars($_SESSION['username']) ?>
            </strong>

        </p>

        <div class="profile-box">

            <div class="profile-item">

                <span>ID User</span>

                <strong>
                    <?= htmlspecialchars($_SESSION['user_id']) ?>
                </strong>

            </div>

            <div class="profile-item">

                <span>Nama</span>

                <strong>
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </strong>

            </div>

            <div class="profile-item">

                <span>Email</span>

                <strong>
                    <?= htmlspecialchars($_SESSION['email']) ?>
                </strong>

            </div>

        </div>

        <a
            href="logout.php"
            class="logout-button"
        >
            🚪 Logout
        </a>

    </div>

</div>

</body>

</html>