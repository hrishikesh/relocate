<div class="row">
    <div class="projects index">
        <h2><?php echo __('Accounts');?>
            <a href="javascript:window.history.back();" class="pull-right backButton"></a>

            <div class="pull-right" style="margin-right: 10px;">
                <?php echo $this->Html->link(__('New Account'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
            </div>

        </h2>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th><?php echo $this->Paginator->sort('id');?></th>
                <th><?php echo $this->Paginator->sort('account_name');?></th>
                <th><?php echo $this->Paginator->sort('slug');?></th>
                <th><?php echo $this->Paginator->sort('is_active');?></th>
                <th class="actions"><?php echo __('Actions');?></th>
            </tr>
            <?php
            if(count($projectAccounts)>0){
               foreach ($projectAccounts as $projectAccount): ?>
                    <tr>
                        <td><?php echo h($projectAccount['ProjectAccount']['id']); ?>&nbsp;</td>
                        <td><?php echo $this->Html->link(__($projectAccount['ProjectAccount']['name']), array('plugin'=>false,'controller'=>'projects','action' => 'all_projects',$projectAccount['ProjectAccount']['id'])); ?>&nbsp;</td>
                        <td><?php echo h($projectAccount['ProjectAccount']['slug']); ?>&nbsp;</td>
                        <td><?php echo h($projectAccount['ProjectAccount']['is_active']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php //echo $this->Html->link(__(''), array('action' => 'view', $project['Project']['id']), array('class' => 'icon-eye-open'));
                            echo $this->Html->link(__(''), array('action' => 'edit', $projectAccount['ProjectAccount']['id']), array('class' => 'icon-edit'));
                            echo $this->Html->link(
                                '', array('action' => 'delete', $projectAccount['ProjectAccount']['id']), array(
                                    'class' => 'icon-trash',
                                ),
                                __('You are about to delete %s', '"'.$projectAccount['ProjectAccount']['name']. '", Are you sure?')
                            );
                            ?>
                        </td>
                    </tr>
                   <?php endforeach;
            } else{ ?>
                <tr>
                    <td colspan="6">No projects accounts are added yet.</td>
                </tr>
                <?php } ?>
        </table>
        <?php
        $hasPages = ($this->params['paging']['ProjectAccount']['pageCount'] > 1);

        if ($hasPages) {
            echo $this->element('pagination');
        } ?>
    </div>
</div>