$(document).ready(function () {

    $(function () {

        $('.circleBox-business-analyst').hide();

        $('.objectBox-business-analyst').masonry({
            // options
            itemSelector:'.draggableObjects-business-analyst',
            columnWidth:10,
            cornerStampSelector:''
        });

        $('.circleBox-business-analyst').masonry({
            // options
            itemSelector:'.draggableObjects-business-analyst',
            columnWidth:10,
            cornerStampSelector:'.corner-stamp'
        });

        //genericAdd("Object Title","Object Image URL","URL linked to Object");

        displayObjectsFromDatabase('objectBox-business-analyst');
    });

    function displayObjectsFromDatabase(objectId) {
//        console.log('displayObjects method called');

        $.each(objectDatabasebusinessanalyst, function (i, v) {
            genericAdd(objectId, v.title, v.image, v.url);
        });
    }


    $("#show_all-business-analyst").click(function (e) {
        $('.circleBox-business-analyst').hide();
        $('.objectBox-business-analyst').show();
        displayObjectsFromDatabase('objectBox-business-analyst');
    });


    function genericAdd(objectId, object_title, object_image, object_url) {
        var object_url_html = '';
        var object_image_html = '';

        if (null == object_title || $.trim(object_title) == "") {
            alert('Please enter an Object title');
            return;
        }

        if (null != object_url && $.trim(object_url) != "") {
            object_url_html = '<div class="data"><a href="' + object_url + '" id="' + object_url + '">' + object_title + '</a></div>';
        } else {
            object_url_html = '<div class="data" id="' + object_url + '">' + object_title + '</div>';
        }

        if (null != object_image && $.trim(object_image) != "") {
            object_image_html = '<div class="object-img" style="background-image:url(' + object_image + '); " id="' + object_url + '" ></div>';
        }

        attachObject(objectId, object_image_html, object_url_html, object_url)


    }

    function attachObject(objectId, object_image_html, object_url_html, object_url) {
        var $object = $('<div class="object draggableObjects draggableObjects-business-analyst"  id="' + object_url + '" >' + object_image_html + object_url_html + '</div>');
        $('.' + objectId).prepend($object).masonry('reload');
        $object.draggable({
            cancel:"a.ui-icon",
            revert:"invalid",
            containment:"document",
            helper:"clone",
            zIndex:120,
            cursor:"move"
        });
    }


    $(".draggableObjects-business-analyst").draggable({
        drag:function (event, ui) {
            alert('dragstart');
        },
        cancel:"a.ui-icon",
        revert:"invalid",
        containment:"document",
        helper:"clone",
        zIndex:120,
        cursor:"move"

    });

    function onDragStart(event) {
        alert('hi');
    }

    $(".draggableObjects-business-analyst").bind('dragstart', onDragStart, false);

    $(".mainContainer-business-analyst").droppable({
        accept:".draggableObjects-business-analyst",
        activeClass:"ui-state-highlight",
        drop:function (event, ui) {
//            alert('dropable called')
            var projectId = $("#projectId").val();
            var draggableContent = ui.draggable;
            var user_id = draggableContent.children("div").attr("id");
//            console.log(user_id);
            var thisobject = this;
            $.ajax({
                type: "POST",
                url:"/projects/add_project_resource",
                data:{'project_id':projectId,'user_id':user_id},
                success:function (result) {
                    var resultDt = jQuery.parseJSON(result);
                    if(resultDt['status'] == 'success'){
                        addCirclebusinessanalyst(ui.draggable, thisobject.id);
                        $("#errorDivbusiness-analyst").html();
                        $("#errorDivbusiness-analyst").html('<span class="success">'+resultDt['message']+'</span>');
                    }else{
                        $("#errorDivbusiness-analyst").html();
                        $("#errorDivbusiness-analyst").html('<span class="error">'+resultDt['message']+'</span>');
                    }

                }
            });


        }
    });

    $(".mainContainer-business-analyst").mouseover(function () {
        var containerId = this.id;
        TweenLite.to($("#b" + containerId), 0.2, {css:{width:150, height:150, marginLeft:-20, marginTop:-20}, ease:Power2.easeOut, onComplete:function () {
            calculatePositionsbusinessanalyst(containerId);
        }
        });
    });

    $(".mainContainer-business-analyst").mouseleave(function () {
        var resultCircleClass = ".r" + this.id;
        var bigCircleId = "#b" + this.id;

        TweenLite.to($(resultCircleClass), 0.2, {css:{autoAlpha:0, scaleX:0.1, scaleY:0.1}});

        TweenLite.to($(bigCircleId), 0.2, {css:{width:110, height:110, marginLeft:0, marginTop:0}, delay:0.2, overwrite:"all"});
    });

    $(".smallCircle-business-analyst").click(function () {
        var object;
        var containerId = $(this).parent().attr("id");
        var bigCircleId = "#b" + containerId;
        var resultCircleClass = ".r" + containerId;
        $('.objectBox-business-analyst').hide();
        $('.circleBox-business-analyst').show();

        $('.circleBox-business-analyst').masonry('remove', $('.draggableObjects-business-analyst')).masonry('reload');

        $(bigCircleId).children(resultCircleClass).each(function () {
            var context = $(this);
            object = $('<div class="object draggableObjects draggableObjects-business-analyst" >' + context.html() + '</div>');
            $('.circleBox-business-analyst').append(object).masonry('appended', object);
        });

        $('.draggableObjects-business-analyst:not(.ui-draggable)').draggable({
            cancel:"a.ui-icon",
            revert:"invalid",
            containment:"document",
            helper:"clone",
            zIndex:120,
            cursor:"move"
        });

    });

    /*
     $(".bigCircle").click(function(){
     alert('bigCircle');
     });*/

    $(".bigCircle-business-analyst").on('click', '.resultCircle-business-analyst', function () {
        //$(this).parent().append("<div class='akash' >asdasdasdasd </div>");
//        $("<div class='akash dn'> </div>").insertAfter(this);
        console.log($(this).css('top'));
        var new_top = (parseInt($(this).css('top')) + 30 );
        var new_left = (parseInt($(this).css('left')));
        console.log(new_top);
        $(".project-resource").css('top', new_top);
        $(".project-resource").css('left', new_left);
        /*$(".project-resource").css({top: (parseInt($(this).css('left')))+ "px !important"});*/
        $(".project-resource").show();

        /*var containerId = this.id;
         TweenLite.to($("#b"+containerId), 0.2, {css: {width:150,height:150,marginLeft:-20, marginTop:-20}, ease:Power2.easeOut,onComplete:
         function(){
         calculatePositions(containerId);
         }
         });*/

    });

});

