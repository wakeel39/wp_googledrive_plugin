<?php 
class google_drive_db {
    //insert data into databse 
	
	function google_drive_insertData($table,$colnames,$values){
		global $wpdb;
		$SQL = "INSERT INTO $table ($colnames) VALUES ($values) "; 
		$wpdb->QUERY($SQL); 
		}
	function google_drive_fetchdata($table,$filter="") {
		global $wpdb;
		//echo "SELECT * from $table $filter"; 
		 $SQL = $wpdb->get_results("SELECT * from $table $filter"); 
		return $SQL;
		}
	function google_drive_single_data($table,$filter) {
		global $wpdb;
                
		return $SQL = $wpdb->get_row( $wpdb->prepare("SELECT * from $table WHERE $filter",""));
		$wpdb->show_errors();
		}
	function update_data($table,$colums,$filter) {
		global $wpdb;
		$SQL = "UPDATE $table SET $colums WHERE $filter "; 
		 $wpdb->QUERY($SQL);
	}

	function Delete_data($table,$filter) {
		global $wpdb;
		 $SQL = "Delete  FROM $table WHERE $filter "; 
		 $wpdb->QUERY($SQL);
	}
}
?>