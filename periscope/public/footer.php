<script>
    $(document).ready( function () {
        //background selector
        <?php
            require_once("../lib/cfg.php");
            require_once("../lib/database.php");
            shuffle($backgrounds);
            $bgpath = "../images/bg/" . $backgrounds[0];
            echo "$('body').css('background-image', 'url({$bgpath})');";
        ?>

    });

</script>
<script>
    $(function() {
        $( "#from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
            $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>
</body>
</html>
<?php $db->disconnect();?>


