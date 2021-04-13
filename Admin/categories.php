


















<?php
/*
=======================================================
== Category page
=======================================================
*/

ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['Username'])) {

    $pageTitle = 'Categories';
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // start manage page

    if ($do == 'Manage') {      // manage page 

        $sort = 'ASC';

        $sort_array = array('DESC', 'ASC');

        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

            $sort = $_GET['sort'];

        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll(); 

        if (! empty($cats)) {
        
        ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories 
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering:[
                            <a class="<?php if($sort == 'ASC') {echo 'active';} ?> " href="?sort=ASC">ASC</a>
                            <a class="<?php if($sort == 'DESC') {echo 'active';} ?> " href="?sort=DESC">SESC</a>]
                            <i class="fa fa-eye"></i> View:[
                            <span class="active" data-view="full">Full</span>
                            <span data-view="full">Classic</span>]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php

                            foreach ($cats as $cat) {   
                                echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&catid=" .$cat['ID']. "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                                    echo "<a href='categories.php?do=Delete&catid=" .$cat['ID']. "' class=' confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                                echo "</div>";
                                    echo "<h3>" . $cat['Name'] . '</h3>';
                                    echo "<div class='full=view'>";
                                        echo "<p>"; if($cat['Description'] == '') {echo 'This Category Has No Description';} else { $cat['Description'];} echo "</p>";
                                        if($cat['Visibility'] == 1) {echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden </span>'; }
                                        if($cat['Allow_Comment'] == 1) {echo '<span class="commenting"><i class="fa fa-comment"></i>  Comment Disabled </span>'; }
                                        if($cat['Allow_Ads'] == 1) {echo '<span class="advertises"><i class="fa fa-close"></i>  Ads Disabled </span>'; }
                                    echo "</div>";

                                                                    // get chiled category
                                    $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
                                    if (! empty($childCats)) {
                                        echo "<h4 class='child-head'> Child Categories <h4>";
                                        echo "<ul class='list-unstyled child-cats'>";
                                        foreach ($childCats as $c) {
                                            echo "<li class='child-link'>
                                                <a href='categories.php?do=Edit&catid=" .$c['ID']. "'>"  . $c['Name'] . "</a>
                                                <a href='categories.php?do=Delete&catid=" .$c['ID']. "' class='show-delete confirm'>Delete</a>
                                            </li>";
                                        }
                                        echo "</ul>";
                                    }
                                    echo "</div>";
                                echo "<hr>";
                            }
                                
                        ?>
                    </div>
                </div>
                <a class="add-category btn btn-primary" href="categories.php?do=Add">
                    <i class="fa fa-plus"></i> Add New Category</a>
            </div>

            <?php }  else {
                echo '<div class="container">';
                    echo '<div class="nice-message"> There No Comments To Show</div>';
                    echo '<a class="add-category btn btn-primary" href="categories.php?do=Add">
                        <i class="fa fa-plus"></i> Add New Category
                        </a>';
                echo '</div>';
            } ?>                 
        <?php
    
        
    }elseif ($do == 'Add') {?>

        wellcom to add page

        <container class="col-lg-8" style=" margin: 50px 20px 20px 250px;">  
            <div class="row">
            <div class="col-lg-12">
            <div class="panel panel-info" style=" margin-bottom: 100px;">
                <div class="panel panel-heading text-center">Add New Category Members</div>
                <div class="panel-body">
                        
                        <form class="form-horizontal" action="?do=Insert" method="POST">
                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" required="required"
                                    placeholder=" Name Of The Category"/>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" name="description" class="form-control"
                                    placeholder="Description The Category"/>
                                    
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Ordering</label>
                                <div class="col-sm-9">
                                    <input type="text" name="ordering" class="form-control"
                                    placeholder="To Arange The Categories"/>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Parent?</label>
                                <div class="col-sm-9" col-md-6>
                                    <select name="parent">
                                        <option value="0">Nune</option>
                                        <?php
                                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "DESC");
                                            foreach ($allCats as $cat) {
                                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Visible</label>
                                <div class="col-sm-9">
                                    <div>
                                        <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                        <label for="vis-yes"> Yes</label>
                                    </div>
                                <div>
                                        <input id="vis-no" type="radio" name="visibility" value="1" />
                                        <label for="vis-no"> No</label>
                                    </div>
                            </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Allow Commenting</label>
                                <div class="col-sm-9">
                                    <div>
                                        <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                        <label for="com-yes"> Yes</label>
                                    </div>
                                <div>
                                        <input id="com-no" type="radio" name="commenting" value="1" />
                                        <label for="com-no"> No</label>
                                    </div>
                            </div>
                            </div>

                            <div class="form-group form-group-lg">           
                                <label class="col-sm-2 control-label">Allow Ads</label>
                                <div class="col-sm-9">
                                    <div>
                                        <input id="Ads-yes" type="radio" name="ads" value="0" checked />
                                        <label for="Ads-yes"> Yes</label>
                                    </div>
                                <div>
                                        <input id="Ads-no" type="radio" name="ads" value="1" />
                                        <label for="Ads-no"> No</label>
                                    </div>
                            </div>
                            </div>



                            <div class="form-group">          
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
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

            echo "<h1 class='text-center'> Insert Category </h1>";
            echo "<div class='container'>";
            // get informtion fron the form
    
            $name            = $_POST['name'];
            $desc            = $_POST['description'];
            $parent          = $_POST['parent'];
            $order           = $_POST['ordering'];
            $visible         = $_POST['visibility'];
            $comment         = $_POST['commenting'];
            $ads             = $_POST['ads'];

                // chek if  Category is exessit in database

                $check = checkItem("Name", "categories", $name);

                if($check == 1){
                    $theMsg =  "<div class='alert alert-danger'>sorry user is exessist</div>";
                    redirectHome($theMsg, 'back', 5);

                } else {

                    // update the datebade with this informtion

                    $stmt = $con->prepare("INSERT INTO 
                                        categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                        VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads )");

                    $stmt->execute(array(
                    'zname'       =>   $name,
                    'zdesc'       =>   $desc,
                    'zparent'     =>   $parent,
                    'zorder'      =>   $order,
                    'zvisible'    =>   $visible,
                    'zcomment'    =>   $comment,
                    'zads'        =>   $ads
                    ));    

                    // echo success message

                    $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' record updated</div>';
                    redirectHome($theMsg, 'back');

                }    
         
    

    } else {

        echo "<div class='container'>";
        $theMsg =  "<div class='alert alert-danger'>sorry you cant browse this page dirctly</div>";
        redirectHome($theMsg, 'back', 5);
        echo "</div>";
    }
 
    }elseif ($do == 'Edit') {    

        // chack if userid is numeric and ger inerger value of it

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        // select all data deband in this id

        $stmt = $con->prepare("SELECT * FROM  categories WHERE ID = ?");

        // Execte Query

        $stmt->execute(array($catid));

        // Fetch the data

        $cat = $stmt->fetch();

        // the row count

        $count = $stmt->rowCount();

        // if id is found show the form

        if ($count > 0)  { ?>


            <container class="col-lg-8" style=" margin: 50px 20px 20px 250px;">  
                <div class="row">
                <div class="col-lg-12">
                <div class="panel panel-info" style=" margin-bottom: 100px;">
                    <div class="panel panel-heading text-center">Edit Category</div>
                    <div class="panel-body">
                            
                            <form class="form-horizontal" action="?do=Update" method="POST">
                            <input type="hidden" name="catid" value="<?php echo $cat['ID'] ?> "/>
                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" required="required"
                                        placeholder=" Name Of The Category" value="<?php echo $cat['Name']; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="description" class="form-control"
                                        placeholder="Description The Category" value="<?php echo $cat['Description']; ?>"/>
                                        
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Ordering</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="ordering" class="form-control"
                                        placeholder="To Arange The Categories"  value="<?php echo $cat['Ordering']; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Parent?</label>
                                    <div class="col-sm-9" col-md-6>
                                        <select name="parent">
                                            <option value="0">Nune</option>
                                            <?php
                                                $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "DESC");
                                                foreach ($allCats as $c) {
                                                    echo "<option value='" . $c['ID'] . "'";
                                                    if($cat['parent'] == $c['ID']) { echo ' selected' ;}
                                                    echo ">" . $c['Name'] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Visible</label>
                                    <div class="col-sm-9">
                                        <div>
                                            <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked';} ?> />
                                            <label for="vis-yes"> Yes</label>
                                        </div>
                                    <div>
                                            <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo 'checked';} ?>/>
                                            <label for="vis-no"> No</label>
                                        </div>
                                </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Allow Commenting</label>
                                    <div class="col-sm-9">
                                        <div>
                                            <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';} ?> />
                                            <label for="com-yes"> Yes</label>
                                        </div>
                                    <div>
                                            <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked';} ?>/>
                                            <label for="com-no"> No</label>
                                        </div>
                                </div>
                                </div>

                                <div class="form-group form-group-lg">           
                                    <label class="col-sm-2 control-label">Allow Ads</label>
                                    <div class="col-sm-9">
                                        <div>
                                            <input id="Ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';} ?> />
                                            <label for="Ads-yes"> Yes</label>
                                        </div>
                                    <div>
                                            <input id="Ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';} ?>/>
                                            <label for="Ads-no"> No</label>
                                        </div>
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

        <?php 

        // Else show the Error message
            
        } else {
            
            echo "<div class='container'>";
            $theMsg =  "<div class='alert alert-danger'>There is no sutch id</div>";
            redirectHome($theMsg, 'back', 5);
            echo "</div>";
        }

    }elseif ($do == 'Update') { 
        
        echo "<h1 class='text-center'> Update Members </h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // get informtion fron the form
            
            $id          = $_POST['catid'];
            $name        = $_POST['name'];
            $desc        = $_POST['description'];
            $order       = $_POST['ordering'];
            $parent      = $_POST['parent'];
            $visible     = $_POST['visibility'];
            $comment     = $_POST['commenting'];
            $ads         = $_POST['ads'];

            // chack if there is no error do the database oparetor

            // update the datebade with this informtion

            $stmt = $con->prepare("UPDATE
                                                categories 
                                        SET
                                                Name           = ?,
                                                Description    = ?,
                                                Ordering       = ?,
                                                parent         = ?,
                                                Visibility     = ?,
                                                Allow_Comment  = ?,
                                                Allow_Ads      = ?
                                        WHERE
                                                ID             = ?
                                ");
                                            
            $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

            // echo success message

            $theMsg =  "<div class='alert alert-success'>" .$stmt->rowCount() . ' record updated</div>';
            redirectHome($theMsg, 'back');
                
        }else{
                
                $theMsg =  "<div class='alert alert-success'> you cant browse this page dirctly</div>";
                redirectHome($theMsg, 'back');

        }

            echo "</div>";
        
        
    }elseif ($do == 'Delete') {

        echo '<h1 class="text-center"> Delete Category</h1>';
        echo '<div class="container">';
         // chack if userid is numeric and ger inerger value of it

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

         // select all data deband in this id

        $check = checkItem('ID', 'categories', $catid);
 
         // if id is found show the form
 
        if ($check > 0)  { 
             
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
            $stmt->bindParam(":zid", $catid);
            $stmt->execute();
             
            // success message
            $theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . ' Deleted</div>';
            redirectHome($theMsg, 'back', 3);

        } else {

            $theMsg = "<div class='alert alert-success'> there is no suth id</div>";
            redirectHome($theMsg,);
          

          echo '</div>';
    }

    include   $tpl .'footer.php';

}else {

    header("Location: index.php");

    exit();
}

ob_end_flush();
}?>    
