<!DOCTYPE html>

<head>

    <!-- Basic Page Needs
================================================== -->
    <title><?php echo get_bloginfo(); if(wp_title()){ echo " · ".wp_title(); } ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
================================================== -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css?v=<?php echo date('dmY'); ?>">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main-color.css" id="colors">

    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png" sizes="32x32" />

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/slick/slick.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/slick/slick-theme.css">

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

    <meta http-equiv="Expires" content="0"> 
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

</head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EBMS6Y46PT"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'G-EBMS6Y46PT');
</script>

<script src="https://player.vimeo.com/api/player.js"></script>

<body>

    <!-- <div class="alert alert-warning" style="
    z-index: 10000000;
"><p style="
    text-align: center;
    font-weight: bold;
">HOLA!, ESTÁMOS EN MANTENCIÓN, LA ACADEMIA REGRESARÁ A LA NORMALIDAD EN BREVE</p></div> -->

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Header Container
================================================== -->
        <header id="header-container" class="fixed fullwidth dashboard">

            <!-- Header -->
            <div id="header" class="not-sticky">
                <div class="container-fluid">

                    <!-- Left Side Content -->
                    <div class="left-side">

                        <!-- Logo -->
                        <div id="logo">
                            <a href="<?php echo home_url(); ?>" class="dashboard-logo"><img
                                    src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt=""></a>
                        </div>

                        <!-- Mobile Navigation -->
                        <div class="mmenu-trigger">
                            <button class="hamburger hamburger--collapse" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>

                        <!-- Main Navigation -->
                        <nav id="navigation" class="style-1" style="margin-left: 285px;">

                            <?php 
                            wp_nav_menu(array( 
                                'menu' => 'menu_principal',
                                'theme_location' => 'menu_principal',
                                'container_class' => 'responsive__menu',
                            )); 
					        ?>

                        </nav>

                        <div class="clearfix"></div>
                        <!-- Main Navigation / End -->

                    </div>
                    <!-- Left Side Content / End -->

                    <!-- Right Side Content / End -->
                    <div class="right-side">
                        <!-- Header Widget -->
                        <div class="header-widget">

                            <?php if(isset($_SESSION['user_fipi'])) { ?>

                            <!-- User Menu -->
                            <div class="user-menu">
                                <div class="user-name">Mi cuenta</div>
                                <ul>
                                    <li><a href="<?php echo home_url(); ?>/wp-login.php?action=lostpassword"><i
                                                class="sl sl-icon-pencil"></i> Cambiar clave</a></li>
                                    <li><a href="<?php echo home_url(); ?>/mis-cursos"><i class="fa fa-play-circle"></i>
                                            Mis cursos</a></li>
                                    <li><a href="<?php echo home_url(); ?>/mis-certificados"><i class="fa fa-play-circle"></i>
                                            Mis certificados</a></li>
                                    <li><a href="<?php echo home_url(); ?>/logout"><i class="sl sl-icon-power"></i>
                                            Cerrar sesión</a></li>
                                </ul>
                            </div>

                            <?php } else { echo "<a href='".home_url()."/login' class='iniciar-sesion-cta'>Iniciar Sesión...</a>"; } ?>

                        </div>
                        <!-- Header Widget / End -->
                    </div>
                    <!-- Right Side Content / End -->

                </div>
            </div>
            <!-- Header / End -->

        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->

        <?php 
        echo "- - - - -";
        print_r($_SESSION);
        echo "- - - - -";
        ?>