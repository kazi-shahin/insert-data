<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<style>
    body {
        overflow-x: hidden;
    }
    .margin-right-12 {
        margin-right: 12px;
    }
    .selected_date{
        background: greenyellow;
    }
    .single-date:hover{
        background: greenyellow;
    }
</style>
<?php
$installer = new Installer();

$view_page_message = "";
if (post_data('delete_id') != '') {
    $installer = new Installer();
    $confirmation = $installer->deleteInstaller(post_data('delete_id'));
    if ($confirmation) {
        $view_page_message = '<div class="alert alert-success fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Installer successfully deleted</div>';
    } else {
        $view_page_message = '<div class="alert alert-danger fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Installer deletion failed</div>';
    }
}

if(post_data('bulk_action') != ''){
    $count = 0;
    $tag = '';
    $bulk_installer = new Installer();
    $ids = post_data('installer_ids');
    $status = post_data('bulk_action');
    if($status=='delete'){
        foreach($ids as $single){
            if($installer->deleteInstaller(post_data('delete_id'))){
                $count++;
            }
        }
        $tag = " deleted";
    }else if($status=='activate'){
        foreach($ids as $single){
            $data=array();
            $data['status']=1;
            if($bulk_installer->updateInstaller($data,$single)){
                $count++;
            }
        }
        $tag = " activated";
    }else if($status=='inactivate'){
        foreach($ids as $single){
            $data=array();
            $data['status']=0;
            if($bulk_installer->updateInstaller($data,$single)){
                $count++;
            }
        }
        $tag = " deactivated";
    }

    $view_page_message = '<div class="alert alert-success fade in" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>'.$count.' Installer successfully '.$tag.'</div>';
}
?>

<script>
    //console.log(dataActive.length);
</script>
<?php include 'css-configure.php'; ?>


<section style="background:#efefe9;width:99%">
    <div class="container">
        <div class="row">
            <div class="board margin-right-12">
                <!-- <h2>Welcome to IGHALO!<sup>™</sup></h2>-->
                <?php //print_r($installer->getInstaller());?>
                <div class="board-inner">
                    <ul class="nav nav-tabs" id="myTab">
                        <div class="liner"></div>
                        <li class="tab_add_modify <?php if(post_data('delete_id')=='' && post_data('bulk_action')==''){echo 'active';}?>">
                            <a href="#tab_add_modify" data-toggle="tab" title="Add/Modify Installer">
                            <span class="round-tabs one">
                                <i class="glyphicon glyphicon-plus"></i>
                            </span>
                            </a>
                        </li>

                        <li class="tab_view <?php if(post_data('delete_id')!='' || post_data('bulk_action')!=''){echo 'active';}?>">
                            <a href="#tab_view" data-toggle="tab" title="View Installers">
                                <span class="round-tabs two">
                                <i class="glyphicon glyphicon-th-list"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    <div class="tab-pane fade <?php if(post_data('delete_id')=='' && post_data('bulk_action')==''){echo 'in active';}?>" id="tab_add_modify">
                        <div class="col-sm-8">
                            <?php include 'create.php'; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade <?php if(post_data('delete_id')!=''||post_data('bulk_action')!=''){echo 'in active';}?>" id="tab_view">
                        <div class="col-sm-12">
                            <?php include 'view.php';?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
