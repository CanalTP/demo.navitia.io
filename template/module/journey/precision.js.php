<script type="text/javascript">
<!--

$(document).ready(function() {
    $('.point_list input[type="radio"]').bind('change', function() {
        $(this).parent().parent().find('li').removeClass('choosen');
        $(this).parent().addClass('choosen');
    });
});

//-->
</script>