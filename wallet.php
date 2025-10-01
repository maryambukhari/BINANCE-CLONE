<?php
// wallet.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currency = $_POST['currency'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    try {
        if ($type === 'deposit') {
            $wallet_address = bin2hex(random_bytes(16)); // Mock wallet address
            $stmt = $pdo->prepare("INSERT INTO wallets (user_id, currency, balance, wallet_address) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE balance = balance + ?");
            $stmt->execute([$user_id, $currency, $amount, $wallet_address, $amount]);
        } else {
            $stmt = $pdo->prepare("UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND currency = ? AND balance >= ?");
            $stmt->execute([$amount, $user_id, $currency, $amount]);
        }
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, currency, amount, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $currency, $amount, $type]);
        $success = ucfirst($type) . " successful!";
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
    <title>Wallet - Binance Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #1a1a2e;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #0f0f1f;
            padding: 15px;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: #f0b90b;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            transition: color 0.3s ease;
        }
        .navbar a:hover {
            color: #fff;
        }
        .wallet-container {
            width: 80%;
            margin: 50px auto;
        }
        .wallet-container h2 {
            text-align: center;
            color: #f0b90b;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #2a2a3e;
            color: #fff;
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
        .success, .error {
            text-align: center;
            margin-bottom: 20px;
        }
        .success { color: #0f0; }
        .error { color: #f00; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('trade.php')">Trade</a>
            <a href="#" onclick="redirect('transactions.php')">Transactions</a>
        </div>
        <div>
            <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </div>
    </div>
    <div class="wallet-container">
        <h2>Manage Wallet</h2>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="currency">Currency</label>
                <select id="currency" name="currency" required>
                    <option value="BTC">BTC</option>
                    <option value="ETH">ETH</option>
                    <option value="BNB">BNB</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.00000001" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                </select>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
