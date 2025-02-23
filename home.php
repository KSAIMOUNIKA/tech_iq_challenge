<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            padding: 20px 40px; /* Increased padding */
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: auto; /* Allow the width to adjust based on the content */
            min-width: 350px; /* Optional: Set a minimum width */
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            white-space: nowrap;  /* Prevent line breaks */
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 1rem;
            color: #fff;
            padding: 10px 20px;
            background: #2575fc;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
            font-weight: bold;
        }

        .nav-links a:hover {
            background: #6a11cb;
            transform: translateY(-3px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .nav-links a:active {
            transform: translateY(0);
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the TechIQ Challenge</h1>
        <div class="nav-links">
            <a href="quiz.php">Take a Quiz</a>
            <a href="profile.php">View Profile</a>
            <a href="flashcard.php">Flashcards</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
