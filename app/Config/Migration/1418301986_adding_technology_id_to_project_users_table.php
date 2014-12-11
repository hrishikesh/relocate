<?php
class AddingTechnologyIdToProjectUsersTable extends CakeMigration {

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
            'create_field' => array(
                'projects_users' => array(
                    'technology_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11 , 'after' => 'percentage_of_allocation')
                ),
            )
        ),
        'down' => array(
            'drop_field' => array(
                'projects_users' => array('technology_id')
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
        return true;
    }
}
