<?php

add_shortcode( 'luvinpeople', 'lovinpeople_func' );
function lovinpeople_func( $atts) { // New function parameter $content is added!
   	extract( shortcode_atts( array(
      'title' => 'something'
   	), $atts ));
   	$args = array('type' => 'luvinpeople', 'taxonomy' => 'category');
    $categories = get_categories($args); ?>
    <script>
		var ajax_path = '<?php echo get_template_directory_uri().'/get_info_ajax.php'; ?>';
	</script>
        <div class="container"><div class="section_title">luvin world</div></div>
	<?php foreach ($categories as $category){
	    $loop = new WP_Query( array( 'post_type' => 'luvinpeople', 'posts_per_page' => 100,'category_name' => $category->name ) );
	?>

    <?php
	echo '<div class="luvinpeople">';
	echo '<div class="container"><div class="section_title section_title-sub">'.$category->name .'</div></div>';
	echo '<div class="container">';
	echo '<div class="row">';
	while ( $loop->have_posts() ) : $loop->the_post();
    	$pmeta = get_post_meta( get_the_ID() );
		$fb = $pmeta['_facebook'][0];
		$insta = $pmeta['_instagram'][0];

		?>
		<div class="lp_column wmargin">
			<div class="lpeople-img">
            	<a class="lpeople_a" href="#" id="lp_<?php echo get_the_ID(); ?>" data-lang="<?php echo qtranxf_getLanguage(); ?>" data-fb="<?php echo $fb; ?>" data-insta="<?php echo $insta; ?>">
				<?php if ( has_post_thumbnail()) the_post_thumbnail('luvinpeople-thumb'); ?>
				<div class="luvinpeople_hover">
                	<div class="lp_name ">
                		<?php the_title(); ?>
                    </div>
                    <div class="stats_wrapper">
                    	<div class="stats pull-left">
                        <?php if($hasFB){ ?>
                        	<p><a href="https://www.facebook.com/<?php echo $fb; ?>" target="_blank"><span class="stats_network" data-conn="https://www.facebook.com/<?php echo $fb; ?>">facebook</span></a></p>
                            <p><?php echo $likes; ?></p>
                        <?php } ?>
                        </div>
                        <div class="stats pull-right">
                        <?php if($hasInst){ ?>
                        	<p><a href="https://www.instagram.com/<?php echo $insta; ?>" target="_blank"><span class="stats_network" data-conn="https://www.instagram.com/<?php echo $insta; ?>">instagram</span></a></p>
                            <p><?php echo $followers; ?></p>
                        <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                </a>
			</div>
		</div>
        <?php
	endwhile;
	echo '</div>';
	echo '</div>';
	echo '</div>';
	};

}

add_action( 'vc_before_init', 'meye_vc_lovinpeople' );
function meye_vc_lovinpeople() {
   vc_map( array(
      "name" => __( "Luvin People", "meye" ),
      "base" => "luvinpeople",
      "class" => "luvinpeople",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text", "meye" ),
            "param_name" => "title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for this section", "meye" )
         )
      )
   ) );
}








add_shortcode( 'ourplatforms', 'ourplatforms_func' );
function ourplatforms_func( $atts, $content = null) { // New function parameter $content is added!
   	extract( shortcode_atts( array(
      'title' => 'Title'
   	), $atts ) );

	$content = wpb_js_remove_wpautop($content, true);
	
	?>
	<div class="ourplatforms">
		<div class="container">
        	<div class="section_title"><?php echo $title; ?></div>
            <div class="ourplatforms_content">
            	<?php echo $content ?>
            </div>
        </div>
	</div>
    <?php
}

add_action( 'vc_before_init', 'meye_vc_ourplatforms' );
function meye_vc_ourplatforms() {
   vc_map( array(
      "name" => __( "Our Platforms", "meye" ),
      "base" => "ourplatforms",
      "class" => "ourplatforms",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Title", "meye" ),
            "param_name" => "title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for this section", "meye" )
         ),
		 array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content", "meye" ),
            "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
            "value" => __( "<p>Text block. Click edit button to change this text.</p>", "meye" ),
            "description" => __( "Enter your content.", "meye" )
         )
      )
   ) );
}





