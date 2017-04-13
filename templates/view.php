<form class="hide" action="" id="deleteForm" method="post">
    <input type="text" name="delete_id" id="delete_id"/>
</form>

<form class="hide" action="" id="bulk_action_form" method="post">

</form>
<?php echo $view_page_message; ?>

<style>
    .tab_content {
        display: none;
    }

    .active {
        display: block !important;
    }
</style>

<ul>
    <li class="tab_head" data-map="all_info">All Installer (<span id="count_all_info"></span>)</li>
    <li class="tab_head" data-map="active_info">Active (<span id="count_active_info"></span>)</li>
    <li class="tab_head" data-map="inactive_info">Inactive (<span id="count_inactive_info"></span>)</li>
</ul>

<div class="form-group">
    <div class="col-sm-4">
        <select class="form-control" name="bulk_action" id="bulk_action">

        </select>
    </div>
    <div class="col-sm-4">
        <button type="button" id="btn_apply_bulk_action" class="btn btn-success btn-sm">Apply</button>
    </div>
</div>

<div class="col-sm-12">
    <div class="tab_content active" id="all_info">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" name="check_content" class="check_content"/></th>
                    <th>Installer Name</th>
                    <th>Industry</th>
                    <th>Product</th>
                    <th>Street Line 1</th>
                    <th>Street Line 2</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Country</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th class="hide">phone</th>
                    <th class="hide">website</th>
                    <th class="hide">industries_id</th>
                    <th class="hide">products_id</th>
                </tr>
                </thead>
                <tbody id="tbl_all_info">
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab_content" id="active_info">
        <div class="table-responsive">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" name="check_content" class="check_content"/></th>
                    <th>Installer Name</th>
                    <th>Industry</th>
                    <th>Product</th>
                    <th>Street Line 1</th>
                    <th>Street Line 2</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Country</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th class="hide">phone</th>
                    <th class="hide">website</th>
                    <th class="hide">industries_id</th>
                    <th class="hide">products_id</th>
                </tr>
                </thead>
                <tbody id="tbl_active_info">

                </tbody>
            </table>
        </div>
    </div>

    <div class="tab_content" id="inactive_info">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" name="check_content" class="check_content"/></th>
                    <th>Installer Name</th>
                    <th>Industry</th>
                    <th>Product</th>
                    <th>Street Line 1</th>
                    <th>Street Line 2</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Country</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th class="hide">phone</th>
                    <th class="hide">website</th>
                    <th class="hide">industries_id</th>
                    <th class="hide">products_id</th>
                </tr>
                </thead>
                <tbody id="tbl_inactive_info">

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    //alert();
</script>