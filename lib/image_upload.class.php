<?php

class Image_upload {

    private $upload_dir = ROOT;

    private $image;

    private $image_type;

    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }
    public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

        // do this or they'll all go to jpeg
        $pathForSave = $this->upload_dir . $filename;
        $image_type=$this->image_type;
        if( $image_type == IMAGETYPE_JPEG ) {
            //imagejpeg($this->image,$filename,$compression);
            imagejpeg($this->image,$pathForSave,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            //imagegif($this->image,$filename);
            imagegif($this->image,$pathForSave);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            // need this for transparent png to work
            imagealphablending($this->image, false);
            imagesavealpha($this->image,true);
            //imagepng($this->image,$filename);
            imagepng($this->image,$pathForSave);
        }
        if( $permissions != null) {
            //chmod($filename,$permissions);
            chmod($pathForSave,$permissions);
        }

        return $filename;
    }
    public function output($image_type=IMAGETYPE_JPEG) {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image);
        }
    }
    public function getWidth() {
        return imagesx($this->image);
    }
    public function getHeight() {
        return imagesy($this->image);
    }
    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }
    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }
    public function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }
    public function resize($width,$height,$forcesize='n') {
        /* optional. if file is smaller, do not resize. */
        if ($forcesize == 'n') {
            if ($width > $this->getWidth() && $height > $this->getHeight()){
                $width = $this->getWidth();
                $height = $this->getHeight();
            }
        }
        $new_image = imagecreatetruecolor($width, $height);
        /* Check if this image is PNG or GIF, then set if Transparent*/
        if(($this->image_type == IMAGETYPE_GIF) || ($this->image_type==IMAGETYPE_PNG)){
            imagealphablending($new_image, false);
            imagesavealpha($new_image,true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
        }
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}

// ПРИМЕР ИСПОЛЬЗОВАНИЯ
//$image = new SimpleImage();
//$image->load($_FILES['picture']['tmp_name']);
//$image->resize(100, 100);
//$test = $image->save(ROOT.DS.'webroot'.DS.'image'.DS.'products'.DS.'small'.DS.'test1.png');
//$image->load($_FILES['picture']['tmp_name']);
//$image->resize(500, 500);
//$test1 = $image->save(ROOT.DS.'webroot'.DS.'image'.DS.'products'.DS.'big'.DS.'test2.png');