<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "quiz_app");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user details (username)
$stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$username = $user['username'];

// Fetch subjects and scores for the logged-in user
$stmt = $mysqli->prepare("SELECT subject, score FROM scores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$scores_result = $stmt->get_result();

// Check if there are any scores
$scores = [];
if ($scores_result->num_rows > 0) {
    while ($row = $scores_result->fetch_assoc()) {
        $scores[] = $row;
    }
} else {
    $scores = null;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Purple to Blue Gradient */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .container {
            background: white;
            width: 100%;
            max-width: 600px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
        }

        h1 {
            color: #6a11cb;
            font-size: 36px;
            margin-bottom: 20px;
        }

        h2 {
            color: #2575fc;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #6a11cb;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
        }

        a {
            display: inline-block;
            background-color: #6a11cb;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #2575fc;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
        }

        .message.no-quizzes {
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

        <h2>Your Quiz Scores:</h2>
        
        <?php if ($scores): ?>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                </tr>
                <?php foreach ($scores as $score): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($score['subject']); ?></td>
                        <td><?php echo htmlspecialchars($score['score']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="message no-quizzes">You have not taken any quizzes yet.</p>
        <?php endif; ?>

        <a href="home.php">Back to Home</a>
    </div>

</body>
</html>
