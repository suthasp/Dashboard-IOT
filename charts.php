<?php
require_once"include/connection.php";
if(isset($_SESSION["USER_ID"])){
?>

<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "touchcom_esp32";
// REPLACE with Database user
$username = "touchcom_board";
// REPLACE with Database user password
$password = "Engimanyz4714";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, value1, value2, value3, reading_time FROM SensorData order by reading_time desc limit 40";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');

// ******* Uncomment to convert readings time array to your timezone ********
/*$i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading - 1 hours"));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    //$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 4 hours"));
    $i += 1;
}*/

$value1 = json_encode(array_reverse(array_column($sensor_data, 'value1')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'value2')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'value3')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

/*echo $value1;
echo $value2;
echo $value3;
echo $reading_time;*/

$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="gauge1.css" />
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

  <title>Dashboard CN-PBI</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<style>
    body {
 /*      min-width: 310px;
    	max-width: 1200px;
    	height: 500px; */
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.php"><img src="true.png" alt="Dashboard CN-PBI" width="90" height="39"></a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <script>
        var value1 = <?php echo $value1; ?>;
        var value2 = <?php echo $value2; ?>;
        var value3 = <?php echo $value3; ?>;
        var reading_time = <?php echo $reading_time; ?>;
    </script>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <!-- <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-danger">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li> -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- <i class="fas fa-user-circle fa-fw"> --><?php echo $_SESSION["USERNAME"];?><!-- </i> -->
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <h6 class="dropdown-header">Login Screens:</h6>
          <a class="dropdown-item" href="login.html">Login</a>
          <a class="dropdown-item" href="register.html">Register</a>
          <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Other Pages:</h6>
          <a class="dropdown-item" href="404.html">404 Page</a>
          <a class="dropdown-item" href="blank.html">Blank Page</a>
        </div>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="charts.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tables.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">CN-PBI</li>
        </ol>

        <!-- Area Chart Example-->
<!--         <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Temperature Chart</div>
          <div class="card-body">
          <div id="chart-temperature" class="container"></div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div> -->


     <?php      
    $sql = "SELECT id, sensor, location, value1, value2, value3, reading_time FROM SensorData order by id desc limit 10";    
    if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_sensor = $row["sensor"];
        $row_location = $row["location"];
        $row_value1 = $row["value1"];
        $row_value2 = $row["value2"]; 
        $row_value3 = $row["value3"]; 
        $row_reading_time = $row["reading_time"];
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
      
        // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 4 hours"));
      
/*         echo '<tr> 
                <th>' . $row_id . '</th> 
                <th>' . $row_sensor . '</th> 
                <th>' . $row_location . '</th> 
                <th>' . $row_value1 . '</th> 
                <th>' . $row_value2 . '</th> 
                <th>' . $row_value3 . '</th> 
                <th>' . $row_reading_time . '</th>  
              </tr>'; */

/*                 $row_value1 = json_encode(array_reverse(array_column($sensor_data, 'value1')), JSON_NUMERIC_CHECK);
                $row_value2 = json_encode(array_reverse(array_column($sensor_data, 'value2')), JSON_NUMERIC_CHECK);
                $row_value3 = json_encode(array_reverse(array_column($sensor_data, 'value3')), JSON_NUMERIC_CHECK);
                $row_reading_time = json_encode(array_reverse($row_readings_time), JSON_NUMERIC_CHECK); */
        
    }
   /*  $result->free(); */
}

