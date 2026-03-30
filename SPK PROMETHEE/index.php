<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM tb_user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['password'] === $password) {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            if ($user['level'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_manajer.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login SPK Cindomart</title>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    body {
        margin: 0;
        height: 100vh;
        background: linear-gradient(135deg, #2b6cb0, #63b3ed);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        padding: 40px 35px;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        width: 350px;
        color: #fff;
        text-align: center;
    }
    .login-card h2 {
        margin-bottom: 20px;
        color: #fff;
        font-weight: 600;
        letter-spacing: 1px;
    }
    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 8px;
        border: none;
        outline: none;
        background: rgba(255, 255, 255, 0.85);
        color: #333;
        font-size: 14px;
    }
    input:focus {
        box-shadow: 0 0 0 2px #2b6cb0;
    }
    button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: none;
        border-radius: 8px;
        background: #2b6cb0;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    button:hover {
        background: #1e4f86;
    }
    .error {
        background: rgba(255, 0, 0, 0.1);
        border: 1px solid rgba(255,0,0,0.4);
        color: #ffdddd;
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .footer {
        margin-top: 15px;
        font-size: 12px;
        color: rgba(255,255,255,0.8);
    }
</style>
</head>
<body>
    <div class="login-card">
        <h2>LOGIN DISINI</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
        <div class="footer">© <?= date('Y'); ?> Cindomart System , Created By adrianusrival</div>
    </div>
</body>
</html>
