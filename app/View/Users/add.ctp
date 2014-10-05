<?php
echo $this->Html->script(array('validations'), false);
?>

<div class="users form" xmlns="http://www.w3.org/1999/html">
    <section id="forms">
        <div class="page-header">
            <h3>Create User <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>

        <div class="row">
            <div class="span10 offset1">
                <?php
                echo $this->Form->create('User', array(
                    'class' => "form-horizontal well",
                    'inputDefaults' => array('label' => false, 'div' => false)
                )); ?>
                <div class="form-actions">
                    <div class="page-header">
                        <h3>Personal information</h3>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="userName">Email Id</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.username', array('placeholder'=>'Enter Email Id'));
                            echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator'));
                            echo $this->Html->image('available.png', array('id' => 'available'));
                            echo $this->Html->image('unavailable.gif', array('id' => 'unavailable'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="password">Password</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.password',array('placeholder'=>'Enter Password'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="first_name">First Name</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.first_name',array('placeholder'=>'Enter First Name'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="last_name">Last Name</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.last_name', array('placeholder'=>'Enter Last Name'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="last_name">Date Of Birth</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.date_of_birth', array('placeholder'=>'dd/mm/yyyy','class'=>'date-picker'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="employee_id">Employee id</label>

                        <div class="controls">
                            <?php echo $this->Form->input('User.employee_id', array('type' => 'text','placeholder'=>'Enter Employee Id'));?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="salary">Salary</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('User.salary',array('placeholder'=>'Enter Annual Salary'));
                            ?>
                        </div>
                    </div>
                </div>


                <!--Project Reequirements : start-->
                <div class="form-actions">
                    <div class="page-header">
                        <h3>Technical Information</h3>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="work_experience">Work Experience</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('User.work_experience',array('placeholder'=>'Enter Total Work Experience','id'=>"totalExperience"));
                            ?>
                            <input type="button" class="fancybox" name="addCompany" id="addCompany" value="Add Experience" />

                            <div class="company companyHide" id="company">
                                <div>
                                    <input type="button" name="addMore" id="addMore" value="Add More" />
                                </div>
                                <div class="companyInfo">
                                    <span class="pull-left">
                                       <?php echo $this->Form->input('UserPreviousExperience.start_date',array('name'=>'data[UserPreviousExperience][1][start_date]','type'=>'text','placeholder'=>'dd/mm/yyyy','class'=>'date-picker startDate'));?>
                                       <?php echo $this->Form->input('UserPreviousExperience.end_date',array('name'=>'data[UserPreviousExperience][1][end_date]','type'=>'text','placeholder'=>'dd/mm/yyyy','class'=>'date-picker endDate'));?>
                                    </span>
                                    <span class="pull-left">
                                        <?php echo $this->Form->input('UserPreviousExperience.company_name',array('name'=>'data[UserPreviousExperience][1][company_name]','placeholder'=>'Company Name'));?>
                                    </span>
                                    <span class="pull-left">
                                        <?php echo $this->Form->textarea('UserPreviousExperience.description',array('name'=>'data[UserPreviousExperience][1][description]','placeholder'=>'Description'));?>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="primary_skill">Primary Skill</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('UserSkill.primary_skill', array('options' => $skills,'empty' => 'Select Primary Skill'));
                            ?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="secondary_skill">Secondary Skill</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('UserSkill.secondary_skill', array('options' => $skills,'empty' => 'Select Secondary Skills','multiple' => true),array('multiple' => true));
                            ?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="team_id">Team</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('UserProfile.team_id', array('options' => $teams,'empty' => 'Select Team'));
                            ?>
                        </div>
                    </div>

                    <div class="control-group info">
                        <label class="control-label" for="designation_id">Designation</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('UserProfile.designation_id', array('options' => $designations,'empty' => 'Select Designation'));
                            ?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="grade_id">Grade</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('UserProfile.grade_id', array('options' => $grades,'empty' => 'Select Grade'));
                            ?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="date_of_joining">Date Of Joining</label>
                        <div class="controls">
                            <?php
                            echo $this->Form->input('User.date_of_joining',array('placeholder'=>'dd/mm/yyyy','type'=>'text','class'=>'date-picker'));
                            ?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="is_verified">Verified</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_verified');?>
                        </div>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="is_active">Active</label>
                        <div class="controls">
                            <?php echo $this->Form->input('is_active');?>
                        </div>
                    </div>
                </div>
                <!--Project Reequirements : end-->

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="/users/all_users" class="btn">Cancel</a>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </section>
</div>