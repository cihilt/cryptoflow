<?php
$_total=0;
$param=$_POST['param'];

          $conn = new mysqli("localhost", "timestamp","lancia123");
          mysqli_select_db($conn,"ts");


$result = mysqli_query($conn,"select * from `result` where `dtime`='$param'");
if(mysqli_num_rows($result)){
    echo "success(no neccessay)";die;
}

$timestamp=time()-1800;
$service_url = "https://api.whale-alert.io/v1/transactions?api_key=qyyl3RvjdDMkelzJC8s8Vxk0rQ7eFrv9&min_value=500000&start=$timestamp";
//echo $service_url;
$ch = curl_init($service_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content=curl_exec($ch);
curl_close($ch);
$nodes=json_decode($content);//convert to scraped data to json object.
$Property = 'transactions';
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
        echo "success$$";
    }
    echo "success";
}else{
    echo "no";
}

?>
