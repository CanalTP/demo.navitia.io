<?php use Nv2\Lib\Nv2\Debug\Debug; ?>
<!DOCTYPE html>
<html>
<?php include(TEMPLATE_DIR . '/common/page_head.php'); ?>
<body>
    <div id="navitia_io_bar"></div>
    <div class="page">
        <?php include(TEMPLATE_DIR . '/common/main_menu.php'); ?>
        <div class="main_content">
            <?php echo $module_result; ?>
        </div>
        <?php include(TEMPLATE_DIR . '/common/footer.php'); ?>
    </div>
    <?php if ($this->Request->getEnvironment() == 'prod') { ?>
        <?php include(TEMPLATE_DIR . '/common/navitia_io.php'); ?>
    <?php } ?>
    <?php if ($this->Request->getDebugStatus() == Debug::STATUS_WEB) { ?>
        <?php include(TEMPLATE_DIR . '/common/debug_console.php'); ?>
    <?php } ?>
</body>
</html>