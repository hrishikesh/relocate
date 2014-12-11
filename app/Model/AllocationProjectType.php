<?php
App::uses('AppModel', 'Model');
/**
 * AllocationProjectType Model
 *
 */
class AllocationProjectType extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
     public $hasMany = array(
         'Project' => array(
             'className' => 'Project',
             'foreignKey' => 'project_type_id',
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
         'ProjectsUser' => array(
             'className' => 'ProjectsUser',
             'foreignKey' => 'resource_type_id',
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

    public function getProjectType(){
        $this->recursive = -1;
        return $this->find('list', array('conditions'=>array('type'=>'project_type'),'fields' => array('id', 'name')));
    }

}
