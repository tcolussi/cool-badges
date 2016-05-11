<?php
	include("photo.php");
	class mergePictures{
		/**
		 * Imagem 1 var
		 *
		 * @var photo
		 */
		public $photo1;
		
		/**
		 * Image2 var
		 *
		 * @var photo
		 */
		public $photo2;
		
		/**
		 * Destination Image
		 *
		 * @var Resource imagecreatetruecolor
		 */
		public $image;
		
		/**
		 * resize percentage of image 2
		 *
		 * @var float
		 */
		public $perc;
		
		/**
		 * co-ordinated X
		 *
		 * @var float
		 */
		public $x;
		
		/**
		 * co-ordinated Y
		 *
		 * @var float
		 */
		public $y;
		/**
		 * Class constructor
		 *
		 * @param String $file1 File Name1
		 * @param String $file2 File Name2
		 * @return mergePictures
		 * 
		 * @uses $file1 and $file2 has to be a JPG or PNG file
		 */
		function mergePictures($file1,$file2){
			//  it verifies which is the source picture type
			if(strtolower( substr($file1,strlen($file1)-3 ,strlen($file1)))=="png"){
				$this->photo1 = new photoPng($file1);
				$png = true;
			}else {
				$png = false;
				$this->photo1 = new photo($file1);
				
			}
			
			
			//same thing for the picture 2
			if(strtolower( substr($file2,strlen($file2)-3 ,strlen($file2)))=="png"){
				$this->photo2 = new photoPng($file2);
			}else{
				$this->photo2 = new photo($file2);
			}
			
			//percentage of reduction of imagem2
			$this->perc = 1;
			if ($this->photo1->tam < $this->photo2->tam) {
				$this->perc= ($this->photo2->tam - $this->photo1->tam)*100/ $this->photo2->tam ;
				$this->perc =1- $this->perc/100;
			}
			if ($this->photo1->tam > $this->photo2->tam) {
				$this->perc= ($this->photo1->tam - $this->photo2->tam)*100/ $this->photo2->tam ;
				$this->perc = 1 + $this->perc/100;
			}
			$this->x = $this->photo1->tam;
			$this->y = $this->photo1->alt + $this->photo2->alt*$this->perc;
		}
		/**
		 * it catches image1 and it places on proportionally reduced image2
		 *
		 */
		function over(){
		
			$this->image = imagecreatetruecolor($this->x,$this->photo1->alt);
			imagecopyresampled($this->image,$this->photo1->img,0,0,0,0, $this->photo1->tam,$this->photo1->alt, $this->photo1->tam ,$this->photo1->alt);
			$x_ideal = $this->photo1->tam - $this->photo2->tam;
			$y_ideal = $this->photo1->alt - $this->photo2->alt;
			imagecopy($this->image, $this->photo2->img,$x_ideal,$y_ideal,0,0, $this->photo2->tam, $this->photo2->alt);
		}
		/**
		 * it catches proportionally reduced image 2 and it joins it with imagem1
		 *
		 * @param String $pos The position where image 1 will be(up or down)
		 */
		function merge($pos){
			$this->image = imagecreatetruecolor($this->x,$this->y);
			//	imagealphablending( $this->image, true );
			switch ($pos) {
				case "up":
					imagecopy( $this->image, $this->photo1->img , 0 , $this->photo2->alt*$this->perc , 0, 0, $this->photo1->tam, $this->photo1->alt);
					imagecopyresampled($this->image,$this->photo2->img,0, 0,0,0 , $this->photo1->tam ,$this->y-$this->photo1->alt , $this->photo2->tam ,$this->photo2->alt);
					break;
				case "down":
					imagecopy( $this->image, $this->photo1->img , 0 , 0, 0, 0, $this->photo1->tam, $this->photo1->alt);
					imagecopyresampled($this->image,$this->photo2->img,0, $this->photo1->alt,0,0 , $this->photo1->tam ,$this->y-$this->photo1->alt , $this->photo2->tam ,$this->photo2->alt);
					break;
			}
		}
		/**
		 * Saves the new picture
		 *
		 * @param String $dirName Path Location
		 * @param String $nameFile File Name
		 * @param String $ext File extension (JPG,PNG or GIF)
		 */	
		function save($dirName, $nameFile, $ext){
			if($dirName!=""){
				$dirName.="/";
			}
			
			if($nameFile!="" and $ext!="" and ($ext=="jpg" or $ext=="gif" or $ext="png")){			
				switch (strtolower($ext)){
					case "jpg":
						imagejpeg($this->image, $dirName.$nameFile.".jpg");
						break;
					case "gif":
						imagegif($this->image, $dirName.$nameFile.".gif");
						break;
					case "png":
						imagepng($this->image, $dirName.$nameFile.".png");
						break;
				}
			}
		}
	}
?>
