<?php
App::uses('AppModel', 'Model');
/**
 * Technology Model
 *
 * @property ProjectResourceRequirement $ProjectResourceRequirement
 * @property User $User
 */
class Technology extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ProjectResourceRequirement' => array(
			'className' => 'ProjectResourceRequirement',
			'foreignKey' => 'technology_id',
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
		'UserTechnology' => array(
			'className' => 'UserTechnology',
			'foreignKey' => 'technology_id',
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

    public  $actsAs = array('Sluggable' => array(
        'label'=>'stream_name',
        'slug'=>'slug',
        'separator'=>'-',
        'overwrite'=>false
    ));


    public function getList(){
        return $this->find('list',array(
            'fields'=>array('id','stream_name')
        ));
    }

    public function getAllSkills(){
        $this->recursive = -1;
        return $this->find('list', array('fields' => array('id', 'stream_name')));
    }

    public function getSkills(){
        return $this->find('list',array('fields' => array('id', 'stream_name')));
    }

    public function getTechnologyUserCount() {
        $this->virtualFields['employee_count'] = 'count(UserTechnology.user_id)';

        $result = $this->find('all',array(
            'fields' => array(
                'Technology.id','Technology.stream_name','Technology.employee_count'
            ),
            'joins' => array(
                array(
                    'table' => 'user_technologies',
                    'alias' => 'UserTechnology',
                    'type'  => 'LEFT',
                    'conditions' =>array(
                        'Technology.id = UserTechnology.technology_id'
                    )
                )
            ),
            'group'=> array(
                'Technology.id'
            ),
            'recursive' => -1
        ));
        return $result;
    }

    public function getProjectAllocationStats($projectId){
        $this->virtualFields['employee_count'] = 'COUNT(User.id)';
        $result = $this->find('all', array(
            'fields'=>array(
                'Technology.id',
                'Technology.stream_name',
                'Technology.employee_count'
            ),
            'joins'=>array(

                array(
                    'table' => 'project_resource_requirement',
                    'alias' => 'ProjectResourceRequirement',
                    'type' => 'RIGHT',
                    'conditions' => array(
                        'Technology.id = ProjectResourceRequirement.technology_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Technology.id = User.technology_id',
                    )
                ),
                array(
                    'table' => 'projects_users',
                    'alias' => 'ProjectsUser',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'User.id = ProjectsUser.user_id',
                    )
                )

            ),
            'conditions'=>array(
                'ProjectResourceRequirement.project_id'=>$projectId
            ),
            'group'=>array('Technology.id'),
            'recursive' => -1
        ));
        return $result;
    }
}
