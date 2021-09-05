<?php

namespace DBOperations;

class DBHandler {
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $database = "consultants_db";
    protected $conn;

    function __construct() {
        $this->conn = $this->connectDB();
    }

    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $conn;
    }
}

class DBRunQueries extends DBHandler {
    /**
     * It takes a query and returns a result set (for manipulation) as obtained from running that query.
     */
    function runBaseQuery($query) {
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        if (!empty($resultset))
            return $resultset;
    }

    function runQuery($query, $param_type, $param_value_array) {

        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (!empty($resultset)) {
            return $resultset;
        }
    }

    function bindQueryParams($sql, $param_type, $param_value_array) {
        $param_value_reference[] = &$param_type;
        for ($i = 0; $i < count($param_value_array); $i++) {
            $param_value_reference[] = &$param_value_array[$i];
        }
        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }

    function populateDropDownFromDB($sql, $select_tag_name, $class_name, $id_col, $val_col) {
        $result = $this->runBaseQuery($sql);
        echo "<select name='{$select_tag_name}' class= '{$class_name}' >";

        for ($i = 0; $i < count($result); $i++) {
            echo "<option value='" . $result[$i][$id_col] . "'>" . $result[$i][$val_col] . "</option>";
        }
        echo "</select>";
    }
    function insert($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        return $sql->execute();
    }

    function update($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        return $sql->execute();
    }
    function delete($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        return $sql->execute();
    }
}


class DBAuth extends DBHandler {
    function getMemberByUsername($username) {
        $db_handle = new DBRunQueries();
        $query = "Select * from members where member_name = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }

    function getConsultantByUsername($username) {
        $db_handle = new DBRunQueries();
        $query = "Select * from consultants where consultant_username = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }

    function getTokenByUsername($username, $expired) {
        $db_handle = new DBRunQueries();
        $query = "Select * from tbl_token_auth where username = ? and is_expired = ?";
        $result = $db_handle->runQuery($query, 'si', array($username, $expired));
        return $result;
    }

    function markAsExpired($tokenId) {
        $db_handle = new DBRunQueries();
        $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
        $expired = 1;
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }

    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        $db_handle = new DBRunQueries();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }
}
