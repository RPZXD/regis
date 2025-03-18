<?php 
require_once 'header.php'; 
require_once 'config/Database.php';
require_once 'class/Upload.php';

$connectDB = new Database_Regis();
$db = $connectDB->getConnection();

$uploads = new Uploads($db);


?>
<body class="hold-transition sidebar-mini layout-fixed light-mode">
<div class="wrapper">

    <?php require_once('warpper.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">อัพโหลดหลักฐาน</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">

    <div class="container-fluid">

        <!-- เพิ่มส่วนนี้ -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h2 class="text-center text-lg"><i class="fas fa-bullhorn"></i>&nbsp;&nbsp;อัพโหลดหลักฐาน</h2>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="form-row justify-content-center align-items-center form-control-lg">
                                <div class="col-auto">
                                    <label class="sr-only" for="search_input">เลขบัตรประชาชน 13 หลักหรือชื่อ:</label>
                                    <input type="text" class="form-control text-center mb-2" id="search_input" name="search_input" placeholder="เลขบัตรประชาชน 13 หลักหรือชื่อ" required>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary mb-2">ค้นหา</button>
                                </div>
                            </div>
                        </form>
                        <p class="text-center text-danger mt-3">*** กรุณากรอกเลขบัตรประชาชน 13 หลักหรือชื่อเพื่อค้นหา ***</p>
                        
                    </div>
                    <!-- /.card-body -->
                </div>
                
                <div id="studentInfo" class="d-none">
                    <div class="card">
                        <div class="card-body">
                        
                            <h5 class="card-title">ข้อมูลผู้สมัคร</h5>
                            <p id="studentDetails"></p>
                            <hr class="my-2 mx-2">

                            <!-- Form for level 1 -->
                            <form id="uploadFormLevel1" enctype="multipart/form-data">
                                <?php
                                    $documents1 = $uploads->selectConfigUploadsByLevel(1);
                                    // เรียกฟังก์ชัน generateFileUploadForm เพื่อแสดงฟอร์ม
                                    $uploads->generateFileUploadForm($documents1);
                                ?>

                                <span class="text-danger text-lg">หมายเหตุ ให้นักเรียนอัปโหลดเอกสารในข้อ (1 - 7) ในระบบการรับสมัครออนไลน์</span><br>
                                <button type="submit" class="btn btn-success my-2 form-control">อัพโหลด</button>
                            </form>

                            <!-- Form for level 4 -->
                            <form id="uploadFormLevel4" enctype="multipart/form-data">
                                <?php
                                    $documents4 = $uploads->selectConfigUploadsByLevel(4);
                                    // เรียกฟังก์ชัน generateFileUploadForm เพื่อแสดงฟอร์ม
                                    $uploads->generateFileUploadForm($documents4);
                                ?>
                                <span class="text-danger text-lg">หมายเหตุ ให้นักเรียนอัปโหลดเอกสารในข้อ (1 - 7) ในระบบการรับสมัครออนไลน์</span><br>
                                <button type="submit" class="btn btn-success my-2 form-control">อัพโหลด</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- จบส่วนที่เพิ่ม -->

    </div><!-- /.container-fluid -->
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php'); ?>
</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var searchInput = document.getElementById('search_input').value;

    Swal.fire({
        title: 'กำลังค้นหา...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('api/fetch_reg.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ search_input: searchInput })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.exists) {
            Swal.fire({
                icon: 'success',
                title: 'พบข้อมูล',
                text: 'ข้อมูลผู้สมัครที่ค้นหา'
            });

            // แสดงข้อมูลเบื้องต้น
            document.getElementById('studentDetails').innerText = 
                `ชื่อ: ${data.fullname}\n` +
                `ประเภทการสมัคร: ${data.typeregis}\n` +
                `ระดับชั้นที่สมัคร: ชั้นมัธยมศึกษาปีที่ ${data.level}\n` +
                `วันเกิด: ${data.birthday}\n` +
                `เบอร์โทร: ${data.now_tel}\n` +
                `เบอร์โทรผู้ปกครอง: ${data.parent_tel}`;
            
            document.getElementById('studentInfo').classList.remove('d-none');

            // Show the appropriate form based on the level
            if (data.level == 1) {
                document.getElementById('uploadFormLevel1').classList.remove('d-none');
                document.getElementById('uploadFormLevel4').classList.add('d-none');
            } else if (data.level == 4) {
                document.getElementById('uploadFormLevel4').classList.remove('d-none');
                document.getElementById('uploadFormLevel1').classList.add('d-none');
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ไม่พบข้อมูล',
                text: 'ไม่พบข้อมูลผู้สมัครที่ค้นหา'
            });
            document.getElementById('studentInfo').classList.add('d-none');
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถตรวจสอบข้อมูลได้'
        });
        document.getElementById('studentInfo').classList.add('d-none');
    });
});

