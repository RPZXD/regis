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
                <div class="flex flex-wrap mt-4">
                    <div class="w-full md:w-1/2 px-2 mb-4">
                        <!-- small box -->
                        <a href="#" class="bg-green-500 text-white p-4 rounded-lg shadow block hover:bg-green-600 transition">
                            <div class="flex justify-between items-center">
                                <h3 class="text-3xl font-bold"></h3>
                                <i class="fas fa-file-signature text-4xl"></i>
                            </div>
                            <p class="mt-2">รายงานตัวมัธยมศึกษาปีที่ 1</p>
                        </a>
                    </div>
                    <!-- ./col -->
                    <div class="w-full md:w-1/2 px-2 mb-4">
                        <!-- small box -->
                        <a href="confirm_before4.php" class="bg-blue-500 text-white p-4 rounded-lg shadow block hover:bg-blue-600 transition">
                            <div class="flex justify-between items-center">
                                <h3 class="text-3xl font-bold"></h3>
                                <i class="fas fa-file-signature text-4xl"></i>
                            </div>
                            <p class="mt-2">รายงานตัวมัธยมศึกษาปีที่ 4</p>
                        </a>
                    </div>

                
                </div>
            </div>
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
//     document.addEventListener("DOMContentLoaded", function () {
//     // กำหนดวันที่และช่วงเวลาที่ต้องการตรวจสอบ
//     let reportDate = new Date(2568 - 543, 3, 13); // ปี ค.ศ. = ปี พ.ศ. - 543 (March 13, 2025)
//     let startTime = new Date(reportDate);
//     startTime.setHours(8, 30, 0); // 08:30:00

//     let endTime = new Date(reportDate);
//     endTime.setHours(13, 10, 0); // 12:00:00

//     // เวลาปัจจุบัน
//     let now = new Date();

//     // ตรวจสอบเงื่อนไข ถ้ายังไม่ถึงเวลาให้แจ้งเตือนและ redirect
//     if (now < startTime || now > endTime || now.toDateString() !== reportDate.toDateString()) {
//         Swal.fire({
//             icon: "warning",
//             title: "ยังไม่ถึงเวลารายงานตัว",
//             text: "",
//             confirmButtonText: "ตกลง"
//         }).then(() => {
//             window.location.href = "index.php"; // Redirect ไปที่ index.php
//         });
//     }
// });
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

    fetch('api/fetch_confirm.php', {
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
            if (data.status !== 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'คุณสมบัติไม่ผ่านเกณฑ์',
                    text: 'ผู้สมัครไม่ผ่านเงื่อนไขที่กำหนด'
                });
                document.getElementById('studentInfo').classList.add('d-none');
                return; // หยุดการทำงาน ไม่ต้องแสดงข้อมูล
            }

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
            
            if (data.confirm_status == 0) {
                document.getElementById('ConfirmButton').classList.remove('d-none');
                document.getElementById('DisclaimButton').classList.remove('d-none');
            } else {
                document.getElementById('ConfirmButton').classList.add('d-none');
                document.getElementById('DisclaimButton').classList.add('d-none');
                let statusText = data.confirm_status == 1 ? 'ยืนยัน' : 'สละสิทธิ์';
                document.getElementById('studentDetails').innerText += `\nคุณได้ทำการ${statusText}สิทธิ์ไปแล้ว`;
            }

            document.getElementById('ConfirmButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'ยืนยันสิทธิ์!',
                    text: "คุณต้องการยืนยันสิทธิ์การเข้าศึกษาต่อในรอบโควตาใช่หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateConfirmStatus(data.numreg, 1);
                    }
                });
            });

            document.getElementById('DisclaimButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'สละสิทธิ์!',
                    text: "คุณต้องการสละสิทธิ์การเข้าศึกษาต่อในรอบโควตาใช่หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'สละสิทธิ์',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateConfirmStatus(data.numreg, 9);
                    }
                });
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

function updateConfirmStatus(numreg, status) {
    fetch('api/update_confirm_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ numreg: numreg, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'สถานะได้รับการอัปเดตเรียบร้อยแล้ว'
            });
            document.getElementById('ConfirmButton').classList.add('d-none');
            document.getElementById('DisclaimButton').classList.add('d-none');
            let statusText = status == 1 ? 'ยืนยัน' : 'สละสิทธิ์';
            document.getElementById('studentDetails').innerText += `\nคุณได้ทำการ${statusText}สิทธิ์ไปแล้ว`;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถอัปเดตสถานะได้'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถอัปเดตสถานะได้'
        });
    });
}
</script>
<?php require_once('scirpt.php');?>
</body>
</html>
