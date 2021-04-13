<?php 
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';
    if (isset($_SESSION['Username'])) {
        header("Location: dashboard.php");
    }
    include 'init.php';  

// chack if user come from http post request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);

// chack if user exesist in databaser

        $stmt = $con->prepare("SELECT
                                     UserID  Username, Password  
                                     FROM
                                             users  
                                     WHERE 
                                            Username = ?
                                      AND 
                                            Password = ? 
                                      AND 
                                            GroupID = 1
                                      LIMIT 1");
        $stmt->execute(array($username, $hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
            $_SESSION['Username'] = $username; // registor session with username varuable
            $_SESSION['ID'] = $row['UserID']; // registor session ID
            header("Location: dashboard.php");
            exit();
        }


    }
?>

    <form class="login" <?php echo $_SERVER['PHP_SELF']; ?> method="POST">
    <h3 class="text-center">Admin Login</h3>
        <input class="form-control" name="user" type="text" placeholder="Username" />
        <input class="form-control" name="pass" type="pass" placeholder="Password" />
        <input class="btn btn-primary form-control" type="submit" value="Login" />
    </form>    

<?php 
    include $tpl .'/footer.php';
?>