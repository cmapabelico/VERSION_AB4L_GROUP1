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
	
	class Burger{
	
		var $brgr;
		var $bun;
		var $cheese;
		var $premium;
		var $basic;
		var $sauce;
		var $price;
		var $qty;
		var $name;
		
		function __construct($brgr, $bun, $cheese, $premium, $basic, $sauce, $qty, $price){
			$this->brgr = $brgr;
			$this->bun = $bun;
			$this->cheese = $cheese;
			$this->premium = $premium;
			$this->basic = $basic;
			$this->sauce = $sauce;
			$this->qty = $qty;
			$this->price = $price;
			$this->name = "Custom burger";
		}
		
		//GETTER
		function get_brgr(){
			return $this->brgr;
		}
		
		function get_bun(){
			return $this->bun;
		}
		
		function get_cheese(){
			return $this->cheese;
		}
		
		function get_premium(){
			return $this->premium;
		}
		
		function get_basic(){
			return $this->basic;
		}
		
		function get_sauce(){
			return $this->sauce;
		}
		
		function get_qty(){
			return $this->qty;
		}
		
		function get_price(){
			return $this->price;
		}
		
		function get_name(){
			return "Custom burger";
		}
		
		function set_qty($qty){
			$this->qty = $qty;
		}
	
	}
	
?>