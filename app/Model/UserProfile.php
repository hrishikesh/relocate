<?php
App::uses('AppModel', 'Model');
/**
 * UserProfile Model
 *
 * @property Grade $Grade
 * @property Designation $Designation
 * @property Team $Team
 * @property User $User
 */
class UserProfile extends AppModel {

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
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public function  saveProfile($user_id, $doj){
        if($doj != "" && $doj != null){
            $data['user_id'] = $user_id;
            $data['date_joining'] = date('Y-m-d H:i:s', strtotime($doj)) ;
            $this->create();
            $this->save($data);
        }
        return true;
    }
}
