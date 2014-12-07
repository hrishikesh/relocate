<?php
App::uses('AppModel', 'Model');
/**
 * Skill Model
 *
 */
class Skill extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
    public $hasMany = array(
        'UserSkill' => array(
            'className' => 'UserSkill',
            'foreignKey' => 'skill_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'modified' => array(
			'n' => array(
				'rule' => array('n'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

    public function getAllSkills(){
        $this->recursive = -1;
        return $this->find('list', array('fields' => array('id', 'name')));
    }

    public function getSkills(){
        return $this->find('list');
    }
}
