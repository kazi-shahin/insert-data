<?php

/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 4/10/2017
 * Time: 11:44 AM
 */
class bbi_backend {

    private $file;
    private $dataArray;
    private $dirname;
    private $insertArray;
    private $tableName;
    public $currentTime;
    public $errors = false;
    private $worksheetName;

    private function get_upload_drirectory_url($dirname='xml_upload'){
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $path = $upload_dir.'/'. $dirname .'/';
        return $path;
    }

    public function make_directory($dirname='xml_upload'){
        //The name of the directory that we need to create.
        $path = $this->get_upload_drirectory_url($dirname);
        //Check if the directory already exists.
        if(!is_dir($path)){
            //Directory does not exist, so lets create it.
            if (!mkdir($path, 0777, true)) {
                die('Failed to create folders...');
            } else { return true; }
        }
    }

    public function throw_error_msg($txt){
        echo '<div class="errorMsg">'.$txt.'</div>';
    }

    public function throw_success_msg($txt){
        echo '<div class="successMsg">'.$txt.'</div>';
    }

    public function xml_upload($file){
        $returnData = array();
        $path = $this->get_upload_drirectory_url();
        $this->currentTime = round(microtime(true));
        $fileInfo =  explode('.', $file['name']);
        $uploadfile = $path . $fileInfo[0] .'_'. $this->currentTime .'.'.$fileInfo[1];

        $returnData['fileName'] = $fileInfo[0] .'_'. $this->currentTime.'.'.$fileInfo[1];
        $returnData['extension'] = $fileInfo[1];
        $returnData['filePath'] = $uploadfile;

        // VALIDATION
        if ($file["type"] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
            $this->errors = true;
            $this->throw_error_msg('Type Error : Not valid file type');
        }

        if (file_exists($uploadfile)){
            $this->errors = true;
            $this->throw_error_msg('Duplicate Error : File is already exists');
        }

        // THERE IS NO ERROR
        if (!$this->errors){
            if(!is_dir($path)){ $this->make_directory(); }
            $moveFile = move_uploaded_file($file['tmp_name'], $uploadfile);
            if ($moveFile) {
                //$this->throw_error_msg('Successfully uploaded');
                return $returnData;
            } else {
                $this->throw_error_msg("Sorry try again");
                return false;
            }
        }

    }

    public function parse_xls($file){
        include 'excel_reader.php';
        $excel = new PhpExcelReader;
        $excel->read($file);
        if ($excel->sheets && count($excel->sheets) > 1 ) { return $excel->sheets; }
        else { return false; }
    }

    public function parse_xlsx2($file){
        include 'simplexlsx.class.php';
        $xlsx = new SimpleXLSX($file);
        //var_dump($xlsx);
        echo "<pre>"; print_r($xlsx->rows());echo "</pre>";
        echo "<pre>"; print_r($xlsx->rowsEx());echo "</pre>";
        echo "something";
        if ($xlsx->rows() && count($xlsx->rows()) > 1 ) { return $xlsx->rows(); }
        else { return false; }
    }

    public function parse_xlsx($file){
        include 'excel_lib/PHPExcel.php';
        $this->worksheetName = 'Final Copy';
        //  Read your Excel workbook
        $inputFileName = $file;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            //$objPHPExcel = $objReader->load($inputFileName);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                . '": ' . $e->getMessage());
        }

