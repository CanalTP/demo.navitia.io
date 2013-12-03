<div class="main_nav">
    <div class="main_nav_container">
        <a class="logo" href="<?php url_link('/'); ?>"><span><?php echo translate('global.main_menu', 'home_link_label') ?></span></a>
        <a id="phone-menu-icon" class="menu-icon" href="javascript:void(0);"><span>Menu</span></a>
        <div class="clear visible-phone"></div>
        <?php if ($this->Request->getModuleName() != 'home') { ?>
            <nav>
                <ul id="phone-menu" class="large-menu">
                    <li<?php if ($this->Request->getModuleName() == 'journey') echo ' class="current"'; ?>>
                        <a href="<?php url_link('journey/search'); ?>"><?php echo translate('global.main_menu', 'journey_link_label') ?></a>
                    </li>
                    <?php /*
                    <li<?php if ($this->Request->getModuleName() == 'proximity') echo ' class="current"'; ?>>
                        <a href="<?php url_link('proximity/search'); ?>"><?php echo translate('global.main_menu', 'proximity_link_label') ?></a>
                    </li>
                    */ ?>
                    <li<?php if ($this->Request->getModuleName() == 'schedule') echo ' class="current"'; ?>>
                        <a href="<?php url_link('schedule/search'); ?>"><?php echo translate('global.main_menu', 'schedule_link_label') ?></a>
                    </li>
                    <?php /*
                    <li<?php if ($this->Request->getModuleName() == 'meeting') echo ' class="current"'; ?>>
                        <a href="<?php url_link('meeting/search'); ?>"><?php echo translate('global.main_menu', 'meeting_link_label') ?></a>
                    </li>
                    */ ?>
                </ul>
            </nav>
        <?php } ?>
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
<!--
    $(document).ready(function() {
        $('#phone-menu-icon').bind('click', function() {
            $('#phone-menu').slideToggle('fast');
        });
    });
//-->
</script>