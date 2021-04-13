





/*
=======================================================
== Comments page
=======================================================
*/

<?php 
    session_start();
    if (isset($_SESSION['Username'])) {
        $pageTitle = 'Comments';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // start manage page

        if ($do == 'Manage') {      // manage page

        
            $stmt = $con->prepare("SELECT 
                                        comments.* , items.Name AS Item_Name, users.Username AS Member
                                    FROM
                                        comments
                                    INNER JOIN
                                        items
                                    ON 
                                        items.Item_ID = comments.item_id
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id  
                                    ORDER BY
                                        c_id
                                    DESC        
                                ");

            $stmt->execute();

            $comments = $stmt->fetchAll();

            if (! empty($comments)) {
        
        ?>
        
            <h1 class="text-center"> Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comments</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>

                        <?php

                            foreach ($comments as $comment) {
                                
                                echo "<tr>";
                                    echo "<td>" . $comment['c_id']       ."</td>";
                                    echo "<td>" . $comment['comment']    . "</td>";
                                    echo "<td>" . $comment['Item_Name']  . "</td>";
                                    echo "<td>" . $comment['Member']     . "</td>";
                                    echo "<td>" .$comment['comment_date']. "</td>";
                                    echo "<td>
                                        <a href='comments.php?do=Edit&comid=" .$comment['c_id'] . "' class='btn btn-success'>
                                        <i class='fa fa-edit'></i>Edit</a>
                                        <a href='comments.php?do=Delete&comid=" .$comment['c_id'] . "' class='btn btn-danger confirm'>
                                        <i class='fa fa-close'></i>Delete </a>";

                                        if ($comment['status'] == 0) {
                                            
                                            echo "<a href='comments.php?do=Approve&comid=" .$comment['c_id'] . "'
                                            class='btn btn-info activate'>
                                            <i class='fa fa-check'></i> Approve</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }

                        ?>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php }  else {
                echo '<div class="container">';
                    echo '<div class="nice-message"> There No Comments To Show</div>';
                echo '</div>';
            } ?>
        <?php

        } elseif ($do == 'Edit') { // Edite page 

        // chack if userid is numeric and ger inerger value of it

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        // select all data deband in this id

        $stmt = $con->prepare("SELECT * FROM  comments WHERE c_id = ? ");

        // Execte Query

        $stmt->execute(array($comid));

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
                    <div class="panel panel-heading text-center">Edit Comments</div>
                    <div class="panel-body">

                            <form class="form-horizontal" action="?do=Update" method="POST">
                                <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">comment</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">          
                                    <div class="col-sm-offset-2 col-sm-6">
                                        <input type="submit" value="Save" class="btn btn-primary btn-lg" />
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
    
    }elseif ($do == 'Update') {    // update page
         
        echo "<h1 class='text-center'> Update Comments </h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // get informtion fron the form
            
            $comid      = $_POST['comid'];
            $comment    = $_POST['comment'];

            
                    // update the datebade with this informtion

                    $stmt = $con->prepare("UPDATE comments SET comment = ?  WHERE c_id = ? ");
                    $stmt->execute(array($comment, $comid));

                    // echo success message

                    $theMsg =  "<div class='alert alert-success'>" .$stmt->rowCount() . ' Comment updated</div>';
                    redirectHome($theMsg, 'back');
            
        } else {
            
            $theMsg =  "<div class='alert alert-success'> you cant browse this page dirctly</div>";
            redirectHome($theMsg, 'back');
        }

        echo "</div>";

    } elseif ($do == 'Delete') {    // Delete Members Page

        echo '<h1 class="text-center"> Delelte Commetn</h1>';
        echo '<div class="container">';
         // chack if userid is numeric and ger inerger value of it

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

         // select all data deband in this id

        $check = checkItem('c_id', 'comments', $comid);
 
         // if id is found show the form
 
        if ($check > 0)  { 
             
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
            $stmt->bindParam(":zid", $comid);
            $stmt->execute();
             
            // success message
            $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' Deleted updated</div>';
            redirectHome($theMsg, 'back', 20);

        } else {

            $theMsg = "<div class='alert alert-success'> there is no suth id</div>";
            redirectHome($theMsg,);
          }

          echo '</div>';

    
    
}elseif ($do == 'Approve') {

    echo '<h1 class="text-center">Approve</h1>';
    echo '<div class="container">';

    // chack if userid is numeric and ger inerger value of it

    $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

    // select all data deband in this id

    $check = checkItem('c_id', 'comments', $comid);

    // if id is found show the form

    if ($check > 0)  { 

        $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE c_id = ?");

        $stmt->execute(array($comid));

        // success message
        $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' Approve</div>';
        redirectHome($theMsg, 'back', 3);

    } else {

        $theMsg = "<div class='alert alert-success'> there is no suth id</div>";
        redirectHome($theMsg,);

    }      
    echo '</div>';

}

     include   $tpl . 'footer.php';
     
     } else {

        header("Location: index.php");

        exit();
    }