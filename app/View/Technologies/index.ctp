<div class="row">
    <div class="projects index">
        <h2><?php echo __('Skills');?>
            <a href="javascript:window.history.back();" class="pull-right backButton"></a>

            <div class="pull-right" style="margin-right: 10px;">
                <?php echo $this->Html->link(__('New Skill'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
            </div>

        </h2>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th><?php echo $this->Paginator->sort('id');?></th>
                <th><?php echo $this->Paginator->sort('stream_name');?></th>
                <th><?php echo $this->Paginator->sort('slug');?></th>
                <th><?php echo $this->Paginator->sort('No. of Resources');?></th>
                <th class="actions"><?php echo __('Actions');?></th>
            </tr>
            <?php
            if(count($technologies)>0){
                foreach ($technologies as $technology): ?>
                <tr>
                    <td><?php echo h($technology['Technology']['id']); ?>&nbsp;</td>
                    <td><?php echo h($technology['Technology']['stream_name']); ?>&nbsp;</td>
                    <td><?php echo h($technology['Technology']['slug']); ?>&nbsp;</td>
                    <td><?php echo h(count($technology['UserTechnology'])); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $technology['Technology']['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $technology['Technology']['id']), null, __('Are you sure you want to delete # %s?', $technology['Technology']['stream_name'])); ?>
                    </td>
                </tr>
            <?php
                endforeach;
            }else{?>
                <tr>
                    <td colspan="6">Skills are not added yet.</td>
                </tr>
                <?php
            }
                ?>
	    </table>
        <?php
        $hasPages = ($this->params['paging']['Technology']['pageCount'] > 1);
        if ($hasPages) {
            echo $this->element('pagination');
        } ?>
    </div>
</div>
