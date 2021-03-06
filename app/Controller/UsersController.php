<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property XlsWriterComponent $XlsWriter
 * @property XlsReaderComponent $XlsReader
 * @property FileUploadComponent $FileUpload
 */
class UsersController extends AppController {


    public $components = array('FileUpload', 'XlsWriter', 'XlsReader');

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow(array('index', 'get_resources_by_skill_set','get_resources_by_skill_set_new'));
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->set(compact('tab'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        if ($this->loggedInUserId() != '' && $this->loggedInUserRole() == 1) {
            $this->redirect(array('action' => 'all_users'));
        } else {
            $this->redirect(array('action' => 'login'));
        }
    }

    public function login() {
        $loggedInUserData = $this->Auth->login();

        if ($this->loggedInUserId() == '') {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            }
        }

        if ($this->request->is('post') && !empty($this->request->data)) {
            if ($loggedInUserData) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Username or password is incorrect'), 'set_flash');
            }
        }
    }

    public function logout() {
        $this->Session->setFlash(__('You are successfully logged out from the system'), 'set_flash');
        $this->redirect($this->Auth->logout());
    }

    public function all_users($project_id = "",$technology_id = "") {

        $this->User->recursive = 0;
        if (isset($this->request->data['User']['project_id']) || ($project_id !=""&& $project_id !=0) ) {
            $project_id = isset($this->request->data['User']['project_id'])?$this->request->data['User']['project_id']:$project_id;
            $this->Session->write('project_id', $project_id);
        }
        if (isset($this->request->data['User']['technology_id']) || ($technology_id !="" && $technology_id !=0)) {
            $technology_id = isset($this->request->data['User']['technology_id'])?$this->request->data['User']['technology_id']:$technology_id;
            $this->Session->write('technology_id', $technology_id);
        }
        $project_id = $this->Session->read('project_id');
        $technology_id = $this->Session->read('technology_id');

        if ($project_id == "" && $technology_id =="") {
            $users = $this->paginate('User', array('User.role_id != ' => 1));
        } else if($project_id != "" && $technology_id =="") {
            $this->paginate = array(
                'conditions' => array('User.role_id != ' => 1),
                'joins' => array(
                    array(
                        'alias' => 'ProjectsUser',
                        'table' => 'projects_users',
                        'type' => 'RIGHT',
                        'conditions' => array('ProjectsUser.user_id=User.id', 'ProjectsUser.project_id' => $project_id)
                    )
                )
            );
            $users = $this->paginate('User');
        }else if($project_id == "" && $technology_id !="") {
            $this->paginate = array(
                'conditions' => array('User.role_id != ' => 1),
                'joins' => array(
                    array(
                        'alias' => 'UserTechnology',
                        'table' => 'user_technologies',
                        'type' => 'RIGHT',
                        'conditions' => array('UserTechnology.user_id=User.id', 'UserTechnology.technology_id' => $technology_id)
                    )
                )
            );
            $users = $this->paginate('User');
        }else if($project_id != "" && $technology_id !="") {
            $this->paginate = array(
                'conditions' => array('User.role_id != ' => 1),
                'joins' => array(
                    array(
                        'alias' => 'ProjectsUser',
                        'table' => 'projects_users',
                        'type' => 'RIGHT',
                        'conditions' => array('ProjectsUser.user_id=User.id', 'ProjectsUser.project_id' => $project_id)
                    ),
                    array(
                        'alias' => 'UserTechnology',
                        'table' => 'user_technologies',
                        'type' => 'RIGHT',
                        'conditions' => array('UserTechnology.user_id=User.id', 'UserTechnology.technology_id' => $technology_id)
                    )
                )
            );
            $users = $this->paginate('User');
        }


        $userData = $this->User->formatUser($users);
        $allProjects = $this->User->ProjectsUser->Project->find('list', array('fields' => array('id', 'project_name')));
        $allSkills = $this->User->UserTechnology->Technology->find('list', array('fields' => array('id', 'stream_name')));
        $tab = 'users';
        $this->set('users', $userData);
        $this->set(compact('tab', 'allProjects', 'project_id','allSkills','technology_id'));
    }

    public function dashboard() {

        $this->User->recursive = 0;
        $projects = $this->User->ProjectsUser->Project->getActiveProjects();

        $teams = $this->User->UserTechnology->Technology->getTechnologyUserCount();
        $projects = $this->User->ProjectsUser->Project->getProjectUserCount();
        $projects = array_values($projects);
        foreach ($projects as $key => $project) {
            unset($project['id']);
            $projects[$key] = $project['Project'];
        }
        $projects = json_encode($projects);
        foreach ($teams as $key => $team) {
            unset($team['id']);
            $teams[$key] = $team['Technology'];
        }
        $teams = json_encode($teams);
        $this->set(compact('projects', 'teams'));
    }

    public function user_dashboard() {
        $this->autoRender = false;
        $tab = 'dashboard';
        $this->set(compact('projects', 'tab'));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->read(null, $id);
        $tab = 'users';
        $this->set(compact('user', 'tab'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $userData = $this->request->data;
            $this->User->create($userData);
            if ($this->User->save()) {
                $user_id = $this->User->getLastInsertID();
                $userData['UserProfile']['user_id'] = $user_id;
                $userData['UserProfile']['date_joining'] = date('Y-m-d H:i:s',strtotime($userData['UserProfile']['date_joining']));
                $this->User->UserProfile->create($userData);
                $this->User->UserProfile->save();
                $saveSkills = $this->User->UserTechnology->saveUserTechnologies($userData['UserSkill'], $user_id);

                $this->Session->setFlash(__('The user has been saved'), 'set_flash');
                $this->redirect('/');
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'set_flash');
            }
        }
        $roles = $this->User->Role->getList();

        $skills = $this->User->UserTechnology->Technology->getAllSkills();

        $tab = 'users';
        $this->set(compact('skills', 'teams', 'roles', 'tab', 'designations', 'grades'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $userData = $this->request->data;
            if ($this->User->save($this->request->data)) {
                $this->request->data['UserProfile']['user_id'] = $this->request->data['User']['id'];
                $this->request->data['UserProfile']['date_joining'] = date('Y-m-d H:i:s', strtotime($this->request->data['UserProfile']['date_joining']));
                if($this->request->data['UserProfile']['id'] != null && $this->request->data['UserProfile']['id'] !=""){
                    $this->User->UserProfile->id = $this->request->data['UserProfile']['id'];
                }else{
                    $this->User->UserProfile->create();
                }
                $this->User->UserProfile->save($this->request->data);
                $saveSkills = $this->User->UserTechnology->updateUserTechnologies($userData['UserSkill'], $userData['User']['id']);
                $this->Session->setFlash(__('The user has been saved'), 'set_flash');
                $this->redirect('/');
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'set_flash');
            }
        } else {
            $userData = $this->User->read(null, $id);
            if (!empty($userData['UserTechnology'])) {
                $user_skills = array();
                foreach ($userData['UserTechnology'] as $key => $val) {
                    if ($val['primary_skill'] == 1) {
                        $user_skills['primary_skill'] = $val['technology_id'];
                    } else {
                        $user_skills['secondary_skill'][] = $val['technology_id'];
                    }
                }
                $userData['UserSkill'] = $user_skills;

            }

            $this->request->data = $userData;

        }
        $roles = $this->User->Role->getList();
        $skills = $this->User->UserTechnology->Technology->getAllSkills();
        $tab = 'users';
        $this->set(compact('skills', 'roles', 'tab'));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'), 'set_flash');
            $this->redirect('/');
        }
        $this->Session->setFlash(__('User was not deleted'), 'set_flash');
        $this->redirect('/');
    }

    public function change_password() {
        $role = $this->loggedInUserRole();
        $loggedInUserId = $this->loggedInUserId();
        if ($role == 1) {
            if (!empty($this->request->data)) {

                if ($this->User->checkUserCurrentPassword($loggedInUserId, $this->request->data['User']['password'])) {
                    if ($this->request->data['User']['new_password'] == $this->request->data['User']['confirm_password']) {

                        $this->request->data['User']['password'] = $this->request->data['User']['new_password'];

                        if ($this->User->save($this->request->data)) {
                            $this->Session->setFlash(__('Password has been updated'), 'set_flash');
                        } else {
                            $this->Session->setFlash(__('Password can not be updated, please try again'), 'set_flash');
                        }
                    }
                    $this->redirect(array('action' => 'dashboard'));
                } else {
                    $this->Session->setFlash(__('Invalid old password. Please enter valid old password'), 'set_flash');
                    $this->redirect($this->referer());
                }
            }
        } else {
            $this->Session->setFlash('You are not authorized user.', 'set_flash');
            $this->redirect($this->Auth->logout());
        }

    }

    public function check_availability() {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $username = null;
        $password = null;

        //checking for unique username
        if (isset($this->request->data['username'])) {
            $result = $this->User->checkUserByCount($this->request->data);
            return $result;
        } //checking for old password and new password matching at the time of changing password
        elseif (isset($this->request->data['password'])) {
            $old_user = $this->User->findById($this->loggedInUserId());
            $old = $old_user['User']['password'];
            if ($old == AuthComponent::password($this->request->data['password'])) {
                return true;
            } else {
                return false;
            }
        } elseif (isset($this->request->data['employee_id'])) {
            $result = $this->User->checkUserByEmpIdCount($this->request->data);
            return $result;
        } else {
            return false;
        }
    }

    public function get_resources_by_skill_set()
    {
        $this->layout = 'ajax';
        if (!$this->request->query['skill_id']) {
            return false;
        }
        $count = $this->request->query['count'];
        $skill_id = $this->request->query['skill_id'];
        $project_id = $this->request->query['project_id'];
        $skilledResources = $this->User->UserTechnology->getUserTechnologies(trim($this->request->query['skill_id']));
        $resource_type = $this->User->ProjectsUser->AllocationProjectType->find('list',array('conditions'=>array('type'=>'resource_type'),'fields'=>array('id','name')));
        $this->set(compact('skilledResources','resource_type','count','project_id','skill_id'));
    }

    public function get_resources_by_skill_set_new($count=0, $skill_id=1, $project_id=20)
    {

        if (!$skill_id) {
            return false;
        }
        $count = $count;
        $skill_id = $skill_id;
        $project_id = $project_id;

        $skilledResources = $this->User->UserTechnology->getUserTechnologies($skill_id);
        $resource_type = $this->User->ProjectsUser->AllocationProjectType->find('list',array('conditions'=>array('type'=>'resource_type'),'fields'=>array('id','name')));
        $this->set(compact('skilledResources','resource_type','count','project_id','skill_id'));
    }

    public function get_resources_formatted_data($resourcesData = array())
    {
        $this->layout('ajax');

        if (!$resourcesData) {
            return array();
        }
        $resource_type = $this->User->ProjectsUser->AllocationProjectType->find('list',array('conditions'=>array('type'=>'resource_type'),'fields'=>array('id','name')));
        $resources = '';
        $this->set(compact('resourcesData','resource_type'));

        //Format the data in the variable : $resourcesData
        foreach ($resourcesData as $key => $value) {
            $resources .= "<tr><input type='hidden' id='user_id' name='user_id' value='" . $value['User']['id'] . "'>";
            $resources .= "<td>" . $value['User']['first_name'] . ' ' . $value['User']['last_name'] . '(' . $value['User']['work_experience'] . ')</td>';
            $resources .= "<td><input type='text' id='percentage_allocation' name='percentage_allocation' value=''></td>";
            $resources .= "<td><input type='text' id='start_date' name='start_date' value=''></td>";
            $resources .= "<td><input type='text' id='end_date' name='end_date' value=''></td>";
            $resources .= "</tr>";
        }
        return $resources;
    }

    public function upload_user_xls() {
        try {
            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->request->data['User']['file_name']['error'] != 4) {
                    $xls = $this->FileUpload->uploadFiles('files/usersUpload/', $this->request->data['User']['file_name']
                        , null, $this->permittedXls, false);

                    if (empty($xls[0]['errors'])) {
                        $fileName = $xls[0]['urls'][0];
                        chmod(WWW_ROOT . 'files/usersUpload/' . $fileName,777);
                        $filePath = WWW_ROOT . 'files/usersUpload/' . $fileName;
                        $this->XlsReader->setHeaders(
                            array('emp_id',
                                'email',
                                'first_name',
                                'last_name',
                                'dob',
                                'doj',
                                'salary',
                                'primary_skill',
                                'secondary_skills',
                                'work_ex',
                            ));
                        $userXlData = $this->XlsReader->getExcelData($filePath);


                        if ($userXlData) {
                            // Save Data
                            $isUserDataSaved = $this->User->saveUserDataFromXls($userXlData['succeed']);
                            if ($isUserDataSaved) {
                                $this->Session->setFlash('Data saved successfully');
                            } else {
                                $this->Session->setFlash('Problem Saving Data');
                            }
                        } else {
                            $this->Session->setFlash('Data is empty');
                        }
                    } else {
                        $this->Session->setFlash('Invalid Data');
                    }
                } else {
                    $this->Session->setFlash('Problem Uploading File');
                }
                $this->redirect($this->referer());
            }
        } catch (Exception $ex) {
            $this->Session->setFlash('There is some problem occur, please try after some time');
            $this->redirect($this->referer());
        }
    }

    //Method to save Data for Project Users, from Resource Loading page (Allocations Page)
    public function saveAllocationsData() {


        if ($this->request->is('post') && !empty($this->request->data)) {

            $saveUser = false;
            $saveFalse = false;
            $userAllocationData = $this->request->data;
            foreach($userAllocationData['ProjectUser'] as $key => $projectUsr){
                if($projectUsr['user_id']!="" && $projectUsr['percentage_of_allocation']!=""){
                    $projectUsr['start'] = date('Y-m-d H:i:s',strtotime($projectUsr['start']));
                    $projectUsr['end'] = date('Y-m-d H:i:s',strtotime($projectUsr['end']));

                    $saveUserData  = $this->User->ProjectsUser->saveData($projectUsr);
                    if($saveUserData){
                        $saveUser = true;
                    }else{
                        $saveFalse = true;
                    }
                }

            }
            if ($saveUser) {
                $this->Session->setFlash('Allocation data saved.');
                $this->redirect(array('controller' => 'projects', 'action' => 'all_projects'));

            } else {
                $this->Session->setFlash('There is some problem occur, please try after some time');
                $this->redirect(array('controller' => 'projects', 'action' => 'all_projects'));
            }
        }
    }

    /**
     * @description Download xls
     * @param int $projectId
     */
    public function export_users($projectId = 0){
        $conditions = array();
        if(!empty($projectId) && $projectId !=0 && $projectId !=null) {
            $conditions['Project.id'] = $projectId;
        }
        $userData = $this->User->exportUsersData($conditions);
        $this->XlsWriter = $this->Components->load('XlsWriter');

        $xlsName = 'EmployeesData';
        $workSheetName = 'Employees xls';
        $dataKey = key($userData[0]);
        $fields = array_keys($userData[0][$dataKey]);

        $worksheet = $this->XlsWriter
            ->createWorkbook()
            ->createWorksheet($workSheetName);
        $worksheet->freezePanes(array(1, 0, 1, 0));

        $this->XlsWriter->addXlsHeader($fields);
        foreach ($userData as $data) {
            $excelData = $this->XlsWriter->replaceNulls($data[$dataKey]);
            $this->XlsWriter->addXlsRecord($excelData);
        }
        /*Download excel sheet*/
        $this->XlsWriter->download($xlsName);
    }
}