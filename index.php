<?php require_once('header.php');?>
<body class="hold-transition sidebar-mini layout-fixed light-mode">
<div class="wrapper">

    <?php require_once('warpper.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <section class="content">

    <div class="container-fluid">
        <h3 class="text-dark">รับสมัครนักเรียน 2568</h3>
        <hr>
        <h4 class="text-primary">ยอดผู้สมัคร</h4>
        <div class="row">
                <?php
                function createSmallBox($number, $description, $bgClass, $iconClass) {
                    return "
                    <div class=\"col-lg-6 col-sm-12 col-md-6\">
                        <div class=\"small-box $bgClass\">
                            <div class=\"inner\">
                                <h3>$number</h3>
                                <p>$description</p>
                            </div>
                            <div class=\"icon\">
                                <i class=\"$iconClass\"></i>
                            </div>
                        </div>
                    </div>";
                }

                require_once('config/Database.php');
                require_once('class/StudentRegis.php');

                $connectDB = new Database_Regis();
                $db = $connectDB->getConnection();
                
                $studentRegis = new StudentRegis($db);

                $m1NomalStudents = $studentRegis->getM1NomalStudents();
                $m4NomalStudents = $studentRegis->getM4NomalStudents();
                $m4QuotaStudents = $studentRegis->getM4QuotaStudents();

                $m1NomalCount = count($m1NomalStudents);
                $m4NomalCount = count($m4NomalStudents);
                $m4QuotaCount = count($m4QuotaStudents);

                echo createSmallBox($m1NomalCount, 'นักเรียนมัธยมศึกษาปีที่ 1', 'bg-success', 'fas fa-user-plus');
                echo createSmallBox($m4NomalCount + $m4QuotaCount, 'นักเรียนมัธยมศึกษาปีที่ 4', 'bg-info', 'fas fa-user-plus');

                $startDate = '2025-02-15';
                $endDate = '2025-02-20';
                $dailyCounts = $studentRegis->getDailyRegistrationCounts($startDate, $endDate);
                ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">ยอดผู้สมัครนักเรียนมัธยมศึกษาปีที่ 1</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">ยอดผู้สมัครนักเรียนมัธยมศึกษาปีที่ 4</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">ยอดสมัครในแต่ละวัน</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

    </div>
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php');?>
</div>
<!-- ./wrapper -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('donutChart1').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['ในเขต', 'นอกเขต'],
            datasets: [{
                data: [<?php echo count(array_filter($m1NomalStudents, function($student) { return $student['typeregis'] == 'ในเขต'; })); ?>, <?php echo count(array_filter($m1NomalStudents, function($student) { return $student['typeregis'] == 'นอกเขต'; })); ?>],
                backgroundColor: ['#f39c12', '#007bff'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    var ctxBooking = document.getElementById('donutChart4').getContext('2d');
    var donutChartBooking = new Chart(ctxBooking, {
        type: 'doughnut',
        data: {
            labels: ['รอบทั่วไป', 'โควต้า'],
            datasets: [{
                data: [<?php echo $m4NomalCount; ?>, <?php echo $m4QuotaCount; ?>],
                backgroundColor: ['#f39c12', '#007bff'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: [<?php echo implode(',', array_map(function($date) { return "'$date'"; }, array_keys($dailyCounts))); ?>],
            datasets: [{
                label: 'ยอดสมัคร',
                data: [<?php echo implode(',', $dailyCounts); ?>],
                backgroundColor: '#007bff',
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
  });
</script>
<?php require_once('scirpt.php');?>
</body>
</html>
