<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/6/26
 * Time: 5:52 PM
 */

namespace epii\html\tools;

class PdfPagesResultAsyn
{
    private $_success = true;

    public function __construct($data)
    {

        if (!is_array($data))
        {
            $this->_success = false;
            return;
        }
        $this->_data = $data;
        if ((!$data) || (!isset($data["code"])) || ($data["code"] != 1)) {
            $this->_success = false;
            return;
        }
    }

    public function code()
    {
        return isset($this->_data["code"]) ? $this->_data["code"] : "";
    }

    public function msg()
    {
        return isset($this->_data["msg"]) ? $this->_data["msg"] : "";
    }

    public function isSuccess()
    {

        return $this->_success;
    }

    public function taskId()
    {
        return isset($this->_data["data"]["task_id"]) ? $this->_data["data"]["task_id"] : null;
    }
}