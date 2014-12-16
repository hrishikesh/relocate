<div class="projectAccounts view">
<h2><?php  echo __('Project Account');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['is_active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($projectAccount['ProjectAccount']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Project Account'), array('action' => 'edit', $projectAccount['ProjectAccount']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Project Account'), array('action' => 'delete', $projectAccount['ProjectAccount']['id']), null, __('Are you sure you want to delete # %s?', $projectAccount['ProjectAccount']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Project Accounts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project Account'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects'), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project'), array('controller' => 'projects', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Projects');?></h3>
	<?php if (!empty($projectAccount['Project'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Project Name'); ?></th>
		<th><?php echo __('Project Account Id'); ?></th>
		<th><?php echo __('Project Type Id'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Start Date'); ?></th>
		<th><?php echo __('End Date'); ?></th>
		<th><?php echo __('Project Lead Id'); ?></th>
		<th><?php echo __('Project Ba Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($projectAccount['Project'] as $project): ?>
		<tr>
			<td><?php echo $project['id'];?></td>
			<td><?php echo $project['project_name'];?></td>
			<td><?php echo $project['project_account_id'];?></td>
			<td><?php echo $project['project_type_id'];?></td>
			<td><?php echo $project['description'];?></td>
			<td><?php echo $project['start_date'];?></td>
			<td><?php echo $project['end_date'];?></td>
			<td><?php echo $project['project_lead_id'];?></td>
			<td><?php echo $project['project_ba_id'];?></td>
			<td><?php echo $project['created'];?></td>
			<td><?php echo $project['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'projects', 'action' => 'view', $project['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'projects', 'action' => 'edit', $project['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'projects', 'action' => 'delete', $project['id']), null, __('Are you sure you want to delete # %s?', $project['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Project'), array('controller' => 'projects', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
