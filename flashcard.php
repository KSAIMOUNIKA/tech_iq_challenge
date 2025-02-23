<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "quiz_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];

    // Add a new flashcard
    if (isset($_POST['add_flashcard'])) {
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        $stmt = $mysqli->prepare("INSERT INTO flashcards (user_id, question, answer) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $question, $answer);
        $stmt->execute();
        $stmt->close();
    }

    // Edit an existing flashcard
    if (isset($_POST['edit_flashcard'])) {
        $flashcard_id = $_POST['flashcard_id'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        $stmt = $mysqli->prepare("UPDATE flashcards SET question = ?, answer = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $question, $answer, $flashcard_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete a flashcard
    if (isset($_POST['delete_flashcard'])) {
        $flashcard_id = $_POST['flashcard_id'];

        $stmt = $mysqli->prepare("DELETE FROM flashcards WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $flashcard_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all flashcards for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT id, question, answer FROM flashcards WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$flashcards = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Flashcards</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Purple to Blue */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            width: 100%;
            max-width: 800px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: center;
            position: relative;
        }

        h1 {
            color: #6a11cb;
            font-size: 36px;
            margin-bottom: 30px;
        }

        h2 {
            color: #2575fc;
            font-size: 24px;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #f9f9f9;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li strong {
            display: block;
            color: #6a11cb;
            width: 120px;
        }

        .flashcard-fields {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .flashcard-fields input[type="text"] {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: calc(45% - 20px); /* Adjust to keep fields side-by-side */
        }

        button {
            padding: 10px 15px;
            background-color: #6a11cb;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2575fc;
        }

        /* Go Home Button Style */
        .go-home-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #2575fc;
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .go-home-btn:hover {
            background-color: #6a11cb;
        }

    </style>
</head>
<body>

    <div class="container">
        <!-- Go Home Button -->
        <form action="home.php" method="get">
            <button type="submit" class="go-home-btn">Go Home</button>
        </form>

        <h1>Your Flashcards</h1>

        <!-- Display Flashcards -->
        <?php if (!empty($flashcards)): ?>
            <ul>
                <?php foreach ($flashcards as $flashcard): ?>
                    <li>
                        <div class="flashcard-fields">
                            <div>
                                <strong>Question:</strong> <?= htmlspecialchars($flashcard['question']) ?>
                            </div>
                            <div>
                                <strong>Answer:</strong> <?= htmlspecialchars($flashcard['answer']) ?>
                            </div>
                        </div>
                        <!-- Edit Form -->
                        <form method="POST" style="display:inline; width: 100%; padding-top: 10px;">
                            <input type="hidden" name="flashcard_id" value="<?= $flashcard['id'] ?>">
                            <div class="flashcard-fields">
                                <input type="text" name="question" value="<?= htmlspecialchars($flashcard['question']) ?>" required>
                                <input type="text" name="answer" value="<?= htmlspecialchars($flashcard['answer']) ?>" required>
                            </div>
                            <button type="submit" name="edit_flashcard">Edit</button>
                        </form>
                        <!-- Delete Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="flashcard_id" value="<?= $flashcard['id'] ?>">
                            <button type="submit" name="delete_flashcard" style="background-color: #e74c3c;">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No flashcards found. Create one below!</p>
        <?php endif; ?>

        <!-- Add Flashcard Form -->
        <h2>Create a New Flashcard</h2>
        <form method="POST">
            <div class="flashcard-fields">
                <input type="text" id="question" name="question" placeholder="Question" required>
                <input type="text" id="answer" name="answer" placeholder="Answer" required>
            </div>
            <button type="submit" name="add_flashcard">Add Flashcard</button>
        </form>
    </div>

</body>
</html>
