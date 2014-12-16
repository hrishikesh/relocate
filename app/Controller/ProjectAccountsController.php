<?php
App::uses('AppController', 'Controller');
/**
 * ProjectAccounts Controller
 *
 * @property ProjectAccount $ProjectAccount
 */
class ProjectAccountsController extends AppController {


    public function beforeRender()
    {
        parent::beforeRender();
        if ($this->loggedInUserId() != '') {
            $tab = 'accounts';
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
	public function index() {
		$this->ProjectAccount->recursive = 0;

		$this->set('projectAccounts', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->ProjectAccount->id = $id;
		if (!$this->ProjectAccount->exists()) {
			throw new NotFoundException(__('Invalid project account'));
		}
		$this->set('projectAccount', $this->ProjectAccount->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProjectAccount->create();
			if ($this->ProjectAccount->save($this->request->data)) {
				$this->Session->setFlash(__('The project account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project account could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->ProjectAccount->id = $id;
		if (!$this->ProjectAccount->exists()) {
			throw new NotFoundException(__('Invalid project account'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ProjectAccount->save($this->request->data)) {
				$this->Session->setFlash(__('The project account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project account could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ProjectAccount->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
//		if (!$this->request->is('post')) {
//			throw new MethodNotAllowedException();
//		}
		$this->ProjectAccount->id = $id;
		if (!$this->ProjectAccount->exists()) {
			throw new NotFoundException(__('Invalid project account'));
		}
		if ($this->ProjectAccount->delete()) {
			$this->Session->setFlash(__('Project account deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project account was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
