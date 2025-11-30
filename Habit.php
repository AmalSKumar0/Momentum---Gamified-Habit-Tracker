<?php
include 'config/DBconfig.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: Auth/login.php");
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT gold,xp FROM users WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($gold,$xp);
    $stmt->fetch();
?>
<h1><?php echo $gold; ?> - gold</h1>
<h1><?php echo $xp; ?> - exp</h1>

<?php
$sql = "SELECT * FROM habits WHERE user_id = $user_id";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
?>


        <?php foreach ($items as $item): ?>
            
            <ol>
                <li><?php echo htmlspecialchars($item['title']); ?></li>
                <li><?php echo htmlspecialchars($item['difficulty']); ?></li>
                <li>XP Reward: <?php echo htmlspecialchars($item['xp_reward']); ?></li>
                <li>Gold Reward: <?php echo htmlspecialchars($item['gold_reward']); ?></li>
                <li><a href="habits/delete_habit.php?habit_id=<?php echo $item['id']; ?>">Delete</a></li>
                <li><a href="habits/edit_habit.php?habit_id=<?php echo $item['id']; ?>">Edit</a></li>
            </ol>

        <?php endforeach; ?>

        <?php if (count($items) === 0): ?>
            <p>The shop is currently empty. Check back later!</p>
            <a href="Habit.php">Add Habits</a>
        <?php endif; ?>

        <form action="habits/add_habit.php" method="POST">
            <label for="title">Habit Title:</label>
            <input type="text" id="title" name="title" required><br><br>
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option value="Easy">Easy</option>
                <option value="Medium">Medium</option>
                <option value="Hard">Hard</option>
            </select><br><br>
            <input type="submit" value="Add Habit">
        </form>