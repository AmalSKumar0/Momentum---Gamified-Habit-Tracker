<?php
include '../config/DBconfig.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $difficulty = $_POST['difficulty'];
    $user_id = $_SESSION['user_id'];
    $habit_id = $_POST['habit_id'];
    $gold = 0;
    $xp = 0;
    if($difficulty == 'Easy') {
        $gold = 5;
        $xp = 3;
    } elseif ($difficulty == 'Medium') {
        $gold = 10;
        $xp = 6;
    } elseif ($difficulty == 'Hard') {
        $gold = 15;
        $xp = 9;
    }

    $stmt = $conn->prepare("UPDATE habits SET title = ?, difficulty = ?, xp_reward = ?, gold_reward = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssiiii", $title, $difficulty, $xp, $gold, $habit_id, $user_id);

    if($stmt->execute()){
        header("Location: ../Habit.php");
        exit();
    } else {
        $err = "Error: " . $stmt->error;
        
    }

    $stmt->close();
    $conn->close();
}
$id = $_GET['habit_id'];
$sql = "SELECT * FROM habits WHERE id = $id";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<form method="POST">
            <label for="title">Habit Title:</label>
            <input type="hidden" name="habit_id" value="<?php echo $items[0]['id']; ?>">
            <input type="text" id="title" value="<?php echo htmlspecialchars($items[0]['title']); ?>" name="title" required><br><br>
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option <?php if ($items[0]['difficulty'] == 'Easy') echo 'selected'; ?> value="Easy">Easy</option>
                <option <?php if ($items[0]['difficulty'] == 'Medium') echo 'selected'; ?> value="Medium">Medium</option>
                <option <?php if ($items[0]['difficulty'] == 'Hard') echo 'selected'; ?> value="Hard">Hard</option>
            </select><br><br>
            <input type="submit" value="Update Habit">
    </form>