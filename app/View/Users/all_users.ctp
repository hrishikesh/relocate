<div class="row">

    <div class="users index">
        <h2><?php echo __('Users / Resources');?>
            <a href="javascript:window.history.back();" class="pull-right backButton"></a>
            <div class="pull-right" style="margin-right: 10px;">
                <?php echo $this->Html->link(__('Export Users'), array('action' => 'export_users', $project_id),array('class'=>'btn btn-primary')); ?>
                <?php echo $this->Html->link(__('Import Users'), array('action' => 'upload_user_xls'),array('class'=>'btn btn-primary')); ?>
                <?php echo $this->Html->link(__('New User'), array('action' => 'add'),array('class'=>'btn btn-primary')); ?>
            </div>
        </h2>
        <div>
            <?php
            echo $this->Form->create('User', array('method'=>'get',
                'class' => "form-horizontal well",
                'inputDefaults' => array('label' => false, 'div' => false)
            )); ?>
            <div class="control-group info">
                <label class="control-label" for="projectId">Project</label>

                <div class="controls">
                    <?php
                    echo $this->Form->input('project_id', array(
                        'options' => $allProjects,
                        'div' => false,
                        'class'=>'span3',
                        'label' => false,
                        'empty' => 'Select Project',
                        'value'=>$project_id
                    ));
                    ?>
                </div>

            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>

            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th><?php echo $this->Paginator->sort('employee_id', 'Employee Id');?></th>
                <th><?php echo $this->Paginator->sort('first_name','Name');?></th>
                <th><?php echo $this->Paginator->sort('username', 'Email Id');?></th>
                <th><?php echo "Skills";//echo $this->Paginator->sort('UserTechnology.technology_id','Skills');?></th>
                <th><?php echo 'Current Project'; //$this->Paginator->sort('ProjectsUser.project_id','Current Project');?></th>
                <th><?php echo '% Allocation';?></th>
                <th><?php echo $this->Paginator->sort('work_experience');?></th>
                <th class="actions"><?php echo __('Actions');?></th>
            </tr>
            <?php
            if(!empty($users)) {
            foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo h($user['User']['employee_id']); ?>&nbsp;</td>
                    <td><?php echo h($user['User']['first_name'].' '.$user['User']['last_name']); ?>&nbsp;</td>
                    <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
                    <?php
                        $technologies = "";
                        foreach($user['UserTechnology'] as $key=>$technology){
                            if($technologies == ""){
                                $technologies .= $technology['Technology']['stream_name'];
                            }else{
                                $technologies .= ", ".$technology['Technology']['stream_name'];
                            }
                        }
                    ?>
                    <td><?php echo h($technologies); ?>&nbsp;</td>
                    <?php
                    $projects = "";
                    $percent_allocation = 0;
//                    pr($user['ProjectsUser']);
                    foreach($user['ProjectsUser'] as $key=>$project){
                        if($projects == ""){
                            $projects .= $project['Project']['project_name'];
                        }else{
                            $projects .= ", ".$project['Project']['project_name'];
                        }
                        $percent_allocation = $percent_allocation + (isset($project['ProjectsUser']['percentage_of_allocation'])?$project['ProjectsUser']['percentage_of_allocation']:0);
                    }
                    ?>
                    <td><?php echo h($projects); ?>&nbsp;</td>
                    <td><?php echo h($percent_allocation); ?>&nbsp;</td>

                    <td><?php echo h($user['User']['work_experience']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__(''), array('action' => 'view', $user['User']['id']), array('class' => 'icon-eye-open'));
                        echo $this->Html->link(__(''), array('action' => 'edit', $user['User']['id']), array('class' => 'icon-edit'));
                        echo $this->Html->link(
                            '', array('action' => 'delete', $user['User']['id']), array(
                                'class' => 'icon-trash',
                            ),
                            __('You are about to delete %s', '"'.$user['User']['first_name'] . ' ' . $user['User']['last_name'] . '", Are you sure?')
                        ); ?>
                    </td>
                </tr>
                <?php }
            } else { ?>
                <tr><td colspan="7">No users are added yet.</td> </tr>
            <?php } ?>
        </table>

        <?php
        $hasPages = ($this->params['paging']['User']['pageCount'] > 1);

        if ($hasPages)
        {
            echo $this->element('pagination');
        } ?>
    </div>
</div>
