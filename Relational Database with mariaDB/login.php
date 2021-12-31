<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ICT2103 University</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<img class="wave" src="images/wave.png">
<div class="container">
    <div class="img">
        <img src="images/bg.svg">
    </div>
    <div class="login-content">
        <form action="process_login.php" method="post">
            <img src="images/avatar.svg">
            <h2 class="title">Welcome</h2>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="div">
                    <h5>Email</h5>
                    <input type="text" name="uemail" class="input">
                </div>
            </div>
            <div class="input-div pass">
                <div class="i">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input type="password" name="password" class="input">
                </div>
            </div>
            <a href="#">Forgot Password?</a>
            <input type="submit" class="btn" value="Login">
            <input type="submit" class="btn" value="Guest Login" formaction="user.php">
        </form>
    </div>
</div>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>