</section>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    var dataInsatller = JSON.parse(JSON.stringify(<?php echo json_encode($installer->getInstaller()); ?>));

    jQuery(document).ready(function ($) {
        var trActive = '';
        var trAll = '';
        var trInactive = '';
        var countAll = 0;
        var countActive = 0;
        var countInactive = 0;


        $.each(dataInsatller,function(index,item){
            var commonTr = '';
            commonTr +='<tr>';
            commonTr += '<td><input type="checkbox" value="'+item.installer_id+'" name="checked_row" class="checked_row" /> </td>';
            commonTr += '<td class=""><span class="installer_name">'+item.installer_name+'</span>';
            commonTr += '<br/>';
            commonTr += '<a style="cursor: pointer" data-id="'+item.installer_id+'" class="edit-icon">Edit</a> |';
            commonTr += '<a style="cursor: pointer" data-id="'+item.installer_id+'" class="delete-icon">Delete</a>';
            commonTr += '</td>';
            commonTr += '<td class="industry_name">'+item.industries_name+'</td>';
            commonTr += '<td class="product_name">'+item.products_name+'</td>';
            commonTr += '<td class="street_line_1">'+item.street_line_1+'</td>';
            commonTr += '<td class="street_line_2">'+item.street_line_2+'</td>';
            commonTr += '<td class="city">'+item.city+'</td>';
            commonTr += '<td class="state">'+item.state+'</td>';
            commonTr += '<td class="zip">'+item.zip+'</td>';
            commonTr += '<td class="country">'+item.country+'</td>';
            commonTr += '<td class="latitude">'+item.latitude+'</td>';
            commonTr += '<td class="longitude">'+item.longitude+'</td>';
            commonTr += '<td class="phone hide">'+item.phone+'</td>';
            commonTr += '<td class="website hide">'+item.website+'</td>';
            commonTr += '<td class="products hide">'+item.products_id+'</td>';
            commonTr += '<td class="industries hide">'+item.industries_id+'</td>';
            commonTr += '</tr>';
            if(item.status==1||item.status=="1"){
                trActive += commonTr;
                countActive++;
            }else if(item.status==0||item.status=="0"){
                trInactive += commonTr;
                countInactive++;
            }
            trAll += commonTr;
            countAll++;
        });
        $("#tbl_all_info").html(trAll);$("#count_all_info").html(countAll);
        $("#tbl_active_info").html(trActive);$("#count_active_info").html(countActive);
        $("#tbl_inactive_info").html(trInactive);$("#count_inactive_info").html(countInactive);
        //alert(trAll);
        $('table').DataTable({
            "pageLength": 40
        });

        $('.nav-tabs a[title]').tooltip();
        //$('.info[title]').tooltip();
        $('.info').popover({
            'placement':'bottom',
            'content':'Leave latitude & longitude blank to get automatically by address'
        });

        $('.nav-tabs').click(function () {
            reset();
        });

        function edit() {
            $('.alert').remove();
            $('.nav-tabs>li').removeClass('active');
            $('.tab_add_modify').addClass('active');
            $('.tab-pane').removeClass('active');
            $('.tab-pane').removeClass('in');
            $('#tab_add_modify').addClass('active');
            $('#tab_add_modify').addClass('in');
            $('#btn_submit').text('MODIFY');
        }

        $('.tab_add_modify').click(function () {
            $('#btn_submit').text('REGISTER');
        });

        $(document).on('click', '.edit-icon', function () {
            edit();
            var obj = $(this);

            function fetch(className) {
                var value = obj.parents().children('.' + className).text();
                return value;
            }

            var id = $(this).attr("data-id");
            $('#installer_id').val(id);
            $('#installer_name').val(fetch('installer_name'));
            $('#phone').val(fetch('phone'));
            $('#website').val(fetch('website'));
            $('#street_line_1').val(fetch('street_line_1'));
            $('#street_line_2').val(fetch('street_line_2'));
            $('#city').val(fetch('city'));
            $('#state').val(fetch('state'));
            $('#zip').val(fetch('zip'));
            $('#country').val(fetch('country'));
            $('#latitude').val(fetch('latitude'));
            $('#longitude').val(fetch('longitude'));
			$('input[type="text"]').each(function(){
				var value = $(this).val()=='null'?'':$(this).val();
				$(this).val(value);
			})
            var products = fetch('products')+",";
            //alert(products);
            $('input[name="products[]"]').each(function () {
                var value = $(this).val() + ",";
                if (products.indexOf(value) >= 0) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });

            var industries = fetch('industries')+",";
            //alert(products);
            $('input[name="industry[]"]').each(function () {
                var value = $(this).val() + ",";
                if (industries.indexOf(value) >= 0) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });
        });


        $(document).on('click', '#reset', function () {
            reset();
        });

        function reset() {
            $('#btn_submit').text('REGISTER');
            $('input[type="text"]').val('');
            $('input[type="hidden"]').val('');
            $('input[type="checkbox"]').prop("checked", false);
        }


        //Delete Section
        $(document).on('click', '.delete-icon', function () {
            var id = $(this).attr("data-id");
            var confirmation = confirm("Are you sure??");
            if (confirmation === true) {
                $('#delete_id').val(id)
                $('#deleteForm').submit();
            }
        });


        var optionsAll = '<option value="">Bulk Action</option><option value="activate">Activate</option><option value="inactivate">Inactivate</option><option value="delete">Delete</option>';
        var optionsActive = '<option value="">Bulk Action</option><option value="inactivate">Inactivate</option><option value="delete">Delete</option>';
        var optionsInactive = '<option value="">Bulk Action</option><option value="activate">Activate</option><option value="delete">Delete</option>';

        $("select[name='bulk_action']").html(optionsAll);

        $(document).on('click','.tab_head',function(){
            var id = $(this).attr("data-map");
            $('.tab_content').removeClass('active');
            $('.check_content').removeProp('checked');
            $('.checked_row').removeProp('checked');
            $('#'+id).addClass('active');
            if(id=="all_info"){
                $("select[name='bulk_action']").html(optionsAll);
            }else if(id=="active_info"){
                $("select[name='bulk_action']").html(optionsActive);
            }else if(id=="inactive_info"){
                $("select[name='bulk_action']").html(optionsInactive);
            }
        });

        $(document).on('click','.check_content',function(){
            $('.check_content').not(this).removeProp('checked');
            var checked = $(this).prop('checked');
            $('.checked_row').removeProp('checked');
            if(checked===true){
                $(this).parents('table').children().find('.checked_row').prop("checked",true);
            }else{
                $(this).parents('table').children().find('.checked_row').removeProp("checked");
            }
        });


        $(document).on('click','#btn_apply_bulk_action',function(){
            var action = $('select[name="bulk_action"]').val();

            var checkedCount = $('.checked_row:checked');
            //alert(action);
            if(action==""||checkedCount.length==0){
                return false;
            }
            var values = "";
            checkedCount.each(function(){
                values += '<input type="hidden" name="installer_ids[]" value="'+$(this).val()+'" />';
            });
            values += '<input type="hidden" name="bulk_action" value="'+action+'" />';
            $('#bulk_action_form').html(values);
            $('#bulk_action_form').submit();
            //alert(values);
        });
    });
</script>