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
                            <h2 class="text-center"><i class="fas fa-bullhorn"></i>&nbsp;&nbsp;ค้นหาข้อมูลเพื่อพิมพ์ใบสมัคร</h2>
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
                        <p class="text-center text-danger mt-3">*** กรุณากรอกเลขบัตรประชาชน 13 หลักหรือชื่อเพื่อค้นหาข้อมูล ***</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                 <div class="card-body">
                    <div class="form-row justify-content-center align-items-center form-control-lg">
                        <div class="col-auto">
                            <div id="studentInfo" class="callout callout-info d-none">
                                    <h5>ข้อมูลผู้สมัคร</h5>
                                    <p id="studentDetails"></p>
                                    <button id="printButton" class="btn btn-success mt-3 d-none"><i class="nav-icon fas fas fa-print"></i> พิมพ์ใบสมัคร</button>
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
            // if (data.status !== 0) {
            //     Swal.fire({
            //         icon: 'warning',
            //         title: 'คุณได้ผ่านเกณฑ์ไปแล้ว',
            //         text: ''
            //     });
            //     document.getElementById('studentInfo').classList.add('d-none');
            //     return; // หยุดการทำงาน ไม่ต้องแสดงข้อมูล
            // }

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
            document.getElementById('printButton').classList.remove('d-none');
            document.getElementById('printButton').addEventListener('click', function() {
                window.location.href = `print_reginfo.php?citizenid=${data.citizenid}`;
            });

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

</script>
<?php require_once('scirpt.php');?>
</body>
</html>