/* $conn->close(); */
?> 
    <script>
        var row_value1 = <?php echo $row_value1; ?>;
        var row_value2 = <?php echo $row_value2; ?>;
        var row_value3 = <?php echo $row_value3; ?>;
        var row_reading_time = <?php echo $row_reading_time; ?>;
    </script>

        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
              <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                Latest Temperature</div>
              <div class="card-body">
              <div class="outer">
                  
              <div id="container-temp" class="chart-container"></div>

          </div>
              </div>
              <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?></div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
              <i class="fa fa-tint" aria-hidden="true"></i>
                Latest Humidity</div>
              <div class="card-body">
              <div class="outer">
              <div id="container-hum" class="chart-container"></div>
                  
            </div>
              </div>
              <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?></div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
              <i class="fas fa-fw fa-tachometer-alt" aria-hidden="true"></i>
                Latest Pressure</div>
              <div class="card-body">
              <div class="outer">
                  
                    <div id="container-press" class="chart-container"></div>
  
            </div>
              </div>
              <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?> </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Temperature Chart</div>
          <div class="card-body">
          <div id="chart-temperature" class="container"></div>
          </div>
          <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?></div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Humidity Chart</div>
          <div class="card-body">
          <div id="chart-humidity" class="container"></div>
          </div>
          <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?></div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Pressure Chart</div>
          <div class="card-body">
          <div id="chart-pressure" class="container"></div>
          </div>
          <div class="card-footer small text-muted">Updated <?$date = date("วันที่ j เดือน n ปี ค.ศ. Y") ;echo $date ;?></div>
        </div>

        <p class="small text-center text-muted my-5">
          <em>More chart examples coming soon...</em>
        </p>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
 <!--      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
          </div>
        </div>
      </footer> -->

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

 <!-- Logout Modal-->
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
          <h1 style="font-size:5.5rem;"><i class="fa fa-sign-out text-danger" aria-hidden="true"></i></h1>
          <p>Are you sure you want to log-out?</p>
      </div>
          <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-bar-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  <script src="node_modules/popper.js/dist/umd/popper.min.js"></script>

  <script>
            var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-temperature' ,
          plotBorderWidth: 0},
  title: { text: 'Temperature' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value1
  }/* , {
    showInLegend: true,
    name: 'Cisco',
    data: value2
  } */],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
     series: { color: '#f55d10' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Temperature (Celsius)',
/*     style: {
                color: '#f55d10'

            } */
    }   
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    responsive: {
        rules: [{
            condition: {
                maxWidth: 1280
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity',plotBorderWidth: 0 },
  title: { text: 'Humidity' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value2
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#7cb5ec' }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Humidity (%RH)' ,
    /*   style: {
                color: '#7cb5ec'

            } */
    }
  },
  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    responsive: {
        rules: [{
            condition: {
                maxWidth: 1200
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    },
  credits: { enabled: false }
});

var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart-pressure',plotBorderWidth: 0 },
  title: { text: 'Pressure' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value3
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#207905' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Pressure (hPa)' ,
 /*      style: {
                color: '#207905'

            } */
    }
  },

  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    responsive: {
        rules: [{
            condition: {
                maxWidth: 1200
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    },

  credits: { enabled: false }
});

</script>

<!-- Gauge chart -->

<script>
var gaugeOptions = {

chart: {
    type: 'solidgauge'
},

title: null,

pane: {
    center: ['50%', '85%'],
    size: '140%',
    startAngle: -90,
    endAngle: 90,
    background: {
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
        innerRadius: '60%',
        outerRadius: '100%',
        shape: 'arc'
    }
},

tooltip: {
    enabled: false
},

// the value axis
yAxis: {
    stops: [
        [0.1, '#55BF3B'], // green
        [0.5, '#DDDF0D'], // yellow
        [0.9, '#DF5353'] // red
    ],
    lineWidth: 0,
    minorTickInterval: null,
    tickAmount: 2,
    title: {
        y: -70
    },
    labels: {
        y: 16
    }
},

plotOptions: {
    solidgauge: {
        dataLabels: {
            y: 5,
            borderWidth: 0,
            useHTML: true
        }
    }
}
};

// The temperature gauge
var chartTemp = Highcharts.chart('container-temp', Highcharts.merge(gaugeOptions, {
yAxis: {
    min: 0,
    max: 100,
    title: {
        text: null
    }
},

credits: {
    enabled: false
},

series: [{
    name: 'Temperature',
    data: value1,
    dataLabels: {
        format:
            '<div style="text-align:center">' +
            '<span style="font-size:25px">{y}</span><br/>' +
            '<span style="font-size:12px;opacity:0.4">Celsius</span>' +
            '</div>'
    },
    tooltip: {
        valueSuffix: ' Celsius'
    }
}]

}));

// The Humidity gauge
var chartHum = Highcharts.chart('container-hum', Highcharts.merge(gaugeOptions, {
yAxis: {
    min: 0,
    max: 100,
    title: {
        text: null
    }
},
credits: {
    enabled: false
},

series: [{
    name: 'Humidity',
    data: value2,
    dataLabels: {
        format:
            '<div style="text-align:center">' +
            '<span style="font-size:25px">{y:.1f}</span><br/>' +
            '<span style="font-size:12px;opacity:0.4">' +
            '%RH' +
            '</span>' +
            '</div>'
    },
    tooltip: {
        valueSuffix: ' %RH'
    }
}]

}));

// The Pressure gauge
var chartPress = Highcharts.chart('container-press', Highcharts.merge(gaugeOptions, {
yAxis: {
    min: 0,
    max: 2000,
    title: {
        text: null
    }
},
credits: {
    enabled: false
},

series: [{
    name: 'Pressure',
    data: value3,
    dataLabels: {
        format:
            '<div style="text-align:center">' +
            '<span style="font-size:25px">{y:.1f}</span><br/>' +
            '<span style="font-size:12px;opacity:0.4">' +
            'hPa' +
            '</span>' +
            '</div>'
    },
    tooltip: {
        valueSuffix: ' %RH'
    }
}]

}));

// Bring life to the dials
setInterval(function () {
// Temperature
var point,
    newVal,
    inc;

if (chartTemp) {
    point = chartTemp.series[0].points[0];
    // inc = Math.round((Math.random() - 0.5) * 100);
    newVal = point.y + inc;

    if (newVal < 0 || newVal > 100) {
        newVal = point.y - inc;
    }

    point.update(newVal);
}

// Humidity
if (chartHum) {
    point = chartHum.series[0].points[0];
    /* inc = Math.random() - 0.5; */
    newVal = point.y + inc;
    

    if (newVal < 0 || newVal > 100) {
        newVal = point.y - inc;
    }

    point.update(newVal);
}

// Pressure
if (chartPress) {
    point = chartPress.series[0].points[0];
    /* inc = Math.random() - 0.5; */
    newVal = point.y + inc;

    if (newVal < 0 || newVal > 100) {
        newVal = point.y - inc;
    }

    point.update(newVal);
}
}, 2000);
</script>

</body>

</html>

<?php 
}else{
    // header("location:login.php");} 
    include"login.php";
}
?>
