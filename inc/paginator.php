<?php
	class Paginator{
		
		private $_conn;
		private $_limit;
		private $_page;
		private $_query;
		private $_total;
		private $_letter;
		
		public function __construct($conn, $query, $letter){
			$this->_conn = $conn;
			$this->_query = $query;
			$this->_letter = $letter;
			
			$rs = $this->_conn->query($this->_query . " WHERE first_name LIKE '$letter%'");
			$this->_total = $rs->num_rows;
		}
		
		public function getData( $limit = 10, $page = 1, $letter ){
			$this->_limit = $limit;
			$this->_page = $page;
			//$this->_letter = $letter;
			
			if( $this->_limit == 'all' )
				$query = $this->_query;
			else 
				$query = $this->_query . " WHERE first_name LIKE '$letter%' LIMIT " . (($this->_page - 1) * $this->_limit) . ", " . $this->_limit;
			
			$rs = $this->_conn->query($query);
			
			while( $row = $rs->fetch_assoc() )
				$results[] = $row;
			
			$result = new stdClass();
			$result->page = $this->_page;
			$result->limit = $this->_limit;
			$result->total = $this->_total;
			$result->data = $results;
			
			return $result;
		}
		
		public function wordLink(){
			
			$html = "";
			
			for($i = 0x61; $i <= 0x7a; $i++){
				if( chr($i) == $this->_letter ){
					$html .= '<a class="page active" href="?limit=10&page=1&letter=' . chr($i) . '">' . chr($i) . "</a>";
					continue;
				}
				$html .= '<a class="page" href="?limit=10&page=1&letter=' . chr($i) . '">' . chr($i) . "</a>";
			}
			
			return $html;
		}
		
		public function createLinks( $links, $list_class ) {
			if ( $this->_limit == 'all' ) {
				return '';
			}
		 
			$last = ceil( $this->_total / $this->_limit );
		 
			$start = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
			$end = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
		 
			$html = '<ul class="' . $list_class . '">';
		 
			$class = ( $this->_page == 1 ) ? "disabled" : "";
			$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '&letter=' . $this->_letter . '">&laquo;</a></li>';
		 
			if ( $start > 1 ) {
				$html .= '<li><a href="?limit=' . $this->_limit . '&page=1' . '&letter=' . $this->_letter . '">1</a></li>';
				$html .= '<li class="disabled"><span>...</span></li>';
			}
		 
			for ( $i = $start ; $i <= $end; $i++ ) {
				$class  = ( $this->_page == $i ) ? "active" : "";
				$html   .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '&letter=' . $this->_letter . '">' . $i . '</a></li>';
			}
		 
			if ( $end < $last ) {
				$html .= '<li class="disabled"><span>...</span></li>';
				$html .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '&letter=' . $this->_letter . '">' . $last . '</a></li>';
			}
		 
			$class = ( $this->_page == $last ) ? "disabled" : "";
			$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '&letter=' . $this->_letter . '">&raquo;</a></li>';
		 
			$html .= '</ul>';
		 
			return $html;
		}
	}
?>