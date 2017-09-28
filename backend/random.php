<?php
	 function generateRand($length = 5) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function generateRefCode() {
    $characters = '01MpqX9ABrstVIJwxGYZWEF345ogSTChi2yzvQRcuKLjklmndefNOabPUD678H';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 7; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
};
?>