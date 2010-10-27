<?php
class A{
	
	public static function test(){
		echo __CLASS__;
	}
	
	public static function blah(){
		echo __CLASS__;
	}
}

class B extends A{
	public static function test(){
		echo __CLASS__;
	}
}

B::test();
B::blah();
?>