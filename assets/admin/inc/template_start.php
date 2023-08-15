<?php
/**
 * template_start.php
 * Author: pixelcave
 * The first block of code used in every page of the template
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> 
<html class="no-js">
<!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title><?php echo $template['title'] ?></title>
        <meta name="description" content="<?php echo $template['description'] ?>">
        <meta name="author" content="<?php echo $template['author'] ?>">
        <meta name="robots" content="<?php echo $template['robots'] ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?php echo ASSETS_IMAGE; ?>favicon.png">
        <!-- <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="<?php //echo assest_url(); ?>img/icon180.png" sizes="180x180"> -->
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>fonts.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
        <?php if ($template['theme']) { ?><link id="theme-link" rel="stylesheet" href="<?php echo ASSETS_CSS; ?>themes/<?php echo $template['theme']; ?>.css"><?php } ?>

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>themes.css">

        <!--  load here custom stylesheet -->
        <link rel="stylesheet" href="<?php echo ASSETS_CSS; ?>style.css">
        <link href="http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/build/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) & Respond.js (enables responsive CSS code on browsers that don't support it, eg IE8) -->
        <script src="<?php echo ASSETS_JS; ?>vendor/modernizr-respond.min.js"></script>
    
    <body>