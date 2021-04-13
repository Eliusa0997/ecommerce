











<?php 

    ob_start();
    session_start();
    $pageTitle = 'Create New Item';
    include 'init.php';  
    if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        
        $formErrors = array();

        $avatarName = $_FILES['avatar']['name'];
        $avatarSize = $_FILES['avatar']['size'];
        $avatarTmp  = $_FILES['avatar']['tmp_name'];
        $avatarType = $_FILES['avatar']['type'];

        // list of allow type file to upload

        $avatarAllowedExtension = array("jpeg", "jpg", "png", "jif");

        // get avatar Extension

        $avatarExtension = strtolower ( end( explode('.' , $avatarName)));


        $name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price    = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status   = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

        if (strlen($name) < 4) {

            $formErrors[] = 'Sorry User Name Must Be 4 Char ';
        }

        if (strlen($desc) < 10) {

            $formErrors[] = 'Sorry description Must Be 10 Char ';
        }

        if (empty($price)) {

            $formErrors[] = 'Sorry price Cant Be Empty ';
        }

        if (strlen($country) < 2) {

            $formErrors[] = 'Sorry description Must Be 10 Char ';
        }

        if (empty($status)) {

            $formErrors[] = 'Sorry status Cant Be Empty ';
        }

        if (empty($category)) {

            $formErrors[] = 'Sorry category Cant Be Empty ';
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


        // chack if there is no error do the database oparetor

        if (empty($formErrors)) {

            $avatar = rand(0, 1000000) . '_' . $avatarName;

            move_uploaded_file($avatarTmp, "uploads\avatars\\" .$avatar);


            // update the datebade with this informtion

            $stmt = $con->prepare("INSERT INTO 
                                items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags, avatar_img)
                                VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now() , :zcat, :zmember, :ztags, :zavatar)");

            $stmt->execute(array(
            'zname'     =>   $name,
            'zdesc'     =>   $desc,
            'zprice'    =>   $price,
            'zcountry'  =>   $country,
            'zstatus'   =>   $status,
            'zcat'      =>   $category,
            'zmember'   =>   $_SESSION['uid'],
            'ztags'     =>   $tags,
            'zavatar'   =>   $avatar
            ));    

            // echo success message

            if ($stmt) {
                $succesMsg = 'Ttem Has Been Added Successflly'; 
            }    
        }
    }

?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading"><?php echo $pageTitle ?></div>
        <div class="panel-body">
            <div class="col-md-8">
            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group form-group-lg"> 
                    <!-- Start Name Field -->          
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-9">
                        <input
                            pattern=".{4,}"
                            title="This Fiald Requred At Least 4 Char"
                            type="text"
                            name=       "name" 
                            class=      "form-control live" 
                            placeholder=" Name Of The Item"
                            data-class=".live-title"
                            required
                        />
                    </div>
                    </div>
                    <!-- End Name Field -->     
                    <!-- Start Descriptiom Field -->     
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <input
                                pattern=".{10,}"
                                title="This Fiald Requred At Least 10 Char"
                                type="text"
                                name=       "description"
                                class=      "form-control live"
                                placeholder="Description The Item"
                                data-class=".live-desc"
                                required
                            />
                            
                        </div>
                    </div>
                    <!-- End Descriptiom Field -->     
                    <!-- Start Price Field -->     
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Price</label>
                        <div class="col-sm-9">
                            <input
                                type= "text"
                                name=       "price"
                                class=      "form-control live"
                                placeholder="Price for The Item"
                                data-class=".live-price"
                                required
                            />
                        </div>
                    </div>
                    <!-- End Price Fiuld -->     
                    <!-- Start Country_Made Field -->     
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Country</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                name=       "country"
                                class=      "form-control"
                                placeholder="Country of Made Item"
                                required
                            />
                        </div>
                    </div>
                    <!-- End Country_Made Field --> 
                    <!-- Start avatar Field -->     
                    <div class="form-group form-group-lg">           
                                <label 
                                    class="col-sm-3 control-label"> 
                                    User Avatatr
                                </label>
                                <div class="col-sm-9">
                                    <input 
                                        type="file" 
                                        name="avatar" 
                                        class="form-control" 
                                        required="required" />
                                </div>
                            </div>
                    <!-- end avatar Field -->     
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <select name="status" required>
                                <option value="">.....</option>
                                <option value="1">New</option>
                                <option value="2">Lile New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->  
 
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Categories</label>
                        <div class="col-sm-9">
                            <select name="category" required>
                                <option value="">.....</option>
                                <?php

                                    $cats = getAllFrom('*', 'categories', '', '', 'ID');
                        
                                    foreach ($cats as $cat) {
                                        
                                        echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>            
                    <!-- End Categories Field --> 

                    <!-- Start tags Field -->     
                    <div class="form-group form-group-lg">           
                        <label class="col-sm-3 control-label">Tags</label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text"
                            name=       "tags"
                            class=      "form-control"
                            placeholder="Use Spase tO sprate The Tags"/>
                        </div>
                    </div>
                    <!-- End tags Field -->  

                    <div class="form-group">          
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="submit"
                            value="Add Item"
                            class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="thumbnail item-box live-preview">
                    <span class="price-tag">
                        $<span class="live-price">0</span>
                    </span>
                        <img class="img-responsive" src="yasoo.png" alt=""/>
                        <div class="caption">
                            <h3 class="live-title">Title </h3>
                            <p class="live-desc">Description</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start looping throw errors  -->
            <?php
                if (! empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                }
                if (isset($succesMsg)) {
                    echo '<div class="alert alert-success">' .$succesMsg. '</div>';
                }
            ?>
            <!-- end looping throw errors -->
        </div>
    </div>
</div>
</div>

<?php

}else {
    header('Location: login.php');
    exit();
}


include $tpl .'/footer.php';

ob_end_flush();
?>