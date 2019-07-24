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
<head>
<title>Timestamp</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="layout/styles/layout.css" type="text/css" />
<script type="text/javascript" src="layout/scripts/jquery.min.js"></script>

<link href="layout/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="layout/dist/js/datepicker.min.js"></script>
<script src="layout/dist/js/i18n/datepicker.en.js"></script>
<script type="text/javascript" src="layout/scripts/select2.js"></script>
<script src="layout/scripts/highcharts.js"></script>

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
   
    function week(){
        if($("#my-element").val()=="")return;
        myform.action="week.php";
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
    <h1><a href="index.php">TIMESTAMP</a></h1>
    <p>TimeStamp</p>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
  <div id="topbar">
    <div class="fl_left"><span style="font-size:20px;">Current DateTime(UTC):</span><span id="cdt" name="cdt" style="font-size:20px;margin:10px;"></span></div>
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
          <input type="button" name="go" id="go" style="margin:0 20px;" value="week" onclick="week()"/>
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
    <div class="whitebox">
      <div class="content">
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

          echo '<div style="margin:0px;background-color:#EFEFEF;padding:25px;">';
          echo "<table style='margin:0px;'>"; 
          echo "<thead><tr>";    
          echo "<th>Date</th>";
          echo "<th>Bitcoin</th>";
          echo "<th>Subtotal</th>";
          echo "</tr></thead>";

          $a = explode("/",$date);$j=0;
          $usds=array();
          $usd_string="";
          $usd_string_from="";
          $usd_string_to="";
          $bit_string="";
          $xstring="";
          $bits=array();
          $timestamp0 = strtotime($date)-6*24*3600;
          for($i=0;$i<7;$i++){
              $cdate=date('m/d/Y',$timestamp0+$i*24*3600);
              /****************************/
              $hour=sprintf("%02d",$i);
              $sql="SELECT avg(`rate`) FROM bitcoin where `dtime` like '%-$cdate';";   
              $result = mysqli_query($conn,$sql);
              $bits[$i]=0;
              while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
                if($line[0]==0)break;
                $bits[$i]=intval($line[0]);

              }  
              /*****************************/
              $total_sql="SELECT sum(`amount_usd`),`timestamp` FROM result where  `dtime` like '%-$cdate';";    
              $result = mysqli_query($conn,$total_sql);
              $usds[$i]=0;
              $subtotal=0;
              $timestamp="";
              $rdtm="";
              while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
                $timestamp=$line[1];
                $rdtm=date('m/d/Y H:i:s',$timestamp);
                if($line[0]==""){
                  break;
                }
                $subtotal=$line[0];
              } 
              $npsubtotal=$subtotal;
              $subtotal=intval($npsubtotal);
              /******************************/
              $total_sql="SELECT sum(`amount_usd`),`timestamp` FROM result where `from_owner_type`='exchange' and  `dtime` like '%-$cdate';";    
              $result = mysqli_query($conn,$total_sql);
              $subtotal_from=0;
              $timestamp_from="";
              $rdtm_from="";
              while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
                $timestamp_from=$line[1];
                $rdtm_from=date('m/d/Y H:i:s',$timestamp_from);
                if($line[0]==""){
                  break;
                }
                $subtotal_from=$line[0];
              } 
              $npsubtotal_from=$subtotal_from;
              $subtotal_from=intval($npsubtotal_from);
              /******************************/
              $total_sql="SELECT sum(`amount_usd`),`timestamp` FROM result where `to_owner_type`='exchange' and `dtime` like '%-$cdate';";    
              $result = mysqli_query($conn,$total_sql);
              $subtotal_to=0;
              $timestamp_to="";
              $rdtm_to="";
              while($line=mysqli_fetch_array($result,MYSQLI_NUM)){
                $timestamp_to=$line[1];
                $rdtm_to=date('m/d/Y H:i:s',$timestamp_to);
                if($line[0]==""){
                  break;
                }
                $subtotal_to=$line[0];
              } 
              $npsubtotal_to=$subtotal_to;
              $subtotal_to=intval($npsubtotal_to);
              /******************************/
              if($timestamp=="")continue;
              $usd_string.=($subtotal.",");
              $usd_string_from.=($subtotal_from.",");
              $usd_string_to.=($subtotal_to.",");
              $bit_string.=($bits[$i].",");  
              $xstring.=("'$cdate',");               
               
              if($j%2==0){
                $clr="light";
              }else{
                $clr="dark";
              }
              $j++;
              echo "<tr  class='$clr'>";    
              echo "<td>$cdate</td>";
              echo "<td>$bits[$i]</td>";
              echo "<td>$subtotal</td>";
              echo "</tr>"; 
          }
          echo "</table>";  
          echo '</div>'; 
          mysqli_close($conn);
          ?>
      </div>
      <div class="column">
        <div class="subnav">
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
        <div class="subnav">
        <div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>  
        <div class="subnav">
        <div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>                 
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<!-- ####################################################################################################### -->
</body>
<script>
// Highcharts.chart('container', {
//   chart: {
//     type: 'spline'
//   },
//   title: {
//     text: '<?php //echo $date;?>-Bitcoin And Amount_usd',
//     style: {
//         fontFamily: 'Times New Roman',
//         fontWeight:'bold',
//         fontSize:'20px',
//         color: "#000"
//     }
//   },
//   subtitle: {
//     text: ''
//   },
//   credits: {
//     enabled: false
//   },
//   xAxis: {
//     categories: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11','12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23']
//   },
//   yAxis: {
//     title: {
//       text: 'btc and amount_usd (°C)'
//     }
//   },
//   plotOptions: {
//     line: {
//       dataLabels: {
//         enabled: false
//       },
//       enableMouseTracking: false
//     }
//   },
//   series: [{
//     name: 'bitcoin',
//     color:'#FF0000',
//     data: [<?php //echo $bit_string;?>]
//   }, {
//     name: 'amount_usd',
//     color:'#00FF00',
//     data: [<?php// echo $usd_string;?>]
//   }]
// });

