<?php
class CreateProjectAccountsTable extends CakeMigration {

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
                'project_accounts' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
                    'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
                    'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
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
                    'account_name' => 'project_account_id'
                )
            ),
            'alter_field' => array(
                'projects' => array(
                    'project_account_id' => array('type' => 'integer', 'length' => 11)
                )
            ),
		),
		'down' => array(
            'drop_table'=>array('allocation_project_types'),
            'rename_field' => array(
                'projects' => array(
                    'project_account_id' => 'account_name'
                )
            ),
            'alter_field' => array(
                'projects' => array(
                    'account_name' => array('type' => 'varchar', 'length' => 255)
                )
            ),
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
            $ProjectAccount = $this->generateModel('ProjectAccount');
            $Project = $this->generateModel('Project');
            $project_accounts = array('drf','usaf','cognyte','fanrag');

            foreach($project_accounts as $key=>$val){
                $typeData = '';
                $typeData = array(
                    'name' => $val,
                    'slug' => $val,
                    'is_active' => 1
                );
                $ProjectAccount->create();
                if ($ProjectAccount->save($typeData)) {
                    $this->log('>>> Inside Migrations | SUCCESS : Master data entry done.');
                } else {
                    $this->log('>>> Inside Migrations | FAILED : Master data entry could not be done.');
                }

            }
            $query = "Update projects SET project_account_id=1 WHERE 1";
            if($Project->query($query)){
                $this->log('>>> Inside Migrations | SUCCESS : project type migrated.');
            } else {
                $this->log('>>> Inside Migrations | FAILED : project type could not migrate.');
            }

        }
		return true;
	}
}
