<?php
/**
 * Created by Webonise Lab.
 * User: Priyanka Bhoir <priyanka.bhoir@weboniselab.com>
 * Date: 12/11/13 4:07 PM
 */
App::import('Vendor','XlS_new',array('file'=>'reader'.DS.'Excel'.DS.'reader.php'));
class ReadXls {

    /**
     * to hold the 1st level processed file content
     */
    public $content = array();
    /**
     * total number of records in the file
     */
    public $inputFileName=null;
    public $totalProducts = 0;
    public $error = '';
    protected $requiredFields = array();

    public $primaryField = null;
    public $secondaryField = null;

    public $ids = array();
    public $duplicateRecords = array();
    public $duplicateCsvInfo = array();
    public $duplicateIds = array();
    protected $headers = array();
    public $inputFileType = '';
    public $filteredUsers = array();
    public $duplicateToRemove=array();
    public $duplicateToRemoveId=array();

    /**
     * @param       $inputFileName
     * @param array $headers
     *
     */
    public function __construct($inputFileName,$options=array()){
        $this->inputFileName=$inputFileName;
        if(isset($options['headers'])&&!empty($options['headers'])){
            $this->headers=$options['headers'];
        }
        if(isset($options['primaryField'])&&!empty($options['primaryField'])){
            $this->primaryField=$options['primaryField'];
        }
    }

    /**
     * @return array|string
     */
    public function readExcelData(){
        $records=array('failed'=>null,'succeed'=>null,'duplicate'=>null);
        $excel = new Spreadsheet_Excel_Reader();
        $excel->setOutputEncoding('UTF-8');
        $excel->read($this->inputFileName);
        $dataStartsAt=1;

        if(empty($this->headers)){
            $this->headers=range(1,($excel->sheets[0]['numCols']));
            $this->totalProducts=($excel->sheets[0]['numRows']);
            $sheet_clms=range(1,($excel->sheets[0]['numCols']));
            $dataStartsAt=1;
        }else{
            $this->totalProducts=($excel->sheets[0]['numRows'])-1;
            $sheet_clms=array_values($excel->sheets[0]['cells'][1]);
            $dataStartsAt=2;
        }

        $loop=$excel->sheets[0]['numRows'];
        $diff=array_diff($this->headers,$sheet_clms);
        if (empty($diff)) {
            //iterate the array & change the index from alphabetic to the headers
            $j = 0;
            for ($i = $dataStartsAt; $i <= $loop; $i++) {

                //change the array index
                if(isset($excel->sheets[0]['cells'][$i]) && count($this->headers)==count($excel->sheets[0]['cells'][$i])){
                    $data[$j] = array_combine($this->headers, $excel->sheets[0]['cells'][$i]);
                }else{
                    foreach($this->headers as $key=>$header){
                        if(isset($excel->sheets[0]['cells'][$i]) && array_key_exists($key+1,$excel->sheets[0]['cells'][$i])){
                            $data[$j][$header]=$excel->sheets[0]['cells'][$i][$key+1];
                        }else{
                             $data[$j][$header]='';
                        }
                    }
                }

                $j++;
            }
            if (!empty($data)) {
                $this->content = $data;
                $this->error['total_count'] = count($data);
                //check for the correct fields in the csv
                if ($this->checkHeaders()) {
                    if(!empty($this->primaryField)){
                        $this->checkDuplicate($this->content);

                        if (is_array($this->duplicateIds) && !empty($this->duplicateIds)) {
                            $this->error['filtered_data_count'] = count($this->filteredUsers);
                            $records['succeed']=$this->filteredUsers;
                            $records['duplicate']=$this->duplicateCsvInfo;
                            return $records;
                        }
                    }
                    $this->error['filtered_data_count'] = count($this->content);
                    $records['succeed']= $this->content;
                    return $records;

                } else {
                    return $this->error['error'];
                }
            } else {
                return $this->error['error'] = 'File is empty';
            }
        } else {
            return $this->error['error'] = 'Invalid data in the csv.';
        }

    }

    /**
     * @param $email
     * @return bool
     */
    private function emailFormat($email) {
        if (preg_match('/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/', $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function checkHeaders() {
        $file_headers = array_keys($this->content[0]);
        $check_headers = array_diff($this->headers, $file_headers);
        if (empty($check_headers)) {
            $check_headers = array_diff($file_headers, $this->headers);
        }

        if (!empty($check_headers)) {
            $this->error['error'] = 'File does not have enough number of required columns ';
        } else {
            return true;
        }
    }

    /**
     * @param $records
     * @return bool
     */
    public function checkDuplicate($records) {
        if (is_array($records) && !empty($records)) {
            foreach ($records as $record) {
                if (in_array($record[$this->primaryField], $this->ids)) {
                    if (!in_array($record[$this->primaryField], $this->duplicateIds)) {
                        $this->duplicateToRemoveId[] = $record[$this->primaryField];
                        $this->duplicateCsvInfo[] = $record;
                    }
                    $this->duplicateCsvInfo[] = $record;
                    $this->duplicateIds[]     = $record[$this->primaryField];
                } else {
                    $this->ids[]           = $record[$this->primaryField];
                    $this->filteredUsers[] = $record;

                }
            }
            $finalFilteredUsersIds = array_diff($this->ids, $this->duplicateToRemoveId);
            $finalFilteredUsers    = array();
            foreach ($this->filteredUsers as $filteredUser) {
                foreach ($finalFilteredUsersIds as $finalFilteredUsersId) {
                    if ($finalFilteredUsersId == $filteredUser[$this->primaryField]) {
                        $finalFilteredUsers[] = $filteredUser;
                    }
                }
            }
            $this->filteredUsers = $finalFilteredUsers;

            return false;
        } else {
            return false;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    protected function checkAlphabets($name) {
        if (!empty($name) && preg_match('/\PL/', $name)) {
            return false;
        }
        return true;
    }


}
