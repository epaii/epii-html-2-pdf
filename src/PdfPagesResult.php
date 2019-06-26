<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/6/21
 * Time: 5:01 PM
 */

namespace epii\html\tools;

class PdfPagesResult
{
    private $_data = [];
    private $pages = [];
    private $_success = false;
    private $_successNum = 0;
    private $_errorNum = 0;
    private $_successList = [];
    private $_errorList = [];

    public function code()
    {
        return isset($this->_data["code"]) ? $this->_data["code"] : "";
    }

    public function msg()
    {
        return isset($this->_data["msg"]) ? $this->_data["msg"] : "";
    }

    public function pages()
    {
        return $this->pages;
    }

    public function __construct($data)
    {
        if (!is_array($data)) {
            $this->_success = false;
            return;
        }
        $this->_data = $data;
        if ((!$data) || ($data["code"] != 1)) {
            $this->_success = false;
            return;
        }
        $this->pages = $data["data"]["pages"];


        $this->_success = true;

        foreach ($this->pages as $key => $_page) {


            if ($_page["success"] == 1) {

                $this->_successNum++;
                $this->_successList[] = $this->pages[$key];
            } else {
                $this->_success = false;
                $this->_errorNum++;
                $this->_errorList[] = $this->pages[$key];
            }
        }


    }


    public function map(callable $function)
    {
        foreach ($this->pages as $key => $value) {
            $function($value);
        }
    }

    public function isSuccess()
    {
        return $this->_success;
    }

    public function successNum()
    {
        return $this->_successNum;
    }

    public function errorNum()
    {
        return $this->_errorNum;

    }

    public function successList()
    {
        return $this->_successList;
    }

    public function errorList()
    {
        return $this->_errorList;
    }

    public function taskId()
    {
        return isset($this->_data["data"]["task_id"]) ? $this->_data["data"]["task_id"] : null;
    }

}