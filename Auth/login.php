<?php
include '../config/DBconfig.php';
session_start();
$err = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT uid,name,email,password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $email, $hashed_password);
    $stmt->fetch();
    if(!$stmt->num_rows > 0){
        echo "<script>alert('No user found with this email. Please register first.');</script>";
    }
    if(!password_verify($password, $hashed_password)){
        echo "<script>alert('Incorrect password. Please try again.');</script>";
    }

    if($stmt->num_rows > 0 && password_verify($password, $hashed_password)){
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        header("Location: ../Dashboard.php");
        exit();
    } else {
        $err = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <?php if($err): ?>
            <p style="color:red;"><?php echo $err; ?></p>
        <?php endif; ?>
        <label for="Email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>