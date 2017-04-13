<style type="text/css">
    html {
        height: 100%
    }

    body {
        height: 100%;
        margin: 0;
        padding: 0
    }

    #map_canvas {
        height: 100%
    }
</style>

<?php 

	$installer = new Installer();
	if(isset($_POST['btn_search'])){
        $installer_name = $_POST['installer_name'];
        $zip = $_POST['zip'];
        $state = $_POST['state'];
        $products = $_POST['products'];
        $Industries = $_POST['Industries'];
		$data = $installer->getInstallerForFrontend($installer_name,$zip,$state,$products, $Industries);
	}else{
		$data = $installer->getInstallerForFrontend();
	}
	//echo '<pre>';print_r($data);echo '</pre>';
?>

<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB1tbIAqN0XqcgTR1-FxYoVTVq6Is6lD98&sensor=false">
</script>
<script type="text/javascript">
    var map = null;
    /*var locations = [
        {name:'loan 1', lattitude:33.890542, longitude:151.274856, address:'address 1'},
        {name:'loan 2', lattitude:32.923036, longitude:151.259052, address:'address 2'},
        {name:'loan 3', lattitude:38.028249, longitude:151.157507, address:'address 3'},
        {name:'loan 4', lattitude:36.80010128657071, longitude:151.28747820854187, address:'address 4'},
        {name:'loan Test Address Fifth', lattitude:36.80010128657071, longitude:158.28747820854187, address:'address fifth'},
        {name:'loan 5', lattitude:34.950198, longitude:151.259302, address:'address 5'}
    ];*/
	var locations = JSON.stringify(<?php echo json_encode($data);?>);
	//alert(locations);
	locations = JSON.parse(locations);
	//alert(JSON.stringify(locations));

    function initialize(center_lat,center_long) {

        var myOptions = {
            center: new google.maps.LatLng(center_lat,center_long),//33.890542, 151.274856
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("default"),
            myOptions);

        setMarkers(map, locations)

    }



    function customSetCenter(center_lat,center_long,message){
        latlngset = new google.maps.LatLng(center_lat,center_long);
        var infoWindow = new google.maps.InfoWindow({
            content: message
        });

        var marker = new google.maps.Marker({
            map: map, title: "Test", position: latlngset, infoWindow: infoWindow
        });
        //marker.setAnimation(google.maps.Animation.BOUNCE);
        map.setCenter(marker.getPosition())
        //marker.showInfoWindow();
        infoWindow.open(map, marker);
    }


    function setMarkers(map, locations) {

        var marker, i

        for (i = 0; i < locations.length; i++) {

            var loan = locations[i].installer_name;
            var lat = locations[i].latitude;
            var long = locations[i].longitude;
			var address = locations[i].city!=""&&locations[i].city!=null?locations[i].city+", ":"";
			address += locations[i].state!=""&&locations[i].state!=null?locations[i].state+", ":"";
			address += locations[i].zip!=""&&locations[i].zip!=null?locations[i].zip+", ":"";
			address += locations[i].country!=""&&locations[i].country!=null?locations[i].country+", ":"";
			var add = address;
			//alert(loan+"=="+lat+"=="+long+"=="+add);
            latlngset = new google.maps.LatLng(lat, long);
            var marker = new google.maps.Marker({
                map: map, title: loan, position: latlngset
            });


            map.setCenter(marker.getPosition())


            var content = "Loan Number: " + loan + '</h3>' + "Address: " + add

            var infowindow = new google.maps.InfoWindow()

            google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                return function () {
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                };
            })(marker, content, infowindow));

        }
    }
