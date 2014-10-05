<?php
App::uses('AppModel', 'Model');
/**
 * Grade Model
 *
 * @property OrganisationHierarchy $OrganisationHierarchy
 * @property UserProfile $UserProfile
 */
class Grade extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'OrganisationHierarchy' => array(
			'className' => 'OrganisationHierarchy',
			'foreignKey' => 'grade_id',
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
			'foreignKey' => 'grade_id',
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
            'fields'=>array('id','abbreviation')
        ));
    }

}
