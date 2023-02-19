<?php

class Database
{
    private $database  = "apijwtdb";
    private $server  = "localhost";
    private $password  = "";
    private $username  = "root";
    private $mysqli = "";
    private $result = array();
    private $connection = false;


    /*
     * classes connection constructor
    */
    public function __construct()
    {
        if(!$this->connection){

            $this->mysqli = new mysqli($this->server, $this->username, $this->password, $this->database);
            $this->connection = true;

            if ($this->mysqli->connect_error){
                array_push($this->result,
                    $this->mysqli_connection_error);
                return false;

            }
        }
        else{

            return true;
        }
    }

    /*Get data select statement*/
    public function select($table, $row="*", $join =null, $where=null, $order= null, $limit=null){
        if($this->tableExist($table)){
            $sql = "SELECT $row FROM $table";
            if($join !=null){
                $sql .= " JOIN $join";
            }
            if($where != null){
                $sql .= " WHERE $where";
            }
            if($order != null){
                $sql .= " ORDER BY $order";
            }

            if ($limit != null){
                $sql .=" LIMIT $limit";
            }

            $query = $this->mysqli->query($sql);
            if($query->num_rows > 0){
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true;
            }
            else {
                return false;
            }
        }
    }


    /*
     * INSERT statement
     * */
    public function insert($table, $params = array()){
        if($this->tableExist($table)){
            $columns = implode(',', array_keys($params));
            $values = implode("','", array_values($params));

            $sql = "INSERT INTO $table ($columns) VALUES('$values')";
            if($this->mysqli->query($sql)){
                array_push($this->result, true);
                return true;
            }
            else{
                array_push($this->result, false);
                return false;
            }
        }
        else{
            return false;
        }

    }

    /*
     * check existing table
    */
    public function tableExist($table){
        $sql = "SHOW TABLES FROM $this->database LIKE '{$table}'";
        $tableInDb = $this->mysqli->query($sql);
        if($tableInDb){
            if($tableInDb->num_rows == 1){
                return true;
            }
            else{
                array_push($this->result, $table. "Doesn't exist");
            }
        }
        else{
            return false;
        }
    }


    /*
     * Parse result to array
     * */
    public function getResult(){
        $val = $this->result;
        $this->result = array();
        return $val;
    }


    /* Destruct database connection */
    public function __destruct()
    {
        if($this->connection){
            if($this->mysqli->close()){
                $this->connection = false;
                return true;
            }
        }
    }
}