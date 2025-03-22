<?php 

session_start();

require_once '../config/Database.php';
require_once '../class/UserLogin.php';
require_once '../class/Utils.php';


// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();

// Initialize UserLogin class
$user = new UserLogin($db);


// Fetch terms and pee
$term = $user->getTerm();
$pee = $user->getPee();

if (isset($_SESSION['Teacher_login'])) {
    $userid = $_SESSION['Teacher_login'];
    $userData = $user->userData($userid);
} else {
    $sw2 = new SweetAlert2(
        'คุณยังไม่ได้เข้าสู่ระบบ',
        'error',
        '../login.php' // Redirect URL
    );
    $sw2->renderAlert();
    exit;
}
$teacher_id = $userData['Teacher_id'];


require_once('header.php');
?>

<?php
?>
<style>
    .form-check-input {
        transform: scale(2);
        margin-right: 30pxๆ;
    }
</style>
<head>
    <!-- ...existing code... -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
</head>
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
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Modal -->




 


    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="callout callout-success text-center">
                <h3 class="text-lg text-bold bt-2">นักเรียนที่สมัครชั้นมัธยมศึกษาปีที่ 1 รอบทั่วไป<br></h3>
                    <hr>
                    <div class="text-right mt-3">
                        <input type="date" id="filter_date" class="form-control" style="display: inline-block; width: auto;">
                        <!-- Remove the Filter button -->
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                                <table class="display table-bordered responsive" id="record_table" style="width:100%;">
                                    <thead class="table-dark text-white text-center">
                                    <tr >
                                        <th style="vertical-align: middle; text-align: center; width:5%;">ลำดับ</th>
                                        <th style="vertical-align: middle; text-align: center; width:10%;">เลขบัตรประชาชน</th>
                                        <th style="vertical-align: middle; text-align: center; width:5%;">สถานะ</th>
                                        <th style="vertical-align: middle; text-align: center; width:5%;">ประเภท</th>
                                        <th style="vertical-align: middle; text-align: center;">ชื่อ - นามสกุล</th>
                                        <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์</th>
                                        <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์ผู้ปกครอง</th>
                                        <th style="vertical-align: middle; text-align: center;">วันที่สมัคร</th>
                                        <th style="vertical-align: middle; text-align: center;">รรเดิม</th>
                                        <th style="vertical-align: middle; text-align: center;">เวลาผ่านการตรวจสอบ</th>
                                        <!-- Add more table column headers as needed -->
                                    </tr>
                                    </thead>       
                                    <tbody>
                                    </tbody>                      
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('../footer.php');?>
</div>
<!-- ./wrapper -->




<script>
$(document).ready(function() {
    $('#filter_date').on('change', function() {
        loadTable();
    });
});

function loadTable() {
    var filterDate = $('#filter_date').val();
    console.log(filterDate); // Log the response to check the data
    $.ajax({
        url: 'api/fetch_m1nomal_pass.php',
        method: 'GET',
        data: { date: filterDate },
        dataType: 'json',
        success: function(response) {
            console.log(response); // Log the response to check the data
            if ($.fn.DataTable.isDataTable('#record_table')) {
                $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            }
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="11" class="text-center">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    var StatusText;
                    switch(record.status) {
                        case 0:
                            StatusText = '<p class="badge text-base font-bold text-white bg-yellow-500">รอตรวจสอบ<p>';
                            break;
                        case 1:
                            StatusText = '<p class="badge text-base font-bold text-white bg-green-500">เรียบร้อย<p>';
                            break;
                        case 2:
                            StatusText = '<p class="badge text-base font-bold text-white bg-purple-500">รออัพโหลดหลักฐาน<p>';
                            break;
                        case 9:
                            StatusText = '<p class="badge text-base font-bold text-white bg-red-500">แก้ไข<p>';
                            break;
                        default:
                            StatusText = 'ไม่ระบุ';
                    }
                    var row = '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td class="text-center">' + (record.citizenid || '') + '</td>' +
                    '<td class="text-center">' + StatusText + '</td>' +
                    '<td class="text-center">' + (record.typeregis || '') + '</td>' +
                    '<td>' + (record.fullname || '') + '</td>' +
                    '<td class="text-center">' + (record.now_tel || '') + '</td>' +
                    '<td class="text-center">' + (record.parent_tel || '') + '</td>' +
                    '<td class="text-center">' + (record.create_at || '') + '</td>' +
                    '<td class="text-center">' + (record.old_school || '') + '</td>' +
                    '<td class="text-center">' + (formatThaiDate(record.update_at) || '') + '</td>' +
                    '</tr>';
                    $('#record_table tbody').append(row);
                });
            }
            
  function formatThaiDate(date) {
    const months = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    const d = new Date(date);
    const day = d.getDate();
    const month = months[d.getMonth()];
    const year = d.getFullYear() + 543;
    return `เวลา ${d.getHours()}:${d.getMinutes()}:${d.getSeconds()} วันที่ ${day} ${month} ${year} `;
  }


            // Reinitialize DataTable with responsive settings and export buttons
            $('#record_table').DataTable({
                "pageLength": 10,
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<span class="btn btn-success">Export to Excel</span>',
                        className: 'btn btn-success',
                        exportOptions: {
                            modifier: {
                                selected: null
                            },
                            rows: {
                                search: 'applied',
                                order: 'applied'
                            }
                        }
                    }
                ]
            });
        },
        error: function(xhr, status, error) {
            console.error("Load Table Error:", xhr.responseText); // Debugging
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูล', 'error');
        }
    });
}
</script>

<?php require_once('scirpt.php');?>
</body>
</html>
