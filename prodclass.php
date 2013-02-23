<?php
	class Product{
	
		var $name;
		var $price;
		var $qty;
		
		function __construct($name, $price, $qty){	
			$this->name = $name;
			$this->price = $price;
			$this->qty = $qty;
		}
		
		//SETTER
		function set_name($name){
			$this->name = $name;
		}
		function set_price($price){
			$this->price = $price;
		}
		function set_qty($qty){
			$this->qty = $qty;
		}
		
		//GETTER
		function get_name(){
			return $this->name;
		}
		function get_price(){
			return $this->price;
		}
		function get_qty(){
			return $this->qty;
		}
	
	}
	
?>