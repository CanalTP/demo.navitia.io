<div class="footer">
    <div class="footer_content">
        <p>
            IHM NV2 - version "kouglof" (03/07/2013) - &copy; 2012 - 2013 Canal TP
            <?php if (!isset($_GET['debug']) && config_get('debug', 'DebugLink', 'Active') == 'true') { ?>
                - <a href="<?php echo $this->Request->getUrl() . config_get('debug', 'DebugLink', 'ParamString'); ?>">Debug</a>
            <?php } ?>
        </p>
    </div>
</div>