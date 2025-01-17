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

//validate function : start
    function validate_total(){
    var total = new Array();
    var total_val = 0;
      $(document).on("click",this,function() {
          $('.datatable_body tr').each(function() {
              var values = $(this).find('.amount_usd').text();
              if(values == ""){
              }else{
                total.push(values);
              }
          });
          //console.log(total);
          for (var i = 0; i < total.length; i++) {
              total_val = parseFloat(total_val)+parseFloat(total[i]);
          }          
          var total_get= $('#total_all').text().replace('TOTAL:','');
          total_get = parseFloat(total_get);
          if(total_get==total_val){
            $('#total_all').css("background-color", "green");
          }
          else{
            $('#total_all').css("background-color", "red");
          }
      });
//validate function : end
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
          <input type="button"  id="go" class="validate" style="margin:0 20px;" value="Verify" onclick="validate_total()" />
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
          if($hour==24){
            $param="%-$date";
            $sql="SELECT * FROM result where `dtime` like '$param';";
            $total_sql="SELECT sum(`amount_usd`) FROM result where `dtime` like '$param';";
          }else{
            $param=$hour."-".$date;   
            $sql="SELECT * FROM result where `dtime`='$param';"; 
            $total_sql="SELECT sum(`amount_usd`) FROM result where `dtime`='$param';";       
          }

          $conn = new mysqli("localhost", "timestamp","lancia123");
          mysqli_select_db($conn,"ts");

          $total=0;
          $result = mysqli_query($conn,$total_sql);
          if($line=mysqli_fetch_array($result,MYSQLI_NUM)){
              $total=$line[0];
          }
          echo "<span style='font-size:18px;' id='total_all'>TOTAL:$total</span>";

          $result = mysqli_query($conn,$sql);
          while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
            $slt=explode("-",$line[0]);
            $t=$slt[0];
            if(round($t/12)==1){
              $t=$slt[0]%12;
              $t.=".pm";
            }else{
              $t=$slt[0]%12;
              $t.=".am";
            }
            $out="DATE:$slt[0]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIME:$t";     
  echo '<div style="margin:20px;background-color:#F0F0F0;padding:10px;">';
  echo "<table style='margin:0px;' class='datatable'>";
  echo "<thead class='datatable_head'><tr><th>Time:</th><th>$out</th></tr></thead>";
  echo "<tbody style='background-color:#EEEEEE;' class='datatable_body'>";
  echo "<tr><td>Hash:</td><td class='hash'>$line[2]</td></tr>";
  echo "<tr><td>From_Address:</td><td class='from_address'>$line[3]</td></tr>";
  echo "<tr><td>From_Owner:</td><td class='from_owner'>$line[4]</td></tr>";
  echo "<tr><td>From_Owner_Type:</td><td class='from_owner_type>$line[5]</td></tr>";
  echo "<tr><td>To_Address:</td><td class='to_address'>$line[6]</td></tr>";
  echo "<tr><td>To_Owner:</td><td class='to_owner'>$line[7]</td></tr>";
  echo "<tr><td>To_Owner_Type:</td><td class='to_owner_type'>$line[8]</td></tr>";
  echo "<tr><td>Amount_Usd:</td><td class='amount_usd'>$line[9]</td></tr>";
  echo "<tr><td>Timestamp:</td><td  class='timestamp'>$line[10]</td></tr>";
  $rdtm=date('m/d/Y H:i:s', $line[10]);
  echo "<tr><td class='timestamp_human'>readable timestamp</td><td>$rdtm</td></tr>";
  echo "</tbody>";
  echo "</table>";  
  echo '</div>'; 
          }
      ?>
      <div class="clear"></div>
    </div>

  </div>
</div>
<!-- ####################################################################################################### -->
</body>
</html>
