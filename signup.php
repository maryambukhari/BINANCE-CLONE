<?php
// signup.php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Binance Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #1a1a2e;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #0f0f1f;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 400px;
            animation: slideIn 1s ease-in;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f0b90b;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #2a2a3e;
            color: #fff;
        }
        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 5px #f0b90b;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background: #f0b90b;
            border: none;
            border-radius: 5px;
            color: #0f0f1f;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #fff;
        }
        .error {
            color: #f00;
            text-align: center;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="#" onclick="redirect('login.php')" style="color: #f0b90b;">Login</a>
        </p>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
