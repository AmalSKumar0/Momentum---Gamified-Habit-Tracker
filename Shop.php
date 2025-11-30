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
$sql = "SELECT * FROM shop";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<div class="shop-grid">
        
        <?php foreach ($items as $item): ?>
            
            <ol>
                <li><?php echo htmlspecialchars($item['title']); ?></li>
                <li><?php echo htmlspecialchars($item['difficulty']); ?></li>
                <li>XP Reward: <?php echo htmlspecialchars($item['xp_reward']); ?></li>
                <li>Gold cost: <?php echo htmlspecialchars($item['gold_cost']); ?></li>
                <li><a href="Rewards/buy_rewards.php?type=shop&id=<?php echo htmlspecialchars($item['id']); ?>">Buy</a></li>
            </ol>

        <?php endforeach; ?>
<?php
$sql = "SELECT * FROM customshop where user_id = $user_id";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<?php foreach ($items as $item): ?>
            
            <ol>
                <li><?php echo htmlspecialchars($item['title']); ?></li>
                <li><?php echo htmlspecialchars($item['difficulty']); ?></li>
                <li>XP Reward: <?php echo htmlspecialchars($item['xp_reward']); ?></li>
                <li>Gold Cost: <?php echo htmlspecialchars($item['gold_cost']); ?></li>
                <li><a href="Rewards/buy_rewards.php?type=custom&cost=<?php echo htmlspecialchars($item['gold_cost']); ?>&id=<?php echo htmlspecialchars($item['id']); ?>">Buy</a></li>
            </ol>

        <?php endforeach; ?>
        <?php if (count($items) === 0): ?>
            <p>The shop is currently empty. Check back later!</p>
            <a href="rewards/Rewards.php">Your Own Rewards</a>
            <a href="rewards/Rewards.php">Add Rewards</a>
        <?php endif; ?>