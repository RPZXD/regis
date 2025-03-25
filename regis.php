<?php 
require_once('header.php');
?>
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

        <div class="row">

        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                <h2 class="text-center"><i class="fas fa-bullhorn"></i>
                  &nbsp;&nbsp;ข้อตกลงก่อนการกรอกข้อมูลใบสมัคร</h2>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="callout callout-danger">
                  <h4>กรุณาอ่านข้อความด้านล่างและยอมรับเงื่อนไขต่าง ๆ ก่อนการกรอกข้อมูลใบสมัคร&nbsp;&nbsp;</h4>
                </div>
                <div class="callout callout-info">
                <p class="text-left"><h5>1. ผู้สมัครควรใช้เครื่องคอมพิวเตอร์ที่มีระบบโปรแกรเบราว์เซอร์ google chrome , microsoft Edge หรือ firefox ในการกรอกใบสมัคร</h5></p>

                <p><h5>2. หากข้อมูลผู้สมัครสอบที่ผู้สมัครสอบบันทึกในระหว่างการดำเนินการกรอกข้อมูลใบสมัคร ไม่ตรงกับความเป็นจริง ไม่ถูกต้อง หรือผู้สมัครให้ข้อมูลอันเป็นเท็จ<ins class="text-danger">ทางโรงเรียนขอสงวนสิทธิ์ในการไม่พิจารณาผู้สมัครเข้าเรียนในปีนั้นๆ และท่านจะไม่สามารถเรียกร้องใด ๆ ได้ทั้งสิ้น</ins></h5></p>
                
                <!-- <p><h5>3. หลังจากผู้สมัครกรอกข้อมูลใบสมัครเรียบร้อยแล้ว<ins class="text-danger">ผู้สมัครจะต้องพิมพ์ใบสมัคร ลงลายมือชื่อผู้สมัคร และลายมือชื่อผู้ปกครอง ติดรูปถ่ายแล้วทำการสแกนแล้วอัปโหลดเข้าสู่ระบบตามวันและเวลาที่กำหนด หากผู้สมัครมีคุณสมบัติผ่านเกณฑ์ตามประกาศ โรงเรียนจะดำเนินการออกเลขที่สมัครให้</ins></h5></p> -->

                <p><h5>3. ข้อมูลที่ผู้สมัครกรอกในแต่ละหน้าทั้งหมดจะยังไม่บันทึก จนกว่าจะจบขั้นตอนการกรอกข้อมูล</h5></p>

                <p><h5>4. การกระทำใดซึ่งผู้ใช้ได้กระทำให้เกิดมีอันตรายต่อระบบ โรงเรียนจะดำเนินคดีอาญาต่อผู้กระทำผิดจนถึงที่สุด</h5></p>
                </div>

                <div class="callout callout-danger text-center">
                  <h2 class="text-danger">เวลาในการกรอกข้อมูลการสมัคร 08.30 น. - 16.00 น.</h2>
                  <hr><br>
                  <div class="row">

                    <img src="dist/img/calendarregis.jpg" alt=""  class="img-fluid w-100">
                  </div>
                </div>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-12 col-sm-12 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fas fas fa-bullhorn"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><h5>กรุณาเลือกระดับชั้นมัธยมศึกษาที่ท่านต้องการสมัครเพื่อศึกษาต่อในปีการศึกษา 2568</h5></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-6 ml-auto mr-auto">
            <div class="card card-outline card-info">
              <div class="card-header">
                <div class="info-box">
                  <span class="info-box-icon bg-primary"><i class="fas fas fa-user-plus"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">สมัครระดับชั้นมัธยมศึกษาปีที่ 1</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="card-body">
                <button id="regis1Btn" class="btn btn-primary form-control mt-3" onclick="location.href='regis1.php'" disabled><i class="fas fas fa-user-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;สมัคร ม.1 (สอบคัดเลือกทั่วไป) << คลิกที่นี่ &nbsp;&nbsp;<i class="fas fas fa-exclamation"></i></button>
                <!-- <button id="regis1escBtn" class="btn btn-primary form-control mt-3" onclick="location.href='regis1_esc.php'" disabled><i class="fas fas fa-user-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;สมัคร ม.1 (ห้องเรียนพิเศษ) << คลิกที่นี่ &nbsp;&nbsp;<i class="fas fas fa-exclamation"></i></button> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-md-6 ml-auto mr-auto">
            <div class="card card-outline card-info">
              <div class="card-header">
                <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="fas fas fa-user-plus"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">สมัครระดับชั้นมัธยมศึกษาปีที่ 4</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="card-body">

                <button id="regis4quotaBtn" class="btn btn-success form-control mt-3" onclick="location.href='regis4_quota.php'" disabled><i class="fas fas fa-user-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;สมัคร ม.4 (โควต้า) << คลิกที่นี่ &nbsp;&nbsp;<i class="fas fas fa-exclamation"></i></button>
                <button id="regis4Btn" class="btn btn-success form-control mt-3" onclick="location.href='regis4.php'" disabled><i class="fas fas fa-user-plus"></i> &nbsp;&nbsp;&nbsp;&nbsp;สมัคร ม.4 (สอบคัดเลือกทั่วไป) << คลิกที่นี่ &nbsp;&nbsp;<i class="fas fas fa-exclamation"></i></button>
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <?php require_once('footer.php');?>
</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const regis1Btn = document.getElementById('regis1Btn');
  const regis1escBtn = document.getElementById('regis1escBtn');
  const regis4quotaBtn = document.getElementById('regis4quotaBtn');
  const regis4Btn = document.getElementById('regis4Btn');

  const currentDate = new Date();
  const todayString = currentDate.toDateString();

  const startTime = new Date(todayString + ' 08:30:00');
  const endTime = new Date(todayString + ' 16:00:00');

  const regis1StartDate = new Date('2025-03-20');
  const regis1EndDate = new Date('2025-03-23');
  const regis1escStartDate = new Date('2025-03-15');
  const regis1escEndDate = new Date('2025-03-19');
  const regis4quotaStartDate = new Date('2025-02-15');
  const regis4quotaEndDate = new Date('2025-02-20');
  const regis4StartDate = new Date('2025-03-20');
  const regis4EndDate = new Date('2025-03-23');

  function isWithinDateRange(start, end) {
    return currentDate >= start && currentDate <= end;
  }

  function enableButton(button, condition) {
    if (button) {
      button.disabled = !condition;
    }
  }

  if (currentDate >= startTime && currentDate <= endTime) {
    enableButton(regis1Btn, isWithinDateRange(regis1StartDate, regis1EndDate));
    enableButton(regis1escBtn, isWithinDateRange(regis1escStartDate, regis1escEndDate));
    enableButton(regis4quotaBtn, isWithinDateRange(regis4quotaStartDate, regis4quotaEndDate));
    enableButton(regis4Btn, isWithinDateRange(regis4StartDate, regis4EndDate));
  }
});
</script>

<?php require_once('scirpt.php');?>
</body>
</html>
