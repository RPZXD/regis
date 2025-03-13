<?php 

session_start();

include_once("../config/Database.php");
include_once("../class/UserLogin.php");
include_once("../class/Utils.php");


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
                    <h3 class="fw-bold bt-2">นักเรียนที่สมัครชั้นมัธยมศึกษาปีที่ 4 (โควต้า ม.3 เดิม)<br></h3>
                    <hr>
                    <div class="text-left">

                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                                <table class="display table-bordered responsive" id="record_table" style="width:100%;">
                                    <thead class="table-dark text-white text-center">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ลำดับ</th>
                                            <th style="vertical-align: middle; text-align: center; ">รายงานตัว</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">เลขบัตรประชาชน</th>
                                            <th style="vertical-align: middle; text-align: center;">ชื่อ - นามสกุล</th>
                                            <th style="vertical-align: middle; text-align: center;">วันเดือนปีเกิด</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์ผู้ปกครอง</th>
                                            <th style="vertical-align: middle; text-align: center;">วันที่สมัคร</th>
                                            <th style="vertical-align: middle; text-align: center;">GPA</th>
                                            <th style="vertical-align: middle; text-align: center;">เลขประจำตัว</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 1</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 2</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 3</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 4</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 5</th>
                                            <th style="vertical-align: middle; text-align: center;">แผน 6</th>
                                            <th style="vertical-align: middle; text-align: center; width:13%;">จัดการ</th>
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

<!-- Modal for Editing Student Information -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขสถานะการรายงานตัว</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editNumreg" name="numreg">
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">สถานะการรายงานตัว:</label>
                        <select class="form-control text-center" id="editStatus" name="status">
                            <option value="0">รอการยืนยัน</option>
                            <option value="1">ยืนยันสิทธิ์</option>
                            <option value="9">สละสิทธิ์</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-left">บันทึก</button>
                        <button type="button" class="btn btn-secondary text-right" data-dismiss="modal">ปิด</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m4quota_con.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="16" class="text-center">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    var confirmStatusText;
                    switch(record.confirm_status) {
                        case 0:
                            confirmStatusText = '<p class="text-warning">รอการยืนยัน<p>';
                            break;
                        case 1:
                            confirmStatusText = '<p class="text-success">ยืนยันสิทธิ์<p>';
                            break;
                        case 9:
                            confirmStatusText = '<p class="text-danger">สละสิทธิ์<p>';
                            break;
                        default:
                            confirmStatusText = 'ไม่ระบุ';
                    }
                    var row = '<tr>' +
                    '<td class="text-center">' + record.no + '</td>' +
                    '<td class="text-center">' + confirmStatusText + '</td>' +
                    '<td class="text-center">' + record.citizenid + '</td>' +
                    '<td>' + record.fullname + '</td>' +
                    '<td class="text-center">' + record.birthday + '</td>' +
                    '<td class="text-center">' + record.now_tel + '</td>' +
                    '<td class="text-center">' + record.parent_tel + '</td>' +
                    '<td class="text-center">' + record.create_at + '</td>' +
                    '<td class="text-center">' + record.gpa_total + '</td>' +
                    '<td class="text-center">' + record.old_school_stuid + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number1) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number2) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number3) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number4) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number5) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number6) + '</td>' +
                    '<td class="text-center">' + 
                        '<button class="btn btn-primary edit-btn" data-id="' + record.id + '" data-numreg="' + record.numreg + '" data-status="' + record.confirm_status + '">Edit</button> ' +
                    '</td>' +
                    '</tr>';
                    $('#record_table tbody').append(row);
                });
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
                            columns: ':not(:last-child)', // ไม่รวมคอลัมน์สุดท้าย
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

function getPlanName(planNumber) {
    const plans = {
        1: 'ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)',
        2: 'ห้อง 3 : วิทยาศาสตร์พลังสิบ',
        3: 'ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์',
        4: 'ห้อง 5 : ศิลปศาสตร์และสังคมศาสตร์',
        5: 'ห้อง 6 : ภาษาศาสตร์',
        6: 'ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร',
        7: 'ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร',
        8: 'ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม'
    };
    return plans[planNumber] || 'ไม่ระบุ';
}

// Call the function once when the page loads
$(document).ready(function() {
    loadTable();

    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        var numreg = $(this).data('numreg');
        var status = $(this).data('status');
        $('#editNumreg').val(numreg);
        $('#editStatus').val(status);
        $('#editModal').modal('show');
    });

    // Handle form submission
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'api/update_confirm_status.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide');
                Swal.fire('สำเร็จ', 'อัปเดตสถานะเรียบร้อยแล้ว', 'success');
                loadTable();
            },
            error: function(xhr, status, error) {
                console.error("Update Error:", xhr.responseText); // Debugging
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการอัปเดตสถานะ', 'error');
            }
        });
    });
});
</script>

<?php require_once('scirpt.php');?>
</body>
</html>
