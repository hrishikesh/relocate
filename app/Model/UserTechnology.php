<?php
App::uses('AppModel', 'Model');
/**
 * UserSkill Model
 *
 * @property User $User
 * @property Skill $Skill
 * @property Technology $Technology
 */
class UserTechnology extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */ CONST PRIMARY_SKILLS = 1;
    CONST SECONDARY_SKILLS = 0;
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
                    if($userData['primary_skill'] != $secondarySkill){
                        $secondarySkillData = array('technology_id' => $secondarySkill, 'user_id' => $user_id);
                        $this->create();
                        $this->save($secondarySkillData);
                    }

                }
            }

        }
        return $returnSave;
    }


    /**
     * @description Save user skills
     * used at xls upload
     * @param $userId
     * @param $primarySkill
     * @param $secondarySkills
     * @return bool
     */
    public function saveUserSkills($userId, $primarySkill, $secondarySkills){
        try {
            // Change Skills to lowercase
            $primarySkill = strtolower(trim($primarySkill));
            $secondarySkills =  explode(',', $secondarySkills);

            // merge secondary with primary skill and create single array
            array_push($secondarySkills, $primarySkill);
            $skillSet = array_map(function($skill){
                return trim(strtolower($skill));
            }, $secondarySkills);

            // Make unique skills stack
            $skillSet = array_unique($skillSet);
            // Fetch Skill id and name list in lowercase
            $this->Technology->virtualFields['tech_name'] = 'LOWER(TRIM(stream_name))';
            $skillIds = $this->Technology->find('list',
                array(
                    'fields'=> array('Technology.id','Technology.tech_name'),
                    'conditions'=>array('LOWER(TRIM(Technology.stream_name))'=> $skillSet)
                )
            );

            // Delete old user skill data
            $this->deleteAll(array('user_id'=>$userId));

            // Save new user skill data
            foreach($skillIds as $skillId => $skillName){
                $userTechData = array(
                    'user_id'=>$userId,
                    'technology_id'=>$skillId,
                    'primary_skill'=> ($skillName == $primarySkill) ? self::PRIMARY_SKILLS : self::SECONDARY_SKILLS
                );

                $this->create();
                $this->save($userTechData);
            }

            return true;
        } catch(Exception $ex){
            return false;
        }
    }


    public function updateUserTechnologies($userData, $user_id){

//        pr($userData);
//        die;die
        $returnSave = false;
        if(!empty($userData)){
            $userTechnologies = $this->find('all',array('conditions'=>array('UserTechnology.user_id'=>$user_id)));
            if(!empty($userTechnologies)){
                foreach($userTechnologies as $key => $userTechnology){
                    $this->delete($userTechnology['UserTechnology']['id']);
                }

            }
            $primaryData['technology_id'] = $userData['primary_skill'];
            $primaryData['user_id'] = $user_id;
            $primaryData['primary_skill'] = 1;
            $this->create();
            if($this->save($primaryData)){
                $returnSave = true;
            }
            if(isset($userData['secondary_skill'])){

                foreach($userData['secondary_skill'] as $key => $secondarySkill){
                    if($userData['primary_skill'] != $secondarySkill){
                        $secondarySkillData = array('technology_id' => $secondarySkill, 'user_id' => $user_id);
                        $this->create();
                        $this->save($secondarySkillData);
                    }

                }
            }

        }
        return $returnSave;
    }

    public function getUserTechnologies($skill_id){
        $skilledResources = array();
        $this->recursive = -1;
        $skilledResourcesNew = $this->query("SELECT User.id , CONCAT(User.first_name,' ', User.last_name) AS first_name
                                                                FROM user_technologies AS UserTechnology
                                                                LEFT JOIN users  AS User ON User.id=UserTechnology.user_id
                                                                WHERE UserTechnology.technology_id=".$skill_id." GROUP BY UserTechnology.user_id");
        if(!empty($skilledResourcesNew)){
            foreach($skilledResourcesNew as $skilledResource){
                $skilledResources[$skilledResource['User']['id']] = $skilledResource[0]['first_name'];
            }
        }
        return $skilledResources;
    }

    public function getUserTechnologiesAllocatted($projectUsers){
        $skilledResources = array();
        if(!empty($projectUsers)){
            foreach($projectUsers as $key => $projectUser){
                $this->recursive = -1;
                $skilledResourcesNew = $this->query("SELECT User.id , CONCAT(User.first_name,' ', User.last_name) AS first_name
                                                                FROM user_technologies AS UserTechnology
                                                                LEFT JOIN users  AS User ON User.id=UserTechnology.user_id
                                                                WHERE UserTechnology.technology_id=".$projectUser['ProjectsUser']['technology_id']." GROUP BY UserTechnology.user_id");
                if(!empty($skilledResourcesNew)){
                    foreach($skilledResourcesNew as $skilledResource){
                        $skilledResources[$projectUser['ProjectsUser']['technology_id']][$skilledResource['User']['id']] = $skilledResource[0]['first_name'];
                    }
                }
            }
        }
        return $skilledResources;
    }
}
