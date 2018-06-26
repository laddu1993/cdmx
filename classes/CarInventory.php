<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/cdmx/config/db.php';
/**
 * @package    CarInventory
 * @author     Vinil Lakkavatri (vinil.lakkavatri@gmail.com)
 * @copyright  23-06-2018 Vinil Lakkavatri
*/
class CarInventory
{
	private static $instance;

	function __construct(){
		//parent::__construct();
	}

	public static function getInstance(){
		if(!self::$instance)
			self::$instance = new static();
		return self::$instance;
	}

	/**
	 * @param string & array
	 * @return boolean
	*/
	function insert_data($table,$post_data){
		global $mysqli;
		if (!empty($post_data)) {
			$key = array_keys($post_data);
		    $val = array_values($post_data);
		    $sql = "INSERT INTO $table (" . implode(', ', $key) . ") "
		         . "VALUES ('" . implode("', '", $val) . "')";
		    $result = $mysqli->query($sql);
		 	//echo "<pre>";print_r($result);die;
		    return $result;
		}
	}

	/**
	 * @param strings & array
	 * @return boolean
	*/
	function update_data($table, $data, $where){
		global $mysqli;
	    $cols = array();
	    foreach($data as $key=>$val) {
	        $cols[] = "$key = '$val'";
	    }
	    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";
	    $result = $mysqli->query($sql);
	    return $result;
	}

	/**
	 * @param strings
	 * @return boolean
	*/
	function delete_data($table, $where){
		global $mysqli;
	    $sql = "DELETE FROM $table WHERE $where";
	    $result = $mysqli->query($sql);
	    return $result;
	}

	/**
	 * @param strings
	 * @return array
	*/
	function fetch_data(){
		global $mysqli;
		$sql = "SELECT t1.*, t2.name from model t1
		LEFT JOIN manufacturer t2 ON t2.id = t1.m_id";
		$result = $mysqli->query($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$table_data[] = $row;
			}
		} 
		return $table_data;
	}

	/**
	 * @param strings
	 * @return array
	*/
	function fetch_table_data($table,$whr=''){
		global $mysqli;
		$sql = "SELECT * FROM $table";
		if (!empty($whr)) {
			$sql .= " WHERE $whr";
		}
		$result = $mysqli->query($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$table_data[] = $row;
			}
		} 
		return $table_data;
	}


}


?>