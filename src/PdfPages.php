<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/6/21
 * Time: 4:12 PM
 */

namespace epii\html\tools;

class PdfPages
{
    private $pages = [];


    public function addPage(string $url, $page_id = 0,array $options = null)
    {
        $this->pages[] = ["url" => $url, "options" => $options, "page_id" => $page_id];
    }

    public function getPages()
    {
        return $this->pages;
    }


}