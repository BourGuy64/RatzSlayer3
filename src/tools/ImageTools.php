<?php namespace ratzslayer3\tools;


class ImageTools {

    public function __construct($file, $name) {
        $this->file = $file;
        $this->name = $name;
        $this->fileName = $this->generateFileName($name);
    }

    private function generateFileName() {
        $fileInfo = pathinfo($this->file['name']);
        $extension = $fileInfo['extension']; // get the extension of the file
        $fileName = $this->name . '.' . $extension;
        return $fileName;
    }

    public function upload() {

        $target = 'src/img/fighters/' . $this->fileName; // better link maybe ?
        move_uploaded_file($this->file['tmp_name'], $target);

        return true;
    }

    public function getFileName() {
        return $this->fileName;
    }

}