add_shortcode( 'our_projects', 'ourprojects_func' );
function ourprojects_func( $atts, $content = null) { // New function parameter $content is added!
   	extract( shortcode_atts( array(
      'title' => 'Title'
   	), $atts ) );
	
	$content = wpb_js_remove_wpautop($content, true);
	
	?>
    <div class="container">
		<div class="section_title"><?php echo $title; ?></div>            
    </div>
    <?php
	
	$loop = new WP_Query( array( 'post_type' => 'ourprojects', 'posts_per_page' => 4 ) );
	
	$loop_counter = 0;
	
	if(qtranxf_getLanguage() == 'en') {
		$_more = 'load more';
	}
	else{
		$_more = 'ver mais';
	}
	
	while ( $loop->have_posts() ) : $loop->the_post();	
		$pmeta = get_post_meta( get_the_ID() );
		$yt = $pmeta['_youtube'][0];	
		$desc = $pmeta['_desc'][0];
		
		
		if($yt == '' && !has_post_thumbnail()){
			//ALL EMPTY
			continue;
		}
		else{
			//HAS EMPTY
			if($loop_counter == 0){
				if($yt != ''){
					// IS YOUTUBE VIDEO
					?>
                    <div class="main-container">
                        <div class="video-container">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $yt; ?>?modestbranding=1&enablejsapi=1&iv_load_policy=3&disablekb=1&wmode=transparent&controls=0&playsinline=0&showinfo=0&rel=0&autoplay=0&loop=0" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
					<?php
				}
				else{
					// IS PHOTO
					?>	
					<div class="main-container">
                    	<img src="<?php the_post_thumbnail_url('full'); ?>" class="img-responsive" width="100%" />
					</div>
					<?php
				}								
			}
			else{
				if($loop_counter == 1){
					?>
                    <div class="container">
						<div class="proj-int">
                        <?php echo $content; ?>
                        </div>
                    <?php
				}								
				
				if($yt != ''){
					// IS YOUTUBE VIDEO
					?>
                    <div class="col-sm-4 single" >
                        <div class="video-container">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $yt; ?>?modestbranding=1&enablejsapi=1&iv_load_policy=3&disablekb=1&wmode=transparent&controls=0&playsinline=0&showinfo=0&rel=0&autoplay=0&loop=0" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="proj-title">
                            <?php the_title(); ?>
                        </div>
                        <div class="proj-desc">
                            <?php echo $desc; ?>
                        </div>
                    </div>
					<?php
				}
				else{
					// IS PHOTO
					?>	
                    <div class="col-sm-4 single" >
                        <div class="image-proj" style="background-image:url(<?php the_post_thumbnail_url('full'); ?>);">
                            <img src="<?php echo get_template_directory_uri().'/img/spacer.png'; ?>" class="vid-spacer" />                           
                        </div>
                        <div class="proj-title">
                            <?php the_title(); ?>
                        </div>
                        <div class="proj-desc">
                            <?php echo $desc; ?>
                        </div>
                    </div>
					<?php
				}
			}
			$loop_counter++;
		}
	endwhile;
	if($loop_counter >= 4){				
		
	}
	echo '<div id="ajax-posts"></div>';
	echo '<div class="clearfix"></div>';	
	echo '</div>'; // close container	
	echo '<div class="ver-mais"><a href="#">'.$_more.'</a></div>';
	if($loop_counter > 1) echo '</div>'; //close main-container
}

add_action( 'vc_before_init', 'meye_vc_ourprojects' );
function meye_vc_ourprojects() {
   vc_map( array(
      "name" => __( "Our Projects", "meye" ),
      "base" => "our_projects",
      "class" => "our_projects",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Title", "meye" ),
            "param_name" => "title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for section", "meye" )
         ),
		 array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content", "meye" ),
            "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
            "value" => __( "<p>Text block. Click edit button to change this text.</p>", "meye" ),
            "description" => __( "Enter your content.", "meye" )
         )
      )
   ) );
}




add_shortcode( 'ourclients', 'ourclients_func' );
function ourclients_func( $atts ) { // New function parameter $content is added!
   	extract( shortcode_atts( array(
      'title' => 'Title'
   	), $atts ) );

	$loop = new WP_Query( array( 'post_type' => 'ourclients', 'posts_per_page' => 9999 ) );
			
	?>
    <div class="container">
		<div class="section_title"><?php echo $title; ?></div>            
    </div>
	<div class="ourclients">
		<div class="container">
        	<div class="logos">
			<?php
            while ( $loop->have_posts() ) : $loop->the_post();						
                if ( has_post_thumbnail()){
					?>
                    <div class="client_wrapper">
                    	<span class="helper"></span><img src="<?php echo the_post_thumbnail_url('full');?>" />
                    </div>
                    <?php
				}
            endwhile;
            ?>
            </div>
        </div>
	</div>
    <?php
}

add_action( 'vc_before_init', 'meye_vc_ourclients' );
function meye_vc_ourclients() {
   vc_map( array(
      "name" => __( "Our Clients", "meye" ),
      "base" => "ourclients",
      "class" => "ourclients",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Title", "meye" ),
            "param_name" => "title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for this section", "meye" )
         )
      )
   ) );
}



add_shortcode( 'sectiontitle', 'sectiontitle_func' );
function sectiontitle_func( $atts ) { // New function parameter $content is added!
   	extract( shortcode_atts( array(
      'title' => 'Title'
   	), $atts ) );

	
	?>
    <div class="container">
		<div class="section_title"><?php echo $title; ?></div>            
    </div>
	<?php
}

