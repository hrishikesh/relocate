<?php
class AddMasterTableForProjectTypeAndAlocationType extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
            'create_table' => array(
                'allocation_project_types' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
                    'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
                    'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
                    'is_active' => array('type' => 'boolean', 'default' => 1),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
                ),
            ),
            'rename_field' => array(
                'projects' => array(
                    'project_type' => 'project_type_id'
                )
            ),
            'alter_field' => array(
                'projects' => array(
                    'project_type_id' => array('type' => 'integer', 'length' => 11)
                )
            ),
            'create_field' => array(
                'projects_users' => array(
                    'resource_type_id' => array('type' => 'integer', 'length' => 11)
                )
            )
		),
		'down' => array(
           'drop_table'=>array('allocation_project_types'),
           'rename_field' => array(
                'projects' => array(
                    'project_type_id' => 'project_type'
                )
            ),
            'alter_field' => array(
                'projects' => array(
                    'project_type' => array('type' => 'integer', 'length' => 11)
                )
            ),
            'drop_field'=>array(
                'projects_users' => array(
                    'resource_type_id'
                )
            )
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {

		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
        if ($direction == 'up') {
            $AllocationProjectType = $this->generateModel('AllocationProjectType');
            $Project = $this->generateModel('Project');
            $project_types = array('Fixed Price','Time and Material');
            $allocation_types = array('Billed','Partial Billed','Shadow','Not Billed');

            foreach($project_types as $key=>$val){
                $typeData = '';
                $typeData = array(
                    'name' => $val,
                    'type' => 'project_type',
                    'is_active' => 1
                );
                $AllocationProjectType->create();
                if ($AllocationProjectType->save($typeData)) {
                    $this->log('>>> Inside Migrations | SUCCESS : Master data entry done.');
                } else {
                    $this->log('>>> Inside Migrations | FAILED : Master data entry could not be done.');
                }
            }

            foreach($allocation_types as $key=>$valAllocation){
                $typeData = '';
                $typeData = array(
                    'name' => $valAllocation,
                    'type' => 'resource_type',
                    'is_active' => 1
                );
                $AllocationProjectType->create();
                if ($AllocationProjectType->save($typeData)) {
                    $this->log('>>> Inside Migrations | SUCCESS : Master data entry done.');
                } else {
                    $this->log('>>> Inside Migrations | FAILED : Master data entry could not be done.');
                }
            }

            $query = "Update projects SET project_type_id=1 WHERE 1";
            if($Project->query($query)){
                $this->log('>>> Inside Migrations | SUCCESS : project type migrated.');
            } else {
                $this->log('>>> Inside Migrations | FAILED : project type could not migrate.');
            }

        }
        return true;
	}
}
