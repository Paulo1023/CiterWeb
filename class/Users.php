<?php
session_start();
//use \DataSource;


class Users
{
    private $dbConn;

    private $ds;

    function __construct()
    {
        require_once "DataSource.php";
        $this->ds = new DataSource();
    }
    function getUsersList()
    {
        $query = "select * FROM registered_users";
        $usersResult = $this->ds->select($query);
        
        return $usersResult;
    }

    function usersList($offset, $pageNum)
    {
        $query = "select * from registered_users limit ?, ?";
        $paramType = "ii";
        $paramArray = array($offset, $pageNum);
        $getUsers = $this->ds->select($query, $paramType, $paramArray);
        return $getUsers;
    }

    function countUsers()
    {
        $query = "select * FROM registered_users where superuser = 0";
        $usersResult = $this->ds->numRows($query);
        return $usersResult;
    }

    public function registerUser($uname, $pass, $time) {
        $passwordHash = md5($pass);
        $query = "insert into registered_users (user_name, password, date_created) VALUES (?, ?, ?)";
        $paramType = "sss";
        $paramArray = array($uname, $passwordHash, $time);

        $userRegis = $this->ds->insert($query, $paramType, $paramArray);
        return $userRegis;
    }

    public function deleteUser($id)
    {
        $query = "delete from registered_users where id = ?";
        $paramType = "i";
        $paramArray = array($id);

        $userDel = $this->ds->delete($query, $paramType, $paramArray);
        return $userDel;
    }
    public function updateUser($id, $un, $pw)
    {
        $query = "update registered_users set user_name = ?, password = ? where id = ?";
        $paramType = "ssi";
        $paramArray = array($un, $pw, $id);
        $userEdit = $this->ds->update($query, $paramType, $paramArray);
        return $userEdit;

    }

    public function loginUser($date, $id)
    {
        $query = "update registered_users set last_loggedin = ? where id = ?";
        $paramType = "si";
        $paramArray = array($date, $id);
        $userLoggedIn = $this->ds->update($query, $paramType, $paramArray);
        return $userLoggedIn;
    }
}

?>