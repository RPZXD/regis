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
                        <span class="text-red-700 text-bold text-lg">*** คลิกที่ภาพแแล้วสามารถตรวจหลักฐานได้เลยครับ ***</span>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                                <table class="display table-bordered responsive" id="record_table" style="width:100%;">
                                    <thead class="table-dark text-white text-center">
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ลำดับ</th>
                                            <th style="vertical-align: middle; text-align: center; width:10%;">เลขบัตรประชาชน</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">สถานะ</th>
                                            <th style="vertical-align: middle; text-align: center; width:5%;">ประเภท</th>
                                            <th style="vertical-align: middle; text-align: center;">ชื่อ - นามสกุล</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์</th>
                                            <th style="vertical-align: middle; text-align: center;">เบอร์โทรศัพท์ผู้ปกครอง</th>
                                            <th style="vertical-align: middle; text-align: center;">วันที่สมัคร</th>
                                            <th style="vertical-align: middle; text-align: center;">รรเดิม</th>
                                            <th style="vertical-align: middle; text-align: center;">1. ใบสมัครของโรงเรียนพิชัย</th>
                                            <th style="vertical-align: middle; text-align: center;">2. สำเนาบัตรประจำตัวประชาชนของนักเรียน</th>
                                            <th style="vertical-align: middle; text-align: center;">3. สำเนาทะเบียนบ้านของนักเรียน</th>
                                            <th style="vertical-align: middle; text-align: center;">4. สำเนาทะเบียนบ้านของบิดา</th>
                                            <th style="vertical-align: middle; text-align: center;">5. สำเนาทะเบียนบ้านของมารดา</th>
                                            <th style="vertical-align: middle; text-align: center;">6.1 ใบรับรองผลการเรียนเฉลี่ยรวม (หน้าแรก)</th>
                                            <th style="vertical-align: middle; text-align: center;">6.2 ใบรับรองผลการเรียนเฉลี่ยรวม (หน้าหลัง)</th>
                                            <th style="vertical-align: middle; text-align: center;">7. รูปถ่ายสี</th>
                                            <th style="vertical-align: middle; text-align: center;">8. หนังสือรับรองการอยู่อาศัย</th>
                                            <th style="vertical-align: middle; text-align: center;">จัดการ</th>
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
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">สถานะการสมัคร:</label>
                        <select class="form-control text-center" id="editStatus" name="status">
                        <option value="0">รอการตรวจสอบหลักฐาน</option>
                            <option value="1">การสมัครเสร็จสมบูรณ์</option>
                            <option value="2">กรุณาอัพโหลดหลักฐาน</option>
                            <option value="9">กรุณาแก้ไขหลักฐาน</option>
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

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- ภาพใน modal -->
        <a href="" id="modalImageLink" target="_blank"><img id="modalImage" src="" alt="Image" style="width: 100%; max-height: 800px; object-fit: contain;"></a>

        <!-- ฟอร์มสำหรับอัพเดทสถานะ -->
        <div class="form-group mt-2">
          <label for="statusDropdown">ตรวจหลักฐาน:</label>
          <select id="statusDropdown" class="form-control text-center" onchange="toggleErrorDetailField()">
            <option value="0">⏰ รอการตรวจสอบจากเจ้าหน้าที่</option>
            <option value="1">✅ ตรวจสอบเรียบร้อยแล้ว</option>
            <option value="2">❌ ไม่ผ่านกรุณาแก้ไขหลักฐาน</option>
          </select>
        </div>

        <!-- ฟอร์มสำหรับกรอกรายละเอียดหากเลือกสถานะ 2 -->
        <div class="form-group" id="errorDetailField" style="display: none;">
          <label for="errorDetail">กรุณาระบุรายละเอียด:</label>
          <textarea id="errorDetail" class="form-control" rows="4"></textarea>
        </div>
        
        <!-- ไฟล์ชื่อที่จะแสดง -->
        <input type="hidden" id="uploadFileName" />
        <input type="hidden" id="citizenIdField" />
        
        <!-- ปุ่มสำหรับอัพเดท -->
        <button type="button" class="btn btn-success" onclick="updateStatus()">อัพเดทสถานะ</button>
      </div>
    </div>
  </div>
</div>






