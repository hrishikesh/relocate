$(document).ready(function () {

    $('.date-picker').datepicker({
        dateFormat:'dd-mm-yy'
    });

    $('#start').datepicker({
        dateFormat:'dd-mm-yy',
        onSelect:function (selectedDate) {
            $("#end").attr("value", selectedDate);
            $("#end").datepicker("option", "minDate", selectedDate);
        }
    });
    $('#end').datepicker({
        dateFormat:'dd-mm-yy'
    });

});