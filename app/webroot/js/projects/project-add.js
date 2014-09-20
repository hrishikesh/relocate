$(document).ready(function () {

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
        var requirements_html = $('.requirements:first').clone();
        var resAllocSelect = $(requirements_html).find('select');
        var resAllocInput = $(requirements_html).find('input');

        var project_requirements_count = $('.project-requirements .requirements').length;
        project_requirements_count = parseInt(project_requirements_count) + 1;

        replaceName(resAllocSelect, project_requirements_count);

        replaceName(resAllocInput, project_requirements_count);

        $('#project-requirements').append(requirements_html);


    });

    $('.comboBox').combobox();

});