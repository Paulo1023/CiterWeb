<?php



date_default_timezone_set('Asia/Manila');

class Analytics
{
    private $dbConn;

    private $ds;

    function __construct()
    {
        require_once "DataSource.php";
        $this->ds = new DataSource();
    }

    function countData($val)
    {
        $query = "select * FROM analytics where segment = ?";
        $paramType = "s";
        $paramArray = array($val);
        $usersResult = $this->ds->numRows($query, $paramType, $paramArray);
        
        return $usersResult;
    }

    function deleteData($col, $val)
    {
        if($col == "providerID")
        {
            $query = "delete from analytics where providerID = ?";
        }
        elseif($col == "topicID")
        {
            $query = "delete from analytics where topicID = ?";
        }
        elseif($col == "questionID")
        {
            $query = "delete from analytics where questionID = ?";
        }
        $paramType = "s";
        $paramArray = array($val);
        $getData = $this->ds->select($query, $paramType, $paramArray);
        return $getData;
    }

    function insertData($segment, $provID, $topID, $questID)
    {
        
        $query = "insert into analytics (segment, providerID, topicID, questionID) VALUES (?, ?, ?, ?)";
        $paramType = "ssss";
        $paramArray = array($segment, $provID, $topID, $questID);
        $newData = $this->ds->insert($query, $paramType, $paramArray);
        return $newData;
    }
}

?>