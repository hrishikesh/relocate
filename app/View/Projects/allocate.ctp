<?php //pr($allocationMatrix); pr($projectDetails); ?>
<div class="row">
    <div class="projects index">
        <h2><?php echo __($projectDetails['Project']['project_name']);?>
            <a href="javascript:window.history.back();" class="pull-right backButton"></a>

            <div class="pull-right" style="margin-right: 10px;"></div>

        </h2>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    Skill set Required
                </th>
                <th>
                    Percentage required (Contracted)
                </th>
                <th>
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allocationMatrix as $allocate) { ?>
            <tr>
                <td>
                    <?php echo $allocate['Skill']['name']; ?>
                </td>
                <td>
                    <?php echo $allocate['ProjectResourceRequirement']['required_percentage'] . '%'; ?>
                </td>
                <td>
                    <a href="javascript:void(0);" class="btn resourceLoadingInitiate"
                       data-id="<?php echo $allocate['Skill']['id']; ?>">
                        Allocate
                    </a>
                </td>
            </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="resourceLoading" style="display:none;">

        </div>
    </div>
</div>

<script type="text/javascript">
    function getResourcesBySkillSet() {

    }

    $(".resourceLoadingInitiate").click(function () {
        skill_id = $(this).attr("data-id");

        if (!skill_id) {
            return false;
        }

        //Ajax Call for fetching Skill wise resources
        $.ajax({
            type:"GET",
            url:"/user/getResourcesBySkillSet/",
            data:{'skill_id':skill_id},
            dataType:'html',
            success:function (result) {
                $('#projectChart').html(result);
                /*chart.categoryField = "title";
                chart.valueField = 'employee_count';
                chart.titleField = 'stream_name';

                chart.dataProvider = result.chartData;
                chart.validateData();*/


            }
        });

        $(".resourceLoading").show();
    });


</script>