add_action( 'vc_before_init', 'meye_vc_sectiontitle' );
function meye_vc_sectiontitle() {
   vc_map( array(
      "name" => __( "Section Title", "meye" ),
      "base" => "sectiontitle",
      "class" => "sectiontitle",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(
         array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Title", "meye" ),
            "param_name" => "title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for section", "meye" )
         )
      )
   ) );
}



add_shortcode( 'our_map', 'ourmap_func' );
function ourmap_func( $atts ) { // New function parameter $content is added!
   	extract( shortcode_atts( array(      
	  'lat' => 0,
	  'lon' => 0
   	), $atts ) );	

	?>
    	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5_x-2EssSHL1P2I1P23g-tFrCBbrM6Mc&language=pt_pt"></script>
        <script>
        function initialize() {
            var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
            var myOptions = {
                            zoom: 17,
							scrollwheel: false,
                            center: myLatlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        }
                    
            map = new google.maps.Map(document.getElementById("map"), myOptions);
        
            
			var marker = new google.maps.Marker({
                                position: myLatlng,
                                map: map,
                                icon: new google.maps.MarkerImage('<?php echo get_template_directory_uri().'/img/marker.png'; ?>',null,null,new google.maps.Point(12, 45),new google.maps.Size(25, 37)),
                                animation: google.maps.Animation.DROP,
                                title: "This is Luvin"
                            });
							
        }
		jQuery(document).ready(function(){
			initialize();
		});
		</script>
        <div class="section_title"><?php echo $title; ?></div>               
        <div id="map"></div>        
                    
    <?php
}

add_action( 'vc_before_init', 'meye_vc_ourmap' );
function meye_vc_ourmap() {
   vc_map( array(
      "name" => __( "Our Map", "meye" ),
      "base" => "our_map",
      "class" => "our_map",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(         
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Latitude", "meye" ),
            "param_name" => "lat",
            "value" => __( "", "meye" ),
            "description" => __( 'Google maps latitude', "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Longitude", "meye" ),
            "param_name" => "lon",
            "value" => __( "", "meye" ),
            "description" => __( 'Google maps longitude', "meye" )
         )
      )
   ) );
}





add_shortcode( 'our_contacts', 'ourcontacts_func' );
function ourcontacts_func( $atts, $content = null) { // New function parameter $content is added!
   	extract( shortcode_atts( array(     
	  'contacts_title' => '',
	  'email' => '',
	  'telephone' => '',
	  'facebook' => '',
	  'linkedin' => '',
	  'twitter' => ''
   	), $atts ) );	

	if(qtranxf_getLanguage() == 'en') {
		$_address = 'address';
		$_email = 'email';
		$_tel = 'tel';
	}
	else{
		$_address = 'morada';
		$_email = 'e-mail';
		$_tel = 'tel';
	}

	$content = str_replace('<p>','',$content);
	$content = str_replace('</p>','',$content);
	
	$output = '<div class="contact_list">';
	$output .= '<div class="section_title contacts_title">'.$contacts_title.'</div>';
    $output .= '<p style="margin-bottom:20px;"><strong><u>'.$_address.'</u></strong> '.$content.'</p>';
	$output .= '<p style="margin-bottom:20px;"><strong><u>'.$_email.'</u></strong> '.$email.'</p>';
	$output .= '<p style="margin-bottom:20px;"><strong><u>'.$_tel.'</u></strong> '.$telephone.'</p>';
	$output .= '<div class="sm_wrapper">';
	if($twitter != '')
		$output .= '<a href="'.$twitter.'" target="_blank" class="sm tw"></a>';
	if($linkedin != '')
		$output .= '<a href="'.$linkedin.'" target="_blank" class="sm in"></a>';
	if($facebook != '')
		$output .= '<a href="'.$facebook.'" target="_blank" class="sm fb"></a>';
	$output .= '<div class="clearfix"></div>';
	$output .= '</div>';	
	
	$output .= '</div>';
	
	
	return $output;
                    
}

add_action( 'vc_before_init', 'meye_vc_ourcontacts' );
function meye_vc_ourcontacts() {
   vc_map( array(
      "name" => __( "Our Contacts", "meye" ),
      "base" => "our_contacts",
      "class" => "our_contacts",
      "category" => __( "Luvin", "meye"),
      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
      "params" => array(         		
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Contacts Title", "meye" ),
            "param_name" => "contacts_title",
            "value" => __( "", "meye" ),
            "description" => __( "Title for contacts", "meye" )
         ),
		 array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Address", "meye" ),
            "param_name" => "content",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin Address", "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Email", "meye" ),
            "param_name" => "email",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin Email", "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Telephone", "meye" ),
            "param_name" => "telephone",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin Telephone", "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Facebook", "meye" ),
            "param_name" => "facebook",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin Facebook URL", "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "LinkedIn", "meye" ),
            "param_name" => "linkedin",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin LinkedIn URL", "meye" )
         ),
		 array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Twitter", "meye" ),
            "param_name" => "twitter",
            "value" => __( "", "meye" ),
            "description" => __( "Luvin Twitter URL", "meye" )
         )
      )
   ) );
}



?>