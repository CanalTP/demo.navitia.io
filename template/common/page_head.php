<head>
    <title>IHM Navitia 2</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="http://navitia.io/_static/bootswatch-3.0.0/united/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="http://navitia.io/_static/navitia-io-styles.css" />
    <link rel="stylesheet" type="text/css" href="<?php css_link('bootstrap-responsive.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php css_link('reset.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php css_link('screen.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php css_link('navitia_io.css'); ?>" />
    <?php if (isset($_GET['debug']) && $_GET['debug'] == 1) { ?>
        <link rel="stylesheet" type="text/css" href="<?php css_link('debug.css'); ?>" />
    <?php } ?>
    <script type="text/javascript" src="<?php js_link('jquery-1.7.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php js_link('Base.js'); ?>"></script>
    <script type="text/javascript" src="<?php js_link('autocompletion.js'); ?>"></script>
    <!--<script type="text/javascript" src="http://navitia.io/_static/bootstrap-3.0.0/js/bootstrap.min.js"></script>-->
    <?php include(TEMPLATE_DIR . '/common/analytics.php'); ?>
    <link rel="icon" type="image/png" href="<?php img_src('/img/favicon.png'); ?>" />
</head>