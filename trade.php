<?php
// trade.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to trade.'); redirect('login.php');</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Verify if user_id exists in the users table
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if (!$user) {
        echo "<script>alert('User not found. Please log in again.'); redirect('login.php');</script>";
        session_destroy();
        exit;
    }
} catch (PDOException $e) {
    $error = "Error verifying user: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trading_pair = $_POST['trading_pair'];
    $order_type = $_POST['order_type'];
    $side = $_POST['side'];
    $amount = $_POST['amount'];
    $price = $_POST['price'] ?? null; // Price is optional for market orders

    // Validate input
    if (empty($trading_pair) || empty($order_type) || empty($side) || empty($amount)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, trading_pair, order_type, side, amount, price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $trading_pair, $order_type, $side, $amount, $price]);
            $success = "Order placed successfully!";
        } catch (PDOException $e) {
            $error = "Error placing order: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade - Binance Clone</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            align-items: center;
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
        .trade-container {
            width: 80%;
            margin: 50px auto;
            display: flex;
            gap: 20px;
            animation: fadeIn 1s ease-in;
        }
        .chart-container {
            flex: 2;
            background: #0f0f1f;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .order-form {
            flex: 1;
            background: #0f0f1f;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .order-form h2 {
            color: #f0b90b;
            margin-bottom: 20px;
            text-align: center;
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
            font-size: 16px;
        }
        .form-group input:focus, .form-group select:focus {
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
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            background: #fff;
            transform: scale(1.05);
        }
        .success, .error {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success { background: #0f0; color: #000; }
        .error { background: #f00; color: #fff; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            .trade-container {
                flex-direction: column;
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="#" onclick="redirect('index.php')">Home</a>
            <a href="#" onclick="redirect('wallet.php')">Wallet</a>
            <a href="#" onclick="redirect('transactions.php')">Transactions</a>
        </div>
        <div>
            <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        </div>
    </div>
    <div class="trade-container">
        <div class="chart-container">
            <canvas id="priceChart"></canvas>
        </div>
        <div class="order-form">
            <h2>Place Order</h2>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="trading_pair">Trading Pair</label>
                    <select id="trading_pair" name="trading_pair" required>
                        <option value="BTC/USDT">BTC/USDT</option>
                        <option value="ETH/USDT">ETH/USDT</option>
                        <option value="BNB/USDT">BNB/USDT</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="order_type">Order Type</label>
                    <select id="order_type" name="order_type" required>
                        <option value="market">Market</option>
                        <option value="limit">Limit</option>
                        <option value="stop-loss">Stop-Loss</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="side">Side</label>
                    <select id="side" name="side" required>
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" step="0.00000001" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (USDT)</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="Optional for market orders">
                </div>
                <button type="submit" class="btn">Place Order</button>
            </form>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
        // Chart.js for real-time price chart
        const ctx = document.getElementById('priceChart').getContext('2d');
        const priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1m', '2m', '3m', '4m', '5m'],
                datasets: [{
                    label: 'BTC/USDT',
                    data: [62000, 62500, 63000, 62800, 63500],
                    borderColor: '#f0b90b',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { display: true },
                    y: { display: true }
                }
            }
        });
        // Simulate real-time updates
        setInterval(() => {
            const newPrice = 62000 + Math.random() * 1500;
            priceChart.data.datasets[0].data.shift();
            priceChart.data.datasets[0].data.push(newPrice);
            priceChart.update();
        }, 5000);
    </script>
</body>
</html>
