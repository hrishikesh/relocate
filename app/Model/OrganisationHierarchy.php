<?php
App::uses('AppModel', 'Model');
/**
 * OrganisationHierarchy Model
 *
 * @property Grade $Grade
 * @property Year $Year
 * @property Designation $Designation
 * @property Team $Team
 */
class OrganisationHierarchy extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'organisation_hierarchy';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Grade' => array(
			'className' => 'Grade',
			'foreignKey' => 'grade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Year' => array(
			'className' => 'Year',
			'foreignKey' => 'year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Designation' => array(
			'className' => 'Designation',
			'foreignKey' => 'designation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'team_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