document.getElementById('uploadFormLevel1').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var citizenid = document.getElementById('search_input').value;
    formData.append('citizenid', citizenid);

    // สร้าง array ของเอกสาร
    const documents = [
        { inputId: 'document1', name: 'ใบสมัครของโรงเรียนพิชัย', key: 'document1_name' },
        { inputId: 'document2', name: 'สำเนาบัตรประจำตัวประชาชนของนักเรียน', key: 'document2_name' },
        { inputId: 'document3', name: 'สำเนาทะเบียนบ้านของนักเรียน', key: 'document3_name' },
        { inputId: 'document4', name: 'สำเนาทะเบียนบ้านของบิดา', key: 'document4_name' },
        { inputId: 'document5', name: 'สำเนาทะเบียนบ้านของมารดา', key: 'document5_name' },
        { inputId: 'document6', name: 'ใบรับรองผลการเรียนเฉลี่ยรวม(หน้าแรก)', key: 'document6_name' },
        { inputId: 'document7', name: 'ใบรับรองผลการเรียนเฉลี่ยรวม(หน้าหลัง)', key: 'document7_name' },
        { inputId: 'document8', name: 'รูปถ่ายสี', key: 'document8_name' },
        { inputId: 'document9', name: 'หนังสือรับรองการอยู่อาศัย', key: 'document9_name' }
    ];

    // เช็คว่า input มีไฟล์ไหม ก่อนจะ append
    documents.forEach(doc => {
        const fileInput = document.getElementById(doc.inputId);
        if (fileInput && fileInput.files.length > 0) {
            formData.append(doc.key, doc.name);
        }
    });

    Swal.fire({
        title: 'กำลังอัพโหลด...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('api/upload_document.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'อัพโหลดหลักฐานเรียบร้อยแล้ว'
            }).then(() => {
              location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถอัพโหลดหลักฐานได้'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถอัพโหลดหลักฐานได้'
        });
    });
});


document.getElementById('uploadFormLevel4').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var citizenid = document.getElementById('search_input').value; // Get the citizenid from the search input
    formData.append('citizenid', citizenid); // Append the citizenid to the formData

    // Append name parameters for each file input
    formData.append('document1_name', 'ใบสมัครของโรงเรียนพิชัย');
    formData.append('document2_name', 'สำเนาบัตรประจำตัวประชาชนของนักเรียน');
    formData.append('document3_name', 'สำเนาทะเบียนบ้านของนักเรียน');
    formData.append('document4_name', 'สำเนาทะเบียนบ้านของบิดา');
    formData.append('document5_name', 'สำเนาทะเบียนบ้านของมารดา');
    formData.append('document6_name', 'ระเบียนแสดงผลการเรียนหลักสูตรแกนกลางการศึกษาขั้นพื้นฐาน (หน้าแรก)');
    formData.append('document7_name', 'ระเบียนแสดงผลการเรียนหลักสูตรแกนกลางการศึกษาขั้นพื้นฐาน (หน้าหลัง)');
    formData.append('document8_name', 'รูปถ่ายสี');

    Swal.fire({
        title: 'กำลังอัพโหลด...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false, 
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('api/upload_document.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'อัพโหลดหลักฐานเรียบร้อยแล้ว'
            }).then(() => {
              location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถอัพโหลดหลักฐานได้'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถอัพโหลดหลักฐานได้'
        });
    });
});
</script>
<?php require_once('scirpt.php'); ?>
</body>
</html>
