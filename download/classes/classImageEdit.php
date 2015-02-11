<?php
/*
Скрипт загруз центра для JohnCMS
Автор: Максим (simba)
ICQ: 61590077
Сайт: http://symbos.su
R866920725287
Z117468354234
*/

/////////////////////////////////////
// Класс для обработки изображений //
/////////////////////////////////////

class ImageEdit{
    
    private $mimetype;
    private $imageproperties;
    private $quality = 100; // Качество
    private $weight;
    private $height;
    private $size = FALSE;
    private $error = FALSE;
    
    // Констуктор...
    public function __construct($file, $imagesize = 100, $size = FALSE){
    // $file - путь до файла.
    // $imagesize - размер до которого будет урезано изображение. 0 - оставить исходные размеры.
    // $size - заданные размеры (непропорциональное изменение размера)
    
    if(!is_file($file))
    $file = 'img/img_not_found.jpg';
    
    if(!$this->imageproperties = getimagesize($file)){
        $this->imageproperties = getimagesize('img/img_not_found.jpg');
        $file = 'img/img_not_found.jpg';
        $this->error = TRUE;
    }
    // Свойства файла
    $this->mimetype = image_type_to_mime_type($this->imageproperties[2]);
    // MIME тип файла
    switch($this->imageproperties[2]){
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($file);	
				break;
			case IMAGETYPE_GIF:	
				$this->image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($file);
				break;
			default:
				die('Не удалось создать изображение.');
		}
    
    if($size){
        $sizes = explode("x", $size);
        $this->setSize(intval($sizes[1]), intval($sizes[0]));
    }
    
    $this->createThumb($imagesize);
    }
    
    // Деструктор класса.
    public function __destruct(){
		if(isset($this->image)){
			imagedestroy($this->image);			
		}
	}
    
    // Создание мини превьюшки //
    private function createThumb($imagesize){
        // Ширина / высота
		$srcW = $this->imageproperties[0];
		$srcH = $this->imageproperties[1];
		// Перегоняем размеры
		
        if($this->size){
		  
          // Непропорциональный перегон
			$copy = imagecreatetruecolor($this->weight, $this->height);			
			imagecopyresampled($copy,$this->image,0,0,0,0,$this->weight, $this->height, $srcW, $srcH)
				 or die ('Не удалось создать копию изображения для обработки.');
			//Удаляем оригинал
			imagedestroy($this->image);
			$this->image = $copy;
            
		}elseif(($srcW >$imagesize || $srcH > $imagesize) && $imagesize != 0){
		  
          // Порциональный перегон
			$reduction = $this->calculateReduction($imagesize);
			//Получаем пропорциональные размеры
  		$desW = $srcW/$reduction;
  		$desH = $srcH/$reduction;
        $this->weight = $desW;
        $this->height = $desH;							
			$copy = imagecreatetruecolor($desW, $desH);			
			imagecopyresampled($copy,$this->image,0,0,0,0,$desW, $desH, $srcW, $srcH)
				 or die ('Не удалось создать копию изображения для обработки.');			
			//Удаляем оригинал
			imagedestroy($this->image);
			$this->image = $copy;			
		
        }else{
            
            // Оригинальные размеры
            $this->weight = $srcW;
            $this->height = $srcH;	
		}
	}
    
    
    // Пересчёт размера //
    private function calculateReduction($imagesize){
		$srcW = $this->imageproperties[0];
		$srcH = $this->imageproperties[1];
  	if($srcW < $srcH){
  		$reduction = round($srcH/$imagesize);
  	}else{
  		$reduction = round($srcW/$imagesize);
  	}
		return $reduction;
	}
    
    // Установка размеров //
	private function setSize($height, $weight){
	if($height > 0 && $weight > 0){
	 $this->weight = $weight;
     $this->height = $height;
     $this->size = TRUE;
    }
	}
    
    // Установка качества //
	public function setQuality($quality){
		if($quality > 100 || $quality  <  1){
			$quality = 75;
    }
        // Качество меняется только на JPEGах //
		if($this->imageproperties[2] == IMAGETYPE_JPEG){
			$this->quality = $quality;
		}
	}
    
    // Получить качество //
    public function getQuality(){
		$quality = null;
		if($this->imageproperties[2] == IMAGETYPE_JPEG){
			$quality = $this->quality;
		}
		return $quality;
	}
    
    
    
    // Установка копирайта //
	public function setCopy($font_size, $text, $position = 0){
	// imagefttext ($this->image, $font_size, 0, 1, $font_size+2, $font_color, $font_file, $text);	
    // определяем координаты вывода текста
    if(!$this->error){
    if($position == 1){
    // Верхний левый угол
    $icx_x_text = 1;
    $icx_y_text = 1;
    }else{
    // Нижний правый угол.
    $icx_x_text = $this->weight-imagefontwidth($font_size)*strlen($text)-3;
    $icx_y_text = $this->height-imagefontheight($font_size)-3;
    }
    // определяем каким цветом на каком фоне выводить текст
    $icx_white = imagecolorallocate($this->image, 255, 255, 255);
    $icx_black = imagecolorallocate($this->image, 0, 0, 0);
    $icx_gray = imagecolorallocate($this->image, 127, 127, 127);
    if (imagecolorat($this->image,$icx_x_text,$icx_y_text)>$icx_gray) $icx_color = $icx_black;
    if (imagecolorat($this->image,$icx_x_text,$icx_y_text)<$icx_gray) $icx_color = $icx_white;
    // выводим текст
    imagestring($this->image, $font_size, $icx_x_text-1, $icx_y_text-1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text+1, $icx_y_text+1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text+1, $icx_y_text-1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text-1, $icx_y_text+1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text-1, $icx_y_text,   $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text+1, $icx_y_text,   $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text,   $icx_y_text-1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text,   $icx_y_text+1, $text,$icx_white-$icx_color);
    imagestring($this->image, $font_size, $icx_x_text,   $icx_y_text,   $text,$icx_color);
    }
    }
    
    
    
    // Выдача без сохранения //
    public function getImage(){
		header("Content-type: $this->mimetype");
        // Посылаем браузеру заголовок с MIME типом.
		switch($this->imageproperties[2]){
			case IMAGETYPE_JPEG:
				imagejpeg($this->image,"",$this->quality);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image);
				break;
			case IMAGETYPE_PNG:
				imagepng($this->image);
				break;
			default:
				die('Не удалось создать изображение для вывода.');
		}
	}
    
    
    // Сохранение изображения на диск //
    public function saveImage($saveas){
        // $saveas - сохранить как и куда. Подавать без расширения.
		switch($this->imageproperties[2]){
			case IMAGETYPE_JPEG:
				imagejpeg($this->image, $saveas, $this->quality);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image, $saveas);
				break;
			case IMAGETYPE_PNG:
				imagepng($this->image, $saveas);
				break;
			default:
				die('Не удалось сохранить изображение.');
		}
	}
    
   
    
    }

?>