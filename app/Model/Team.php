<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property Stream $Stream
 * @property OrganisationHierarchy $OrganisationHierarchy
 * @property UserProfile $UserProfile
 */
class Team extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Stream' => array(
			'className' => 'Stream',
			'foreignKey' => 'stream_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'OrganisationHierarchy' => array(
			'className' => 'OrganisationHierarchy',
			'foreignKey' => 'team_id',
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
			'foreignKey' => 'team_id',
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
