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
$sql = "SELECT * FROM inventory WHERE user_id = $user_id";
$stmt = $conn->query($sql);
$items = $stmt->fetch_all(MYSQLI_ASSOC);
foreach ($items as $item):
    if($item['sid']){
        $sql = "SELECT * FROM shop WHERE id = ".$item['sid'];
    } else if($item['cid']){
        $sql = "SELECT * FROM customshop WHERE id = ".$item['cid'];
    }
    $stmt = $conn->query($sql);
    $itemDetails = $stmt->fetch_assoc();
?>

            <ol>
                <li><?php echo htmlspecialchars($itemDetails['title']); ?></li>
                <li><?php echo htmlspecialchars($itemDetails['difficulty']); ?></li>
                <li>XP Reward: <?php echo htmlspecialchars($itemDetails['xp_reward']); ?></li>
                <li>Quantity: <?php echo htmlspecialchars($item['Quantity']); ?></li>
                <li><a href="Rewards/use_item.php?qty=<?php echo htmlspecialchars($item['Quantity']); ?>&xp=<?php echo htmlspecialchars($itemDetails['xp_reward']); ?>&inventory_id=<?php echo $item['id']; ?>">Use</a></li>
            </ol>

        <?php endforeach; ?>

        <?php if (count($items) === 0): ?>
            <p>The Inventory is currently empty. Check back later!</p>
            <a href="Shop.php">Buy Items</a>
        <?php endif; ?>
