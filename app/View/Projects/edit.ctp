<?php
echo $this->Html->script(array('validations','jquery.ui.widget','combobox', 'autoCompleteLazyLoad','projects/project-add'), false);
?>
<div class="projects form">
    <section id="forms">
        <div class="page-header">
            <h3>Edit Project  <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>

                <?php
                echo $this->Form->create('Project', array(
                    'class' => "form-horizontal",
                    'inputDefaults' => array('label' => false, 'div' => false)
                ));
                echo $this->Form->hidden('Project.id' , array('value' => $project_id));
                ?>
                <div class="form-actions">
                    <div class="control-group info">
                        <label class="control-label" for="projectName">Project Name</label>

                        <div class="controls">
                            <?php echo $this->Form->input('project_name'); ?>
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
                        <label class="control-label" for="projectType">Project Type</label>

                        <div class="controls">
                            <?php
                            echo $this->Form->input("project_type_id", array(
                                'options' => $projectType,
                                'div' => false,
                                'label' => false,
                                'value' => $this->data['Project']['project_type_id'],
                                'empty' => 'Select Technology'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="description">Description</label>

                        <div class="controls">
                            <?php echo $this->Form->input('description'); ?>
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
                                'value' => date('d-m-Y')
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
                                'value' => date('d-m-Y')
                            ));
                            ?>
                        </div>
                    </div>
                </div>



                <!--Project Requirements : start-->
                <div class="form-actions">
                    <div class="page-header">
                        <h3>Project Requirements</h3>
                    </div>

                    <div class="control-group info project-requirements" id="project-requirements">
                        <div class="add-more">
                            <button type="button" class="btn btn-primary btn-small" id="addMore">Add More</button>
                        </div>

                        <?php
                        if (isset($this->request->data['ProjectResourceRequirement']) && !empty($this->request->data['ProjectResourceRequirement'])) {
                            foreach ($this->request->data['ProjectResourceRequirement'] as $requirementKey => $projectResourceRequirement) {

                                echo $this->Form->input("ProjectResourceRequirements." . ($requirementKey + 1) . ".id", array(
                                    'type'=>'hidden',
                                    'div' => false,
                                    'label' => false,
                                    'value' => $projectResourceRequirement['id'],
                                    ));
                                ?>

                                <div class="requirements clearfix" id="requirements">

                                    <?php echo $this->Form->hidden("ProjectResourceRequirements." . ($requirementKey + 1) . ".id" , array('value' => $projectResourceRequirement['id'])) ?>
                                    <span class="pull-left">
                                    <?php
                                        echo $this->Form->input("ProjectResourceRequirements." . ($requirementKey + 1) . ".technology_id", array(
                                            'options' => $skills,
                                            'div' => false,
                                            'label' => false,
                                            'value' => $projectResourceRequirement['technology_id'],
                                            'empty' => 'Select Technology'
                                        ));
                                        ?>
                                    </span>
                                    <span class="pull-left">
                                    <?php

                                        echo $this->Form->input("ProjectResourceRequirements." . ($requirementKey + 1) . ".required_percentage", array(
                                            'div' => false,
                                            'label' => false,
                                            'value' => $projectResourceRequirement['required_percentage'],
                                            'empty' => 'Allocation percentage'));
                                        ?>
                                    </span>
                                    <span class="pull-left">
                                        <?php

                                        echo $this->Form->input('ProjectResourceRequirements.' . ($requirementKey + 1) . '.no_of_resources', array(
                                            //'options' => $percentages,
                                            'div' => false,
                                            'label' => false,
                                            'class'=>'span2',
                                            'empty' => 'Number of resources',
                                            'placeholder'=>'Enter no of resources',
                                            'value'=>$projectResourceRequirement['no_of_resources']
                                        ));
                                        ?>
                                    </span>
                                    <span class="pull-left">
                                        <?php

                                        echo $this->Form->input('ProjectResourceRequirements.' . ($requirementKey + 1) . '.start_date', array(
                                            'class' => 'tip span3 date-picker',
                                            'placeholder' => 'Enter Start Date',
                                            'type' => 'text',
                                            'value' => date('d-m-Y',strtotime($projectResourceRequirement['start_date']))
                                        ));
                                        ?>
                                    </span>
                                    <span class="pull-left">
                                        <?php

                                        echo $this->Form->input('ProjectResourceRequirements.' . ($requirementKey + 1) . '.end_date', array(
                                            'class' => 'tip span3 date-picker',
                                            'placeholder' => 'Enter End Date',
                                            'type' => 'text',
                                            'value' => date('d-m-Y',strtotime($projectResourceRequirement['end_date']))
                                        ));
                                        ?>
                                    </span>
                                </div>
                                <?php
                            }
                        }else{
                            ?>
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
                            <?php
                        }
                        ?>

                    </div>

                </div>
                <!--Project Reequirements : end-->

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="/projects/all_projects" class="btn">Cancel</a>
                </div>
                <?php echo $this->Form->end(); ?>
    </section>
</div>
