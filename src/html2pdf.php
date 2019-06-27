<?php
/**
 * Created by PhpStorm.
 * User: mrren
 * Date: 2019/6/26
 * Time: 11:53 AM
 */

namespace epii\html\tools;

class html2pdf
{

    const ASYN = 2;
    const SYNC = 1;
    private static $_server_api = "http://html2pdf.wszx.cc/?";

    private static $secret_key = null;

    public static function init($secret_key, $server_api = null)
    {
        if ($server_api)
            self::$_server_api = stripos($server_api, "?") > 0 ? $server_api : ($server_api . "?");

        self::$secret_key = $secret_key;
    }


    public static function create($pdfPages, $type = 1, $notice_url = null)
    {
        if (!$pdfPages instanceof PdfPages) {
            echo "\$pdfPages must instanceof  PdfPages";
            exit;
        }

        $data = [];
        $data["pages"] = json_encode($pdfPages->getPages(), JSON_UNESCAPED_UNICODE);
        $data["notice_url"] = $notice_url;

        $ret = self::curl_post(self::$_server_api . "&app=index@" . ($type == self::SYNC ? "sync" : "asyn"), $data);

        return $type == self::SYNC ? new PdfPagesResult($ret) : new PdfPagesResultAsyn($ret);


    }

    public static function syncPage($url, $page_id = 0, $options = null)
    {


        $pdfPages = new PdfPages();
        $pdfPages->addPage($url, $page_id, $options);
        return self::syncPages($pdfPages);

    }

    public static function syncPages($pdfpages)
    {
        return self::create($pdfpages, 1);
    }

    public static function asynPage($url, $notice_url, $page_id = 0, $options = null)
    {


        $pdfPages = new PdfPages();
        $pdfPages->addPage($url, $page_id, $options);
        return self::asynPages($pdfPages, $notice_url);

    }

    public static function asynPages($pdfpages, $notice_url)
    {
        return self::create($pdfpages, 2, $notice_url);
    }

    public static function queryByTaskId($taskId)
    {
        return self::curl_post(self::$_server_api . "&app=index@query", ["task_id" => $taskId]);
    }


    private static function curl_post($url, $post_data)
    {
        $post_data["sign"] = md5(serialize($post_data) . self::$secret_key);
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        // var_dump($data);
        //关闭URL请求
        curl_close($curl);
        if (!$data) return false;
        return json_decode($data, true);
    }
}