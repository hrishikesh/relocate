<?php
App::uses('AppModel', 'Model');
/**
 * Designation Model
 *
 * @property OrganisationHierarchy $OrganisationHierarchy
 * @property UserProfile $UserProfile
 */
class Designation extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'OrganisationHierarchy' => array(
			'className' => 'OrganisationHierarchy',
			'foreignKey' => 'designation_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'UserProfile' => array(
			'className' => 'UserProfile',
			'foreignKey' => 'designation_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    public function getList(){
        return $this->find('list',array(
            'fields'=>array('id','name')
        ));
    }

}
