<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="container">
	<div id="header" role="banner">
		<h1><span><a href="<?php echo esc_url( home_url() );  ?>" rel="home"><?php bloginfo('name'); ?></a></span></h1>
		<h2><?php bloginfo('description'); ?></h2>
	</div>
	<div id="content-wrapper">
<!-- goto ^(archive image index page search single ...).php:/^div#content -->
