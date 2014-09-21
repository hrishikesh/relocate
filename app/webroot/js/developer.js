$(document).ready(function () {
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

    var replaceName = function(obj, count){


        $(obj).each(function(){
            var name = $(this).attr('name');
            name = name.replace('[1]', '['+count +']');
            $(this).attr('name', name);

            if($(this).hasClass('date-picker')){
                var date = $.datepicker.formatDate('dd-mm-yy', new Date());
                $(this).val(date);
            }else {
                $(this).val('');
            }
        });
    };

    $('#addMore').on('click', function () {

            var company_html = $('.companyInfo:first').clone();
            //$(company_html).find("#addMore").remove();
            var resAllocSelect = $(company_html).find('select');
            var resAllocInput = $(company_html).find('input');
            //$("#addMore" ).remove();
            var company_count = $('.company').length;
            company_count = parseInt(company_count) + 1;

            replaceName(resAllocSelect, company_count);

            replaceName(resAllocInput, company_count);
            $('#company').append(company_html);
    });



    $(".fancybox").fancybox({
        content: $("#company")
    });


});