<?php
    require_once("utils/generateChars.php");

    function upload($file, $type) {
        if(isset($file["image"]) && !empty($file["image"]["tmp_name"])) {
            $image = $file["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            if(in_array($image["type"], $imageTypes)) {
                if(in_array($image["type"], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }

                $imageName = generateImageName();

                imagejpeg($imageFile, "./img/" . $type ."/" . $imageName, 100);

                return $imageName;
            } else {
                return false;
            }
        }
    }