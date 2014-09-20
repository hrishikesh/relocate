<table class="table table-bordered">
    <thead>
        <tr>
            <th>
                Skill
            </th>
            <th>
                %age
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($allocationMatrix as $allocate) { ?>
        <tr>
            <td>
                <?php echo $allocate['Skill']['name']; ?>
            </td>
            <td>
                <?php echo $allocate['ProjectResourceRequirement']['required_percentage'] . '%'; ?>
            </td>
            <td>
                <a href="javascript:void(0);" class="btn" data-id = "<?php echo $allocate['Skill']['id']; ?>">Allocate</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">

</script>