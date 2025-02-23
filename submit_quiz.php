<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect if the user is not logged in
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "quiz_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];
$subject = $_POST['subject']; // Assuming 'subject' is being passed from the quiz form

$score = 0; // Initialize score variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $answers = [];

    // Collect answers for each question
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'answer_') === 0) { // Check if the key starts with 'answer_'
            $question_id = str_replace('answer_', '', $key); // Extract the question ID from the name
            $answers[$question_id] = $value; // Store the selected answer
        }
    }

    // Check if any answers are submitted
    if (empty($answers)) {
        echo "<div class='message error'>Error: No answers submitted!</div>";
        exit;
    }

    // Process the answers, compare them with the correct answers, and calculate the score
    foreach ($answers as $question_id => $selected_answer) {
        // Fetch correct answer for the question from the database
        $stmt = $mysqli->prepare("SELECT correct_option FROM questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($correct_option);
        $stmt->fetch();
        
        // Compare selected answer with the correct answer
        if ($selected_answer === $correct_option) {
            $score++;
        }

        $stmt->close();
    }

    // Save the score in the scores table
    $stmt = $mysqli->prepare("INSERT INTO scores (user_id, subject, score) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $subject, $score);
    $stmt->execute();
    $stmt->close();
} else {
    echo "<div class='message error'>Error: Invalid request!</div>";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scorecard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }
        .container {
            background: #fff;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
        }
        h1 {
            font-size: 32px;
            color: #6a11cb;
            margin-bottom: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
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
        button {
            padding: 12px;
            background: #6a11cb;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            width: 100%;
        }
        button:hover {
            background: #2575fc;
        }
        a {
            text-decoration: none;
            font-weight: bold;
            color: #6a11cb;
            transition: color 0.3s;
        }
        a:hover {
            color: #2575fc;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Scorecard</h1>

        <!-- Display the score message here -->
        <div class="message success">Your score: <?php echo $score; ?></div>

        <a href="home.php"><button>Go Home</button></a>
    </div>

</body>
</html>
