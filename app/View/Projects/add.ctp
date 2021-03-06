
<?php
echo $this->Html->script(array('validations','jquery.ui.widget','combobox', 'autoCompleteLazyLoad','projects/project-add'), false);
?>
<div class="projects form">
    <section id="forms">
        <div class="page-header">
            <h3>Create Project <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>

        <div class="row">
            <div class="span10 offset1">
                <?php
                echo $this->Form->create('Project', array(
                    'class' => "form-horizontal well",
                    'inputDefaults' => array('label' => false, 'div' => false)
                )); ?>
                <div class="form-actions">
                    <div class="control-group info">
                        <label class="control-label" for="projectName">Project Name</label>

                        <div class="controls">
                            <?php echo $this->Form->input('project_name', array('placeholder'=>'Enter Project Name')); ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="accountName">Account</label>
                        <div class="controls">
                            <?php echo $this->Form->input('project_account_id', array('options' => $project_accounts,
                            'div' => false,
                            'label' => false,
                            'empty' => 'Select Account')); ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="projectTypeId">Project Type</label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input('project_type_id', array(
                                'options' => $projectType,
                                'div' => false,
                                'class'=>'span3',
                                'label' => false,
                                'empty' => 'Select Project Type'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="description">Description</label>

                        <div class="controls">
                            <?php echo $this->Form->input('description', array('placeholder'=>'Enter Description')); ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="start">Start Date</label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input(
                                'start_date', array(
                                'class' => 'tip span3 date-picker',
                                'placeholder' => 'Enter Start Date',
                                'id' => 'start',
                                'type' => 'text',
                                /*'value' => date('d-m-Y')*/
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="end">End Date</label>

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
                        </div>
                    </div>

                </div>

                <!--Project Requirements : start-->
                <div class="form-actions">
                    <div class="page-header">
                        <h3>Resource Allocation Plan</h3>
                    </div>

                    <div class="control-group info project-requirements" id="project-requirements" >
                        <div class="add-more">
                            <button type="button" class="btn btn-primary btn-small" id="addMore">Add More</button>
                        </div>

                        <div class="requirements" id="requirements">
                            <span class="pull-left">
                            <?php
                                echo $this->Form->input('ProjectResourceRequirements.1.technology_id', array(
                                    'options' => $skills,
                                    'div' => false,
                                    'class'=>'span2',
                                    'label' => false,
                                    'empty' => 'Select Skill'
                                ));
                                ?>
                            </span>
                            <span class="pull-left">
                                <?php

                                    echo $this->Form->input('ProjectResourceRequirements.1.required_percentage', array(
                                        //'options' => $percentages,
                                        'div' => false,
                                        'class'=>'span2',
                                        'label' => false,
                                        'empty' => 'Allocation percentage',
                                        'placeholder'=>'Enter % Allocation'
                                    ));
                                ?>
                            </span>
                             <span class="pull-left">
                                <?php

                                 echo $this->Form->input('ProjectResourceRequirements.1.no_of_resources', array(
                                     //'options' => $percentages,
                                     'div' => false,
                                     'label' => false,
                                     'class'=>'span2',
                                     'empty' => 'Number of resources',
                                     'placeholder'=>'Enter no of resources'
                                 ));
                                 ?>
                            </span>
                            <span class="pull-left">
                                <?php

                                echo $this->Form->input('ProjectResourceRequirements.1.start_date', array(
                                    'class' => 'start_date tip span3 date-picker',
                                    'placeholder' => 'Enter Start Date',
                                    'type' => 'text',
                                    /*'value' => date('d-m-Y')*/
                                ));
                                ?>
                            </span>
                            <span class="pull-left">
                                <?php

                                echo $this->Form->input('ProjectResourceRequirements.1.end_date', array(
                                    'class' => 'end_date tip span3 date-picker',
                                    'placeholder' => 'Enter End Date',
                                    'type' => 'text',
                                    /*'value' => date('d-m-Y')*/
                                ));
                                ?>
                            </span>
                        </div>
                    </div>

                </div>
                <!--Project Reequirements : end-->

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="/projects/all_projects" class="btn">Cancel</a>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </section>
</div>
