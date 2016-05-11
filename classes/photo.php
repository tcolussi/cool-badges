<?php
	class photo{
		public $alt;
		public $tam;
		public $img;
		public $name;
		function photo($name){
			$dim = getimagesize($name);
			$this->tam = $dim[0];
			$this->alt = $dim[1];
			$this->img = imagecreatefromjpeg($name);
			$this->name = $name;
		}
	}
	class photoPng{
		public $alt;
		public $tam;
		public $img;
		public $name;
		function photoPng($name){
			$dim = getimagesize($name);
			$this->tam = $dim[0];
			$this->alt = $dim[1];
			$this->img = imagecreatefrompng($name);
			$this->name = $name;
		}
	}
?>