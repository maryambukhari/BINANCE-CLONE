<?php
// transactions.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Binance Clone</title>
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
        .transaction-table {
            width: 80%;
            margin: 50px auto;
            border-collapse: collapse;
            background: #0f0f1f;
            border-radius: 10px;
            overflow: hidden;
        }
        .transaction-table th, .transaction-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #2a2a3e;
        }
        .transaction-table th {
            background: #f0b90b;
            color: #0f0f1f;
        }
        .transaction-table tr:hover {
            background: #2a2a3e;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('trade.php')">Trade</a>
            <a href="#" onclick="redirect('wallet.php')">Wallet</a>
        </div>
        <div>
            <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </div>
    </div>
    <div class="transaction-table">
        <h2 style="text-align: center; color: #f0b90b;">Transaction History</h2>
        <table>
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['currency']); ?></td>
                        <td><?php echo number_format($transaction['amount'], 8); ?></td>
                        <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                        <td><?php echo $transaction['created_at']; ?></td>
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
