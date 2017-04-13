<?php

class Installer
{
    public $installer_id;
    public $installer_name;
    public $phone;
    public $website;
    public $street_line_1;
    public $street_line_2;
    public $city;
    public $state;
    public $zip;
    public $country;
    public $latitude;
    public $longitude;
    //public $products;
    //public $industry;
    public $status;

    public function __construct()
    {
        $this->status = 1;
    }

    public function save()
    {
        global $wpdb;
        $confirm = $wpdb->insert(TableInstaller,
            array(
                'installer_name' => $this->installer_name,
                'phone' => $this->phone,
                'website' => $this->website,
                'street_line_1' => $this->street_line_1,
                'street_line_2' => $this->street_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'country' => $this->country,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => $this->status
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        if($confirm){
            $lastid = $wpdb->insert_id;
            return $lastid;
        }else{
            return 0;
        }
    }

    public function getInstaller($page = NULL, $status = "")
    {
        global $wpdb;
        // $query = "SELECT * FROM ".TableInstaller." ORDER BY installer_id DESC";
        //$query = "SELECT ".TableInstaller.".* FROM " . TableInstaller . " WHERE 1=1";
        //echo $query;
        $query = "SELECT ".TableInstaller.".*, (SELECT GROUP_CONCAT(wp_posts.post_title) FROM ".TableWpPost." JOIN ".TableInstallerProduct." ON ".TableWpPost.".ID=".TableInstallerProduct.".product_id WHERE ".TableInstallerProduct.".installer_id=".TableInstaller.".installer_id) AS products_name, (SELECT GROUP_CONCAT(".TableWpPost.".ID) FROM ".TableWpPost." JOIN ".TableInstallerProduct." ON ".TableWpPost.".ID=".TableInstallerProduct.".product_id WHERE ".TableInstallerProduct.".installer_id=".TableInstaller.".installer_id) AS products_id,(SELECT GROUP_CONCAT(".TableWpPost.".post_title) FROM ".TableWpPost." JOIN ".TableInstallerIndustry." ON ".TableWpPost.".ID=".TableInstallerIndustry.".industry_id WHERE ".TableInstallerIndustry.".installer_id=".TableInstaller.".installer_id) AS industries_name, (SELECT GROUP_CONCAT(".TableWpPost.".ID) FROM ".TableWpPost." JOIN ".TableInstallerIndustry." ON ".TableWpPost.".ID=".TableInstallerIndustry.".industry_id  WHERE ".TableInstallerIndustry.".installer_id=".TableInstaller.".installer_id) AS industries_id FROM ".TableInstaller;
        //echo $query;
        if ($status != "") {
            $query .= " AND status = '$status'";
        }
        $query .= " ORDER BY installer_id DESC";
        if ($page != NULL) {
            $start = $page == 1 ? 0 : ($page - 1) * 10;
            $query .= " LIMIT $start,10";
        }
        return $wpdb->get_results($query, OBJECT);
    }



    public function getIndustries($page = NULL)
    {
        global $wpdb;
        $industries = array();
        $args = array('post_type' => 'industry', 'post_status' => 'publish', 'posts_per_page' => -1);
        // $query = new WP_QUERY($args);
        $posts = get_posts($args);
        if ($posts) {
            foreach ($posts as $post) {
                // echo "<pre>"; print_r($post); echo "<pre>";
                $industries[$post->ID] = $post->post_title;
            }
        }
        wp_reset_query();
        return $industries;
    }

    /*public function getInstallerForFrontend($name = "", $zip_code = "", $state = "", $products_array = "", $industry_array = "")
    {
        global $wpdb;
        $query = "SELECT installer_id, installer_name as name, CONCAT_WS(', ', IF(LENGTH(`street_line_1`),`street_line_1`,NULL), IF(LENGTH(`street_line_2`),`street_line_2`,NULL), IF(LENGTH(`city`),`city`,NULL), IF(LENGTH(`state`),`state`,NULL), IF(LENGTH(`country`),`country`,NULL), IF(LENGTH(`zip`),`zip`,NULL)) as address, longitude, latitude FROM " . TableInstaller . " WHERE 1=1 ";
        $query .= " AND status=1 ";
        if ($name != "") {
            $query .= " AND installer_name LIKE '%$name%' ";
        }
        if ($zip_code != "") {
            $query .= " AND zip = '$zip_code' ";
        }
        if ($state != "") {
            $query .= " AND state LIKE '%$state%' ";
        }

        if ($products_array != "") {
            $query .= ' AND (';
            foreach ($products_array as $k => $value) {
                $v_string = $value . ',';
                if ($k == 0) {
                    $query .= " products LIKE '%$v_string%' ";
                } else {
                    $query .= " OR products LIKE '%$v_string%'";
                }
            }
            $query .= ')';
        }

        if ($industry_array != "") {
            $query .= ' AND (';
            foreach ($industry_array as $k => $value) {
                $v_string = $value . ',';
                if ($k == 0) {
                    $query .= " industry LIKE '%$v_string%' ";
                } else {
                    $query .= " OR industry LIKE '%$v_string%'";
                }
            }
            $query .= ')';
        }

        $query .= " ORDER BY installer_id DESC";
        //echo $query;
        return $wpdb->get_results($query, OBJECT);
    }*/
	
	
	public function getInstallerForFrontend($name = "", $zip_code = "", $state = "", $products_array = "", $industry_array = "")
    {
        global $wpdb;
		$sub_query_product_names = "(SELECT GROUP_CONCAT(wp_posts.post_title) FROM ".TableWpPost." JOIN ".TableInstallerProduct." ON ".TableWpPost.".ID=".TableInstallerProduct.".product_id WHERE ".TableInstallerProduct.".installer_id=".TableInstaller.".installer_id)";
		
		$sub_query_product_ids = "(SELECT GROUP_CONCAT(".TableWpPost.".ID) FROM ".TableWpPost." JOIN ".TableInstallerProduct." ON ".TableWpPost.".ID=".TableInstallerProduct.".product_id WHERE ".TableInstallerProduct.".installer_id=".TableInstaller.".installer_id)";
		
		$sub_query_industry_names = "(SELECT GROUP_CONCAT(".TableWpPost.".post_title) FROM ".TableWpPost." JOIN ".TableInstallerIndustry." ON ".TableWpPost.".ID=".TableInstallerIndustry.".industry_id WHERE ".TableInstallerIndustry.".installer_id=".TableInstaller.".installer_id)";
		
		
		$sub_query_industry_ids = "(SELECT GROUP_CONCAT(".TableWpPost.".ID) FROM ".TableWpPost." JOIN ".TableInstallerIndustry." ON ".TableWpPost.".ID=".TableInstallerIndustry.".industry_id  WHERE ".TableInstallerIndustry.".installer_id=".TableInstaller.".installer_id)";
		
		
        $query = "SELECT ".TableInstaller.".*, ".$sub_query_product_names." AS products_name, ".$sub_query_product_ids." AS products_id, ".$sub_query_industry_names." AS industries_name, ".$sub_query_industry_ids." AS industries_id FROM ".TableInstaller." WHERE 1=1 ";
        
		$query .= " AND status=1 ";
        if ($name != "") {
            $query .= " AND installer_name LIKE '%$name%' ";
        }
        if ($zip_code != "") {
            $query .= " AND zip = '$zip_code' ";
        }
        if ($state != "") {
            $query .= " AND state LIKE '%$state%' ";
        }

        if ($products_array != "") {
            $query .= ' AND (';
            foreach ($products_array as $k => $value) {
                $v_string = $value . ',';
                if ($k == 0) {
                    $query .= " CONCAT(".$sub_query_product_ids.",',') LIKE '%$v_string%' ";
                } else {
                    $query .= " OR CONCAT(".$sub_query_product_ids.",',')  LIKE '%$v_string%'";
                }
            }
            $query .= ')';
        }

        if ($industry_array != "") {
            $query .= ' AND (';
            foreach ($industry_array as $k => $value) {
                $v_string = $value . ',';
                if ($k == 0) {
                    $query .= " CONCAT(".$sub_query_industry_ids.",',') LIKE '%$v_string%' ";
                } else {
                    $query .= " OR CONCAT(".$sub_query_industry_ids.",',') LIKE '%$v_string%'";
                }
            }
            $query .= ')';
        }

        $query .= " ORDER BY installer_id DESC";
		return $wpdb->get_results($query, OBJECT); 
    }
	
	
	

    public function updateInstaller($data, $id)
    {
        global $wpdb;
        $where = array('installer_id' => $id);
        return $wpdb->update(TableInstaller, $data, $where);
    }

    public function deleteInstaller($id)
    {
        global $wpdb;
        $where = array('installer_id' => $id);
        return $wpdb->delete(TableInstaller, $where);
    }

}