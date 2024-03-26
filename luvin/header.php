<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="https://fonts.googleapis.com/css?family=Arimo:400,700" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.png" rel="shortcut icon" type="image/png">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>
		

	</head>
	<body <?php body_class(); ?>>

		<?php putRevSlider( 'header' ); ?>

		
        <div class="top-menu sticky">
                <nav class="nav" role="navigation">
                    <?php meye_nav(); ?>
                </nav>
                <div id="nav-icon">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <div class="menu_label">MENU</div>
                <div id="mobile-menu">
                	<?php meye_nav(); ?>
                </div>
        </div>


		
		<!-- wrapper >
		<div class="wrapper">

			<!-- header >
			<header class="header clear" role="banner">

					<!-- logo >
					<div class="logo">
						<a href="<?php echo home_url(); ?>">						
							<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo" class="logo-img">
						</a>
					</div>
					<!-- /logo >

					<!-- nav >
					<nav class="nav" role="navigation">
						<?php meye_nav(); ?>
					</nav>
					<!-- /nav >

			</header>
			<!-- /header -->
