<?php
App::uses('AppModel', 'Model');
/**
 * Skill Model
 *
 */
class Skill extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

    public function getSkills(){
        return $this->find('list');
    }
}
