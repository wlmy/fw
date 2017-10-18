<?php

error_reporting(E_ERROR);
date_default_timezone_set('PRC');

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die ('Your PHP Version is ' . PHP_VERSION . ', But WeiPHP require PHP > 5.3.0 !');
}


class core
{
    public function handleData($mysqli)
    {
        $contractId = $_POST['contractId'];
        $minutePerPeriod = $_POST['minutePerPeriod'];
        $unitPrice = $_POST['unitPrice'];

        $sql_query = "SELECT * FROM contract WHERE `contractId` = '$contractId'";
        $select_result = $mysqli->query($sql_query);
        if ($select_result->num_rows) {
            $sql = "UPDATE contract SET `minutePerPeriod` = '$minutePerPeriod', `unitPrice` = '$unitPrice' WHERE `contractId` = '$contractId'";
            $up_result = $mysqli->query($sql);
            if ($up_result) {
                return $this->print_to('0', '更新成功！');
            } else {
                return $this->print_to('1', '已存在contractid,更新失败！');
            }
        }

        $create_time = date('Y-m-d H:i:s');
        $update_time = date('Y-m-d H:i:s');

        $sql = "INSERT INTO `contract` (`id`, `contractId`, `minutePerPeriod`, `unitPrice`, `create_time`, `update_time`) VALUES ('', '$contractId', '$minutePerPeriod', '$unitPrice', '$create_time', '$update_time')";
        $add_result = $mysqli->query($sql);
        if ($add_result) {
            return $this->print_to('0', '插入成功！');
        } else {
            return $this->print_to('1', '插入失败！');
        }

    }

    public function selectData($mysqli)
    {
        $contractId = $_POST['contractId'];
        $sql_query = "SELECT * FROM contract WHERE `contractId` = '$contractId'";
        $select_result = $mysqli->query($sql_query);
        while ($row = mysqli_fetch_assoc($select_result)) {
            return $this->print_to('0', '查询成功！', [
                'contractId' => $row['contractId'],
                'minutePerPeriod' => $row['minutePerPeriod'],
                'unitPrice' => $row['unitPrice']
            ]);
        }
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
    //建立数据库连接
    $mysqli = new \mysqli();
    $mysqli->connect('localhost', 'root', 'root', 'fuwu');

    $class = new core();

    if ($_GET['act'] == 'handleData') {
        $class->handleData($mysqli);
    }
    if ($_GET['act'] == 'selectData') {
        $class->selectData($mysqli);
    }

    $mysqli->close();
    exit;

}

$app = array(
    'db_host'        => 'localhost',
    'db_username'    => 'root',
    'db_password'    => 'root',
    'db_name'        => 'fuwu',
    'db_port'        => 3306,
    'db_pconnect'    => true
);
$dsn = "mysql:dbname=$app[db_name];host=$app[db_host];port=$app[db_port];charset=utf8";
$db = new PDO($dsn, $app['db_username'], $app['db_password'], array(
    PDO::ATTR_PERSISTENT => $app['db_pconnect'],
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
));

new PDO();
