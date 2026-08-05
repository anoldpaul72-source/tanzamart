<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account - TanzaMart</title>
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

        .register-container {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
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
        }

        .toggle-password:hover {
            color: #0097a7;
        }

        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 6px;
            line-height: 1.4;
        }

        button[name="register"] {
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

        button[name="register"]:hover {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>TanzaMart</h2>
    <p class="subtitle">Create Your Account</p>

    <?php
    if (isset($_POST['register'])) {
        $user = trim($_POST['username']);
        $email = trim($_POST['email']);
        $raw_password = $_POST['password'];

        $errors = [];

        // 1. Validate Email Format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address (e.g., juma@gmail.com).";
        }

        // 2. Validate Strong Password Requirements
        // Must contain at least 8 characters, 1 uppercase, 1 lowercase, and 1 number
        $uppercase = preg_match('@[A-Z]@', $raw_password);
        $lowercase = preg_match('@[a-z]@', $raw_password);
        $number    = preg_match('@[0-9]@', $raw_password);

        if (!$uppercase || !$lowercase || !$number || strlen($raw_password) < 8) {
            $errors[] = "Password must be at least 8 characters long and include an uppercase letter, lowercase letter, and a number.";
        }

        // 3. Check if Email already exists in database
        $check_email = $conn->real_escape_string($email);
        $email_query = $conn->query("SELECT id FROM users WHERE email='$check_email'");
        if ($email_query && $email_query->num_rows > 0) {
            $errors[] = "This email is already registered. Please use another email or log in.";
        }

        // Save user if there are no validation errors
        if (empty($errors)) {
            $user_clean = $conn->real_escape_string($user);
            $email_clean = $conn->real_escape_string($email);
            $pass_hashed = password_hash($raw_password, PASSWORD_DEFAULT);

            $conn->query("INSERT INTO users (username, email, password) VALUES ('$user_clean', '$email_clean', '$pass_hashed')");

            echo "<div class='alert alert-success'>Account created successfully! <a href='login.php' style='color:#2e7d32; font-weight:bold;'>Click to Login</a></div>";
        } else {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
    }
    ?>

    <form method="POST">
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="e.g. Juma Khamis" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        </div>

        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="e.g. juma@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter a strong password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least 8 characters, including upper and lowercase letters and numbers" required>
                <button type="button" id="toggleBtn" class="toggle-password">👁️</button>
            </div>
            <div class="password-hint">
                🔒 At least 8 characters, including uppercase (A-Z), lowercase (a-z), and a number (0-9).
            </div>
        </div>

        <button type="submit" name="register">Register Account</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggleBtn');

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = '🙈'; 
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = '👁️'; 
        }
    });
</script>

</body>
</html>