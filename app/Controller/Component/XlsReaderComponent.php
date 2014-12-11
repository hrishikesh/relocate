<?php
/**
 * Created by Webonise Lab.
 * User: Priyanka Bhoir <priyanka.bhoir@weboniselab.com>
 * Date: 21/11/13 6:25 PM
 */
App::uses('Component','Controller');
App::import('vendor','upload',array('file'=>'ImportXls'.DS.'Lib'.DS.'ReadXls.php'));
App::import('vendor','validator',array('file'=>'ImportXls'.DS.'Lib'.DS.'XlsValidator.php'));

class XlsReaderComponent extends Component{
    public $components=array('FileUpload');
    protected $headers=array();
    protected $primaryField=null;
    protected $rules=array();
    protected $fileToUpload=null;
    private $invalidRule=true;
    public function initialize($controller) {
    }

    public function startup() {
    }

    public function beforeRender() {
    }

    public function beforeRedirect() {
    }

    public function shutdown() {
    }

    /**
     *
     * @param $headers
     * @description sets headers of the excel sheet
     */
    public function setHeaders($headers){
        if(!empty($headers)){
            $this->headers=$headers;
        }
    }
    public function setPrimaryField($primaryField){
        if(!empty($primaryField)&& !is_array($primaryField)){
            $this->primaryField=$primaryField;
            return true;
        }
        $this->primaryField=null;
        return false;
    }
    /**
     * @param null $rules
     * @return bool
     * @description : method sets rules for validating the data in excel sheet
     *                if header mention with rule does not match with $this->headers it will return false and will not validate the data using those rules
     *
     */
    public function setRules($rules=null){

        if(!empty($rules)){
            $ruleHeaders=array_keys($rules);
            foreach($ruleHeaders as $ruleHeader){
                if(!in_array($ruleHeader,$this->headers)){
                    $this->invalidRule=true;
                    return false;
                }
            }
            $this->invalidRule=false;
            $this->rules=$rules;
            return true;
        }
        $this->invalidRule=true;
        return false;
    }

    /**
     * @param $folder
     * @param $formdata
     * @param $itemId
     * @param $permitted
     * @param $multifile
     * @return array : returns $data['succeed']: rows which satisfies all rule
     *                         $data['failed']: rows which fails to satisfy rule
     * @description :
     *                1.read data from the excel sheet
     *                2.validates data if rules are provided by setRules() method
     */
    public function getExcelData($inputFileName) {
        $readXls         = new ReadXls($inputFileName, array('headers' => $this->headers, 'primaryField' => $this->primaryField));
        $data= $readXls->readExcelData();

        if (isset($data['succeed']) && is_array($data['succeed']) && !$this->invalidRule) {

            $validation = new XlsValidator($data['succeed'], $this->rules);

            $filteredData = $validation->validate();
            $data['succeed']=isset($filteredData['succeed'])?$filteredData['succeed']:$data['succeed'];
            $data['failed']=isset($filteredData['failed'])?$filteredData['failed']:$data['failed'];
        }

        return $data;
    }
}
