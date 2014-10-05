<?php
App::uses('AppModel', 'Model');
/**
 * UserSkill Model
 *
 * @property User $User
 * @property Skill $Skill
 */
class UserSkill extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Skill' => array(
			'className' => 'Skill',
			'foreignKey' => 'skill_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public function saveUserSkills($userData,$user_id){
        $returnSave = false;
        if(!empty($userData)){
            $primaryData['skill_id'] = $userData['primary_skill'];
            $primaryData['user_id'] = $user_id;
            $primaryData['primary_skill'] = 1;
            $this->create();
            if($this->save($primaryData)){
                $returnSave = true;
            }
            if(isset($userData['secondary_skill'])){
                $primary_key = array_search($userData['primary_skill'], $userData['secondary_skill']);
                if($primary_key){
                    unset($userData['secondary_skill'][$primary_key]);
                }
                foreach($userData['secondary_skill'] as $key => $secondarySkill){
                    $secondarySkill = array('skill_id' => $userData['primary_skill'], 'user_id' => $user_id);
                    $this->create();
                    $this->save($secondarySkill);

                }
            }


        }
        return $returnSave;
    }
}
