<?php

error_reporting(E_ERROR);
date_default_timezone_set('PRC');

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die ('Your PHP Version is ' . PHP_VERSION . ', But fw require PHP > 5.3.0 !');
}

class core
{
    /**
     * 插入、更新数据
     * @param $dbConnection
     * @return string
     */
    public function handleData($dbConnection)
    {
        //参数过滤
        $request_data = $this->filter_request_params($_POST);

        $sel_res = $dbConnection->prepare('SELECT * FROM contract WHERE `contractId` = :contractId');
        $sel_res->execute(array(':contractId' => $request_data['contractId']));
        $sel_result = $sel_res->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($sel_result)) {
            $sel_res = $dbConnection->prepare('UPDATE contract SET `minutePerPeriod` = :minutePerPeriod, `unitPrice` = :unitPrice, `update_time` = :update_time WHERE `contractId` = :contractId');
            $up_result = $sel_res->execute(array(
                ':minutePerPeriod' => $request_data['minutePerPeriod'],
                ':unitPrice' => $request_data['unitPrice'],
                ':contractId' => $request_data['contractId'],
                ':update_time' => date('Y-m-d H:i:s')
            ));
            if ($up_result) {
                return $this->print_to('0', '更新成功！');
            } else {
                return $this->print_to('1', '已存在contractid,更新失败！');
            }
        }

        $sel_res = $dbConnection->prepare("INSERT INTO `contract` (`id`, `contractId`, `minutePerPeriod`, `unitPrice`, `create_time`, `update_time`) VALUES ('', :contractId, :minutePerPeriod, :unitPrice, :create_time, :update_time)");
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

    /**
     * 查询数据
     * @param $dbConnection
     * @return string
     */
    public function selectData($dbConnection)
    {
        //参数过滤
        $request_data = $this->filter_request_params($_POST);

        $sel_res = $dbConnection->prepare('SELECT * FROM contract WHERE `contractId` = :contractId');
        $sel_res->execute(array(':contractId' => $request_data['contractId']));
        $sel_result = $sel_res->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sel_result as $key => $row) {
            return $this->print_to('0', '查询成功！', [
                'contractId' => stripcslashes($row['contractId']),
                'minutePerPeriod' => stripcslashes($row['minutePerPeriod']),
                'unitPrice' => stripcslashes($row['unitPrice'])
            ]);
        }
    }

    /**
     * 检测request参数防sql注入
     * @param $data
     * @return array
     */
    public function filter_request_params($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    if ($value !== '' && !is_null($value)) {
                        //检测空格、tab、换行
                        $value = trim($value);

                        //get_magic_quotes_gpc()未开启情况下，数据过滤
                        if (!get_magic_quotes_gpc()) {
                            $value = addslashes($value);
                        }

                        //检测数字
                        if (is_numeric($value)) {
                            $value = intval($value);
                        }

                        //检测字符串
                        if (is_string($value)) {
                            $keyword = 'select|insert|update|delete|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile';
                            $arr = explode('|', $keyword);
                            $value = str_ireplace($arr, '', $value);
                            $value = nl2br($value); // 回车转换
                        }
                    }

                    $data[$key] = $value;
                } else {
                    $this->filter_request_params($value[$key]);
                }
            }
        }
        return $data;
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
    $C = require("config.php");

    //建立PDO数据库连接
    $dbConnection = new PDO("mysql:host={$C['DB_HOST']};port={$C['DB_PORT']};dbname={$C['DB_NAME']};charset=utf8",
        $C['DB_USER'], $C['DB_PWD']);
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
