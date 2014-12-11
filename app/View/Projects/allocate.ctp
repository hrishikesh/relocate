<?php //pr($allocationMatrix); pr($projectDetails); ?>
<div class="row">
    <div class="projects index">
        <input type="hidden" name="project_id" id="project_id" value="<?php echo $id; ?>" />
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
        <?php
        if(!empty($projectUsers)){
            ?>
            <div class="resourceLoadingWrapper">
                <?php
                echo $this->Form->create(null, array('url' => array('controller' => 'users', 'action' => 'saveAllocationsData'),
                    'class' => "form-horizontal well",
                    'inputDefaults' => array('label' => false, 'div' => false)
                ));
                ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>
                            Name and Experience
                        </th>
                        <th>
                            Allocation Type
                        </th>
                        <th>
                            Percentage allocation(Actual)
                        </th>
                        <th>
                            Start Date
                        </th>
                        <th>
                            End Date
                        </th>
                    </tr>
                    </thead>
                    <thead class="resourceLoading">
                    <?php
                    foreach($projectUsers as $key=>$projectUser){
                        ?>
                        <tr>
                            <?php
                            echo $this->Form->input('ProjectUser.'.$key.'.project_id', array('type'=>'hidden','value'=>$projectUser['ProjectsUser']['project_id']));
                            echo $this->Form->input('ProjectUser.'.$key.'.technology_id', array('type'=>'hidden','value'=>$projectUser['ProjectsUser']['technology_id']));
                            echo $this->Form->input('ProjectUser.'.$key.'.id', array('type'=>'hidden','value'=>$projectUser['ProjectsUser']['id']));
                            ?>
                            <td>
                                <?php
                                echo $this->Form->input('ProjectUser.'.$key.'.user_id', array('options' => $skilledResources,'empty' => 'Select User','label'=>false,'value'=>$projectUser['ProjectsUser']['user_id']));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Form->input('ProjectUser.'.$key.'.resource_type_id', array('options' => $resource_type,'empty' => 'Select Allocation Type','label'=>false,'value'=>$projectUser['ProjectsUser']['resource_type_id']));
                                ?>
                            </td>
                            <td>
                                <?php echo $this->Form->input('ProjectUser.'.$key.'.percentage_of_allocation',array('label'=>false,'value'=>$projectUser['ProjectsUser']['percentage_of_allocation']));?>
                            </td>
                            <td>
                                <?php echo $this->Form->input('ProjectUser.'.$key.'.start',array('label'=>false,'value'=>$projectUser['ProjectsUser']['start']));?>
                            </td>
                            <td>
                                <?php echo $this->Form->input('ProjectUser.'.$key.'.end',array('label'=>false,'value'=>$projectUser['ProjectsUser']['end']));?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </thead>
                </table>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="/users/all_projects" class="btn">Cancel</a>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
            <?php

        }else{
        ?>
        <div class="resourceLoadingWrapper" style="display:none;">
            <?php
                echo $this->Form->create(null, array('url' => array('controller' => 'users', 'action' => 'saveAllocationsData'),
                    'class' => "form-horizontal well",
                    'inputDefaults' => array('label' => false, 'div' => false)
                ));
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        Name and Experience
                    </th>
                    <th>
                        Allocation Type
                    </th>
                    <th>
                        Percentage allocation(Actual)
                    </th>
                    <th>
                        Start Date
                    </th>
                    <th>
                        End Date
                    </th>
                </tr>
                </thead>
                <thead class="resourceLoading">

                </thead>
            </table>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="/users/all_projects" class="btn">Cancel</a>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    function getResourcesBySkillSet() {

    }

    $(".resourceLoadingInitiate").click(function () {
        var count = $(".resourceLoading tr").length;
        var skill_id = $(this).attr("data-id");
        var project_id = $("#project_id").val();
        if (!skill_id) {
            return false;
        }

        //Ajax Call for fetching Skill wise resources
        $.ajax({
            type:"GET",
            url:"/users/get_resources_by_skill_set",
            data:{'skill_id':skill_id,'count':count,'project_id':project_id},
            dataType:'html',
            success:function (result) {
                $('.resourceLoading').append(result);
            }
        });

        $(".resourceLoadingWrapper").show();
    });


</script>