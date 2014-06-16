<?php use Nv2\Lib\Nv2\Debug\Debug; ?>
<div id="debug_console" class="debug_style">
    <h2><?php echo translate('debug', 'request_title'); ?></h2>
    <?php $requests = Debug::getServiceRequests(); ?>
    <?php if (count($requests) > 0) { ?>
        <table class="service_request_list">
            <tr>
                <th class="number"><?php echo translate('debug', 'table_head_number'); ?></th>
                <th class="time"><?php echo translate('debug', 'table_head_time'); ?></th>
                <th class="time">Code</th>
                <th class="url"><?php echo translate('debug', 'table_head_url'); ?></th>
            </tr>
            <?php foreach ($requests as $index => $r) { ?>
                <tr>
                    <td><?php echo ($index + 1); ?></td>
                    <td><?php echo $r['time']; ?> <?php echo translate('debug', 'table_millisecond_abbr'); ?></td>
                    <td><?php echo $r['code']; ?></td>
                    <td><a href="<?php echo $r['request']; ?>" target="_blank"><?php echo $r['request']; ?></a></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td><?php echo translate('debug', 'table_total'); ?></td>
                <td><?php echo Debug::getServiceRequestsTotalTime(); ?> <?php echo translate('debug', 'table_millisecond_abbr'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    <?php } ?>
</div>