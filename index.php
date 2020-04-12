<?php
ini_set("memory_limit" , -1);
require 'vendor/autoload.php';
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

$mysql_hostname = 'database-1.cwugzci3e2pd.ap-northeast-2.rds.amazonaws.com';

$username = "admin"; // 데이터베이스 ID (수정요망)
$password = "uSaTQ3ZeUP8WEZR"; // 데이터베이스 PW (수정요망)
$dbname = "stock"; //데이터베이스명 (수정요망)

// Create connection
$conn = mysqli_connect($mysql_hostname, $username, $password, $dbname);
// Check connection
if (!$conn) {
    $error = mysqli_connect_error();
    print $error .": error\n";
    exit();
}

$client = new Client(HttpClient::create(['timeout' => 60]));
$sql = 'INSERT INTO stock VALUES';
for($i =1 ; $i <101 ; $i++){
    $url = 'https://finance.naver.com/item/sise_day.nhn?code=005930&page='.$i;

    $crawler = $client->request('GET', $url);


    $news = $crawler->filter('table')->filter('tr')->each(
        function ($tr, $i) {

            if ($i <16){
                $realData = $tr->filter('td')->each(function ($td, $i) {
                    $rVal = trim($td->text());

                    if ($i === 0){
                        $dd = str_replace('.','',$rVal);
                        $rVal = date("Y-m-d",strtotime($dd));
                    }elseif ($i === 2){
                        if(strpos($td->filter('span')->attr('class'),'nv01') !== false){
                            $dd = str_replace(',','',$rVal);
                            $rVal = -$dd;
                        }else{
                            $rVal = str_replace(',','',$rVal);
                        }

                    }else{
                        $rVal = str_replace(',','',$rVal);
                    }
                    return $rVal;

                });
                if (sizeof($realData) === 7)
                    return $realData;
            }

        });

    foreach ($news as $item){
        if ($item !== '' && $item !== null) {
            $name = '삼성전자';
            $code= '005930';
            $sql .= sprintf('("%s","%s",%d,%d,%d,%d,%d,%d,"%s","%s"),',$code,$name,$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[0],date('Y-m-d H:i:s'));
        }
    }

    sleep(1);
}

$insertSql = substr($sql , 0, -1);

if ($conn->query($insertSql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "
" . $conn->error;
}

$conn->close();

echo $insertSql;


