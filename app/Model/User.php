<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property UserTechnology $UserTechnology
 * @property Role $Role
 * @property ProjectsUser $ProjectsUser
 */
class User extends AppModel
{
    /**
     * Validation rules
     *
     * @var array
     */

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(

        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''

        )
    );
    public $hasMany = array(
        'ProjectsUser' => array(
            'className' => 'ProjectsUser',
            'foreignKey' => 'user_id',
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
        'UserPreviousExperience' => array(
            'className' => 'UserPreviousExperience',
            'foreignKey' => 'user_id',
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
        'UserTechnology' => array(
            'className' => 'UserTechnology',
            'foreignKey' => 'user_id',
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
    public $hasOne = array(
        'UserProfile' => array(
            'className' => 'UserProfile',
            'foreignKey' => 'user_id',
            'dependent' => true,
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

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }


    public function checkUserCurrentPassword($user_id, $old_password)
    {
        $user = $this->isExist(array('id' => $user_id, 'password' => AuthComponent::password($old_password)));
        return $user;
    }

    public function checkUserByCount($data = null)
    {
        return $this->isExist(array('username' => $data['username']));
    }

    public function checkUserByEmpIdCount($data)
    {
        return $this->isExist(array('employee_id' => $data['employee_id']));
    }


    public function formatUser($users)
    {
        foreach ($users as $key => $user) {

            unset($users[$key]['UserTechnology']);
            unset($users[$key]['ProjectsUser']);
            $this->UserTechnology->recursive = 0;
            $this->UserTechnology->unbindModel(array('belongsTo' => array('User')));
            $users[$key]['UserTechnology'] = $this->UserTechnology->find('all', array('conditions' => array('UserTechnology.user_id' => $user['User']['id']), 'group' => array('UserTechnology.technology_id')));
            $this->ProjectsUser->recursive = 0;
            $this->ProjectsUser->unbindModel(array('belongsTo' => array('User')));
            $users[$key]['ProjectsUser'] = $this->ProjectsUser->find('all', array('conditions' => array('ProjectsUser.user_id' => $user['User']['id']), 'group' => array('ProjectsUser.project_id')));
        }
        return $users;
    }

    /**
     * @description Save Xl uploaded user data in DB
     * @param $userXlsData
     * @return bool
     */
    public function saveUserDataFromXls($userXlsData)
    {
        try {
            foreach ($userXlsData as $data) {
                $nullChecks = array('', null, '0', '-');
                if (!isset($data['email']) ||
                    empty($data['email']) ||
                    in_array($data['email'], $nullChecks)
                ) {
                    continue;
                }
                $usersData = array(
                    'username' => $data['email'],
                    'employee_id' => $data['emp_id'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'date_of_birth' => $data['dob'],
                    'salary' => $data['salary'],
                    'work_experience' => $data['work_ex'],
                    'role_id' => 2,
                    'is_active' => 1,
                    'is_verified' => 1

                    //'date_of_birth' => $data['dob'],
                );

                // Check if email exist then update record
                if ($userId = $this->isEmailExists($data['email'])) {
                    $usersData['id'] = $userId;
                }

                $this->create();
                $this->save($usersData);

                if (!$userId) {
                    $userId = $this->getLastInsertID();
                }

                $this->UserTechnology->saveUserSkills($userId, $data['primary_skill'], $data['secondary_skills']);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function isEmailExists($emailId)
    {
        return $this->field('id', array('username' => $emailId));
    }

    /**
     * @description This methods extracts users data from various related tables
     * @param array $conditions
     * @return array
     */
    public function exportUsersData($conditions = array()){

        $this->virtualFields['projects'] = 'GROUP_CONCAT(DISTINCT Project.project_name)';
        $this->virtualFields['primary_skill'] = 'GROUP_CONCAT(DISTINCT Technology.stream_name)';
        $this->virtualFields['secondary_skills'] = 'GROUP_CONCAT(DISTINCT SecondaryTechnology.stream_name)';
        $this->virtualFields['date_joining'] = 'UserProfiles.date_joining';


        $joins = array(
            array(
                'table' => 'user_profiles',
                'alias' => 'UserProfiles',
                'type' => 'LEFT',
                'conditions' => array('User.id = UserProfiles.user_id')
            ),
            array(
                'table' => 'user_technologies',
                'alias' => 'UserTechnology',
                'type' => 'LEFT',
                'conditions' => array(
                    'User.id = UserTechnology.user_id',
                    'UserTechnology.primary_skill = 1'
                )
            ),
            array(
                'table' => 'user_technologies',
                'alias' => 'SecondaryUserTechnology',
                'type' => 'LEFT',
                'conditions' => array(
                    'User.id = SecondaryUserTechnology.user_id'
                )
            ),
            array(
                'table' => 'technologies',
                'alias' => 'Technology',
                'type' => 'LEFT',
                'conditions' => array(
                    'UserTechnology.technology_id = Technology.id'
                )
            ),
            array(
                'table' => 'technologies',
                'alias' => 'SecondaryTechnology',
                'type' => 'LEFT',
                'conditions' => array(
                    'SecondaryUserTechnology.technology_id = SecondaryTechnology.id'
                )
            ),
            array(
                'table' => 'projects_users',
                'alias' => 'ProjectUser',
                'type' => 'LEFT',
                'conditions' => array(
                    'User.id = ProjectUser.user_id',
                    /*'DATE(ProjectUser.start) <= CURDATE()',
                    'DATE(ProjectUser.end) >= CURDATE()'*/
                )
            ),
            array(
                'table' => 'projects',
                'alias' => 'Project',
                'type' => 'LEFT',
                'conditions' => array(
                    'ProjectUser.project_id = Project.id'
                )
            )

        );
        $this->recursive = -1;
        $conditions['User.id !='] = 1;
        $users = $this->find('all',
            array(
                'fields'=>array(
                    'User.employee_id',
                    'User.username',
                    'User.first_name',
                    'User.last_name',
                    'User.projects',
                    'User.date_of_birth',
                    'User.date_joining',
                    'User.salary',
                    'User.primary_skill',
                    'User.secondary_skills',
                    'User.work_experience',

                ),
                'joins'=>$joins,
                'conditions'=> $conditions,
                'group'=> array('User.id')
            ));


        return $users;

    }
}
