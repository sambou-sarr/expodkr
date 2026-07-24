<?php
$ch = curl_init('https://api.cloudinary.com/v1_1/dstbqtuxm/image/upload');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CAINFO, 'D:\php\cacert.pem');
$result = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "Résultat: " . ($result ?: 'AUCUN') . "\n";
echo "Erreur: " . ($error ?: 'AUCUNE') . "\n";
echo "Info: " . print_r($info, true);