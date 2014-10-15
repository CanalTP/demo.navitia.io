<div class="footer">
    <div class="footer_content">
        <p>
            IHM NV2 - version 0.13.0 "macaron" - &copy; 2012 - 2014 Canal TP
            <?php if (!isset($_GET['debug']) && config_get('debug', 'DebugLink', 'Active') == 'true') { ?>
                - <a href="<?php echo $request->getUrl() . config_get('debug', 'DebugLink', 'ParamString'); ?>">Debug</a>
            <?php } ?>
        </p>
    </div>
</div>