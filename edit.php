


<?php

    // chack if userid is numeric and ger inerger value of it

    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    // select all data deband in this id

    $stmt = $con->prepare("SELECT * FROM  users WHERE UserID = ? LIMIT 1");

    // Execte Query

    $stmt->execute(array($userid));

    // Fetch the data

    $row = $stmt->fetch();

    // the row count

    $count = $stmt->rowCount();

    // if id is found show the form

    if ($stmt->rowCount() > 0)  { ?>

        <container class="col-lg-8" style=" margin: 50px 20px 20px 250px;">  
            <div class="row">
            <div class="col-lg-12">
            <div class="panel panel-info" style=" margin-bottom: 100px;">
                <div class="panel panel-heading text-center">Edit Members</div>
                <div class="panel-body">

                        <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="userid" value="<?php echo $userid ?>" />

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-9">
                                <input 
                                    type="text" 
                                    name="username" 
                                    value="<?php echo $row['Username']; ?>"
                                    class="form-control" 
                                    required="required" />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input
                                        type="hidden" 
                                        name="oldpassword" 
                                        value="<?php echo $row['Password']; ?>" />
                                    <input 
                                        type="password" 
                                        name="newpassword" 
                                        class="form-control" 
                                        placeholder="leave it empty if you dont wont to update it" />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Full name</label>
                                <div class="col-sm-9">
                                    <input 
                                        type="text" 
                                        name="full" 
                                        value="<?php echo $row['FullName']; ?>" 
                                        class="form-control" 
                                        required="required" />
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input 
                                        type="email" 
                                        name="email" 
                                        value="<?php echo $row['Email']; ?>"
                                        class="form-control" 
                                        required="required" />
                                </div>
                            </div>

                                
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">User Avatatr</label>
                                <div class="col-sm-9">
                                    <input 
                                        type="file" 
                                        name="avatar" 
                                        class="form-control" 
                                        required="required"
                                        value="<?php echo $row['avatar']; ?>"
                                        />
                                </div>
                            </div>

                            <div class="form-group">          
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="submit" value="Updata" class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </container>

    <?php 

    // Else show the Error message

    } else {
        
        echo "<div class='container'>";
        $theMsg =  "<div class='alert alert-danger'>There is no sutch id</div>";
        redirectHome($theMsg, 'back', 5);
        echo "</div>";
    }

if ($_SERVER['REQUEST_METHOD'] == 'update') {

    // update page

    echo "<h1 class='text-center'> Update Members </h1>";
    echo "<div class='container'>";

    // Upload Varubles

    $avatarName = $_FILES['avatar']['name'];
    $avatarSize = $_FILES['avatar']['size'];
    $avatarTmp  = $_FILES['avatar']['tmp_name'];
    $avatarType = $_FILES['avatar']['type'];

    // list of allow type file to upload

    $avatarAllowedExtension = array("jpeg", "jpg", "png", "jif");

    // get avatar Extension

    $avatarExtension = strtolower ( end( explode('.' , $avatarName)));


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // get informtion fron the form
        
        $id      = $_POST['userid'];
        $user    = $_POST['username'];
        $email   = $_POST['email'];
        $name    = $_POST['full'];
        $avatar  = $_FILES['avatar'];

        // password trick

        $pass = empty($_POST['newpassword']) ?  $_POST['oldpassword'] : sha1($_POST['newpassword']);

        // valedate the form

        $formErrors = array();

        if (empty($user)) {
                
                
            $formErrors[] = 'username cant be<strong> empty </strong>';

        }

        if (strlen($user) < 4) {
            
            
            $formErrors[] = 'username cant be less than<strong> 4 charecters</strong>' ;

        }

        if (strlen($user) > 20) {
            
            
            $formErrors[] = 'username cant be more than<strong> 20 charecters</strong>' ;

        }

        if (empty($pass)) {
            
            $formErrors[] = 'password cant be<strong> empty</strong>';

        }

        if (empty($email)) {
            
            $formErrors[] = 'Email cant be<strong> empty</strong>';

        }

        if (empty($name)) {

            $formErrors[] = 'Full Name cant be<strong> empty</strong>';
            

        }

        foreach ($formErrors as $error) {

            echo '<div class="alert alert-danger">'. $error .'</div>' ;

        }

        if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
            $formErrors[] = 'This Extension Is Not<strong> Allowed</strong>';
        }

        if (empty($avatarName)) {
            $formErrors[] = 'Avatar Is Not<strong> Selected</strong>';
        }

        if (($avatarSize > 4194304)) {
            $formErrors[] = 'Avatar Can  Not Be Larger Than<strong> 4MB</strong>';
        }

        foreach ($formErrors as $error) {

            echo  $error ;
        }

        // chack if there is no error do the database oparetor

        if (empty($formErrors)) {
            
            $avatar = rand(0, 1000000) . '_' . $avatarName;

            move_uploaded_file($avatarTmp, "uploads\avatars\\" .$avatar);



            $stmt2 = $con->prepare("SELECT * 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        UserID != ?");

            $stmt2->execute(array($user, $id));
            
            $count = $stmt2->rowCount();

            if ($count == 1) {
                
                echo "<div class='alert alert-success'> This User Is Exeist</div>";
                redirectHome($theMsg, 'back');

            }else {
    
                // update the datebade with this informtion

                $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ?, Password = ?, avatar = ?   WHERE UserID = ? ");
                $stmt->execute(array($user, $email, $name, $pass, $avatar, $id));

                // echo success message

                $theMsg =  "<div class='alert alert-success'>" .$stmt->rowCount() . ' record updated</div>';
                redirectHome($theMsg, 'back');
        }
    }
    } else {
        
        $theMsg =  "<div class='alert alert-success'> you cant browse this page dirctly</div>";
        redirectHome($theMsg, 'back');
    }

    echo "</div>";

