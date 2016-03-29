<?php 
	
    class common {

        public $str;

        function add_slash($d){
                $this->str=addslashes($d);
                return $this->str;
        }

        function strip_slash($d){
                $this->str=stripslashes($d);
                return $this->str;
        }






    }

	

?>