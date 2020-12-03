<?php
//chmod('/var/www/html/avaliacao/cvs/', 0777);
// $uploaddir = '/var/www/html/avaliacao/cvs/';
$uploadfile = __DIR__ . '/cvs/' . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";

?>
