<?php
require 'vendor/autoload.php';
$row = 1;

if (($handle = fopen("kospi.csv", "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $num = count($data);

        echo "<p> $num fields in line $row: <br /></p>\n";

        $row++;
        if ($row !== 1) {
            for ($c = 0; $c < $num; $c++) {
                if ($c == 1)
                    echo "기업코드 :: " . $data[$c];

                if ($c == 2)
                    echo "     기업명 :: " . $data[$c] . "<br />\n";
            }
        }

    }

    fclose($handle);

}
?>