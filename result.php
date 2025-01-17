<?php
include "constant.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Template Name: Realistic
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144180751-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-144180751-1');
</script>

<title>Bitcoin Crypto Flow</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="layout/styles/layout.css" type="text/css" />
<script type="text/javascript" src="layout/scripts/jquery.min.js"></script>

<link href="layout/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="layout/dist/js/datepicker.min.js"></script>
<script src="layout/dist/js/i18n/datepicker.en.js"></script>

<link href="layout/dist1/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="layout/dist1/datepicker.min.js"></script>

<script type="text/javascript" src="layout/scripts/select2.js"></script>
<link rel="stylesheet" href="layout/styles/select2.css" type="text/css" />
<script type="text/javascript">
    var mytimer;
    var timer_cnt=0;
    var timestamp=<?php echo time();?>;
    $(document).ready(function() { 
      $('#my-element').data('datepicker');
      var mytimer= setInterval(myFunction, 1000);
      $("#hour").select2();
    });

    function find(){
        if($("#my-element").val()=="")return;
        if($("#hour").val()=="")return;
        myform.action="result.php";
        myform.submit();
    }

    function graph(){
        if($("#my-element").val()=="")return;
        myform.action="index.php";
        myform.submit();
    }

    function myFunction(){
      timer_cnt++;
      /************************ */
      timestamp++;
      date=new Date(timestamp*1000);
      var year = date.getFullYear();
      var month =date.getMonth()+1;
      var day = date.getDate();
      var hours = date.getHours();
      var minutes = "0" + date.getMinutes();
      var seconds = "0" + date.getSeconds();
      var convdatetime = month+'-'+day+'-'+year+' '+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
      document.getElementById("cdt").innerHTML=convdatetime;
      /************************ */
      if(timer_cnt!=10)return;
      timer_cnt=0;
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      var date = mm+"/"+dd+"/"+yyyy;
      var hour= String(today.getHours()).padStart(2,'0');
      var param=hour+"-"+date;
      $.ajax({
        type: "POST",
        url:"ajax.php",
        data:{"param":param},
        dataType: "text",
        success: function(response) {
            console.log(response);
        },
        error: function(response) {
          console.log("error");
        }
      });
    }
</script>
</head>
<body id="top">
<div class="wrapper">
  <div id="header">
    <h2><a href="index.php">Crypto Flow</a></h2>
    <p>Analyzing Bitcoin price against whale activity</p>  
