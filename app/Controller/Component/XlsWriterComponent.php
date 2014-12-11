<?php
/**
 * Created by Webonise Lab.
 * User: Priyanka Bhoir <priyanka.bhoir@weboniselab.com>
 * Date: 1/11/13 5:46 PM
 * @todo: add method to set user defined formats
 */


App::import('Vendor', 'PearExcel',
    array('file' => 'Spreadsheet_Excel_Writer-0.9.3' . DS . 'Spreadsheet' . DS .'Excel'.DS. 'Writer.php'));
class XlsWriterComponent extends Component {

    /**
     * @var Controller
     */
    protected $_Controller = '';

    /**
     * @var Spreadsheet_Excel_Writer
     */
    protected $_workbook;

    /**
     * @var Spreadsheet_Excel_Writer_Worksheet
     */
    protected $_currentWorksheet = null;

    public $lastMergedRow;
    public $lastMergedColumn;
    public $lastWrittenRow;
    protected $_headerFormat;

    /**
     * @var Spreadsheet_Excel_Writer_Format
     */
    protected $_commonFormat;

    public function initialize(Controller $controller) {
        $this->_Controller = $controller;
    }

    public function startup(Controller $controller) {

    }

    public function beforeRender(Controller $controller) {

    }

    public function shutdown(Controller $controller) {

    }

    public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {

    }

    /**
     * @return XlsWriterComponent
     * @description Creates the new workbook and creates two default formats
     */
    public function createWorkbook($fileName = '') {
        $this->_workbook     = new Spreadsheet_Excel_Writer($fileName);
        // Set format biff8 (excel 2003)
        $this->_workbook->setVersion(8);
        $this->_headerFormat = $this->_workbook->addFormat();
        $this->_headerFormat->setAlign('center');
        $this->_headerFormat->setBorder(2);
        $this->_headerFormat->setFontFamily('Calibri');
        $this->_headerFormat->setSize(11);
        $this->_headerFormat->setColor('black');

        //create format for all records data
        $this->_commonFormat = $this->_workbook->addFormat();
        $this->_commonFormat->setSize(10);
        $this->_commonFormat->_size = 10;
        $this->_commonFormat->setFontFamily('Calibri');
        $this->_commonFormat->setColor('black');

        return $this;
    }

    public function setTmpDir($tmpDir = '/tmp'){
        $this->_workbook->setTempDir($tmpDir);
        return $this;
    }

    /**
     * @param string $name
     * @return Spreadsheet_Excel_Writer_Worksheet
     * @description : creates new worksheet
     */
    public function createWorksheet($name = 'worksheet') {
        $this->_currentWorksheet=$this->_workbook->addWorksheet($name);
        $this->_currentWorksheet->setInputEncoding('UTF-8');
        return $this->_currentWorksheet;
    }

    /**
     * @param array $headerRecords
     * @return XlsWriterComponent
     * @description : writes the header to xls file
     */
    public function addXlsHeader(array $headerRecords) {
        if (empty($this->_currentWorksheet)) {
            $this->_currentWorksheet = $this->createWorksheet();
        }
        $this->WriteRow($headerRecords,$this->_headerFormat);
        return $this;
    }

    /**
     * @param $candidateRecordFields
     * @return XlsWriterComponent
     * @description : writes the row to xls file
     */
    public function addXlsRecord($candidateRecordFields) {
        if (empty($this->_currentWorksheet)) {
            $this->_currentWorksheet = $this->createWorksheet();
        }
        $this->WriteRow($candidateRecordFields,$this->_commonFormat);
        return $this;
    }

    /**
     * @param $records
     * @param $format
     * @return XlsWriterComponent
     * @description : writes the row to xls file in given format
     */
    private function WriteRow($records,$format){
        $currentRow   = !is_null($this->lastWrittenRow) ? ($this->lastWrittenRow) + 1 : 0;
        $columnNumber = 0;
        $this->_currentWorksheet->setColumn(0, count($records), 25);
        foreach ($records as $record) {

            if (is_array($record)) {
                if (isset($record['merge']) && is_array($record['merge']) && $record['merge'] != false && (isset($record['column_name'])||isset($record['record_value']))) {
                    $value=isset($record['column_name'])?$record['column_name']:$record['record_value'];
                    $merge             = $record['merge'];
                    $number_of_rows    = $record['merge']['no_of_rows'];
                    $number_of_columns = $record['merge']['no_of_columns'];
                    if (!isset($number_of_rows)) {
                        $number_of_rows = 0;
                    }
                    if (!isset($number_of_columns) || $number_of_columns <= 0) {
                        $number_of_columns = 1;
                    }
                    if ($this->lastWrittenRow == $currentRow && $columnNumber < $this->lastWrittenColumn) {
                        $columnNumber = $this->lastWrittenColumn;
                    }
                    $this->_currentWorksheet->setMerge($currentRow, $columnNumber,
                        $currentRow + $number_of_rows,
                        $columnNumber + $number_of_columns - 1);
                    $this->_currentWorksheet->write($currentRow, $columnNumber,
                        $value, $format);

                    $this->_currentWorksheet->write($currentRow + $number_of_rows,
                        $columnNumber + $number_of_columns - 1,
                        "",$format);
                    $columnNumber = $columnNumber + $number_of_columns;


                    if ($this->lastMergedRow <= ($currentRow + $number_of_rows)) {
                        $this->lastMergedRow    = ($currentRow + $number_of_rows);
                        $this->lastMergedColumn = $columnNumber;
                    }

                } else {
                    if ($this->lastMergedRow == $currentRow && $columnNumber < $this->lastMergedColumn) {
                        $columnNumber = $this->lastMergedColumn;
                    }
                    $value=isset($record['column_name'])?$record['column_name']:$record['record_value'];
                    $this->_currentWorksheet->write($currentRow, $columnNumber,
                        $value, $format);
                    $columnNumber++;
                }

            } else {
                if (is_array($record) && isset($record['column_name']) && !empty($record['column_name'])) {
                    $value = $record['column_name'];
                } else {
                    $value = isset($record) ? $record : '';
                }

                $this->_currentWorksheet->write($currentRow, $columnNumber,
                    $value, $format);
                $columnNumber++;
            }
        }
        $this->lastWrittenRow = $currentRow;
        return $this;
    }
    /**
     * @param String | null $name
     * @description : send the xls file to user browser
     */
    public function download($name = 'workbook') {
        $this->_workbook->send($name . '.xls');
        $this->_workbook->close();
    }


    /**
     * @param String | null $name
     * @description : send the xls file to user browser
     */
    public function close() {
        $this->_workbook->close();
    }
    /**
     * @param $worksheet
     * @return XlsWriterComponent
     */
    public function setCurrentWorksheet($worksheet) {
        if (!empty($worksheet)) {
            $this->_currentWorksheet = $worksheet;
        }
        return $this;
    }

    /**
     * @description Replace Null values with '-'
     * @param        $data
     * @param string $replace
     * @return array|string
     */
    public function replaceNulls($data, $replace = '-') {
        if (is_string($data) || is_null($data)) {
            $data = trim($data);
            if (!($data === 0 || $data == '0') && (empty($data) || $data == '' || is_null($data))) {
                return $replace;
            }
        }
        if (is_array($data) && !empty($data)) {
            $returnArray = array();
            foreach ($data as $key => $dataValue) {
                $returnArray[$key] = $this->replaceNulls($dataValue);
            }

            return $returnArray;
        }

        return $data;
    }
}