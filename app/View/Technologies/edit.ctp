<div class="projects form">
    <section id="forms">
        <div class="page-header">
            <h3>Edit Skill <a href="javascript:window.history.back();" class="pull-right backButton"></a></h3>
        </div>
        <div class="row">
            <div class="span10 offset1">
                <?php
                echo $this->Form->create('Technology');
                echo $this->Form->input('id');
                ?>
                <div class="form-actions">
                    <div class="control-group info">
                        <label class="control-label" for="stream_name">Skill Name</label>
                        <div class="controls">
                            <?php echo $this->Form->input('stream_name', array('placeholder'=>'Enter Skill Name','div'=>false,'label'=>false)); ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="/technologies/index" class="btn">Cancel</a>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
    </section>
</div>