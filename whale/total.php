<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Template Name: Realistic
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Timestamp</title>
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
    $(document).ready(function() { 
      // const picker = datepicker('#my-element', {
      //               formatter: (input, date, instance) => {
      //               const value = date.toLocaleDateString()
      //               input.value = value // => '1/1/2099'
      //               },
      //               { defaultDate: new Date() }
      // })
      $('#my-element').data('datepicker');
      var mytimer= setInterval(myFunction, 10000);
      $("#hour").select2();
    });

    function find(){
        if($("#my-element").val()=="")return;
        if($("#hour").val()=="")return;
        myform.action="index.php";
        myform.submit();
    }

    function total(){
        if($("#my-element").val()=="")return;
        myform.action="total.php";
        myform.submit();
    }

    function myFunction(){
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
    <h1><a href="index.php">TIMESTAMP</a></h1>
    <p>TimeStamp</p>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topbar">
    <div class="fl_left">Tel: xxxxx xxxxxxxxxx | Mail: info@domain.com</div>
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
          if(isset($_POST['hour'])){
            $hour=$_POST['hour'];
          }else{
            $hour=0;
          }
          ?>
          <input type="button" name="go" id="go" style="margin:0 20px;" value="Total" onclick="total()"/>
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
          $param="%-$date";
          $total_sql="SELECT `dtime`,sum(`amount_usd`),`timestamp` FROM result where `dtime` like '$param' group by `dtime`;";
          
	$conn = new mysqli("localhost", "timestamp","lancia123");
          mysqli_select_db($conn,"ts");


          $result = mysqli_query($conn,$total_sql);
          echo '<div style="margin:20px;background-color:#EFEFEF;padding:10px;">';
          echo "<table style='margin:0px;'>"; 
          echo "<thead><tr>";    
          echo "<th>DATE</th>";
          echo "<th>HOUR</th>";
          echo "<th>SUBTOTAL</th>";
          echo "<th>TIMESTAMP</th>";
          echo "<th>READABLE TIMESTAMP</th>";
          echo "</tr></thead>";
          while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
            echo "<tr>";    
            $slt=explode("-",$line[0]);
            echo "<td>$slt[1]</td>";
            echo "<td>$slt[0]</td>";
            echo "<td>$line[1]</td>";
            echo "<td>$line[2]</td>";
            $rdtm=date('m/d/Y H:i:s', $line[2]);
            echo "<td>$rdtm</td>";
            echo "</tr>";
          }
          echo "</table>";  
          echo '</div>'; 
      ?>
      <div class="clear"></div>
    </div>

  </div>
</div>
<!-- ####################################################################################################### -->
</body>
</html>
