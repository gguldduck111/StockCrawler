<?php

/**
 *
 *  EX ) localhost:9999?s=1&e=11&m=kosdaq
 *
 */


@set_time_limit(0);
ini_set('memory_limit', -1);
require 'vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

if (isset($_GET['s']) === false || isset($_GET['e']) === false || isset($_GET['m']) === false) {
    echo '망';
    exit;
}

$start = $_GET['s'];
$end = $_GET['e'];
$market = $_GET['m'].'.csv';

$mysql_hostname = 'database-1.cwugzci3e2pd.ap-northeast-2.rds.amazonaws.com';

$username = 'admin'; // 데이터베이스 ID (수정요망)
$password = 'uSaTQ3ZeUP8WEZR'; // 데이터베이스 PW (수정요망)
$dbname = 'stock'; //데이터베이스명 (수정요망)

// Create connection
$conn = mysqli_connect($mysql_hostname, $username, $password, $dbname);
// Check connection
if (!$conn) {
    $error = mysqli_connect_error();
    print $error . ": error\n";
    exit();
}

mysqli_set_charset($conn, 'utf8');

$row = 1;

if (($handle = fopen($market, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $cName = '';
        $cCode = '';
        $num = count($data);

        $row++;
        for ($c = 0; $c < $num; $c++) {
            if ($c === 1) {
                if ($data[$c] === '종목코드' && $data[$c] === '' && (int)$data[$c] === 0)
                    break;
                $cCode = $data[$c];
            }

            if ($c === 2) {
                $cName = $data[$c];
            }
        }

        $client = new Client(HttpClient::create(['timeout' => 60]));
        $sql = 'INSERT INTO stock VALUES';
        for ($i = $start; $i < $end; $i++) {
            $url = sprintf('https://finance.naver.com/item/sise_day.nhn?code=%s&page=%d', $cCode, $i);
            $crawler = $client->request('GET', $url);

            $pageNo = $crawler->filterXPath('//table[@class="Nnavi"]')->filter('tr')->each(
                function ($tr, $i) {
                    $tmpPage = $tr->filterXPath('//td[@class="on"]')->each(function ($atag, $i) {
                        return $atag->text();
                    });

                    return $tmpPage[0];
                }
            );

            if ((int)$pageNo[0] !== (int)$i)
                break;

            $news = $crawler->filterXPath('//table[@class="type2"]')->filter('tr')->each(
                function ($tr, $i) {
                    $realData = $tr->filter('td')->each(function ($td, $i) {
                        $rVal = str_replace("\xc2\xa0", '', $td->text());
                        if ($rVal !== '') {
                            if ($i === 0) {
                                $dd = str_replace('.', '', $rVal);
                                $rVal = date('Y-m-d', strtotime($dd));
                            } elseif ($i === 2) {
                                if (strpos($td->filter('span')->attr('class'), 'nv01') !== false) {
                                    $dd = str_replace(',', '', $rVal);
                                    $rVal = -$dd;
                                } else {
                                    $rVal = str_replace(',', '', $rVal);
                                }

                            } else {
                                $rVal = str_replace(',', '', $rVal);
                            }
                            return $rVal;
                        }
                    });
                    if (count($realData) === 7)
                        return $realData;

                });
            $insertData = array_filter($news);
            foreach ($insertData as $item) {
                if ($item !== '' && $item !== null) {
                    $sql .= sprintf('("%s","%s",%d,%d,%d,%d,%d,%d,"%s","%s"),', $cCode, $cName, $item[1], $item[2], $item[3], $item[4], $item[5], $item[6], $item[0], date('Y-m-d H:i:s'));
                }
            }

            sleep(0.5);
        }

        $insertSql = substr($sql, 0, -1);

        if ($conn->query($insertSql) === TRUE) {
            echo $cName . ' :: New record created successfully <br>';
        } else {
            echo 'Error: ' . $sql . ' ' . $conn->error;
        }
    }

    fclose($handle);
}
$conn->close();