function addCirclebusinessanalyst($item, containerId) {

    var resultCircleClass = "r" + containerId;
    console.log('addCircleCalled')
    var div = $("<div class='resultCircle resultCircle-business-analyst " + resultCircleClass + "' >" + $item.html() + "</div>");
    $('#b' + containerId).append(div);
    calculatePositionsbusinessanalyst(containerId);
}

function calculatePositionsbusinessanalyst(containerId) {

    var bigCircleId = "#b" + containerId;
    var resultCircleClass = ".r" + containerId;
    var radius = 75 - 15 - 1; //outer circle radius - result circle radius - offset
    var num = $(bigCircleId).children().length;
    var dividers = 360 / num;
    var center = 60; // radius of middle circle + 5(offset)
    var theta = 0.0;
    var radians = dividers * (Math.PI / 180);

    for (var i = 0; i < num; i++) {

        var x = Math.round(center + radius * Math.cos(theta));
        var y = Math.round(center + radius * Math.sin(theta));
        var M = $(".project-resource").children();

        $(bigCircleId + " :nth-child(" + (i + 1) + ")").not(".project-resource, .slider, .ui-slider-range-min, .ui-slider-handle").css({'left':x, 'top':y});

        theta += radians;
    }
    TweenLite.to($(resultCircleClass), 0.1, {css:{autoAlpha:1, scaleX:1, scaleY:1}});

}