<!-- Created By Jia Jia-->
<nav class="navbar navbar-expand-sm navbar-light" style="background-color: #e3f2fd;">
    <ul class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="admin.php">UNIVERSITY</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Course Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cca_admin.php">CCA Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Back to User Page</a>
            </li>
        </ul>
        <p> Welcome <?php
            echo '' . $_SESSION['name'] . '';
            ?> !</p>
        <a class="btn btn-primary" href="logout.php">Logout</a>
    </ul>
</nav>