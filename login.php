<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 8px;
        }

        p.subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .password-wrapper input {
            padding-right: 60px; 
        }

        .input-group input:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #00bcd4;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            margin: 0;
            width: auto;
            outline: none;
        }

        button[name="login"] {
            width: 100%;
            padding: 12px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button[name="login"]:hover {
            background: #0097a7;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>TanzaMart</h2>
    <p class="subtitle">Enter to your Account</p>

    <?php
    if (isset($_POST['login'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $pass = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($pass, $user['password'])) {
                // HAPA: Tunatengeneza Session zote mbili
                $_SESSION['user'] = $user['id'];
                $_SESSION['email'] = $user['email']; // Hii itaruhusu admin dashboard kufunguka
                
                echo "<div class='alert alert-success'>You are welcome! Redirecting...</div>";
                
                // MPELEKE MTEJA KWENYE HOMEPAGE (index.php)
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
            } else {
                echo "<div class='alert alert-danger'>Incorrect password!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Email is not registered!</div>";
        }
    }
    ?>

    <form method="POST">
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your Email" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <button type="button" id="toggleBtn" class="toggle-password">👁️</button>
            </div>
        </div>

        <button type="submit" name="login">Login</button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggleBtn');

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = '🙈'; // Inabadilika kuwa tumbili wa kuficha macho
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = '👁️'; // Inarudi kuwa jicho lililowazi
        }
    });
</script>

</body>
</html>