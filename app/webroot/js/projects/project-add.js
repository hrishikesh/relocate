$(document).ready(function () {

    var replaceName = function(obj, count){
        $(obj).each(function(){
            var name = $(this).attr('name');
            name = name.replace('[1]', '['+count +']');
            var attrid = $(this).attr('id');
            attrid = attrid.replace(1, count);
            $(this).attr('name', name);
            $(this).attr('id', attrid);

            $(this).val('');
            if($(this).hasClass('start_date')){
                $(this).removeClass('hasDatepicker')
                var dateStart = $.datepicker.formatDate('dd-mm-yy', new Date());
                $(this).datepicker({
                    dateFormat:'dd-mm-yy'
                });
            }
            if($(this).hasClass('end_date')){
                $(this).removeClass('hasDatepicker')
                var dateEnd = $.datepicker.formatDate('dd-mm-yy', new Date());
                $(this).datepicker({
                    dateFormat:'dd-mm-yy'
                });

            }
        });
    };

    $('#addMore').on('click', function () {
        var requirements_html = $('.requirements:first').clone();
        var resAllocSelect = $(requirements_html).find('select');
        var resAllocInput = $(requirements_html).find('input');

        var project_requirements_count = $('.project-requirements .requirements').length;
        project_requirements_count = parseInt(project_requirements_count) + 1;

        replaceName(resAllocSelect, project_requirements_count);

        replaceName(resAllocInput, project_requirements_count);

        $('#project-requirements').append(requirements_html);

        //$(".date-picker").datepicker('remove'); //detach
//        $(".date-picker").datepicker({          //re attach
//            dateFormat:'dd-mm-yy'
//        })
    });

    $('.comboBox').combobox();

});

//if($(this).hasClass('start_date')){
//    $(this).removeClass('hasDatepicker')
//    var date = $.datepicker.formatDate('dd-mm-yy', new Date());
//    $("#ProjectResourceRequirements"+count+"startDate").datepicker({          //re attach
//        dateFormat:'dd-mm-yy'
//    });
//}
//if($(this).hasClass('end_date')){
//    $(this).removeClass('hasDatepicker')
//    var date = $.datepicker.formatDate('dd-mm-yy', new Date());
//    $("#ProjectResourceRequirements"+count+"endDate").datepicker({          //re attach
//        dateFormat:'dd-mm-yy'
//    })