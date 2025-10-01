<?php
// dashboard.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
$stmt->execute([$user_id]);
$wallets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Binance Clone</title>
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
        .dashboard {
            width: 80%;
            margin: 50px auto;
        }
        .dashboard h2 {
            text-align: center;
            color: #f0b90b;
            animation: fadeIn 1s ease-in;
        }
        .wallet-table {
            width: 100%;
            border-collapse: collapse;
            background: #0f0f1f;
            border-radius: 10px;
            overflow: hidden;
        }
        .wallet-table th, .wallet-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #2a2a3e;
        }
        .wallet-table th {
            background: #f0b90b;
            color: #0f0f1f;
        }
        .wallet-table tr:hover {
            background: #2a2a3e;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('trade.php')">Trade</a>
            <a href="#" onclick="redirect('wallet.php')">Wallet</a>
            <a href="#" onclick="redirect('transactions.php')">Transactions</a>
        </div>
        <div>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </div>
    </div>
    <div class="dashboard">
        <h2>Your Portfolio</h2>
        <table class="wallet-table">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Balance</th>
                    <th>Wallet Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wallets as $wallet): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($wallet['currency']); ?></td>
                        <td><?php echo number_format($wallet['balance'], 8); ?></td>
                        <td><?php echo htmlspecialchars($wallet['wallet_address']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
