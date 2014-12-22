<div class="row">
    <?php
//   php pr($projects);
    ?>
    <div class="projects index">
        <h2><?php echo __('Projects');?>
            <a href="javascript:window.history.back();" class="pull-right backButton"></a>
            <div class="pull-right" style="margin-right: 10px;">
                <?php echo $this->Html->link(__('New Project'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
            </div>

        </h2>
        <div>
            <?php
            echo $this->Form->create('Project', array('method'=>'get',
                'class' => "form-horizontal well",
                'inputDefaults' => array('label' => false, 'div' => false)
            )); ?>
            <table style="margin: 0 auto;">
                <tr class="control-group info">
                    <td class="controls">
                        <?php
                        echo $this->Form->input('account_id', array(
                            'options' => $allAccounts,
                            'div' => false,
                            'class'=>'span3',
                            'label' => false,
                            'empty' => 'Select Account',
                            'value'=>$account_id
                        ));
                        ?>
                    </td>
                    <td class="controls">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </td>
                </tr>
            </table>

            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th><?php echo $this->Paginator->sort('account_name');?></th>
                <th><?php echo $this->Paginator->sort('project_name','SOW(name and number)');?></th>
                <th><?php echo $this->Paginator->sort('start_date');?></th>
                <th><?php echo $this->Paginator->sort('end_date');?></th>
                <th><?php echo h('Contracted Allocation (%)');?></th>
                <th><?php echo h('Actual Allocation (%)');?></th>
                <th><?php echo h('Difference in Allocation (%)');?></th>
                <th class="actions"><?php echo __('Actions');?></th>
            </tr>
            <?php
            if(count($projects)>0){
                foreach ($projects as $project){
                    $contracted_allocation = 0;
                    $actual_allocation = 0;
                    $difference_allocation = 0;
                    foreach($project['ProjectResourceRequirement'] as $contracted){
                        $contracted_allocation = $contracted_allocation + ($contracted['required_percentage']*$contracted['no_of_resources']);
                    }
                    foreach($project['ProjectsUser'] as $actual){
                        $actual_allocation = $actual_allocation + $actual['percentage_of_allocation'];
                    }
                    $difference_allocation = $contracted_allocation - $actual_allocation;
                    ?>
                    <tr>
                        <td><?php echo h($project['ProjectAccount']['name']); ?>&nbsp;</td>
                        <td><?php echo h($project['Project']['project_name']); echo"(".$project['AllocationProjectType']['name'].")" ?>&nbsp;</td>
                        <td><?php echo h(date('d-m-Y',strtotime($project['Project']['start_date']))); ?>&nbsp;</td>
                        <td><?php echo h(date('d-m-Y',strtotime($project['Project']['end_date']))); ?>&nbsp;</td>
                        <td><?php echo $contracted_allocation; ?>&nbsp;</td>

                        <td><?php echo $actual_allocation; ?>&nbsp;</td>
                        <td><?php echo $difference_allocation; ?>&nbsp;</td>
                        <td class="actions">
                            <?php //echo $this->Html->link(__(''), array('action' => 'view', $project['Project']['id']), array('class' => 'icon-eye-open'));
                            echo $this->Html->link(__(''), array('action' => 'edit', $project['Project']['id']), array('class' => 'icon-edit'));
                            echo $this->Html->link(
                            '', array('action' => 'delete', $project['Project']['id']), array(
                            'class' => 'icon-trash',
                            ),
                            __('You are about to delete %s', '"'.$project['Project']['project_name']. '", Are you sure?')
                            );
                            echo $this->Html->link(__(''), array('action' => 'allocate', $project['Project']['id']), array('class' => 'icon-user'));

                            ?>
                        </td>
                    </tr>
          <?php }
            } else{ ?>
                <tr>
                    <td colspan="6">No projects are added yet.</td>
                </tr>
                <?php } ?>
        </table>

        <?php
        $hasPages = ($this->params['paging']['Project']['pageCount'] > 1);

        if ($hasPages) {
            echo $this->element('pagination');
        } ?>
    </div>
</div>

