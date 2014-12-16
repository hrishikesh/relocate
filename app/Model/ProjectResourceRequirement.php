<?php
App::uses('AppModel', 'Model');
/**
 * ProjectResourceRequirement Model
 *
 * @property Project $Project
 * @property Technology $Technology
 */
class ProjectResourceRequirement extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'project_resource_requirement';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Technology' => array(
			'className' => 'Technology',
			'foreignKey' => 'technology_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


    public function saveProjectReq($resourceRequirements,$project_id=null){


        foreach($resourceRequirements as $resourceRequirement) {

            if($resourceRequirement['skill_id']!="" && $resourceRequirement['no_of_resources']!=""){
                $resourceRequirement['start_date'] = date('Y-m-d H:i:s', strtotime($resourceRequirement['start_date']));
                $resourceRequirement['end_date'] = date('Y-m-d H:i:s', strtotime($resourceRequirement['end_date']));
                $resourceRequirement['project_id'] = $project_id;
                if(isset($resourceRequirements['id']) && !empty($resourceRequirements['id'])){
                    $this->id=$resourceRequirements['id'];
                }else{

                    $this->create();
                }

                $this->save($resourceRequirement);
            }

        }

        return true;
    }
}