</script>

        <div class="row no-margin">
                <div class="col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="mediam-title">Installers</p>
                            <div class="map-areya" id="default">
                                <!--iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d20892628.353704922!2d-102.2831105710212!3d50.26635582273738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1470390798843" width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></iframe-->
                            </div>
                        </div>
                        <div class="col-sm-12">
                        	<a href="<?php echo esc_url(site_url('/become-an-installer/')) ?>" type="button" class="btn common-btn">Become an installer</a>
                            <a href="<?php echo esc_url(site_url('/recommend-an-installer/')) ?>" type="button" class="btn common-btn">Recommend an Installer</a>
                            <a href="<?php echo esc_url(site_url('/find-an-installer/')) ?>" type="button" class="btn common-btn">Help me Find an Installer</a>
                        </div>
                    </div> <!-- row end-->
                </div>

                <div class="col-md-5 col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="mediam-title">find an installer near me</p>
                            <div class="row border-tlb">
                                <div class="padding_right_100">
                                    <form id="abc" action="" method="post">
                                        <div class="col-sm-6 form-group">
                                            <label for="zip">Zip Code</label>
                                            <input class="form-control cstm-form-ctrl" name="zip" id="zip" type="text">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="State">State</label>
                                            <input class="form-control cstm-form-ctrl" name="state" id="State" type="text">
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label for="sinstaller">Or Search by Installer Name</label>
                                            <input class="form-control cstm-form-ctrl" name="installer_name" id="sinstaller" type="text">
                                        </div>
										
										<div class="col-sm-12 form-group">
                                            <label>Search by Product</label>
                                            <div class="form-inline">
											<?php 
											$products = isset($_POST['products'])?$_POST['products']:'';
											$args = array(
												'post_type' => 'product',
												'posts_per_page' => -1,
												'post_status'=>'publish'
											);
											$the_query = new WP_Query($args);
											$posts = $the_query->get_posts();
											//echo '<pre>';print_r($posts);echo '</pre>';
											foreach($posts as $value){
												$checked = "";
												if($products!="") {
													$current_value = $value->ID;
													if(in_array($current_value,$products)){
														$checked = "checked";
													}
												}
											?>
                                                <div style="display:inline" class="">
                                                    <label ><input type="checkbox" <?php echo $checked;?> name="products[]" value="<?php echo $value->ID; ?>" />
                                                    <?php echo $value->post_title; ?></label>
                                                </div>
											<?php } ?>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-12 form-group">
                                            <label>Search by Industry</label>
                                            <div class="form-inline">
                                            <?php 
                                            $Industries = isset($_POST['Industries'])?$_POST['Industries']:'';
                                            $all_industries = $installer->getIndustries();
                                            foreach($all_industries as $industryID => $value){
                                                $checked = "";
                                                if($Industries!="") {
                                                    $current_value = $industryID;
                                                    if(in_array($current_value,$Industries)){
                                                        $checked = "checked";
                                                    }
                                                }
                                            ?>
                                                <div style="display:inline" class="">
                                                    <label ><input type="checkbox" <?php echo $checked;?> name="Industries[]" value="<?php echo $industryID; ?>" />
                                                    <?php echo $value; ?></label>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_search" class="btn common-btn extra_bold_text">search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 margin_top_35 no_padding_right">
                            <ul class="installer-direction-menu" id="installerAddress"  style="height: 540px; overflow-y: scroll">
                                <li>
                                    <div class="company-name">
                                        <h2>Company name</h2>
                                        <p>Installer Address, State, Zip</p>
                                        <p>Phone   Website</p>
                                    </div>
                                    <div class="company-direction"> 
                                        <h2>Directions</h2>
                                        <p>MARINE, RV</p>
                                        <p>UPHOLSTERY, FLOORING</p>
                                    </div>
                                </li>

                                <li>
                                    <div class="company-name">
                                        <h2>Company name</h2>
                                        <p>Installer Address, State, Zip</p>
                                        <p>Phone   Website</p>
                                    </div>
                                    <div class="company-direction">
                                        <h2>Directions</h2>
                                        <p>MARINE, RV</p>
                                        <p>UPHOLSTERY, FLOORING</p>
                                    </div>
                                </li>

                                <li>
                                    <div class="company-name">
                                        <h2>Company name</h2>
                                        <p>Installer Address, State, Zip</p>
                                        <p>Phone   Website</p>
                                    </div>
                                    <div class="company-direction">
                                        <h2>Directions</h2>
                                        <p>MARINE, RV</p>
                                        <p>UPHOLSTERY, FLOORING</p>
                                    </div>
                                </li>

                            </ul>

                        </div>

                    </div> <!-- row end-->
                </div>
        </div>

        <div class="clear"></div>

            
        
    <script>
    
    var dynamic = '';
    for(var i=0; i<locations.length; i++){
		if(i==(locations.length-1)){
			//alert(locations[i].latitude+"=="+locations[i].longitude);
			initialize(locations[i].latitude, locations[i].longitude);
		}
		
		var address = locations[i].city!=""&&locations[i].city!=null?locations[i].city+", ":"";
			address += locations[i].state!=""&&locations[i].state!=null?locations[i].state+", ":"";
			address += locations[i].zip!=""&&locations[i].zip!=null?locations[i].zip+", ":"";
			address += locations[i].country!=""&&locations[i].country!=null?locations[i].country+", ":"";
		
		var productName = locations[i].products_name!=null&&locations[i].products_name!='null'&&locations[i].products_name!=''?'Products: '+locations[i].products_name:'Products : N/A'; 
		productName += '<br/>';
        
		var industryName = locations[i].industries_name!=null&&locations[i].industries_name!='null'&&locations[i].industries_name!=''?'Industries: '+locations[i].industries_name:'Industries : N/A'; 
		industryName += '<br/>';
        var data = address;
		var dataPhone = locations[i].phone!=null&&locations[i].phone!='null'&&locations[i].phone!=''?'<a href="tel:'+locations[i].phone+'">'+locations[i].phone+'</a>':'Phone: N/A';
			dataPhone += ' &nbsp;';
			dataPhone += locations[i].website!=null&&locations[i].website!='null'&&locations[i].website!=''?'<a target="_blank" href="'+locations[i].website+'">'+locations[i].website+'</a>':'Website: N/A';
        //dynamic += '<span style="display:block" onclick=\'customSetCenter("'+locations[i].latitude+'","'+locations[i].longitude+'","'+data+'")\'>'+data+'</span>';
		dynamic+='<li class="common_location" onclick=\'customSetCenter("'+locations[i].latitude+'","'+locations[i].longitude+'","'+data+'")\'>';
			dynamic+='<div class="company-name">'
			dynamic+='<h2>'+locations[i].installer_name+'</h2>';
			dynamic+='<p>'+data+'</p>';
			dynamic+='<p>'+dataPhone+'</p>';
			dynamic+='</div>';
			dynamic+='<div class="company-direction">';
            dynamic+='<h2>Directions</h2>';
			dynamic+='<p>'+industryName+'</p>';
			dynamic+='<p>'+productName+'</p>';
			dynamic+='</div>';
        dynamic+='</li>';

    }
    //alert(dynamic);
    document.getElementById('installerAddress').innerHTML = dynamic;
    var classLocation = document.getElementsByClassName('common_location');
    var range = classLocation.length>=2?2:classLocation.length;

    for(var i=0; i<range; i++){
    	classLocation[i].onclick();
    	console.log('test');
    }
</script>