<?php
App::uses('AppController', 'Controller');
/**
 * Projects Controller
 *
 * @property Project $Project
 */
class ProjectsController extends AppController
{

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Html', 'Form');


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index','project_stats','add_project_resource','get_project_details'));
    }

    public function beforeRender()
    {
        parent::beforeRender();
        if ($this->loggedInUserId() != '') {
            $tab = 'projects';
        } else {
            $tab = '';
        }
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
            $this->redirect(array('action' => 'all_projects'));
        } else {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    /**
     * index method
     *
     * @return void
     */
    public function all_projects($account_id = "")
    {
        if (isset($this->request->data['Project']['account_id']) || ($account_id !=""&& $account_id !=0)) {
            $account_id = isset($this->request->data['Project']['account_id'])?$this->request->data['Project']['account_id']:$account_id;
            $this->Session->write('account_id', $account_id);
        }
        $account_id = $this->Session->read('account_id');
        $this->Project->recursive = 1;
        if ($account_id == "" ) {
            $projects= $this->paginate('Project');
        } else {
            echo $account_id;
            $this->paginate = array(
                'conditions' => array('Project.start_date <= ' => date('Y-m-d H:i:s'),'Project.end_date >= ' => date('Y-m-d H:i:s'),'Project.project_account_id'=>$account_id),
            );
            $projects = $this->paginate('Project');
        }

        $allAccounts = $this->Project->ProjectAccount->getAccounts();
        $this->set(compact('allAccounts','account_id','projects'));

    }


    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        $project = $this->Project->getProjectDataById($id);
        $this->set(compact('project'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {

            $this->request->data['Project']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Project']['start_date']));
            $this->request->data['Project']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Project']['end_date']));

            $projectResourceRequirement = $this->request->data['ProjectResourceRequirements'];
            $this->Project->create();
            $technologyAlloted = array();
            if ($this->Project->save($this->request->data)) {
                $this->log('>>>> SUCCESS : Saved Project Data');
                $projectId = $this->Project->id;
                if ($this->Project->ProjectResourceRequirement->saveProjectReq($projectResourceRequirement, $projectId)) {
                    $this->log('>>>> SUCCESS : Saved ProjectResourceRequirement Data');
                    $this->Session->setFlash(__('The project has been saved'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->log('>>>> FAILED : Unable to save ProjectResourceRequirement Data');
                }
            } else {
                $this->log('>>>> FAILED : Unable to save Project Data');
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'));
            }
        }
        $projectType = $this->Project->AllocationProjectType->getProjectType();
        $project_accounts = $this->Project->ProjectAccount->getAccounts();
        $this->loadModel('Technology');
        $skills = $this->Technology->getSkills();
        $this->set(compact('skills', 'projectType','project_accounts'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if (($this->request->is('post') || $this->request->is('put') && !empty($this->request->data))) {
            $this->request->data['Project']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Project']['start_date']));
            $this->request->data['Project']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Project']['end_date']));

            if ($this->Project->saveAll($this->request->data)) {

                $projectResourceRequirement = $this->request->data['ProjectResourceRequirements'];

                if ($this->Project->ProjectResourceRequirement->saveProjectReq($projectResourceRequirement, $this->request->data['Project']['id'])) {
                    $this->log('>>>> SUCCESS | ProjectResourceRequirement data saved');
                } else {
                    $this->log('>>>> FAILED | ProjectResourceRequirement data could not be saved');
                }
                $this->Session->setFlash(__('The project has been saved'), 'set_flash');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The project could not be saved. Please, try again.'), 'set_flash');
            }
        }

        $this->request->data = $this->Project->read(null, $id);

        $this->log($this->request->data);
        $project_id = $id;
        $projectType = $this->Project->AllocationProjectType->getProjectType();
        $project_accounts = $this->Project->ProjectAccount->getAccounts();
        $this->loadModel('Technology');
        $skills = $this->Technology->getSkills();
//        $projectLeads = $this->User->find('list', array('fields'=>array('id', 'first_name')));
//        $ba = $this->User->find('list', array('fields'=>array('id', 'first_name')));
        $this->set(compact('skills', 'projectType','project_id','project_accounts'));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            throw new NotFoundException(__('Invalid project'));
        }
        if ($this->Project->delete()) {
            $this->Session->setFlash(__('Project deleted'), 'set_flash');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Project was not deleted'), 'set_flash');
        $this->redirect(array('action' => 'index'));
    }

    public function getAllTechnologies()
    {
        $this->autoRender = false;
        if ($this->request->is('get') && empty($this->request->data)) {
            $technologies = array();
            $technologies = $this->Project->getTechnologyData();
            if (!empty($technologies)) {
                return json_encode($technologies);
            } else {
                return json_encode(array());
            }
        }
    }

    public function add_project_resource()
    {

        $this->autoRender = false;
        if (!empty($this->request->data) && $this->request->data['user_id'] != "" && $this->request->data['project_id']) {
            $saveProjectUser = $this->Project->saveProjectUser($this->request->data);
            if ($saveProjectUser) {
                $respoceArray = array('status' => 'success', 'message' => 'users allocated successfully.');
            } else {
                $respoceArray = array('status' => 'error', 'message' => 'User already added for this project');
            }

        } else {
            $respoceArray = array('status' => 'error', 'message' => 'Required fields are missing');
        }
        return json_encode($respoceArray);
    }

    public function project_stats()
    {
        $projects = $this->Project->getActiveProjects();
        $firstProject = $projects[0];

        //$technologiesWiseData = $this->Project->ProjectTechnology->Technology->getProjectAllocationStats($firstProject['Project']['id']);
        //$technologyData =  $this->getFormattedData($technologiesWiseData);

        $this->set(compact('projects', 'technologyData'));
    }

    public function get_project_details()
    {
        $this->autoRender = false;
        $this->layout = false;
        $technologyData = $this->Project->ProjectTechnology->Technology->getProjectAllocationStats($this->request->query['project_id']);
        if (!empty($technologiesWiseData)) {

            /*
                        $chartData = $this->getFormattedData($technologiesWiseData);

                        echo $chartData;*/
        } else {
            /* echo json_encode(array('status'=>'0'));*/
        }
        $technologyData = $this->getFormattedData($technologyData);
        $this->set(compact('technologyData'));
        echo $this->render('/Elements/project_stats_chart');
        die;
    }

    private function getFormattedData($technologiesWiseData)
    {
        $technologiesWiseData = array_values($technologiesWiseData);
        foreach ($technologiesWiseData as $key => $technology) {
            unset($technology['id']);
            $technologies[$key] = $technology['Technology'];
        }
        if (!empty($technologies)) {
            $technologies = json_encode($technologies);
        } else {
            $technologies = json_encode(array());
        }


        return $technologies;
    }

    public function allocate($id)
    {
        if (!$id) {
            $this->redirect(array('action' => 'all_projects'));
        }
        $projectDetails = $this->Project->getProjectNameAndAccountName($id);

        $this->Project->ProjectResourceRequirement->recursive = 0;
        $allocationMatrix = $this->Project->ProjectResourceRequirement->find('all', array(
            'conditions' => array('project_id' => $id),
            'fields' => array(
                'Technology.id',
                'Technology.stream_name',
                'ProjectResourceRequirement.required_percentage',
                'ProjectResourceRequirement.no_of_resources'
            )
        ));
        $project_id = $id;
        $joins = array(
            array(
                'table' => 'user_technologies',
                'alias' => 'UserTechnology',
                'type' => 'LEFT',
                'conditions' => array(
                    'UserTechnology.user_id'=>'User.id'
                ),
            )
        );
        $this->loadModel('UserTechnology');
        $projectUsers = $this->Project->ProjectsUser->find('all',array('conditions'=>array('ProjectsUser.project_id'=>$project_id)));
        $skilledResources = $this->UserTechnology->getUserTechnologiesAllocatted($projectUsers);
        $resource_type = $this->Project->ProjectsUser->AllocationProjectType->find('list',array('conditions'=>array('type'=>'resource_type'),'fields'=>array('id','name')));
        $this->log($skilledResources);
        $this->log($projectUsers);
        $this->set(compact('allocationMatrix' , 'projectDetails','id','skilledResources','resource_type','projectUsers'));
    }

    /**
     * @description Download xls
     * @param int $projectId
     */
    public function export_project_report(){

    }

    public function export_report(){
        $this->layout = 'report';
        if($this->request->is('post'))
        {
            pr($this->request->data);
            die;
            $conditions = array();
            if(!empty($this->request->data['Project']['start_date']) && !empty($this->request->data['Project']['end_date'])) {
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
}
