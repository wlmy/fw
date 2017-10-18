<?php

error_reporting(E_ERROR);
date_default_timezone_set('PRC');

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die ('Your PHP Version is ' . PHP_VERSION . ', But WeiPHP require PHP > 5.3.0 !');
}


class core
{
    public function handleData($dbConnection)
    {
        //参数过滤
        $request_data = $this->filter_request_data($_POST);

        $sel_res = $dbConnection->prepare('SELECT * FROM contract WHERE `contractId` = :contractId');
        $sel_res->execute(array(':contractId' => $request_data['contractId']));
        $sel_result = $sel_res->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($sel_result)) {
            $params = $sel_result[0];
            $sel_res = $dbConnection->prepare('UPDATE contract SET `minutePerPeriod` = :minutePerPeriod, `unitPrice` = :unitPrice WHERE `contractId` = :contractId');
            $up_result = $sel_res->execute(array(
                ':minutePerPeriod' => $params['minutePerPeriod'],
                ':unitPrice' => $params['unitPrice'],
                ':contractId' => $params['contractId']
            ));
            if ($up_result) {
                return $this->print_to('0', '更新成功！');
            } else {
                return $this->print_to('1', '已存在contractid,更新失败！');
            }
        }

        $sel_res = $dbConnection->prepare("INSERT INTO `contract` (`id`, `contractId`, `minutePerPeriod`, `unitPrice`, `create_time`, `update_time`) VALUES ('', ':contractId', ':minutePerPeriod', ':unitPrice', ':create_time', ':update_time')");
        $ins_result = $sel_res->execute(array(
            ':contractId' => $request_data['contractId'],
            ':minutePerPeriod' => $request_data['minutePerPeriod'],
            ':unitPrice' => $request_data['unitPrice'],
            ':create_time' => date('Y-m-d H:i:s'),
            ':update_time' => date('Y-m-d H:i:s'),
        ));
        if ($ins_result) {
            return $this->print_to('0', '插入成功！');
        } else {
            return $this->print_to('1', '插入失败！');
        }

    }

    public function selectData($dbConnection)
    {
        //参数过滤
        $request_data = $this->filter_request_data($_POST);

        $sel_res = $dbConnection->prepare('SELECT * FROM contract WHERE `contractId` = :contractId');
        $sel_res->execute(array(':contractId' => $request_data['contractId']));
        $sel_result = $sel_res->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sel_result as $key => $row) {
            return $this->print_to('0', '查询成功！', [
                'contractId' => $row['contractId'],
                'minutePerPeriod' => $row['minutePerPeriod'],
                'unitPrice' => $row['unitPrice']
            ]);
        }
    }

    /**
     * 过滤request参数并进行防sql注入处理
     */
    public function filter_request_data($params)
    {
        $request_data = $params;
        return $request_data;

    }

    /**
     * 输出信息
     * @param $code
     * @param $message
     * @param array $data
     * @return string
     */
    public function print_to($code, $message, $data = [])
    {
        echo json_encode(array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        ));
    }
}

$act = $_GET['act'] ? $_GET['act'] : '';
if (empty($act)) {
    exit;
} else {
    //建立PDO数据库连接
    $dbConnection = new PDO('mysql:dbname=fuwu;host=localhost;charset=utf8', 'root', 'root');
    $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $class = new core();

    if ($_GET['act'] == 'handleData') {
        $class->handleData($dbConnection);
    }
    if ($_GET['act'] == 'selectData') {
        $class->selectData($dbConnection);
    }

    exit;

}
