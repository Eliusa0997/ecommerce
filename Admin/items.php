





<?php

//
// =======================================================
// == items page
// =======================================================
//



ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['Username'])) {

    $pageTitle = 'Items';
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // start manage page

    if ($do == 'Manage') {      // manage page

        $stmt = $con->prepare(" SELECT
                                    items.*, 
                                    categories.Name 
                                AS  category_name,
                                    users.Username 
                                FROM 
                                    items

                                INNER JOIN 
                                    categories
                                ON 
                                    categories.ID = items.Cat_ID
        
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = items.Member_ID
                                ORDER BY
                                    Item_ID
                                DESC     
                            ");

        $stmt->execute();

        $items = $stmt->fetchAll();

        if (! empty($items)) {
    
        ?>
    
        <h1 class="text-center"> Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>

                    <?php

                        foreach ($items as $item) {
                            
                            echo "<tr>";
                                echo "<td>" . $item['Item_ID']      . "</td>";
                                echo "<td>" . $item['Name']         . "</td>";
                                echo "<td>" . $item['Description']  . "</td>";
                                echo "<td>" . $item['Price']        . "</td>";
                                echo "<td>" . $item['Add_Date']     . "</td>";
                                echo "<td>" . $item['category_name']        . "</td>";
                                echo "<td>" . $item['Username']     . "</td>";
                                echo "<td>
                                    <a href='items.php?do=Edit&itemid=" .$item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='items.php?do=Delete&itemid=" .$item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                    
                                    if ($item['Approve'] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" .$item['Item_ID'] . "'
                                        class='btn btn-info activate'>
                                        <i class='fa fa-check'></i>  Approve </a>";
                                    }
                                echo "</td>";
                            echo "</tr>";
                        }

                    ?>
                    </tr>
                </table>
            </div>
            <a class="btn btn-info" href="items.php?do=Add">
                <i class="fa fa-plus"></i> Add Item</a>
        </div>
        <?php }  else {
                echo '<div class="container">';
                    echo '<div class="nice-message"> There No Items To Show</div>';
                echo '<a class="btn btn-info" href="items.php?do=Add">
                    <i class="fa fa-plus"></i> Add Item
                    </a>';
                echo '</div>';
            } ?>
        <?php

    }elseif ($do == 'Add') { ?>

        <container class="col-lg-8" style=" margin: 50px 20px 20px 250px;">  
            <div class="row">
            <div class="col-lg-12">
            <div class="panel panel-info" style=" margin-bottom: 100px;">
                <div class="panel panel-heading text-center">Add New Item</div>
                <div class="panel-body">
                        
                        <form class="form-horizontal" action="?do=Insert" method="POST">
                            <div class="form-group form-group-lg"> 
                                <!-- Start Name Field -->          
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "name" 
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder=" Name Of The Item"/>
                                </div>
                            </div>
                            <!-- End Name Field -->     
                            <!-- Start Descriptiom Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "description"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Description The Item"/>
                                    
                                </div>
                            </div>
                            <!-- End Descriptiom Field -->     
                            <!-- Start Price Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-9">
                                    <input type= "text"
                                    name=       "price"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Price for The Item"/>
                                    
                                </div>
                            </div>
                            <!-- End Price Fiuld -->     
                            <!-- Start Country_Made Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "country"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Country of Made Item"/>
                                    
                                </div>
                            </div>
                            <!-- End Country_Made Field --> 
                            <!-- Start Status Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status">
                                        <option value="0">.....</option>
                                        <option value="1">New</option>
                                        <option value="2">Lile New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->  
                            <!-- Start Members Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Members</label>
                                <div class="col-sm-9">
                                    <select name="member">
                                        <option value="0">.....</option>
                                        <?php
                                            $allMembers = getAllFrom("*", "users", "", "", "UserID");
                                            foreach ($allMembers as $user) {
                                                echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Members Field -->  
                            <!-- Start Categories Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Categories</label>
                                <div class="col-sm-9">
                                    <select name="category">
                                        <option value="0">.....</option>
                                        <?php
                                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                                            foreach ($allCats as $cat) {        
                                                echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                                $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                                foreach ($childCats as $child) {
                                                    echo "<option value='".$child['ID']."'> --- " .$child['Name']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>            
                            <!-- End Categories Field -->  
                            <!-- Start tags Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "tags"
                                    class=      "form-control"
                                    placeholder="Use Spase tO sprate The Tags"/>
                                </div>
                            </div>
                            <!-- End tags Field --> 

                            <div class="form-group">          
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="submit"
                                    value="Add Item"
                                    class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </container>

        <?php
    
    
    }elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<div class='container'>";
            // get informtion fron the form
    
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['member'];
            $cat        = $_POST['category'];
            $tags       = $_POST['tags'];

            $formErrors = array();

            if (empty($name)) {
                
                
                $formErrors[] = 'name cant be <strong> empty </strong>';

            }

            if (empty($desc)) {
                
                
                $formErrors[] = 'description cant be <strong> empty </strong>';

           }

            if (empty($price)) {
                
                
                $formErrors[] = 'price cant be <strong> empty </strong>';

            }

            if ($status == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Status </strong>';
            }

            if ($member == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Member </strong>';
            }

            if ($cat == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Category </strong>';
            }
                

            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">'. $error .'</div>' ;
            }

            // chack if there is no error do the database oparetor

            if (empty($formErrors)) {

                        // update the datebade with this informtion
    
                        $stmt = $con->prepare("INSERT INTO 
                                            items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                                            VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now() , :zcat, :zmember, :ztags)");

                        $stmt->execute(array(
                        'zname'     =>   $name,
                        'zdesc'     =>   $desc,
                        'zprice'    =>   $price,
                        'zcountry'  =>   $country,
                        'zstatus'   =>   $status,
                        'zcat'      =>   $cat,
                        'zmember'   =>   $member,
                        'ztags'     =>   $tags
                        ));    

                        // echo success message
    
                        $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' record updated</div>';
                        redirectHome($theMsg, 'back');
                    
            }

        } else {
        
        echo "<div class='container'>";
        $theMsg =  "<div class='alert alert-danger'>you cant browse this page dirctly</div>";
        redirectHome($theMsg,);
        echo "</div>";
    }

    echo "</div>";

    }elseif ($do == 'Edit') {

        // chack if userid is numeric and ger inerger value of it

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // select all data deband in this id
 
        $stmt = $con->prepare("SELECT * FROM  items WHERE Item_ID  = ?");
 
        // Execte Query
 
        $stmt->execute(array($itemid));
 
        // Fetch the data
 
        $item = $stmt->fetch();
 
        // the row count
 
        $count = $stmt->rowCount();
 
        // if id is found show the form
 
        if ($count > 0)  { ?>
 
        <container class="col-lg-8" style=" margin: 50px 20px 20px 250px;">  
            <div class="row">
            <div class="col-lg-12">
            <div class="panel panel-info" style=" margin-bottom: 100px;">
                <div class="panel panel-heading text-center">Eite Item</div>
                <div class="panel-body">
                        
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
                            <div class="form-group form-group-lg"> 
                                <!-- Start Name Field -->          
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "name" 
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder=" Name Of The Item"
                                    value="<?php echo $item['Name']; ?>"/>
                                </div>
                            </div>
                            <!-- End Name Field -->     
                            <!-- Start Descriptiom Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "description"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Description The Item"
                                    value="<?php echo $item['Description']; ?>"/>
                                    
                                </div>
                            </div>
                            <!-- End Descriptiom Field -->     
                            <!-- Start Price Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-9">
                                    <input type= "text"
                                    name=       "price"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Price for The Item" 
                                    value="<?php echo $item['Price']; ?>"/>
                                    
                                </div>
                            </div>
                            <!-- End Price Fiuld -->     
                            <!-- Start Country_Made Field -->     
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "country"
                                    class=      "form-control"
                                    required=   "required"
                                    placeholder="Country of Made Item"
                                    value="<?php echo $item['Country_Made']; ?>"/>
                                    
                                </div>
                            </div>
                            <!-- End Country_Made Field --> 
                            <!-- Start Status Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status">
                                        <option value="1" <?php if( $item['Status'] == 1){echo 'selected';} ?>>New</option>
                                        <option value="2" <?php if( $item['Status'] == 2){echo 'selected';} ?>>Lile New</option>
                                        <option value="3" <?php if( $item['Status'] == 3){echo 'selected';} ?>>Used</option>
                                        <option value="4" <?php if( $item['Status'] == 4){echo 'selected';} ?>>Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->  
                            <!-- Start Members Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Members</label>
                                <div class="col-sm-9">
                                    <select name="member">
                                        <option value="0">.....</option>
                                        <?php
                                            $allMembers = getAllFrom("*", "users", "", "", "UserID");
                                            foreach ($allMembers as $user) {
                                                echo "<option value='".$user['UserID']."'";
                                                if($item['Member_ID'] == $user['UserID'] ) {echo 'selected';}
                                                echo ">".$user['Username']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Members Field -->  
                            <!-- Start Categories Field -->
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Categories</label>
                                <div class="col-sm-9">
                                    <select name="category">
                                        <option value="0">.....</option>
                                        <?php
                                            $allCats = getAllFrom("*", "categories", "", "", "ID");
                                            foreach ($allCats as $cat) {
                                                echo "<option value='".$cat['ID']."'";
                                                if($item['Cat_ID'] == $cat['ID'] ) {echo 'selected';}
                                                echo ">".$cat['Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>            
                            <!-- End Categories Field -->  
                             <!-- Start tags Field -->     
                             <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                    name=       "tags"
                                    class=      "form-control"
                                    placeholder="Use Spase tO sprate The Tags"
                                    value="<?php echo $item['tags']; ?>"/>
                                </div>
                            </div>
                            <!-- End tags Field --> 

                            <div class="form-group">          
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="submit"
                                    value="Add Item"
                                    class="btn btn-primary btn-lg" />
                                </div>
                            </div>
                        </form>

                        <!-- start comment page in items edit page -->  
                        <!-- start comment page in items edit page -->  
                        <!-- start comment page in items edit page -->  


                        <?php
                        
                        $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username AS Member
                                    FROM
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id   
                                    WHERE
                                        item_id = ?
                                ");

            $stmt->execute(array($itemid));

            $rows = $stmt->fetchAll();

            if (! empty($rows)) {
                # code...
            

            ?>
                
                    <h1 class="text-center"> Manage [<?php echo $item['Name']; ?>] Comments</h1>
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                                <tr>
                                
                                    <td>Comments</td>
                                
                                    <td>User Name</td>
                                    <td>Added Date</td>
                                    <td>Control</td>
                                </tr>

                                <?php

                                    foreach ($rows as $row) {
                                        
                                        echo "<tr>";
                                        
                                            echo "<td>" . $row['comment'] . "</td>";
                                        
                                            echo "<td>" . $row['Member'] . "</td>";
                                            echo "<td>" .$row['comment_date']. "</td>";
                                            echo "<td>
                                                <a href='comments.php?do=Edit&comid=" .$row['c_id'] . "' class='btn btn-success'>
                                                <i class='fa fa-edit'></i>Edit</a>
                                                <a href='comments.php?do=Delete&comid=" .$row['c_id'] . "' class='btn btn-danger confirm'>
                                                <i class='fa fa-close'></i>Delete </a>";

                                                if ($row['status'] == 0) {
                                                    
                                                    echo "<a href='comments.php?do=Approve&comid=" .$row['c_id'] . "'
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
                    <?php } ?>
                </div>

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
                                                <textarea class="form-contrlol" name="comment"><?php echo $row['comment'] ?></textarea>
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

            <!-- end comment page in items edit page -->  
            <!-- end comment page in items edit page -->
            <!-- end comment page in items edit page -->  

                    </div>    
                </div>
            </div>
        </container>

        <?php
    
                }
 
        // Else show the Error message
             
        } else {
             
            echo "<div class='container'>";
            $theMsg =  "<div class='alert alert-danger'>There is no sutch id</div>";
            redirectHome($theMsg, 'back', 5);
            echo "</div>";
        }
 
    }elseif ($do == 'Update') {
         
        echo "<h1 class='text-center'> Update Items </h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // get informtion fron the form

            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $cat        = $_POST['category'];
            $member     = $_POST['member'];
            $tags       = $_POST['tags'];
           

            $formErrors = array();

            if (empty($name)) {
                
                
                $formErrors[] = 'name cant be <strong> empty </strong>';

            }

            if (empty($desc)) {
                
                
                $formErrors[] = 'description cant be <strong> empty </strong>';

           }

            if (empty($price)) {
                
                
                $formErrors[] = 'price cant be <strong> empty </strong>';

            }

            if ($status == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Status </strong>';
            }

            if ($member == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Member </strong>';
            }

            if ($cat == 0) {
                
                $formErrors[] = 'You Must Choser The <strong> Category </strong>';
            }
                

            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">'. $error .'</div>' ;
            }
            // chack if there is no error do the database oparetor

            if (empty($formErrors)) {

                    // update the datebade with this informtion

                    $stmt = $con->prepare("UPDATE
                                                items
                                            SET 
                                                Name         = ?,
                                                Description  = ?, 
                                                Price        = ?, 
                                                Country_Made = ?,
                                                Status       = ?, 
                                                Cat_ID       = ?, 
                                                Member_ID    = ?,
                                                tags         =?
                                            WHERE 
                                                Item_ID = ? ");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                    // echo success message

                    $theMsg =  "<div class='alert alert-success'>" .$stmt->rowCount() . ' record updated</div>';
                    redirectHome($theMsg, 'back');
            
            }
    }elseif ($do == 'Delete') {

        echo '<h1 class="text-center"> Delete Item</h1>';
        echo '<div class="container">';

         // chack if userid is numeric and ger inerger value of it

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

         // select all data deband in this id

        $check = checkItem('Item_ID', 'items', $itemid);
 
        // if id is found show the form
 
        if ($check > 0)  { 
             
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
            $stmt->bindParam(":zid", $itemid);
            $stmt->execute();
             
            // success message
            $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' Deleted</div>';
            redirectHome($theMsg, 'back', 3);

        
        } else {

            $theMsg = "<div class='alert alert-success'> there is no suth id</div>";
            redirectHome($theMsg,);
        } 

    }

    }elseif ($do == 'Approve') {

        echo '<h1 class="text-center"> Delete Item</h1>';
        echo '<div class="container">';

        // chack if userid is numeric and ger inerger value of it

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // select all data deband in this id

        $check = checkItem('Item_ID', 'items', $itemid);
 
        // if id is found show the form
 
        if ($check > 0)  { 

            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

            $stmt->execute(array($itemid));

            // success message
            $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' Approve</div>';
            redirectHome($theMsg, 'back', 3);

        } else {

            $theMsg = "<div class='alert alert-success'> there is no suth id</div>";
            redirectHome($theMsg,);

        }      
        echo '</div>';

    }    
       
    include $tpl . 'footer.php';

}else {

    header("Location: index.php");

    exit();
}

ob_end_flush();

?>    
