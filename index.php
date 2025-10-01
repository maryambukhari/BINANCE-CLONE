<?php
// index.php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binance Clone - Homepage</title>
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
        .hero {
            text-align: center;
            padding: 50px;
            background: linear-gradient(45deg, #1a1a2e, #16213e);
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in;
        }
        .market-table {
            width: 80%;
            margin: 50px auto;
            border-collapse: collapse;
            background: #0f0f1f;
            border-radius: 10px;
            overflow: hidden;
        }
        .market-table th, .market-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #2a2a3e;
        }
        .market-table th {
            background: #f0b90b;
            color: #0f0f1f;
        }
        .market-table tr:hover {
            background: #2a2a3e;
            cursor: pointer;
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
            <a href="index.php">Home</a>
            <a href="#" onclick="redirect('trade.php')">Trade</a>
            <a href="#" onclick="redirect('wallet.php')">Wallet</a>
            <a href="#" onclick="redirect('transactions.php')">Transactions</a>
        </div>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="redirect('dashboard.php')">Dashboard</a>
                <a href="#" onclick="redirect('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="redirect('login.php')">Login</a>
                <a href="#" onclick="redirect('signup.php')">Signup</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero">
        <h1>Welcome to Binance Clone</h1>
        <p>Trade cryptocurrencies with ease and security.</p>
    </div>
    <table class="market-table">
        <thead>
            <tr>
                <th>Trading Pair</th>
                <th>Price (USDT)</th>
                <th>24h Change</th>
            </tr>
        </thead>
        <tbody id="market-data">
            <!-- Populated by JavaScript -->
        </tbody>
    </table>
    <script>
        // Mock API for real-time prices
        const mockPrices = [
            { pair: 'BTC/USDT', price: 63000, change: 2.5 },
            { pair: 'ETH/USDT', price: 2500, change: -1.2 },
            { pair: 'BNB/USDT', price: 600, change: 0.8 }
        ];
        function updateMarket() {
            const tbody = document.getElementById('market-data');
            tbody.innerHTML = '';
            mockPrices.forEach(coin => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${coin.pair}</td>
                    <td>$${coin.price.toFixed(2)}</td>
                    <td style="color: ${coin.change >= 0 ? '#0f0' : '#f00'}">${coin.change}%</td>
                `;
                tbody.appendChild(row);
            });
        }
        updateMarket();
        setInterval(updateMarket, 5000); // Update every 5 seconds
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
