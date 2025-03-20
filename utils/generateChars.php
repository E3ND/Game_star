<?php
    function generateImageName() {
        return bin2hex(random_bytes(60)) . ".jpg";
    }