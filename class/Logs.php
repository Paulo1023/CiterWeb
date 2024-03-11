<?php



date_default_timezone_set('Asia/Manila');

class Logs
{
    private $dbConn;

    private $ds;

    function __construct()
    {
        require_once "DataSource.php";
        $this->ds = new DataSource();
    }

    function countLogs()
    {
        $query = "select * FROM logs";
        $usersResult = $this->ds->numRows($query);
        
        return $usersResult;
    }

    function getLogs($offset, $pageNo)
    {
        $query = "select * from logs limit ?, ?";
        $paramType = "ii";
        $paramArray = array($offset, $pageNo);
        $getLogs = $this->ds->select($query, $paramType, $paramArray);
        return $getLogs;
    }

    function filterLogs($filter)
    {
        $query = "select date from logs where segment = ? and operation = 'ADD'";
        $paramType = "s";
        $paramArray = array($filter);
        $getFilteredLogs = $this->ds->select($query, $paramType, $paramArray);
        return $getFilteredLogs;
    }

    function userActivities($user)
    {
        $query = "select date from logs where user_name = ?";
        $paramType = "s";
        $paramArray = array($user);
        $getUserAct = $this->ds->select($query, $paramType, $paramArray);
        return $getUserAct;
    }

    function insertLog($operation, $segment, $remarks, $user)
    {
        $today = date('Y-m-d H:i:s');
        $id = "L" . uniqid();
        $query = "insert into logs (id, operation, segment, remarks, date, user_name) VALUES (?, ?, ?, ?, ?, ?)";
        $paramType = "ssssss";
        $paramArray = array($id, $operation, $segment, $remarks, $today, $user);
        $newLog = $this->ds->insert($query, $paramType, $paramArray);
        return $newLog;
    }
}