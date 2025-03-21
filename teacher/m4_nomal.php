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
                    <h3 class="text-lg text-bold bt-2">นักเรียนที่สมัครชั้นมัธยมศึกษาปีที่ 4 รอบทั่วไป<br></h3>
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
                                            <th style="vertical-align: middle; text-align: center; width:10%;">เลขบัตรประชาชน</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">ประเภท</th>
                                            <th style="vertical-align: middle; text-align: center;">ชื่อ - นามสกุล</th>
                                            <th style="vertical-align: middle; text-align: center;">วันเดือนปีเกิด</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์ผู้ปกครอง</th>
                                            <th style="vertical-align: middle; text-align: center;">วันที่สมัคร</th>
                                            <th style="vertical-align: middle; text-align: center;">GPA</th>
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
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    <input type="hidden" id="editStudentId">
                    <div class="mb-3">
                        <label for="editCitizenId" class="form-label">เลขบัตรประชาชน 13 หลัก</label>
                        <input type="text" class="form-control" id="editCitizenId" pattern="\d{13}" maxlength="13" title="กรุณากรอกเลขบัตรประชาชน 13 หลัก" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTyperegis" class="form-label">ประเภท</label>
                        <select name="editTyperegis" id="editTyperegis"  class="form-control text-center" required>
                            <option value="">โปรดเลือกประเภท</option>
                            <option value="รอบทั่วไป">รอบทั่วไป</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPrefix" class="form-label">คำนำหน้า</label>
                        <select name="editPrefix" id="editPrefix" class="form-control" required>
                                <option class="text-center" value="เด็กชาย">เด็กชาย</option>
                                <option class="text-center" value="เด็กหญิง">เด็กหญิง</option>
                                <option class="text-center" value="นาย">นาย</option>
                                <option class="text-center" value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">ชื่อ</label>
                        <input type="text" class="form-control" id="editFirstName" maxlength="50" title="กรุณากรอกชื่อไม่เกิน 50 ตัวอักษร" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">นามสกุล</label>
                        <input type="text" class="form-control" id="editLastName" maxlength="50" title="กรุณากรอกนามสกุลไม่เกิน 50 ตัวอักษร" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBirthday" class="form-label">วันเดือนปีเกิด (DD-MM-YYYY)</label>
                        <input type="text" class="form-control" id="editBirthday"h required>
                    </div>
                    <div class="mb-3">
                        <label for="editNowTel" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" id="editNowTel" pattern="\d{10}" maxlength="10" title="กรุณากรอกเบอร์โทรศัพท์ 10 หลัก" required>
                    </div>
                    <div class="mb-3">
                        <label for="editParentTel" class="form-label">เบอร์โทรศัพท์ผู้ปกครอง</label>
                        <input type="tel" class="form-control" id="editParentTel" pattern="\d{10}" maxlength="10" title="กรุณากรอกเบอร์โทรศัพท์ผู้ปกครอง 10 หลัก" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGpaTotal" class="form-label">GPA</label>
                        <input type="text" class="form-control" id="editGpaTotal" pattern="\d+(\.\d{1,2})?" title="กรุณากรอก GPA เป็นตัวเลข" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber1" class="form-label">แผนการเรียน 1</label>
                        <select class="form-control" id="editNumber1" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber2" class="form-label">แผนการเรียน 2</label>
                        <select class="form-control" id="editNumber2" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber3" class="form-label">แผนการเรียน 3</label>
                        <select class="form-control" id="editNumber3" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber4" class="form-label">แผนการเรียน 4</label>
                        <select class="form-control" id="editNumber4" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber5" class="form-label">แผนการเรียน 5</label>
                        <select class="form-control" id="editNumber5" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editNumber6" class="form-label">แผนการเรียน 6</label>
                        <select class="form-control" id="editNumber6" required>
                                 <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
                                <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
                                <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
                                <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
                                <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
                                <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
                                <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
                                <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m4nomal.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="16" class="text-center">ไม่พบข้อมูล</td></tr>');
            } else {
                $.each(response, function(index, record) {
                    var row = '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td class="text-center">' + record.citizenid + '</td>' +
                    '<td class="text-center">' + record.typeregis + '</td>' +
                    '<td>' + record.fullname + '</td>' +
                    '<td class="text-center">' + record.birthday + '</td>' +
                    '<td class="text-center">' + record.now_tel + '</td>' +
                    '<td class="text-center">' + record.parent_tel + '</td>' +
                    '<td class="text-center">' + record.create_at + '</td>' +
                    '<td class="text-center">' + record.gpa_total + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number1) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number2) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number3) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number4) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number5) + '</td>' +
                    '<td class="text-center">' + getPlanName(record.number6) + '</td>' +
                    '<td class="text-center">' + 
                        '<button class="btn btn-primary edit-btn" data-id="' + record.id + '">แก้ไข</button> ' +
                        '<button class="btn btn-danger delete-btn" data-id="' + record.id + '">ลบ</button>' +
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

    // Edit button click event
    $(document).on('click', '.edit-btn', function() {
        var studentId = $(this).data('id');
        $.ajax({
            url: 'api/get_student.php',
            method: 'GET',
            data: { id: studentId },
            dataType: 'json',
            success: function(response) {
                $('#editStudentId').val(response.id);
                $('#editCitizenId').val(response.citizenid);
                $('#editTyperegis').val(response.typeregis);
                $('#editPrefix').val(response.stu_prefix);
                $('#editFirstName').val(response.stu_name);
                $('#editLastName').val(response.stu_lastname);
                $('#editBirthday').val(response.birthday);
                $('#editNowTel').val(response.now_tel);
                $('#editParentTel').val(response.parent_tel);
                $('#editGpaTotal').val(response.gpa_total);
                $('#editNumber1').val(response.number1);
                $('#editNumber2').val(response.number2);
                $('#editNumber3').val(response.number3);
                $('#editNumber4').val(response.number4);
                $('#editNumber5').val(response.number5);
                $('#editNumber6').val(response.number6);
                $('#editStudentModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Fetch Student Error:", xhr.responseText); // Debugging
                Swal.fire('Error', 'Failed to fetch student data', 'error');
            }
        });
    });

    // Delete button click event
    $(document).on('click', '.delete-btn', function() {
        var studentId = $(this).data('id');
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบข้อมูลนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_student.php',
                    method: 'POST',
                    data: JSON.stringify({ id: studentId }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('ลบแล้ว!', 'ข้อมูลถูกลบเรียบร้อยแล้ว.', 'success');
                            loadTable(); // Reload the table data
                        } else {
                            Swal.fire('ข้อผิดพลาด', response.message || 'ไม่สามารถลบข้อมูลได้', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Delete Error:", xhr.responseText); // Debugging
                        Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดขณะลบข้อมูล', 'error');
                    }
                });
            }
        });
    });

    document.getElementById("editStudentForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const studentData = {
            id: document.getElementById("editStudentId").value,
            citizenid: document.getElementById("editCitizenId").value,
            typeregis: document.getElementById("editTyperegis").value,
            stu_prefix: document.getElementById("editPrefix").value,
            stu_name: document.getElementById("editFirstName").value,
            stu_lastname: document.getElementById("editLastName").value,
            year_birth: document.getElementById("editBirthday").value.split("-")[2],
            month_birth: document.getElementById("editBirthday").value.split("-")[1],
            date_birth: document.getElementById("editBirthday").value.split("-")[0],
            now_tel: document.getElementById("editNowTel").value,
            parent_tel: document.getElementById("editParentTel").value,
            gpa_total: document.getElementById("editGpaTotal").value,
            number1: document.getElementById("editNumber1").value,
            number2: document.getElementById("editNumber2").value,
            number3: document.getElementById("editNumber3").value,
            number4: document.getElementById("editNumber4").value,
            number5: document.getElementById("editNumber5").value,
            number6: document.getElementById("editNumber6").value
        };

        fetch("api/update_studentm4.php", {
            method: "POST",
            body: JSON.stringify(studentData),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: "สำเร็จ!",
                    text: "อัปเดตข้อมูลนักเรียนเรียบร้อยแล้ว",
                    icon: "success",
                    confirmButtonText: "ตกลง"
                }).then(() => {
                    $("#editStudentModal").modal("hide");
                    loadTable(); // โหลดข้อมูลใหม่
                });
            } else {
                Swal.fire("ข้อผิดพลาด", data.message || "ไม่สามารถอัปเดตข้อมูลได้", "error");
            }
        })
        .catch(error => {
            console.error("Update Error:", error);
            Swal.fire("ข้อผิดพลาด", "เกิดข้อผิดพลาดขณะอัปเดตข้อมูล", "error");
        });
    });
});
</script>






<?php require_once('scirpt.php');?>
</body>
</html>
