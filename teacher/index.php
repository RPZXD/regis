<?php 

session_start();


require_once("../config/Database.php");
require_once("../class/UserLogin.php");
require_once("../class/Utils.php");
require_once("../class/StudentRegis.php");

// Initialize database connection
$connectDB = new Database_User();
$db = $connectDB->getConnection();
$connectDBregis = new Database_Regis();
$dbRegis = $connectDBregis->getConnection();

// Initialize UserLogin class
$user = new UserLogin($db);

// Initialize StudentRegis class
$studentRegis = new StudentRegis($dbRegis);

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

// Fetch counts
$Count_Confirm_Confirmed = $studentRegis->countConfirm(1, 4, 'โควต้า', 2568);
$Count_Confirm_Declined = $studentRegis->countConfirm(9, 4, 'โควต้า', 2568);
$Count_Confirm_Pending = $studentRegis->countConfirm(0, 4, 'โควต้า', 2568);

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
              <div class="w-full">
                      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 text-center">
                          <h4 class="text-lg font-semibold">ยินดีต้อนรับคุณครู <?php echo $userData['Teach_name'] ?> เข้าสู่ระบบรับสมัครนักเรียน | โรงเรียนพิชัย</h4>
                      </div>
                  </div>
            </div>
            <div class="row justify-content-center">
              <h3 class="text-xl font-semibold text-gray-900 mt-6">ยอดรายงานตัว ม4 (โควต้า ม3เดิม)</h3>

            </div>
                
            <div class="row justify-content-center">
                <div class="col-md-8">
                <div class="flex flex-wrap mt-4">
                    <div class="w-full md:w-1/3 px-2 mb-4">
                      <!-- small box -->
                      <div class="bg-green-500 text-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center">
                          <h3 class="text-3xl font-bold"><?=$Count_Confirm_Confirmed?></h3>
                          <i class="ion ion-person-add text-4xl"></i>
                        </div>
                        <p class="mt-2">ยืนยันแล้ว</p>
                      </div>
                    </div>
                    <!-- ./col -->
                    <div class="w-full md:w-1/3 px-2 mb-4">
                      <!-- small box -->
                      <div class="bg-red-500 text-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center">
                          <h3 class="text-3xl font-bold"><?=$Count_Confirm_Declined?></h3>
                          <i class="ion ion-person-add text-4xl"></i>
                        </div>
                        <p class="mt-2">สละสิทธิ์</p>
                      </div>
                    </div>

                    <div class="w-full md:w-1/3 px-2 mb-4">
                      <!-- small box -->
                      <div class="bg-yellow-500 text-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center">
                          <h3 class="text-3xl font-bold"><?=$Count_Confirm_Pending?></h3>
                          <i class="ion ion-person-add text-4xl"></i>
                        </div>
                        <p class="mt-2">รอการยืนยัน</p>
                      </div>
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

<script>

</script>

<?php require_once('scirpt.php');?>
</body>
</html>
