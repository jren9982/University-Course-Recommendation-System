<!-- Created By Jia Jia-->

<?php
session_start();
/*unset session to destroy data */
session_unset();
session_destroy();
header("Location: login.php");
