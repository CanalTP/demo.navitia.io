<div class="footer">
    <div class="footer_content">
        <p>
            IHM NV2 - version 0.12 "limousin" - &copy; 2012 - 2014 Canal TP
            <?php if (!isset($_GET['debug']) && config_get('debug', 'DebugLink', 'Active') == 'true') { ?>
                - <a href="<?php echo $this->Request->getUrl() . config_get('debug', 'DebugLink', 'ParamString'); ?>">Debug</a>
            <?php } ?>
        </p>
    </div>
</div>