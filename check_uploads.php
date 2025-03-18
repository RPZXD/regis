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

                            <div id="uploadStatus" class="callout callout-info d-none">
                                    <h5 class="text-blue-700 text-lg">สถานะการอัพโหลด</h5>
                                    <table class="table table-bordered">
                                        <thead class="bg-blue-400 text-white text-center">
                                            <tr>
                                                <th>ชื่อเอกสาร</th>
                                                <th>สถานะ</th>
                                                <th>รายละเอียดข้อผิดพลาด</th>
                                            </tr>
                                        </thead>
                                        <tbody id="uploadStatusBody">
                                            <!-- Rows will be added here dynamically -->
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- จบส่วนที่เพิ่ม -->

        <!-- เพิ่ม callout สำหรับแสดงข้อมูลเบื้องต้น -->

        <!-- จบ callout -->

    </div><!-- /.container-fluid -->
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php');?>
</div>
<!-- ./wrapper -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            document.getElementById('studentDetails').innerText = `ชื่อ: ${data.fullname}\nประเภทการสมัคร: ${data.typeregis}\nระดับชั้นที่สมัคร: ชั้นมัธยมศึกษาปีที่ ${data.level}\nวันเกิด: ${data.birthday}\nเบอร์โทร: ${data.now_tel}\nเบอร์โทรผู้ปกครอง: ${data.parent_tel}`;
            document.getElementById('studentInfo').classList.remove('d-none');

            // Fetch upload status
            fetch('api/fetch_upload_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ citizenid: data.citizenid })
            })
            .then(response => response.json())
            .then(uploadData => {
                var uploadStatusBody = document.getElementById('uploadStatusBody');
                if (uploadData.length > 0) {
                    var rows = '';
                    uploadData.forEach(function(upload, index) {
                        var statusClass = '';
                        var statusTitle = '';
                        if (upload.status === 'ตรวจสอบเรียบร้อยแล้ว') {
                            statusClass = 'badge bg-success';
                            statusTitle = 'ไฟล์นี้ได้รับการตรวจสอบและผ่านเรียบร้อยแล้ว';
                        } else if (upload.status === 'โปรดแก้ไข') {
                            statusClass = 'badge bg-danger';
                            statusTitle = 'มีข้อผิดพลาด กรุณาแก้ไขไฟล์';
                        } else if (upload.status === 'รอการตรวจสอบจากเจ้าหน้าที่') {
                            statusClass = 'badge bg-warning text-dark';
                            statusTitle = 'กำลังรอเจ้าหน้าที่ตรวจสอบ';
                        }
                        rows += `<tr>
                            <td>${index + 1}. ${upload.name}</td>
                            <td>
                                <span class="${statusClass}" data-bs-toggle="tooltip" title="${statusTitle}">
                                    ${upload.status}
                                </span>
                            </td>
                            <td>${upload.status === 'โปรดแก้ไข' && upload.error_detail ? upload.error_detail : '-'}</td>
                        </tr>`;
                    });
                    uploadStatusBody.innerHTML = rows;
                    document.getElementById('uploadStatus').classList.remove('d-none');

                    // Enable Bootstrap tooltip
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    document.getElementById('uploadStatus').classList.add('d-none');
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ไม่พบข้อมูล',
                text: 'ไม่พบข้อมูลผู้สมัครที่ค้นหา'
            });
            document.getElementById('studentInfo').classList.add('d-none');
            document.getElementById('uploadStatus').classList.add('d-none');
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
        document.getElementById('uploadStatus').classList.add('d-none');
    });
});

</script>
<?php require_once('scirpt.php');?>
</body>
</html>
