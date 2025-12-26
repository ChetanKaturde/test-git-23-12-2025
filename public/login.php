<!DOCTYPE html>
<html>
<head>
    <title>Login - Monitorbizz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }
        .container { max-width: 400px; margin: 100px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { background: #3498db; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        .btn:hover { background: #2980b9; }
        .link { text-align: center; margin-top: 20px; }
        .link a { color: #3498db; text-decoration: none; }
        .notice { background: #fff3cd; padding: 15px; border-radius: 4px; margin-bottom: 20px; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🏭 Monitorbizz Login</h2>
        
        <div class="notice">
            <strong>Note:</strong> Login functionality requires Laravel setup completion. Please contact the administrator.
        </div>
        
        <form>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="Enter your email" disabled>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Enter your password" disabled>
            </div>
            
            <button type="button" class="btn" disabled>Login</button>
        </form>
        
        <div class="link">
            <a href="register.php">Don't have an account? Register here</a><br>
            <a href="welcome.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>