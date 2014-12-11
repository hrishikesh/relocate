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
class UsersController extends AppController
{


    public $components = array('FileUpload', 'XlsWriter', 'XlsReader');
    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow(array('index'));
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->set(compact('tab'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        if ($this->loggedInUserId() != '' && $this->loggedInUserRole() == 1) {
            $this->redirect(array('action' => 'all_users'));
        } else {
            $this->redirect(array('action' => 'login'));
        }
    }

    public function login()
    {
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

    public function logout()
    {
        $this->Session->setFlash(__('You are successfully logged out from the system'), 'set_flash');
        $this->redirect($this->Auth->logout());
    }

    public function all_users()
    {
        $this->User->recursive = 0;
        $users = $this->paginate('User', array('User.role_id != ' => 1));
        $tab = 'users';
        $this->set(compact('users', 'tab'));
    }

    public function dashboard()
    {
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


    public function user_dashboard()
    {
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
    public function view($id = null)
    {
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
    public function add()
    {
        if ($this->request->is('post')) {
            $userData = $this->request->data;
            $this->User->create($userData);
            if ($this->User->save()) {
                $user_id = $this->User->getLastInsertID();
                $userData['UserProfile']['user_id'] = $user_id;
                $this->User->UserProfile->create($userData);
                $this->User->UserProfile->save();
                $saveSkills = $this->User->UserTechnology->saveUserTechnologies($userData['UserSkill'], $user_id);
//                if(!empty($userData['UserPreviousExperience'])) {
//                    $experienceCount = 0;
//                    foreach($userData['UserPreviousExperience'] as $experienceKey=>$experienceValue){
//                        $previousExperience[$experienceCount]['user_id'] = $user_id;
//                        $previousExperience[$experienceCount]['start_date'] =date('Y-m-d H:i:s', strtotime($experienceValue['start_date']));
//                        $previousExperience[$experienceCount]['end_date'] =date('Y-m-d H:i:s', strtotime($experienceValue['start_date']));
//                        $previousExperience[$experienceCount]['company_name'] =$experienceValue['company_name'];
//                        $previousExperience[$experienceCount]['description'] =$experienceValue['description'];
//                        $experienceCount = $experienceCount+1;
//                    }
//                    unset($userData['UserPreviousExperience']);
//                    $userData['UserPreviousExperience'] = $previousExperience;
//                    $this->User->UserPreviousExperience->saveAll($userData['UserPreviousExperience']);
//                }
                $this->Session->setFlash(__('The user has been saved'), 'set_flash');
                $this->redirect('/');
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'set_flash');
            }
        }
        $roles = $this->User->Role->getList();
//        $teams = $this->User->UserProfile->Team->getList();
        $skills = $this->User->UserTechnology->Technology->getAllSkills();
//        $designations = $this->User->UserProfile->Designation->getList();
//        $grades = $this->User->UserProfile->Grade->getList();
        $tab = 'users';
        $this->set(compact('skills', 'teams', 'roles', 'tab', 'designations', 'grades'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'), 'set_flash');
                $this->redirect('/');
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'set_flash');
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            $technologies = $this->User->Technology->getList();
            $tab = 'users';
            $this->set(compact('technologies', 'tab'));
        }
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
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

    public function change_password()
    {
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

    public function check_availability()
    {
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

    public function getResourcesBySkillSet()
    {
        $this->autoRender = false;
        $this->layout = false;

        if (!$this->request->query['skill_id']) {
            return false;
        }

        $formattedData = array();
        $skilledResources = $this->User->UserTechnology->find('all', array(
            'conditions' => array(
                'technology_id' => trim($this->request->query['skill_id']
                ))
        ));

        //Get formatted Data, and pass to View.
        $formattedData = $this->getResourcesFormattedDataBySkill($skilledResources);
        $this->set(compact('formattedData'));

    }

    //@todo : Move this to User Model
    public function getResourcesFormattedDataBySkill($resourcesData = array())
    {
        if (!$resourcesData) {
            return array();
        }

        //Format the data in the variable : $resourcesData
        //WHAT IS THE FORMAT
    }

    public function upload_user_xls(){
        try{
            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->request->data['User']['file_name']['error'] != 4) {
                    $xls = $this->FileUpload->uploadFiles('files/usersUpload/', $this->request->data['User']['file_name']
                        , null, $this->permittedXls, false);

                    if (empty($xls[0]['errors'])) {
                        $fileName = $xls[0]['urls'][0];
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
                                'work_ex'
                            ));
                        $userXlData = $this->XlsReader->getExcelData($filePath);


                        if($userXlData) {
                            // Save Data
                            $isUserDataSaved = $this->User->saveUserDataFromXls($userXlData['succeed']);
                            if($isUserDataSaved){
                                $this->Session->setFlash('Data saved successfully');
                            }else{
                                $this->Session->setFlash('Problem Saving Data');
                            }
                        }else{
                            $this->Session->setFlash('Data is empty');
                        }
                    }else{
                        $this->Session->setFlash('Invalid Data');
                    }
                }else{
                    $this->Session->setFlash('Problem Uploading File');
                }
                $this->redirect($this->referer());
            }
        }catch (Exception $ex){
            $this->Session->setFlash('There is some problem occur, please try after some time');
            $this->redirect($this->referer());
        }
    }
}