        // Loop fot multiple worksheets
        //echo $objPHPExcel->getSheetCount(),' worksheet',(($objPHPExcel->getSheetCount() == 1) ? '' : 's'),' loaded<br /><br />';
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
        }

        if ( $objPHPExcel->getSheetCount() > 1 ){
            $this->throw_error_msg('Please use single worksheet xlsx file');
            return false;
        }
        else { return $worksheet->toArray(); }
    }

    /**
     * insert data array into db
     * @param: array
     * @return: boolean
     */
    public function insert_data($dataArray, $tableName=BBITEMPTABLE){
        //require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;
        $this->tableName = $wpdb->prefix.$tableName;
        $this->insertArray = array(
            "account_name"  => $dataArray[0],
            "contact_name"  => $dataArray[1],
            "phone"         => $dataArray[2],
            "email"         => $dataArray[3],
            "street"        => $dataArray[4],
            "suite"         => $dataArray[5],
            "city"          => $dataArray[6],
            "state"         => $dataArray[7],
            "zip"           => $dataArray[8],
            "business_type" => $dataArray[9],
            "flooring"      => $dataArray[10],
            "canvas"        => $dataArray[11],
            "upholstery"    => $dataArray[12],
            "drops_ship"    => $dataArray[13]
        );
        $inserted = $wpdb->insert($this->tableName, $this->insertArray);
        if ($inserted){ return true; }
        else{ return false; }
    }

    public function delete_table($tableName){
        global $wpdb;
        $tableName = $wpdb->prefix.$tableName;
        $sql = "DROP TABLE IF EXISTS `$tableName`;";
        return $wpdb->query( $sql );
    }

    public function create_table($tableName){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tableName = $wpdb->prefix.$tableName;
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                  `account_name` varchar(256) NOT NULL,
                  `contact_name` varchar(256),
                  `phone` varchar(128),
                  `email` varchar(128),
                  `street` varchar(128),
                  `suite` varchar(128),
                  `city` varchar(128),
                  `state` varchar(128),
                  `zip` varchar(32),
                  `business_type` varchar(256),
                  `flooring` char(1),
                  `canvas` char(1),
                  `upholstery` char(1),
                  `drops_ship` char(1)
                ) $charset_collate;";
        $result = $wpdb->query( $sql );
        return $result;
    }

    public function empty_table($tableName=BBITEMPTABLE){
        global $wpdb;
        $this->tableName = $wpdb->prefix.$tableName;
        $delete = $wpdb->query("TRUNCATE TABLE $this->tableName");
        if ($delete){ return true; }
        else{ return false; }
    }

    public function duplicate_table($temp=BBITEMPTABLE, $main=BBITABLENAME){
        global $wpdb;
        $tempTable = $wpdb->prefix.$temp;
        $mainTable = $wpdb->prefix.$main;
        $duplicate = $wpdb->query("CREATE TABLE $mainTable AS SELECT * FROM $tempTable");
        if ($duplicate){ return true; }
        else{ return false; }
    }

    private function copyFile($source, $destination){
        $fileExists = file_exists($destination);
        if (!$fileExists) {
            if ( copy($source, $destination) ) { return true; }
            else { return false; }
        }
    }

    private function removeFile($file){
        $fileExists = file_exists($file);
        if ($fileExists) {
            if ( unlink($file) ) { return true; }
            else { return false; }
        }
    }

    /**
     * copy file form source to destination and
     * remove or delete form source directory
     * @param: filename
     * @return: boolean
     */
    public function move_file($file='tests.xlsx', $dirname='xml_success'){
        $sourcePath = $this->get_upload_drirectory_url(BBIUPLOADDIR);
        $destinationPath = $this->get_upload_drirectory_url($dirname);

        $srcFileUrl = $sourcePath.$file;
        $destFileUrl = $destinationPath.$file;

        // make directory if there is not
        if(!is_dir($destinationPath)){ $this->make_directory($dirname);}
        if ( $this->copyFile($srcFileUrl, $destFileUrl) ) { $this->removeFile($srcFileUrl); return true; }
        else{
            $this->throw_error_msg('File could not moved');
            return false;
        }

    }

    /**
     * insert parsed data array into db
     * @param: array
     * @return: boolean
     */
    public function insert_parsed_data($dataArray, $tableName=BBITEMPTABLE){
        $counter=0;
        foreach ( $dataArray as $array ){
            $formatedArray = array();
            if ($counter > 0 ){
                $formatedArray[] = (isset($array[0])) ? trim($array[0]) : '';
                $formatedArray[] = (isset($array[1])) ? trim($array[1]) : '';
                $formatedArray[] = (isset($array[2])) ? trim($array[2]) : '';
                $formatedArray[] = (isset($array[3])) ? trim($array[3]) : '';
                $formatedArray[] = (isset($array[4])) ? trim($array[4]) : '';
                $formatedArray[] = (isset($array[5])) ? trim($array[5]) : '';
                $formatedArray[] = (isset($array[6])) ? trim($array[6]) : '';
                $formatedArray[] = (isset($array[7])) ? trim($array[7]) : '';
                $formatedArray[] = (isset($array[8])) ? trim($array[8]) : '';
                $formatedArray[] = (isset($array[9])) ? trim($array[9]) : '';
                $formatedArray[] = (isset($array[10])) ? trim($array[10]) : '';
                $formatedArray[] = (isset($array[11])) ? trim($array[11]) : '';
                $formatedArray[] = (isset($array[12])) ? trim($array[12]) : '';
                $formatedArray[] = (isset($array[13])) ? trim($array[13]) : '';

                if (!$this->insert_data($formatedArray, $tableName)){
                    $this->throw_error_msg('Database Insertion Error');
                    return false;
                }
            }
            $counter++;
        }
        return true;
    }

    /**
     * @param string $tableName
     * @return int
     */
    public function rowCount($tableName=BBITEMPTABLE){
        global $wpdb;
        $this->tableName = $wpdb->prefix.$tableName;
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $this->tableName");
    }

    /**
     * main function to do all the things
     * @param: null
     * @return: boolean
     */
    public function xml_insert($xml_file){

        // upload the xml file to the upload folder
        // return array of file info ie. name, extension and upload path
        $upload = $this->xml_upload($xml_file);
        
        // pasrse the xml file into array depending on its extension
        // return array data
        if ($upload['extension'] == 'xlsx') {
            $dataArray = $this->parse_xlsx($upload['filePath']);
        } else {
            $dataArray = $this->parse_xls($upload['filePath']);
        }

        if ( $dataArray ) {
            // create temp table
            if ( $this->create_table(BBITEMPTABLE) ){
                // insert data into db temp table
                $this->insert_parsed_data($dataArray, BBITEMPTABLE);
            } else { $this->move_file($upload['fileName'], BBIERRORDIR); return false; }
        } else {
            $this->move_file($upload['fileName'], BBIERRORDIR);
            $this->throw_error_msg("Could not pursed data form file");
            return false;
        }
        //echo "<pre>"; print_r($dataArray); echo "</pre>"; die();
        // get number of temp table data rows
        $tempTableRows = $this->rowCount(BBITEMPTABLE);

        // check, is data fully inserted or not
        if ( $tempTableRows == (count($dataArray)-1) ) {
            // delete main table contents
            if ( $this->delete_table(BBITABLENAME) ){
                // copy temp table into main table
                if ( $this->duplicate_table(BBITEMPTABLE, BBITABLENAME) ) {
                    // delete temp table
                    if ( $this->delete_table(BBITEMPTABLE) ) {
                        // move file to the success directory
                        if ( $this->move_file($upload['fileName'], BBISUCCESSDIR)) { return true; }
                        else{ $this->throw_error_msg('Could not move file'); return false; }
                    } else{
                        // move file to the error directory
                        $this->move_file($upload['fileName'], BBIERRORDIR);
                        $this->throw_error_msg('Could not temp table');
                        return false;
                    }
                } else{
                    $this->move_file($upload['fileName'], BBIERRORDIR);
                    $this->throw_error_msg('Could not duplicate table');
                    return false;
                }
            } else{
                $this->move_file($upload['fileName'], BBIERRORDIR);
                $this->throw_error_msg('Could not empty table');
                return false;
            }
        }  else{
            $this->move_file($upload['fileName'], BBIERRORDIR);
            $this->throw_error_msg('Fetch some db insertion error');
            return false;
        }
        //return $formatedData;
        //echo "<pre>"; print_r($dataArray); echo "</pre>";
        echo 'tempTableRows : '.$tempTableRows.' == dataArray count: '.count($dataArray);
    }

}
$backend = new bbi_backend;