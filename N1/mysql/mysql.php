<?php
/*
    +---------------------------------------------------------------------+
    |     Ќаименование:  ласс работы с базой данных                       |
    |            јвтор: ѕеченюк —ерегй aka programmer                     |
    |             —айт: http://phpprogs.ru/                               |
    | ѕри использовании скрипта ссылка на http://phpprogs.ru/ об€зательна |
    +---------------------------------------------------------------------+
*/

class sql_db {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $query_err='';
	var $query_sql=''	;

	//
	// Constructor
	//
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database) {
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;
		
	    $this->db_connect_id = mysql_connect($this->server, $this->user, $this->password);

		if( $this->db_connect_id ) {
			if( $database != "" ) {
				$this->dbname = $database;
				$dbselect = mysql_select_db($this->dbname);

				if( !$dbselect ) {
					mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}

			return $this->db_connect_id;
		}
		else {
			return false;
		}
	}

	// закрыть соединение с базой данных
	function sql_close() {
		if( $this->db_connect_id ) {
			return mysql_close($this->db_connect_id);
		}
		else {
			return false;
		}
	}

	// запрос к серверу Ѕƒ
	function sql_query($query = "") {
		unset($this->query_result);		
		$this->query_result = mysql_query($query, $this->db_connect_id);

		if( $this->query_result ) {
			return $this->query_result;
		}
		else {
			$this->query_err .= "<br>\n" . $this->sql_error_msg()	;
			return false;
		}
	}

	// функци€ отдает количество возвращаемых Ѕƒ-сервером р€дов (дл€ SELECT)
	function sql_numrows($query_id = 0) {
		if( !$query_id ) {
			$query_id = @$this->query_result;
		}

		return ( $query_id ) ? mysql_num_rows($query_id) : false;
	}

	// количество записей, которые были затронуты последним запросом (ƒл€ UPDATE, DELETE, INSERT)
	function sql_affectedrows() {
		return ( $this->db_connect_id ) ? mysql_affected_rows($this->db_connect_id) : false;
	}

	// количество возвращаемых полей в ответе
	function sql_numfields($query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_num_fields($query_id) : false;
	}

	// возвращает наименование пол€, пор€дковый номер которого задаетс€ параметом offset
	function sql_fieldname($offset, $query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_field_name($query_id, $offset) : false;
	}

	// возвращает тип пол€, пор€дковый номер которого задаетс€ параметом offset
	function sql_fieldtype($offset, $query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_field_type($query_id, $offset) : false;
	}

	// обрабатывает р€д запроса и возвращает ассоциативный массив
	function sql_fetchrow($query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		if( $query_id ) {
			return @mysql_fetch_array($query_id, MYSQL_ASSOC);
		}
		else {
			return false;
		}
	}

	// обрабатывает все р€ды запроса и возвращает двумерный ассоциативный массив
	function sql_fetchrowset($query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		if( $query_id ) {
			unset($this->rowset);
			unset($this->row[$query_id]);

			while($this->rowset = @mysql_fetch_array($query_id, MYSQL_ASSOC)) {
				$result[] = $this->rowset;
			}

			return isset($result) ? $result : false ;
			unset($result);
		}
		else {
			return false;
		}
	}


	// смещение указател€ на rownum
	function sql_rowseek($rownum, $query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		return ( $query_id ) ? mysql_data_seek($query_id, $rownum) : false;
	}

	// возвращает последний добавленный ID командой INSERT
	function sql_nextid() {
		return ( $this->db_connect_id ) ? mysql_insert_id($this->db_connect_id) : false;
	}

	// освобождение пам€ти
	function sql_freeresult($query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->query_result;
		}

		if ( $query_id ) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			mysql_free_result($query_id);

			return true;
		}
		else {
			return false;
		}
	}

	// возвращает код последней ошибки и ее описание
	function sql_error() {
		if ( $this->db_connect_id ) {
			$result['code'] = mysql_errno($this->db_connect_id);
			$result['message'] = mysql_error($this->db_connect_id)	;
		}
		else {
		    $result['code'] = "999";
		    $result['message'] = "No Connect";
		}

		return $result;
	}
	
	function sql_error_msg() {
		$err = $this->sql_error()	;
		if ( $err )
			return 'MySQL error ' . $err[ 'code' ] . ': ' . $err[ 'message' ] ;
		return ''	;
	}
} // class sql_db

?>