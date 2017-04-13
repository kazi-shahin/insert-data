<form class="form-horizontal" action="" method="post">
    <?php
    //Initialize for common validation messages
    $message = "";
    $warning_message = "";

    //Initialize for single validation messages
    $e_installer_name = "";
    $e_state = "";
    $e_country = "";
    $count_error = 0;
    $products = "";
    $industry = "";
    $installer = new Installer();

    if (isset($_POST['btn_submit'])) {

        //print_r($_POST);exit();
        $installer_id = post_data('installer_id');
        $installer_name = post_data('installer_name');
        $phone = post_data('phone');
        $website = post_data('website');
        $street_1 = post_data('street_line_1');
        $street_2 = post_data('street_line_1');
        $city = post_data('city');
        $state = post_data('state');
        $zip = post_data('zip');
        $country = post_data('country');
        $latitude = post_data('latitude');
        $longitude = post_data('longitude');
        $products = post_data('products');
        $industries = post_data('industry');

        if ($installer_name == "") {
            $count_error++;
            $e_installer_name = error('Required');
        }


        if ($state == "") {
            $count_error++;
            $e_state = error('Required');
        }

        if ($country == "") {
            $count_error++;
            $e_country = error('Required');
        }


        if ($count_error == 0) {
            //exit('<h2>You are in non error position</h2>');

            if ($latitude == '' || $longitude == '') {
                $string = $zip . ',' . $street_1 . ',' . $street_2 . ',' . $city . ',' . $state . ',' . $country;
                $splited = explode(",", $string);
                $new_array = array();
                foreach ($splited as $value) {
                    if ($value != "") {
                        $new_array[] = str_replace(" ", "%20", $value);
                    }
                }
                $array_string = implode(",", $new_array);
                $url_string = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $array_string . "&sensor=false";
                $data = file_get_contents($url_string);
                $decoded = json_decode($data);
                if ($decoded->status == "OK") {
                    $long_lat = $decoded->results[0]->geometry->location;
                    $latitude = $long_lat->lat;
                    $longitude = $long_lat->lng;
                } else {
                    //echo 'Problem with longitude_lattitude';
                    $warning_message = '<div class="alert alert-danger fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Problem with Longitude and Latitude! May be you provide wrong address</div>';
                }
            }
            $installer->installer_name = $installer_name;
            $installer->street_line_1 = $street_1;
            $installer->street_line_2 = $street_2;
            $installer->phone = $phone;
            $installer->website = $website;
            $installer->city = $city;
            $installer->state = $state;
            $installer->zip = $zip;
            $installer->country = $country;
            $installer->latitude = $latitude;
            $installer->longitude = $longitude;
            //$installer->products = $products;

            $tag = "";
            if ($installer_id == "") {
                $confirm = $installer->save();
                if(!empty($industries)) {
                    $installer_industry = new InstallerIndustry();
                    foreach ($industries as $single_industry) {
                        $installer_industry->installer_id = $confirm;
                        $installer_industry->industry_id = $single_industry;
                        $installer_industry->save();
                    }
                }

                if(!empty($products)) {
                    $installer_product = new InstallerProduct();
                    foreach ($products as $single_product) {
                        $installer_product->installer_id = $confirm;
                        $installer_product->product_id = $single_product;
                        $installer_product->save();
                    }
                }
                $tag = "saved";

                if($confirm>0){
                    $status=true;
                }else{
                    $status=false;
                }

            } else {
                $data = (array)$installer;
                unset($data['installer_id']);
                //print_r($data);exit();
                $confirm = $installer->updateInstaller($data, $installer_id);
                //echo $confirm;
                $installer_industry = new InstallerIndustry();
                $installer_product = new InstallerProduct();

                $installer_industry->deleteByInstallerId($installer_id);
                $installer_product->deleteByInstallerId($installer_id);

                if(!empty($industries)) {
                    foreach ($industries as $single_industry) {
                        $installer_industry->installer_id = $installer_id;
                        $installer_industry->industry_id = $single_industry;
                        $installer_industry->save();
                    }
                }

                if(!empty($products)) {
                    foreach ($products as $single_product) {
                        $installer_product->installer_id = $installer_id;
                        $installer_product->product_id = $single_product;
                        $installer_product->save();
                    }
                }
                $tag = "updated";
                //echo $confirm;
                if($confirm) {
                    $status = true;
                }else{
                    $status = false;
                }
                $status = true;
            }
            if ($status) {
                $message = '<div class="alert alert-success fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>You have successfully ' . $tag . ' installer</div>';
            } else {
                $message = '<div class="alert alert-danger fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Operation Failed</div>';
            }
        } else {
            //exit('<h2>Error position </h2>'.$count_error);
        }
    }

    $industries = $installer->getIndustries();

    $args = array(
        'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status'=>'publish'
    );
    $the_query = new WP_Query($args);
    $posts = $the_query->get_posts();
    //echo '<pre>';print_r($posts);echo '</pre>';
    ?>
    <div class="form-group">
        <div class="col-sm-12">
            <?php
            echo $message;
            echo $warning_message;
            ?>
        </div>
    </div>

    <input type="hidden" id="installer_id" name="installer_id">

    <div class="form-group">
        <label class="col-sm-3 control-label">Installer Name</label>
        <div class="col-sm-6">
            <input type="text" name="installer_name" id="installer_name" class="form-control"
                   value="<?php if ($count_error > 0) {
                       echo post_data('installer_name');
                   } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_installer_name">
            <?php echo $e_installer_name; ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Phone</label>
        <div class="col-sm-6">
            <input type="text" name="phone" id="phone" class="form-control"
                   value="<?php if ($count_error > 0) {
                       echo post_data('phone');
                   } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_phone">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Website</label>
        <div class="col-sm-6">
            <input type="text" name="website" id="website" class="form-control"
                   value="<?php if ($count_error > 0) {
                       echo post_data('website');
                   } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_website">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Street Line 1</label>
        <div class="col-sm-6">
            <input type="text" name="street_line_1" id="street_line_1" class="form-control"
                   value="<?php if ($count_error > 0) {
                       echo post_data('street_line_1');
                   } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_street_line_1">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Street Line 2</label>
        <div class="col-sm-6">
            <input type="text" name="street_line_2" id="street_line_2" class="form-control"
                   value="<?php if ($count_error > 0) {
                       echo post_data('street_line_2');
                   } ?>"/>
        </div>
        <div class="col-sm-3" id="jmsg_street_line_2">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">City</label>
        <div class="col-sm-6">
            <input type="text" name="city" id="city" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('city');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_city">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">State</label>
        <div class="col-sm-6">
            <input type="text" name="state" id="state" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('state');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_state">
            <?php echo $e_state; ?>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Zip/Postal Code</label>
        <div class="col-sm-6">
            <input type="text" name="zip" id="zip" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('zip');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_zip">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Country</label>
        <div class="col-sm-6">
            <input type="text" name="country" id="country" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('country');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_country">
            <?php echo $e_country; ?>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Latitude <i class="glyphicon info glyphicon-info-sign"></i> </label>
        <div class="col-sm-6">
            <input type="text" name="latitude" id="latitude" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('latitude');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_latitude">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Longitude <i class="glyphicon info glyphicon-info-sign"></i> </label>
        <div class="col-sm-6">
            <input type="text" name="longitude" id="longitude" class="form-control" value="<?php if ($count_error > 0) {
                echo post_data('longitude');
            } ?>"/>
        </div>
        <div class="col-sm-3" id="msg_longitude">
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">Products</label>
        <div class="col-sm-6">
            <?php
            foreach ($posts as $post) {
                $checked = "";
                if ($count_error > 0 && $products != "") {
                    $current_value = $post->ID;
                    if (in_array($current_value, $products)) {
                        $checked = "checked";
                    }
                }
                ?>
                <div class="checkbox">
                    <label><input type="checkbox" <?php echo $checked; ?> name="products[]"
                                  value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></label>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-3"></div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Industries</label>
        <div class="col-sm-6">
            <?php
            foreach ($industries as $postID => $single_industry) {
                $checked = "";
                if ($count_error > 0 && $industry != "") {
                    $current_value = $postID;
                    if (in_array($current_value, $industries)) {
                        $checked = "checked";
                    }
                }
                ?>
                <div class="checkbox">
                    <label><input type="checkbox" <?php echo $checked; ?> name="industry[]"
                                  value="<?php echo $postID; ?>"><?php echo $single_industry; ?></label>
                </div>
            <?php } ?>

            <!-- <input type="text" name="industry" id="industry" class="form-control" value="<?php //if($count_error>0){echo post_data('industry');}?>" /> -->
        </div>
        <div class="col-sm-3" id="msg_industries">
            <?php //echo $e_industry;?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3"></label>
        <div class="col-sm-6">
            <button type="button" class="btn btn-danger" id="reset" name="reset">CLEAR</button>
            <button type="submit" name="btn_submit" id="btn_submit" class="btn btn-success">REGISTER</button>
        </div>
        <div class="col-sm-3"></div>
    </div>
</form>