






<?php
    ob_start();
    session_start();
    
    $pageTitle = 'Login';

    if (isset($_SESSION['user'])) {
        header("Location: index.php");
    }

    include 'init.php';

    if (isset($_POST['login'])) {

        // chack if user come from http post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedpass = sha1($pass);

    // chack if user exesist in databaser

            $stmt = $con->prepare("SELECT
                                            UserID, Username, Password  
                                        FROM
                                            users  
                                        WHERE 
                                            Username = ?
                                        AND 
                                            Password = ? 
                                        ");

            $stmt->execute(array($user, $hashedpass));
            
            $get = $stmt->fetch();

            $count = $stmt->rowCount();

            if ($count > 0) {
                $_SESSION['user'] = $user; // registor session with username varuable

                $_SESSION['uid'] = $get['UserID']; // registor USERID In SESSION 
            
                header("Location: index.php");
                exit();
            }

        }else {

            $username  = $_POST['username'];
            $password  = $_POST['password'];
            $password2 = $_POST['password2'];
            $email     = $_POST['email'];

            $formErrors = array();

            if (isset($username)) {

                $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

                if (strlen($filterdUser)  < 4) {
                    
                    $formErrors[] = 'username cant be less than 4 char';

                }
            }

            if(isset($password) && isset($_POST['$password2'])) {

                if (empty($password)) {

                    $formErrors[] = 'sorry passwords desnt be empty';
                }

                if ($sha1($password) !== $sha1($password2)) {
                    
                    $formErrors[] = 'sorry passwords desnt match';

                }
            }

            if (isset($email)) {

                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

                if (filter_var($filterdEmail, FILTER_SANITIZE_EMAIL) != true) {

                    $formErrors[] = 'this email is not valid';
                    
                }
            }

            // chack if there is no error add the user
        
            if (empty($formErrors)) {

                // chek if user is exessit in database

                $check = checkItem("Username", "users", $username);

                if($check == 1){
                    $theMsg =  "<div class='alert alert-danger'>sorry user is exessist</div>";
                    $formErrors[] = 'sorry this user is exest';

                } else {
                    
                    // update the datebade with this informtion

                    $stmt = $con->prepare("INSERT INTO 
                                        users(Username, Password, Email, RegStatus, Date)
                                        VALUES(:zuser, :zpass, :zmail, 0, now())");

                    $stmt->execute(array(
                    'zuser'   =>   $username,
                    'zpass'   =>   sha1($password),
                    'zmail'   =>   $email
                    ));    

                    // echo success message

                   $succesMsg = 'Good';    
                }
            }
        }
    }

?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | 
            <span data-class="signup">Signup</span>
        </h1>
        <!-- Start Login Form -->

        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input
                    type="text" 
                    class="form-control" 
                    name="username" 
                    placeholder="Your User Name" 
                    required
                />
            </div>

            <div class="input-container">
                <input
                    type="password" 
                    class="form-control" 
                    name="password" 
                    placeholder="Your Password" 
                    required
                />
            </div>

                <input
                    type="submit" 
                    class="btn btn-primary btn-block" 
                    value="Login"
                    name="login"
                />
        </form>
        <!-- End Login Form -->


        <!-- Start Signup Form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input 
                    pattern=".{4,8}"
                    title="User Name Must Be 4 Char"
                    type="text" 
                    class="form-control"
                    name="username" 
                    placeholder="Your User Name"
                    required
                />
            </div>

            <div class="input-container">
                <input
                    minlength="4"
                    type="password" 
                    class="form-control" 
                    name="password" 
                    placeholder="Your Password"
                    required 
                />
            </div>

            <div class="input-container">
                <input
                    minlength="4"
                    type="password" 
                    class="form-control" 
                    name="password2" 
                    placeholder="Your Password Agin"
                    required 
                />
            </div>

            <div class="input-container">
                <input 
                    class="form-control" 
                    type="email" 
                    name="email" 
                    placeholder="Your Email"
                    required
                />
            </div>

            <input 
                type="submit"
                class="btn btn-success btn-block" 
                value="Signup" 
                name="signup"
            />
        </form>
    <!-- End Signup Form -->

    <div class="the-erorrs text-center">
    <?php
    
        if (!empty($formErrors)) {

            foreach ($formErrors as $error) {

                echo '<div class="msg error">' .$error . '</div>';
                
            }
            
        }

        if (isset($succesMsg)) {

            echo '<div class="msg error">' .$error . '</div>';
        }
    ?>
        
    </div>
</div>
<?php
    include $tpl .'footer.php';
    ob_end_flush();
?>