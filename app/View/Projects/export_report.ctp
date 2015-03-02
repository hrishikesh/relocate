
<div class="projects form">
    <section id="forms">
        <div class="page-header">
            <h3>Export Project Report <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>

        <div class="row">
            <div class="span10 offset1">
                <?php
                echo $this->Form->create('Project', array(
                    'class' => "form-horizontal well",'id'=>'frmExportReport',
                    'inputDefaults' => array('label' => false, 'div' => false)
                )); ?>
                <div class="form-actions">

                    <div class="control-group info">
                        <label class="control-label" for="start">Select Start Date</label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input(
                                'start_date', array(
                                'class' => 'tip span3 date-picker',
                                'placeholder' => 'Enter Start Date',
                                'id' => 'start',
                                'type' => 'text',
//                                'value' => date('d-m-Y')
                            ));
                            ?>
                            <p id="errorStartDate" class="error" style="display: none;">Please Select Start Date.</p>
                        </div>

                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="end">Select End Date</label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input(
                                'end_date', array(
                                'class' => 'tip span3 date-picker',
                                'placeholder' => 'Enter End Date',
                                'id' => 'end',
                                'type' => 'text',
                                /*'value' => date('d-m-Y')*/
                            ));
                            ?>
                            <p id="errorEndDate" class="error" style="display: none;">Please Select End Date.</p>
                        </div>

                    </div>
                    <button type="button" class="btn btn-primary" id="exportReport">Export Report</button>
                    <a href="/projects/all_projects" class="btn">Cancel</a>

                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(function() {
        $( "#start" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear:true,
            onClose: function( selectedDate ) {
                $( "#end" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#end" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear:true,
            onClose: function( selectedDate ) {
                $( "#start" ).datepicker( "option", "maxDate", selectedDate );
            }
        });

        $("#exportReport").on('click',function(){
            var validate = true;
            if($("#start").val() == '' || $("#end").val()==''){
                validate = false;
                if($("#start").val()==''){
                   $("#errorStartDate").show();
                }else{
                   $("#errorStartDate").hide();
                }
                if($("#end").val()==''){
                    $("#errorEndDate").show();
                }else{
                    $("#errorEndDate").hide();
                }
            }else{
                validate = true;
            }
            if(validate){
                $("#frmExportReport").submit();
            }
        });

    });
</script>
