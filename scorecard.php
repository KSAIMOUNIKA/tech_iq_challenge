<?php
session_start();
$score = $_SESSION['score'];
$subject = $_SESSION['subject'];

// If score or subject is not set, redirect to home or quiz page
if (!isset($score) || !isset($subject)) {
    header("Location: home.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scorecard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #8A2BE2, #4B0082); /* Purple and blue gradient */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .scorecard-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .scorecard-container h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        .scorecard-container p {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .button {
            background-color: #8A2BE2;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #5A2A99;
        }
    </style>
</head>
<body>

    <div class="scorecard-container">
        <h1>Your Score in <?php echo $subject; ?>: <?php echo $score; ?> / 100</h1>
        <p>Great job! Keep it up!</p>
        <a href="home.php" class="button">Go Home</a>
    </div>

</body>
</html>
