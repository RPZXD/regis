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
                <p class="text-lg font-semibold text-gray-900 mt-6 mb-3 text-center">รายละเอียดค่าใช้จ่ายในการจัดการศึกษาห้องเรียนปกติและค่าใช้จ่ายสนับสนุน <br>ชั้นมัธยมศึกษาปีที่ 4 ปีการศึกษา 2568</p>
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h2 class="text-center"><i class="fas fa-bullhorn"></i>&nbsp;&nbsp;ค่าบำรุงการศึกษาแผนการเรียนวิทยาศาสตร์ คณิตศาสตร์และเทคโนโลยี (Coding) (ห้อง 2)						
                            </h2>
                        </h3>
                    </div>
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                            <table class="w-full border-collapse border border-gray-300 bg-white shadow-md">
                                <thead>
                                    <tr class="bg-green-600 text-white">
                                        <th class="border border-gray-300 p-2 text-center">รายการ</th>
                                        <th class="border border-gray-300 p-2 text-center">ภาคเรียนที่ 1<br>จำนวน (บาท)</th>
                                        <th class="border border-gray-300 p-2 text-center">ภาคเรียนที่ 2<br>จำนวน (บาท)</th>
                                        <th class="border border-gray-300 p-2 text-center">ตลอดปีการศึกษา<br>จำนวน (บาท)</th>
                                    </tr>
                                </thead>
                                <?php
                                    $data = [
                                        ['1.1 ค่าโครงการพัฒนาทักษะตามความถนัดของนักเรียน', 500, 500, 1000],
                                        ['1.2 ค่าสอนคอมพิวเตอร์', 500, 500, 1000],
                                        ['1.3 ค่าประกันชีวิต', 150, '', 150],
                                        ['1.4 ค่าจ้างบุคลากร', 1100, 1100, 2200],
                                    ];
                                    $data2 = [
                                        ['1.1 ค่าสอนคอมพิวเตอร์', 500, 500, 1000],
                                        ['1.2 ค่าประกันชีวิต', 150, '', 150],
                                        ['1.3 ค่าจ้างบุคลากร', 1100, 1100, 2200],
                                    ];

                                    $support_data = [
                                        ['2.1 ค่าบำรุงสมาคมแรกเข้าศึกษาระดับชั้นมัธยมศึกษาตอนปลาย', 500, '-', 500],
                                        ['2.2 ค่ามัดจำชุดพลศึกษา', 550, '-', 550],
                                        ['2.3 ค่ากระเป๋านักเรียน', 350, '-', 350],
                                        ['2.4 ค่ามัดจำชุดเข็มขัด', 150, '-', 150],
                                        ['2.5 ค่าเข็มตราโรงเรียน', 60, '-', 60],
                                        ['2.6  ค่าสมัครและค่าหุ้นสหกรณ์ร้านค้าโรงเรียนพิชัย', 110, '-', 110],
                                        ['2.7 ค่าทำบัตรประจำตัวนักเรียน', 100, '-', 100],
                                        ['2.8 ค่าปฐมนิเทศ', 100, '-', 100],
                                    ];

                                    function renderTable($data) {
                                        foreach ($data as $row) {
                                            echo "<tr>";
                                            echo "<td class='border border-gray-300 p-2 pl-6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$row[0]}</td>";
                                            echo "<td class='border border-gray-300 p-2 text-center'>{$row[1]}</td>";
                                            echo "<td class='border border-gray-300 p-2 text-center'>{$row[2]}</td>";
                                            echo "<td class='border border-gray-300 p-2 text-center'>{$row[3]}</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                <tbody>
                                    <tr class="bg-orange-100 font-bold">
                                        <td class="border border-gray-300 p-2" colspan="7">1. เงินบำรุงการศึกษา</td>
                                    </tr>
                                    <?php renderTable($data); ?>
                                    <tr class="font-bold bg-red-300 text-dark">
                                        <td class="border border-gray-300 p-2">รวมเงินบำรุงการศึกษา</td>
                                        <td class="border border-gray-300 p-2 text-center">2,250</td>
                                        <td class="border border-gray-300 p-2 text-center">2,100</td>
                                        <td class="border border-gray-300 p-2 text-center">4,350</td>
                                    </tr>

                                    <tr class="bg-green-300 font-bold">
                                        <td class="border border-gray-300 p-2" colspan="7">2. ค่าใช้จ่ายสนับสนุน</td>
                                    </tr>
                                    <?php renderTable($support_data); ?>
                                    <tr class="font-bold bg-blue-200">
                                        <td class="border border-gray-300 p-2">รวมเงินค่าใช้จ่ายสนับสนุน</td>
                                        <td class="border border-gray-300 p-2 text-center">1,920</td>
                                        <td class="border border-gray-300 p-2 text-center">-</td>
                                        <td class="border border-gray-300 p-2 text-center">1,920</td>
                                    </tr>
                                    <tr class="font-bold bg-green-200">
                                        <td class="border border-gray-300 p-2 text-center">รวมเงิน (เงินบำรุงการศึกษาและค่าใช้จ่ายสนับสนุน)</td>
                                        <td class="border border-gray-300 p-2 text-center">4,170</td>
                                        <td class="border border-gray-300 p-2 text-center">2,100</td>
                                        <td class="border border-gray-300 p-2 text-center">6,270</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h2 class="text-center"><i class="fas fa-bullhorn"></i>&nbsp;&nbsp;ค่าบำรุงการศึกษาแผนการเรียนทั่วไป (ห้อง 3 - ห้อง 7)						
                            </h2>
                        </h3>
                    </div>
                        <div class="col-md-12 mt-3 mb-3 mx-auto">
                            <div class="table-responsive mx-auto">
                            <table class="w-full border-collapse border border-gray-300 bg-white shadow-md">
                                <thead>
                                    <tr class="bg-green-600 text-white">
                                        <th class="border border-gray-300 p-2 text-center">รายการ</th>
                                        <th class="border border-gray-300 p-2 text-center">ภาคเรียนที่ 1<br>จำนวน (บาท)</th>
                                        <th class="border border-gray-300 p-2 text-center">ภาคเรียนที่ 2<br>จำนวน (บาท)</th>
                                        <th class="border border-gray-300 p-2 text-center">ตลอดปีการศึกษา<br>จำนวน (บาท)</th>
                                    </tr>
                                </thead>
                               
                                <tbody>
                                    <tr class="bg-orange-100 font-bold">
                                        <td class="border border-gray-300 p-2" colspan="7">1. เงินบำรุงการศึกษา</td>
                                    </tr>
                                    <?php renderTable($data2); ?>
                                    <tr class="font-bold bg-red-300 text-dark">
                                        <td class="border border-gray-300 p-2">รวมเงินบำรุงการศึกษา</td>
                                        <td class="border border-gray-300 p-2 text-center">1,750</td>
                                        <td class="border border-gray-300 p-2 text-center">1,600</td>
                                        <td class="border border-gray-300 p-2 text-center">3,350</td>
                                    </tr>

                                    <tr class="bg-green-300 font-bold">
                                        <td class="border border-gray-300 p-2" colspan="7">2. ค่าใช้จ่ายสนับสนุน</td>
                                    </tr>
                                    <?php renderTable($support_data); ?>
                                    <tr class="font-bold bg-blue-200">
                                        <td class="border border-gray-300 p-2">รวมเงินค่าใช้จ่ายสนับสนุน</td>
                                        <td class="border border-gray-300 p-2 text-center">1,920</td>
                                        <td class="border border-gray-300 p-2 text-center">-</td>
                                        <td class="border border-gray-300 p-2 text-center">1,920</td>
                                    </tr>

                                    <tr class="font-bold bg-green-200">
                                        <td class="border border-gray-300 p-2 text-center">รวมเงิน (เงินบำรุงการศึกษาและค่าใช้จ่ายสนับสนุน)</td>
                                        <td class="border border-gray-300 p-2 text-center">3,670</td>
                                        <td class="border border-gray-300 p-2 text-center">1,600</td>
                                        <td class="border border-gray-300 p-2 text-center">5,270</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                <!-- /.card -->
                <div class="text-center mt-4 mb-4">
                    <a href="confirm41.php" class="btn-lg btn-primary form-control">เข้าสู่หน้ารายงานตัว</a>
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
// document.addEventListener("DOMContentLoaded", function () {
//     // กำหนดวันที่และช่วงเวลาที่ต้องการตรวจสอบ
//     let reportDate = new Date(2568 - 543, 2, 13); // ปี ค.ศ. = ปี พ.ศ. - 543 (March 13, 2025)
//     let startTime = new Date(reportDate);
//     startTime.setHours(8, 30, 0); // 08:30:00

//     let endTime = new Date(reportDate);
//     endTime.setHours(12, 30, 0); // 12:00:00

//     // เวลาปัจจุบัน
//     let now = new Date();

//     // ตรวจสอบเงื่อนไข ถ้ายังไม่ถึงเวลาให้แจ้งเตือนและ redirect
//     if (now < startTime || now > endTime || now.toDateString() !== reportDate.toDateString()) {
//         Swal.fire({
//             icon: "warning",
//             title: "ยังไม่ถึงเวลารายงานตัว",
//             text: "กรุณารายงานตัวในวันที่ 13 มีนาคม 2568 เวลา 08.30 - 12.00 น.",
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