<script>
function loadTable() {
    $.ajax({
        url: 'api/fetch_m1nomal_check.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#record_table').DataTable().clear().destroy(); // Clear and destroy the existing table
            $('#record_table tbody').empty();

            if (response.length === 0) {
                $('#record_table tbody').append('<tr><td colspan="19" class="text-center">ไม่พบข้อมูล</td></tr>');
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
                    '<td class="text-center">' + record.citizenid + '</td>' +
                    '<td class="text-center">' + StatusText + '</td>' +
                    '<td class="text-center">' + record.typeregis + '</td>' +
                    '<td>' + record.fullname + '</td>' +
                    '<td class="text-center">' + record.now_tel + '</td>' +
                    '<td class="text-center">' + record.parent_tel + '</td>' +
                    '<td class="text-center">' + record.create_at + '</td>' +
                    '<td class="text-center">' + record.old_school + '</td>' ;
                    for (let i = 1; i <= 9; i++) {
                        let uploadPath = record['upload_path' + i];
                        let errorDetail = record['error_detail' + i];
                        let upStatus = record['status' + i];
                        let bgColor = '';
                        // switch case สำหรับเปลี่ยนสีพื้นหลังของ td ตาม upStatus
                        switch (upStatus) {
                            case '0':
                                bgColor = 'bg-yellow-400'; // เขียวอ่อน
                                break;
                            case '1':
                                bgColor = 'bg-green-400'; // เขียวอ่อน
                                break;
                            case '2':
                                bgColor = 'bg-red-400'; // แดงอ่อน
                                break;
                            default:
                                bgColor = ''; // สีปกติ
                        }
                        if (uploadPath) {
                            // แปลง uploadPath ให้เป็นชื่อที่ใช้สำหรับอัพเดท เช่น upload_path1 -> document1
                            let uploadName = 'document' + i;
                            row += '<td class="text-center ' + bgColor + '">' +
                            '<a href="javascript:void(0)" onclick="showImageModal(\'' + record.citizenid + '\', \'' + uploadPath + '\', \'' + uploadName + '\', \'' + encodeURIComponent(errorDetail) + '\')">' +
                            '<img src="../uploads/' + record.citizenid + '/' + uploadPath + '" alt="Upload ' + i + '" style="max-width: 100px; max-height: 100px;"/>' +
                            '</a></td>';
                        } else {
                            row += '<td class="text-center">-</td>';  // กรณีไม่มี upload_path
                        }
                    }
                    row += '<td class="text-center">' + 
                        '<button class="btn btn-primary edit-btn" data-id="' + record.id + '" data-status="' + record.status + '">Edit</button> ' +
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

// ฟังก์ชันสำหรับแสดง modal และเปลี่ยนภาพใน modal
function showImageModal(citizenid, uploadPath, uploadName, errorDetail) {
    // เปลี่ยน src ของภาพใน modal ให้เป็นเส้นทางภาพที่คลิก
    document.getElementById('modalImage').src = "../uploads/" + citizenid + '/' + uploadPath;
    document.getElementById('modalImageLink').href = "../uploads/" + citizenid + '/' + uploadPath;

    // ตั้งค่าชื่อของไฟล์ที่จะใช้ในการอัพเดท
    document.getElementById('uploadFileName').value = uploadName;  // แสดงชื่อไฟล์ใน input
    document.getElementById('errorDetail').value =  decodeURIComponent(errorDetail);

    // ตั้งค่าค่า citizenid ใน modal
    document.getElementById('citizenIdField').value = citizenid;

    // Fetch the status using API
    $.ajax({
        url: 'api/get_statusImg.php',
        method: 'GET',
        data: {
            citizenid: citizenid,
            upload_name: uploadName
        },
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            if (jsonResponse.success) {
                document.getElementById('statusDropdown').value = jsonResponse.status;
                toggleErrorDetailField(); // Update the error detail field visibility
            } else {
                Swal.fire('เกิดข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูลสถานะ', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching status:", error);
            Swal.fire('เกิดข้อผิดพลาด', 'เกิดข้อผิดพลาดในการดึงข้อมูลสถานะ', 'error');
        }
    });

    // เปิด modal
    $('#imageModal').modal('show');
}

// ฟังก์ชันในการแสดงหรือซ่อนฟิลด์รายละเอียดเมื่อเลือกสถานะ
function toggleErrorDetailField() {
    var status = document.getElementById('statusDropdown').value;
    var errorDetailField = document.getElementById('errorDetailField');

    // ถ้าสถานะเป็น 2 (ไม่ผ่าน), ให้แสดงฟิลด์สำหรับกรอกรายละเอียด
    if (status == '2') {
        errorDetailField.style.display = 'block';
    } else {
        errorDetailField.style.display = 'none';
    }
}

// ฟังก์ชันที่ใช้ในการอัพเดทสถานะ
function updateStatus() {
    var citizenId = document.getElementById('citizenIdField').value;
    var uploadFileName = document.getElementById('uploadFileName').value;
    var status = document.getElementById('statusDropdown').value;
    var errorDetail = document.getElementById('errorDetail').value;

    // ตรวจสอบว่าเลือกสถานะ 2 และกรอกข้อมูล errorDetail หรือไม่
    if (status == '2' && !errorDetail) {
        Swal.fire('ข้อผิดพลาด', 'กรุณาระบุรายละเอียดเมื่อสถานะเป็น "ไม่ผ่านกรุณาแก้ไขหลักฐาน"', 'error');
        return; // หยุดการอัพเดทหากไม่มีรายละเอียด
    }

    // ส่งข้อมูลไปอัพเดทในฐานข้อมูล
    $.ajax({
        url: 'api/update_upload_status.php',
        method: 'POST',
        data: {
            citizenid: citizenId,
            upload_name: uploadFileName,
            status: status,
            error_detail: errorDetail
        },
        success: function(response) {
            var jsonResponse = JSON.parse(response);
            if (jsonResponse.success) {
                Swal.fire('สำเร็จ', 'อัพเดทสถานะเรียบร้อย', 'success');
                $('#imageModal').modal('hide'); // ปิด modal หลังจากอัพเดท
                loadTable(); // รีโหลดข้อมูลในตาราง
            } else {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการอัพเดทสถานะ', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error updating status:", error);
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการอัพเดทสถานะ', 'error');
        }
    });
}

// Call the function once when the page loads
$(document).ready(function() {
    loadTable();

    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $('#editId').val(id);
        $('#editStatus').val(status);
        $('#editModal').modal('show');
    });

    // Handle form submission
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'api/update_status.php',
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
