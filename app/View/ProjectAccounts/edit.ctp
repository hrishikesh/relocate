<div class="projects form">
    <section id="forms">
        <div class="page-header">
            <h3>Edit Project Account <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>
        <div class="row">
            <div class="span10 offset1">
                <?php echo $this->Form->create('ProjectAccount');
                    echo $this->Form->input('id');
                ?>
                    <div class="form-actions">
                        <div class="control-group info">
                                <label class="control-label" for="name">Account Name</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('name', array('placeholder'=>'Enter Account Name','div'=>false,'label'=>false)); ?>
                                </div>
                        </div>

                        <div class="control-group info">
                                <label class="control-label" for="is_active">Is Active</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('is_active', array('placeholder'=>'Check account active')); ?>
                                </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <a href="/project_accounts/index" class="btn">Cancel</a>
                    </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
    </section>
</div>
