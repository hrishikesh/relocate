<div class="technologies view">
<h2><?php  echo __('Technology');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Stream Name'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['stream_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Stream Type'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['stream_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($technology['Technology']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Technology'), array('action' => 'edit', $technology['Technology']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Technology'), array('action' => 'delete', $technology['Technology']['id']), null, __('Are you sure you want to delete # %s?', $technology['Technology']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Technologies'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Technology'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Project Resource Requirements'), array('controller' => 'project_resource_requirements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project Resource Requirement'), array('controller' => 'project_resource_requirements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List User Technologies'), array('controller' => 'user_technologies', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Technology'), array('controller' => 'user_technologies', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Project Resource Requirements');?></h3>
	<?php if (!empty($technology['ProjectResourceRequirement'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Project Id'); ?></th>
		<th><?php echo __('Skill Id'); ?></th>
		<th><?php echo __('No Of Resources'); ?></th>
		<th><?php echo __('Required Percentage'); ?></th>
		<th><?php echo __('Start Date'); ?></th>
		<th><?php echo __('End Date'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($technology['ProjectResourceRequirement'] as $projectResourceRequirement): ?>
		<tr>
			<td><?php echo $projectResourceRequirement['id'];?></td>
			<td><?php echo $projectResourceRequirement['project_id'];?></td>
			<td><?php echo $projectResourceRequirement['skill_id'];?></td>
			<td><?php echo $projectResourceRequirement['no_of_resources'];?></td>
			<td><?php echo $projectResourceRequirement['required_percentage'];?></td>
			<td><?php echo $projectResourceRequirement['start_date'];?></td>
			<td><?php echo $projectResourceRequirement['end_date'];?></td>
			<td><?php echo $projectResourceRequirement['created'];?></td>
			<td><?php echo $projectResourceRequirement['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'project_resource_requirements', 'action' => 'view', $projectResourceRequirement['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'project_resource_requirements', 'action' => 'edit', $projectResourceRequirement['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'project_resource_requirements', 'action' => 'delete', $projectResourceRequirement['id']), null, __('Are you sure you want to delete # %s?', $projectResourceRequirement['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Project Resource Requirement'), array('controller' => 'project_resource_requirements', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related User Technologies');?></h3>
	<?php if (!empty($technology['UserTechnology'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Technology Id'); ?></th>
		<th><?php echo __('Primary Skill'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($technology['UserTechnology'] as $userTechnology): ?>
		<tr>
			<td><?php echo $userTechnology['id'];?></td>
			<td><?php echo $userTechnology['user_id'];?></td>
			<td><?php echo $userTechnology['technology_id'];?></td>
			<td><?php echo $userTechnology['primary_skill'];?></td>
			<td><?php echo $userTechnology['created'];?></td>
			<td><?php echo $userTechnology['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'user_technologies', 'action' => 'view', $userTechnology['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'user_technologies', 'action' => 'edit', $userTechnology['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'user_technologies', 'action' => 'delete', $userTechnology['id']), null, __('Are you sure you want to delete # %s?', $userTechnology['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User Technology'), array('controller' => 'user_technologies', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
