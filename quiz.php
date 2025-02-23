<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$mysqli = new mysqli("localhost", "root", "", "quiz_app");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check the request method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the selected subject from the form
    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        $subject = $_POST['subject'];

        // Fetch questions for the selected subject
        $stmt = $mysqli->prepare("SELECT * FROM questions WHERE subject = ?");
        $stmt->bind_param("s", $subject);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display quiz questions
        if ($result->num_rows > 0) {
            echo "<div class='quiz-title'>Quiz: $subject</div>"; // Changed to div for positioning
            echo "<form method='POST' action='submit_quiz.php'>";  // Changed action to submit_quiz.php
            echo "<input type='hidden' name='subject' value='$subject'>";  // Hidden input to pass subject

            while ($row = $result->fetch_assoc()) {
                echo "<div class='question'>";
                echo "<p>" . $row['question'] . "</p>";
                echo "<div class='options'>";
                echo "<input type='radio' name='answer_" . $row['id'] . "' value='" . $row['option1'] . "'> " . $row['option1'] . "<br>";
                echo "<input type='radio' name='answer_" . $row['id'] . "' value='" . $row['option2'] . "'> " . $row['option2'] . "<br>";
                echo "<input type='radio' name='answer_" . $row['id'] . "' value='" . $row['option3'] . "'> " . $row['option3'] . "<br>";
                echo "<input type='radio' name='answer_" . $row['id'] . "' value='" . $row['option4'] . "'> " . $row['option4'] . "<br><br>";
                echo "</div>"; // End options div
                echo "</div>"; // End question div
            }

            echo "<button type='submit' class='submit-btn'>Submit Quiz</button>";
            echo "</form>";
        } else {
            echo "<p class='error-message'>No questions available for the selected subject.</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='error-message'>Error: No subject selected!</p>";
    }
} else {
    // Display subject selection form (GET request)
    echo "<div class='select-subject'>Select a Subject to Start the Quiz</div>";
echo "<form method='POST' action='quiz.php'>";
echo "<div class='subject-select-container'>";
echo "<label for='subject'>Select Subject:</label>";
echo "<select name='subject' id='subject' required>";
echo "<option value=''>--Select--</option>";
echo "<option value='Mathematics'>Mathematics</option>";
echo "<option value='Programming'>Programming</option>";
echo "<option value='C'>C</option>";       // Added C
echo "<option value='C++'>C++</option>";   // Added C++
echo "<option value='Java'>Java</option>"; // Added Java
echo "<option value='DBMS'>DBMS</option>"; // Added DBMS
echo "<option value='OS'>OS</option>";     // Added OS
echo "</select>";
echo "</div>";
echo "<button type='submit' class='start-btn'>Start Quiz</button>";
echo "</form>";

}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #8A2BE2, #4B0082, #6A5ACD); /* Gradient background */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
            position: relative; /* Added to position the title */
        }
        .quiz-title, .select-subject {
            font-size: 30px;
            color: #fff;
            position: absolute;
            left: 20px;
            margin: 0;
        }
        .quiz-title {
            top: 20px; /* Position "Quiz: [subject]" text */
        }
        .select-subject {
            top: 80px; /* Position "Select a subject" text below the quiz title */
        }
        .subject-select-container {
            margin-bottom: 30px; /* Space between label and dropdown */
        }
        form {
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            margin-top: 40px; /* Space between heading and form */
        }
        .question {
            margin-bottom: 20px;
        }
        .question p {
            font-size: 18px;
            color: #fff;
        }
        .options {
            margin-left: 20px;
        }
        .options input {
            margin-right: 10px;
        }
        button {
            background-color: #8A2BE2;
            color: #fff;
            padding: 15px 30px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #5A2A99;
        }
        .start-btn {
            background-color: #8A2BE2;
            color: white;
            padding: 15px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }
        .start-btn:hover {
            background-color: #5A2A99;
        }
        .error-message {
            color: red;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

</body>
</html>