</div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topbar">
    <div class="fl_left"><span style="font-size:20px;">Current DateTime:</span><span id="cdt" name="cdt" style="font-size:20px;"></span></div>
    <div class="fl_right">
      <form action="#" method="post" name="myform" id="myform">
        <fieldset>
          <?php
          $current_date=date("m/d/Y");
          if(isset($_POST['my-element'])){
            $date=$_POST['my-element'];
          }else{
            $date=$current_date;
          }
          $current_hour=date("H");
          echo $current_hour;
          if(isset($_POST['hour'])){
            $hour=$_POST['hour'];
          }else{
            $hour=$current_hour;
          }
          ?>
          <input type="button" name="go" id="go" style="margin:0 20px;" value="graph" onclick="graph()"/>
          <input type="button" name="go" id="go" style="margin:0 20px;" value="result" onclick="find()"/>
          <span style="float:left;margin:5px 5px 0 0;">DATE:</span>
          <input type="text" id="my-element" name="my-element"  value="<?php echo $date;?>" class="datepicker-here"  data-language='en' autocomplete="off">
          <span style="float:left;margin:5px 0px 5px 20px;">HOUR:</span>
          <select id="hour" name="hour" style="width:80px;float:left;">
            <?php
            $cap=array("0(am)","1(am)","2(am)","3(am)","4(am)","5(am)","6(am)","7(am)","8(am)","9(am)","10(am)","11(am)","12(am)","1(pm)","2(pm)","3(pm)","4(pm)","5(pm)","6(pm)","7(pm)","8(pm)","9(pm)","10(pm)","11(pm)");
            
            if(24==$hour){
              echo "<option value='24' selected>all</option>";   
            }else{
              echo "<option value='24'>all</option>";   
            }
            for($i=0;$i<24;$i++){
              $s = sprintf('%02d', $i);
              if($i==$hour){
                echo "<option value='$s' selected>$cap[$i]</option>";   
              }else{
                echo "<option value='$s'>$cap[$i]</option>";   
              }
            }
            ?>
          </select>
        </fieldset>
      </form>
    </div>
    <br class="clear" />
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div class="container">
    <div class="whitebox" id="hpage_services">
      <?php
          $conn = new mysqli("localhost", "root",dbpass);
          if (empty (mysqli_fetch_array(mysqli_query($conn,"SHOW DATABASES LIKE 'timestamp' ")))){
              $sql = "CREATE DATABASE timestamp";
              $conn->query($sql);
              mysqli_select_db($conn,"timestamp");
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
                  `amount_usd`       DOUBLE       NOT NULL,
                  `timestamp`        VARCHAR (20) NOT NULL
              );";
              mysqli_query($conn,$sql);
              $sql="CREATE TABLE bitcoin(
                  `id`               INT(16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  `dtime`            VARCHAR(20)   NOT NULL,
                  `rate`             FLOAT         NOT NULL
              );";
              mysqli_query($conn,$sql);
              //echo "DB does Not exist";
          }else{
              mysqli_select_db($conn,"timestamp");
          }

          if($hour==24){
            $a = explode("/",$date);
            $timestamp = mktime(24,0,0,$a[0],$a[1],$a[2]);
            $timestamp_low=$timestamp-3600*24;
            $param=$hour."-".$date;  
            $hour="0~24"; 
            $sql="SELECT `dtime`, `hash`, `from_address`, `from_owner`, `from_owner_type`, `to_address`, `to_owner`, `to_owner_type`, `amount_usd`, `timestamp` FROM result where  `dtime` like '%$date';"; 
            $total_sql="SELECT sum(`amount_usd`) FROM result where `from_owner_type`='exchange' and `dtime` like '%$date';";   
            // $sql="SELECT `dtime`, `hash`, `from_address`, `from_owner`, `from_owner_type`, `to_address`, `to_owner`, `to_owner_type`, `amount_usd`, `timestamp` FROM result where `timestamp`>$timestamp_low and `timestamp`<$timestamp;"; 
            // $total_sql="SELECT sum(`amount_usd`) FROM result where `timestamp`>$timestamp_low and `timestamp`<$timestamp;";   
          }else{
            $a = explode("/",$date);
            $timestamp = mktime($hour,0,0,$a[0],$a[1],$a[2]);
            $timestamp_low=$timestamp-3600; 
            $sql="SELECT `dtime`, `hash`, `from_address`, `from_owner`, `from_owner_type`, `to_address`, `to_owner`, `to_owner_type`, `amount_usd`, `timestamp` FROM result where `dtime`='$hour-$date';"; 
            $total_sql="SELECT sum(`amount_usd`) FROM result where `from_owner_type`='exchange' and `dtime`='$hour-$date';";       
            // $sql="SELECT `dtime`, `hash`, `from_address`, `from_owner`, `from_owner_type`, `to_address`, `to_owner`, `to_owner_type`, `amount_usd`, `timestamp` FROM result where `timestamp`>$timestamp_low and `timestamp`<$timestamp;"; 
            // $total_sql="SELECT sum(`amount_usd`) FROM result where `timestamp`>$timestamp_low and `timestamp`<$timestamp;";       
          }

          $total=0;
          $result = mysqli_query($conn,$total_sql);
          if($line=mysqli_fetch_array($result,MYSQLI_NUM)){
              $total=$line[0];
          }
          $nptotal=intval($total);
          echo "<span style='font-size:18px;'>TOTAL:$nptotal</span>";

          $result = mysqli_query($conn,$sql);
          while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
            $out="DATE:$date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIME:$hour";     
            echo '<div style="margin:20px;background-color:#F0F0F0;padding:10px;">';
            echo "<table style='margin:0px;'>";
            echo "<thead><tr><th>Time:</th><th>$out</th></tr></thead>";
            echo "<tbody style='background-color:#EEEEEE;'>";
            echo "<tr><td>Hash:</td><td><a  style='color:blue;' target='_blank' href='https://www.blockchain.com/btc/tx/$line[1]'>www.blockchain.com/btc/tx/$line[1]</a></td></tr>";
            echo "<tr><td>From_Address:</td><td>$line[2]</td></tr>";
            echo "<tr><td>From_Owner:</td><td>$line[3]</td></tr>";
            echo "<tr><td>From_Owner_Type:</td><td>$line[4]</td></tr>";
            echo "<tr><td>To_Address:</td><td>$line[5]</td></tr>";
            echo "<tr><td>To_Owner:</td><td>$line[6]</td></tr>";
            echo "<tr><td>To_Owner_Type:</td><td>$line[7]</td></tr>";
            echo "<tr><td>Amount_Usd:</td><td>$line[8]</td></tr>";
            echo "<tr><td>Timestamp:</td><td>$line[9]</td></tr>";
            $rdtm=date('m/d/Y H:i:s', $line[9]);
            echo "<tr><td>readable timestamp</td><td>$rdtm</td></tr>";
            echo "</tbody>";
            echo "</table>";  
            echo '</div>'; 
          }
          mysqli_close($conn);
      ?>
      <div class="clear"></div>
    </div>

  </div>
</div>
<!-- ####################################################################################################### -->
</body>
</html>
