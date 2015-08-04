<div class="footer">
    <div class="footer_content">
        <p>
            IHM NV2 - version 0.14.0 "nougat" - &copy; 2012 - 2015 Canal TP
            <?php if (!isset($_GET['debug']) && config_get('debug', 'DebugLink', 'Active') == 'true') { ?>
                - <a href="<?php echo $request->getUrl() . config_get('debug', 'DebugLink', 'ParamString'); ?>">Debug</a>
            <?php } ?>
        </p>
    </div>
</div>