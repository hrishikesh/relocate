<?php
/**
 * Created by Webonise Lab.
 * User: Priyanka Bhoir <priyanka.bhoir@weboniselab.com>
 * Date: 13/11/13 10:52 AM
 */
App::uses('Validation','Utility');
class XlsValidator extends Validation {
    protected $records=null;   // data to be validate
    protected $rules=array();   // rules to be checked
    protected $headerToCheck=array();
    protected $failedRow=array();

    public function __construct($data=null,$rules=array()){
        if(!empty($data)){
            $this->records=$data;
        }
        if(!empty($rules)){
            $this->rules=$rules;
            $this->headerToCheck=array_keys($rules);
        }
    }

    /**
     * @return array
     * @description validates data with given rules,
     *              returns succeed rows of data in $data['succeed']
     *              returns failed rows of data in $data['failed']
     */
    public function validate() {
        $rows = $this->records;
        foreach ($this->rules as $header => $validateRules) {
            if (is_array($validateRules)) {
                foreach ($validateRules as $key => $validateRule) {
                    //                    $method='validate'.ucfirst($validateRule);
                    if (is_array($validateRule)) {
                        $method     = $key;
                        $parameters = $validateRule;
                        $message    = "$header does not satisfied rule '$method'";
                        if (array_key_exists('rule', $validateRule)) {
                            if (is_array($validateRule['rule'])) {
                                $method     = array_shift(array_keys($validateRule['rule']));
                                $parameters = $validateRule['rule'][$method];
                            } else {
                                $method = $validateRule['rule'];
                            }
                            if (array_key_exists('message', $validateRule)) {
                                $message = $validateRule['message'];
                            }
                        }

                    } else {
                        $method  = $validateRule;
                        $message = "$header does not satisfied rule '$method'";
                    }
                    if (method_exists($this, $method)) {
                        foreach ($this->records as $recordNum => $record) {
                            $arguments   = array();
                            $arguments[] = $record[$header];
                            if (!empty($parameters)) {
                                array_unshift($parameters, $record[$header]);
                                $arguments = $parameters;
                                array_shift($parameters);

                            }
                            if (!call_user_func_array(array($this, $method), $arguments)) {
                                $record['description']       = $message;
                                $this->failedRow[$recordNum] = $record;
                            }

                        }
                        $rows = array_diff_key($rows, $this->failedRow);
                    } else {
                        trigger_error("No such Rule Found");
                    }
                }
            }
        }
        $returnArray = array('succeed' => $rows, 'failed' => $this->failedRow);

        return $returnArray;
    }

    /**
     *
     */
    public function alphabet($value){
        if(1 === preg_match('/^[a-zA-Z]+$/',$value)){
            return true;
        }
        return false;
    }
}
