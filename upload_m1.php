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
                            <h2 class="text-center text-lg"><i class="fas fa-bullhorn"></i>&nbsp;&nbsp;อัพโหลดหลักฐาน ผู้ที่จะสมัครเข้าศึกษามัธยมศึกษาปีที่ 1</h2>
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
                                <input type="hidden" name="level" value="1">
                                <?php
                                    $documents1 = $uploads->selectConfigUploadsByLevel(1);
                                    // เรียกฟังก์ชัน generateFileUploadForm เพื่อแสดงฟอร์ม
                                    $uploads->generateFileUploadForm($documents1);
                                ?>

                                <span class="text-danger text-lg">หมายเหตุ ให้นักเรียนอัปโหลดเอกสารในข้อ (1 - 7) ในระบบการรับสมัครออนไลน์</span><br>
                                <button type="submit" class="btn btn-success my-2 form-control">อัพโหลด</button>
                            </form>
                            
                            <!-- Progress bar -->
                            <div class="progress mt-3">
                                <div id="uploadProgressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                
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

    fetch('api/fetch_reg_m1.php', {
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

            // Show the form for level 1
            document.getElementById('uploadFormLevel1').classList.remove('d-none');

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
    const documents1 = [
        { inputId: 'document1', name: 'document1', key: 'document1_name' },
        { inputId: 'document2', name: 'document2', key: 'document2_name' },
        { inputId: 'document3', name: 'document3', key: 'document3_name' },
        { inputId: 'document4', name: 'document4', key: 'document4_name' },
        { inputId: 'document5', name: 'document5', key: 'document5_name' },
        { inputId: 'document6', name: 'document6', key: 'document6_name' },
        { inputId: 'document7', name: 'document7', key: 'document7_name' },
        { inputId: 'document8', name: 'document8', key: 'document8_name' },
        { inputId: 'document9', name: 'document9', key: 'document9_name' }
    ];

    // เช็คว่า input มีไฟล์ไหม ก่อนจะ append
    documents1.forEach(doc => {
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

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api/upload_document.php', true);

    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            var progressBar = document.getElementById('uploadProgressBar');
            progressBar.style.width = percentComplete + '%';
            progressBar.setAttribute('aria-valuenow', percentComplete);
            progressBar.innerText = Math.round(percentComplete) + '%';
        }
    });

    xhr.onload = function() {
        Swal.close();
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'อัพโหลดหลักฐานเรียบร้อยแล้ว',
                    timer: 3000,
                    timerProgressBar: true,
                    willClose: () => {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถอัพโหลดหลักฐานได้'
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถอัพโหลดหลักฐานได้'
            });
        }
    };

    xhr.onerror = function() {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถอัพโหลดหลักฐานได้'
        });
    };

    xhr.send(formData);
});
</script>
<?php require_once('scirpt.php'); ?>
</body>
</html>
