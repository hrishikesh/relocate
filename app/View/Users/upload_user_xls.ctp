<?php
/**
 * @var $this View
 */
echo $this->Html->script(array('validations'), false);
?>

<div class="users form">
    <section id="forms">
        <div class="page-header">
            <h3>Import Users  <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>

        <div class="row">
            <div class="span10 offset1">
                <?php
                echo $this->Form->create('User', array(
                    'class' => "form-horizontal well",
                    'inputDefaults' => array('label' => false, 'div' => false),
                    'type' => 'file'
                )); ?>
                <div class="form-actions">
                    <div class="page-header">
                        <h3>Import Users</h3>
                    </div>
                    <div class="control-group info">
                        <label class="control-label" for="userName">Upload XLS File</label>

                        <div class="controls">
                            <?php echo $this->Form->file('file_name');?>
                        </div>
                    </div>
                </div>


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Upload File</button>
                    <a href="/users/all_users" class="btn">Cancel</a>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </section>
</div>