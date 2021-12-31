<!-- Created By Jia Jia-->

<?php
session_start();
include "private/db_conn.php";

if (isset($_POST['uemail']) && isset($_POST['password'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $uemail = validate($_POST['uemail']);
    $password = validate($_POST['password']);

    if (empty($uemail)) {
        header("Location: login.php?error=Email is required");
        exit();
    } else if (empty($password)) {
        header("Location: login.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM user WHERE email='$uemail' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        $conn->close();
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['email'] === $uemail && $row['password'] === $password) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];
                header("Location: admin.php");
                exit();
            }else {
                header("Location: login.php?error=Incorrect email or password");
                exit();
            }
        }else {
            header("Location: login.php?error=Incorrect email or password");
            exit();
        }
    }

}else {
    header("Location: login.php");
    exit();
}

