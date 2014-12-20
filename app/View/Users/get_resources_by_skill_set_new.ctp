<tr>
    <?php
    echo $this->Form->input('ProjectUser.'.$count.'.project_id', array('type'=>'hidden','value'=>$project_id));
    echo $this->Form->input('ProjectUser.'.$count.'.technology_id', array('type'=>'hidden','value'=>$skill_id));
    ?>
    <td>
        <?php
        echo $this->Form->input('ProjectUser.'.$count.'.user_id', array('options' => $skilledResources,'empty' => 'Select User','label'=>false));
        ?>
    </td>
    <td>
        <?php
        echo $this->Form->input('ProjectUser.'.$count.'.resource_type_id', array('options' => $resource_type,'empty' => 'Select Allocation Type','label'=>false));
        ?>
    </td>
    <td>
        <?php echo $this->Form->input('ProjectUser.'.$count.'.percentage_of_allocation',array('label'=>false));?>
    </td>
    <td>
        <?php echo $this->Form->input('ProjectUser.'.$count.'.start',array('label'=>false));?>
    </td>
    <td>
        <?php echo $this->Form->input('ProjectUser.'.$count.'.end',array('label'=>false));?>
    </td>
</tr>
