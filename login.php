<?php
require_once 'config.php';
require_once 'functions.php';

if (isLogin()) {
    redirect('index.php');
}

$error = '';

if (isPost()) {
    $username = esc(post('username'));
    $password = md5(post('password'));
    
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = query($sql);
    
    if ($result && numRows($result) > 0) {
        $user = fetch($result);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        
        @query("UPDATE users SET last_login = NOW() WHERE id = {$user['id']}");
        
        if ($user['role'] == 'penyortir') {
            redirect('penyortir/dashboard.php');
        } elseif ($user['role'] == 'sopir') {
            redirect('sopir/dashboard.php');
        } elseif ($user['role'] == 'operator_mesin') {
            redirect('operator/dashboard.php');
        } else {
            redirect('kepala/dashboard.php');
        }
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container { width: 100%; max-width: 400px; }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px 30px;
            text-align: center;
        }
        .logo { margin-bottom: 20px; }
        .logo i {
            font-size: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .welcome-text { font-size: 28px; font-weight: 600; color: #333; margin-bottom: 5px; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label {
            display: block;
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        .error-box {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #fcc;
        }
        .demo-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 13px;
        }
        .demo-info span { color: #667eea; font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo"><i class="fas fa-recycle"></i></div>
            <div class="welcome-text">Selamat Datang</div>
            <div class="subtitle">Login ke sistem produksi</div>
            
            <?php if ($error): ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="demo-info">
                <p>Demo: <span>penyortir1</span> / password • <span>sopir1</span> / password</p>
                <p><span>operator1</span> / password • <span>kepala1</span> / password</p>
            </div>
        </div>
    </div>
</body>
</html>