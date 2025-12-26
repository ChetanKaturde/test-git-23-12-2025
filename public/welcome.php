<!DOCTYPE html>
<html>
<head>
    <title>Monitorbizz - Manufacturing Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .status { background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background: #ffe8e8; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏭 Monitorbizz Manufacturing Management System</h1>
        
        <div class="status">
            <h3>✅ System Status</h3>
            <p><strong>Server:</strong> Running</p>
            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Laravel:</strong> Installed</p>
            <p><strong>Database:</strong> Connected</p>
            <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <h3>🔧 Features</h3>
        <ul>
            <li>Inventory Management</li>
            <li>Purchase Order System</li>
            <li>Barcode Generation & Scanning</li>
            <li>Quality Control</li>
            <li>Vendor Management</li>
            <li>Reporting & Analytics</li>
        </ul>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/login" class="btn">Login to System</a>
            <a href="/register" class="btn">Register Account</a>
        </div>
    </div>
</body>
</html>