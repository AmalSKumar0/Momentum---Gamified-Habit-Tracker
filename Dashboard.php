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

<div class="shop-grid">
        
        <?php foreach ($items as $item): ?>
            
            <ol>
                <li><?php echo htmlspecialchars($item['title']); ?></li>
                <li><?php echo htmlspecialchars($item['difficulty']); ?></li>
                <li><a href="actions.php?didnt=<?php echo htmlspecialchars($item['id']); ?>">didnt do</a></li>
                <li><a href="actions.php?did=<?php echo htmlspecialchars($item['id']); ?>">did it</a></li>
            </ol>

        <?php endforeach; ?>

        <?php if (count($items) === 0): ?>
            <p>The shop is currently empty. Check back later!</p>
            <a href="Habit.php">Add Habits</a>
        <?php endif; ?>

<a href="Auth/logout.php">logout</a>
        <a href="Shop.php">Go to Shop</a>