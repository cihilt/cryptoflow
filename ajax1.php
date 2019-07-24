<?php
include "constant.php";
$conn = new mysqli("localhost", "root",dbpass);
if (empty (mysqli_fetch_array(mysqli_query($conn,"SHOW DATABASES LIKE 'timestamp' ")))){
    $sql = "CREATE DATABASE timestamp";
    $conn->query($sql);
    $sql="CREATE TABLE result(
        `id`               INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `dtime`            VARCHAR(20)   NOT NULL,
        `hash`             VARCHAR (200) NOT NULL,
        `from_address`     VARCHAR (200) NOT NULL,
        `from_owner`       VARCHAR (20) NOT NULL,
        `from_owner_type`  VARCHAR (20) NOT NULL,
        `to_address`       VARCHAR (200) NOT NULL,
        `to_owner`         VARCHAR (20) NOT NULL,
        `to_owner_type`    VARCHAR (20) NOT NULL,
        `amount_usd`       FLOAT        NOT NULL,
        `timestamp`        VARCHAR (20) NOT NULL
     );";
     mysqli_select_db($conn,"timestamp");
     mysqli_query($conn,$sql);
    //echo "DB does Not exist";
}else{
    //echo "DB  exists!";
}
mysqli_close($conn);


$_total=0;
date_default_timezone_set('UTC');
$_date=date("m/d/Y");
$_hour=date('H');
$param=$_hour."-".$_date;
$conn = new mysqli("localhost", "root",dbpass);
mysqli_select_db($conn,"timestamp");
$result = mysqli_query($conn,"select * from `result` where `dtime`='$param'");
if(mysqli_num_rows($result)){
    echo "success(no neccessay)";
    die;
}

$timestamp=time()-3599;
$service_url = "https://api.whale-alert.io/v1/transactions?api_key=qyyl3RvjdDMkelzJC8s8Vxk0rQ7eFrv9&min_value=500000&start=$timestamp";
$ch = curl_init($service_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content=curl_exec($ch);
curl_close($ch);
$nodes=json_decode($content);//convert to scraped data to json object.
$Property = 'transactions';
//print_r($nodes);die;
if (property_exists($nodes, $Property))
{
    $rows=$nodes->$Property;
    $kflag=false;
    $add_flag=false;
    foreach($rows as $row){
        $symbol=$row->symbol;
        if($symbol!="btc")continue;
        $hash=$row->hash;
        $timestamp=$row->timestamp;
        $amount_usd=$row->amount_usd;
        if(isset($amount_usd)){
            $_total+=$amount_usd;
        }

        $from_address=$row->from->address;
        $from_owner_type=$row->from->owner_type;
        if($from_owner_type=="unknown"){
            $from_owner="unknown";
        }else{
            $from_owner=$row->from->owner;
        }
        $to_address=$row->to->address;
        $to_owner_type=$row->to->owner_type;
        if($to_owner_type=="unknown"){
            $to_owner="unknown";
        }else{
            $to_owner=$row->to->owner;
        }
        $output="'$param','$hash','$from_address','$from_owner','$from_owner_type','$to_address','$to_owner','$to_owner_type','$amount_usd','$timestamp'";
        $kflag=true;
        $sql="INSERT INTO `result` (`dtime`, `hash`, `from_address`, `from_owner`, `from_owner_type`, `to_address`, `to_owner`, `to_owner_type`, `amount_usd`, `timestamp`) VALUES ($output);";
        mysqli_query($conn,$sql);
    }
    echo "success";
}else{
    echo "no";
}

?>