Highcharts.chart('container', {
  chart: {
    zoomType: 'xy'
  },
  credits: {
    enabled: false
  },
  title: {
    text: '<?php echo date('m/d/Y',$timestamp0)."~".$date;?>-Amount Usd And Bitcoin',
    style: {
      fontFamily: 'Times New Roman',
      fontWeight:'bold',
      fontSize:'25px',
      color: "#000"
    }
  },
  xAxis: [{
    categories:[<?php echo $xstring;?>],
    crosshair: true,
    labels: {
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    }
  }],
  yAxis: [{ // Primary yAxis
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    },
    title: {
      text: 'Amount',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
      }
    }
  }, { // Secondary yAxis
    title: {
      text: 'Bitcoin',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
        color:'#FF0000'
      }
    },
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#FF0000'
      }
    },
    opposite: true
  }],
  tooltip: {
    shared: true
  },

  series: [{
    name: 'Bitcoin',
    type: 'line',
    color:'#FF0000',
    yAxis: 1,
    data: [<?php echo $bit_string;?>]
  }, {
    name: 'Amount',
    type: 'line',
    data:[<?php echo $usd_string;?>]
  }]
});

Highcharts.chart('container1', {
  chart: {
    zoomType: 'xy'
  },
  credits: {
    enabled: false
  },
  title: {
    text: '<?php echo date('m/d/Y',$timestamp0)."~".$date;?>-Amount Usd And Bitcoin(from_owner=exchange)',
    style: {
      fontFamily: 'Times New Roman',
      fontWeight:'bold',
      fontSize:'25px',
      color: "#000"
    }
  },
  xAxis: [{
    categories:[<?php echo $xstring;?>],
    crosshair: true,
    labels: {
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    }
  }],
  yAxis: [{ // Primary yAxis
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    },
    title: {
      text: 'Amount',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
      }
    }
  }, { // Secondary yAxis
    title: {
      text: 'Bitcoin',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
        color:'#FF0000'
      }
    },
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#FF0000'
      }
    },
    opposite: true
  }],
  tooltip: {
    shared: true
  },

  series: [{
    name: 'Bitcoin',
    type: 'line',
    color:'#FF0000',
    yAxis: 1,
    data: [<?php echo $bit_string;?>]
  }, {
    name: 'Amount',
    type: 'line',
    data:[<?php echo $usd_string_from;?>]
  }]
});


Highcharts.chart('container2', {
  chart: {
    zoomType: 'xy'
  },
  credits: {
    enabled: false
  },
  title: {
    text: '<?php  echo date('m/d/Y',$timestamp0)."~".$date;?>-Amount Usd And Bitcoin(to_owner=exchange)',
    style: {
      fontFamily: 'Times New Roman',
      fontWeight:'bold',
      fontSize:'25px',
      color: "#000"
    }
  },
  xAxis: [{
    categories:[<?php echo $xstring;?>],
    crosshair: true,
    labels: {
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    }
  }],
  yAxis: [{ // Primary yAxis
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#000'
      }
    },
    title: {
      text: 'Amount',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
      }
    }
  }, { // Secondary yAxis
    title: {
      text: 'Bitcoin',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'16px',
        color:'#FF0000'
      }
    },
    labels: {
      format: '${value}',
      style: {
        fontFamily: 'Times New Roman',
        fontWeight:'bold',
        fontSize:'12px',
        color:'#FF0000'
      }
    },
    opposite: true
  }],
  tooltip: {
    shared: true
  },

  series: [{
    name: 'Bitcoin',
    type: 'line',
    color:'#FF0000',
    yAxis: 1,
    data: [<?php echo $bit_string;?>]
  }, {
    name: 'Amount',
    type: 'line',
    data:[<?php echo $usd_string_to;?>]
  }]
});
</script>
</html>