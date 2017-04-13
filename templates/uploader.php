<?php
    // ENSURE ALL ARRY ELEMENTS ARE UNIQUE AND NOT AN EMPTY ARRAY
    if(isset($_POST['btn_submit'])){
        if ( isset($_FILES['xml_file']) ){
            //echo $frontend->htmlForm();
            $xml_file = $_FILES["xml_file"];
            $upload = $backend->xml_insert($xml_file);
            if ($upload) { $backend->throw_success_msg('success! '); }
            else{$backend->throw_error_msg('Error! Please try again'); }

        }
        //echo $xml_file; die();

    } else {
        //$data = $backend->delete_table(BBITEMPTABLE);
        //$data = $backend->rowCount();
        //var_dump($data);
        //echo "<pre>"; print_r($data); echo "<pre>";
    }
?>
<!--link rel="stylesheet" href="<?php //echo plugin_dir_url( __FILE__ ); ?>css/main.css" type="text/css"-->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

<style> 
    body {overflow-x: hidden; padding-right: 15px;} 
    div.section-credit-confusing div.container{max-width:100%; }
    <?php if ( $homeStyleNo == 2 ) { echo ".hidefromadmin{display: none; }"; } ?>
</style>

<?php echo $frontend->htmlForm(); ?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>