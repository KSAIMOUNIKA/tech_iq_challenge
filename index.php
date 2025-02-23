<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "quiz_app");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($_POST['action'] == 'signup') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        echo "<div class='message success'>Signup successful! Please login.</div>";
    } elseif ($_POST['action'] == 'login') {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: home.php");
                exit();
            } else {
                echo "<div class='message error'>Invalid password!</div>";
            }
        } else {
            echo "<div class='message error'>User not found!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Purple to Blue Gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            font-size: 28px;
            color: #4b0082; /* Dark Purple for title */
            margin-bottom: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #6a11cb; /* Purple focus border */
        }
        button {
            padding: 15px;
            width: 100%;
            background: #6a11cb; /* Purple Button */
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 15px; /* Space between buttons */
        }
        button:hover {
            background: #2575fc; /* Blue Button Hover */
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        .toggle-action {
            margin-top: 15px;
            font-size: 14px;
        }
        .toggle-action a {
            color: #6a11cb; /* Purple Links */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .toggle-action a:hover {
            color: #2575fc; /* Blue hover link */
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Login or Signup</h1>

        <form method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="action" value="login">Login</button>
            <button type="submit" name="action" value="signup">Signup</button>
        </form>

        <div class="toggle-action">
            <p>Don't have an account? <a href="index.php" style="font-size:14px;">Sign up here</a></p>
            <p>Already have an account? <a href="index.php" style="font-size:14px;">Login here</a></p>
        </div>

        <?php
        if (isset($error)) {
            echo "<div class='message error'>{$error}</div>";
        }
        ?>

    </div>

</body>
</html>
