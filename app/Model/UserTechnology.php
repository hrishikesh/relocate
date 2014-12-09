<?php
App::uses('AppModel', 'Model');
/**
 * UserSkill Model
 *
 * @property User $User
 * @property Skill $Skill
 */
class UserTechnology extends AppModel {

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
		'Technology' => array(
			'className' => 'Technology',
			'foreignKey' => 'technology_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public function saveUserTechnologies($userData,$user_id){
        $returnSave = false;
        if(!empty($userData)){
            $primaryData['technology_id'] = $userData['primary_skill'];
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
                    $secondarySkillData = array('technology_id' => $secondarySkill['secondary_skill'], 'user_id' => $user_id);
                    $this->create();
                    $this->save($secondarySkillData);

                }
            }

        }
        return $returnSave;
    }


}
