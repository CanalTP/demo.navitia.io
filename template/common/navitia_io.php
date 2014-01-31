<script type="text/javascript">
<!--
$(document).ready(function() {
    $.ajax({
        url: '<?php echo url_link('parentsite/get_bar'); ?>',
        success: function(data) {
            $('#navitia_io_bar').html(data).addClass('container');
        }
    });
});
//-->
</script>
<script type="text/javascript" src="<?php js_link('navitia_io.js'); ?>"></script>