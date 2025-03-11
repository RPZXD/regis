<?php 

require_once('header.php');
?>
<style>
  .tab {
  display: none;
}

</style>

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
      <div class="register-box col-md-12 col-12">
        <div class="card card-outline card-primary">
          <div class="card-header text-center">
            <h3>สมัครเรียนมัธยมศึกษาปีที่ 4 </h3>
          </div>
          <div class="card-body">
            <h4><p class="login-box-msg">* โปรดกรอกข้อมูลให้ครบถ้วน *</p></h4>

            <form id="regForm" method="POST" enctype="multipart/form-data">
              <!-- แสดงข้อความ Error / Success -->
              <?php if (isset($errorMsg)) : ?>
                <?php foreach ($errorMsg as $error) : ?>
                  <div class="alert alert-danger">
                    <strong><?= $error; ?></strong>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>

              <?php if (isset($registerMsg)) : ?>
                <div class="alert alert-success">
                  <strong><?= $registerMsg; ?></strong>
                </div>
              <?php endif; ?>

              <div id="steps">
                <!-- ขั้นตอนที่ 1 ถึง 7 -->
                <div class="tab"><?php include 'stepregm4/step1.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step2.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step3.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step4.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step5.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step6.php'; ?></div>
                <div class="tab"><?php include 'stepregm4/step7.php'; ?></div>

                <!-- ปุ่มควบคุม -->
                <div class="navigation-buttons" style="overflow:auto;">
                  <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="btn btn-secondary">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)" class="btn btn-primary">Next</button>
                  </div>
                </div>

                <!-- ตัวบ่งชี้ขั้นตอน -->
                <div class="step-indicators text-center mt-4">
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  var currentTab = 0;
  showTab(currentTab);

  function showTab(n) {
    var tabs = document.getElementsByClassName("tab");
    
    // ซ่อนทุกขั้นตอนก่อน
    for (var i = 0; i < tabs.length; i++) {
      tabs[i].style.display = "none";
    }

    // แสดงขั้นตอนที่กำหนด
    tabs[n].style.display = "block";

    // ซ่อน/แสดงปุ่ม Previous และ Next
    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
    document.getElementById("nextBtn").innerHTML = n === (tabs.length - 1) ? "Submit" : "Next";

    // อัพเดตตัวบ่งชี้ขั้นตอน
    fixStepIndicator(n);
  }

  function nextPrev(n) {
    var tabs = document.getElementsByClassName("tab");
    
    // ตรวจสอบให้แน่ใจว่าแบบฟอร์มถูกต้อง
    if (n === 1 && !validateForm()) return false;

    // ซ่อนขั้นตอนปัจจุบัน
    tabs[currentTab].style.display = "none";
    
    // เปลี่ยนแปลงตัวแปร currentTab
    currentTab += n;
    
    // ตรวจสอบว่าเราถึงขั้นตอนสุดท้ายแล้วหรือยัง
    if (currentTab >= tabs.length) {
      submitForm();
      return false;
    }

    // แสดงขั้นตอนถัดไป
    showTab(currentTab);
  }

  function validateForm() {
    var valid = true;
    var inputs = document.getElementsByClassName("tab")[currentTab].getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].value === "") {
        inputs[i].classList.add("is-invalid");
        valid = false;
      } else {
        inputs[i].classList.remove("is-invalid");
      }
    }
    if (valid) document.getElementsByClassName("step")[currentTab].classList.add("finish");
    return valid;
  }

  function fixStepIndicator(n) {
    var steps = document.getElementsByClassName("step");
    for (var i = 0; i < steps.length; i++) {
      steps[i].classList.remove("active");
    }
    steps[n].classList.add("active");
  }

  function submitForm() {
    var form = document.getElementById("regForm");
    var formData = new FormData(form);

    // Log form data to console for testing
    // for (var pair of formData.entries()) {
    //   console.log(pair[0] + ': ' + pair[1]);
    // }

    fetch('insert_reg_m4.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) { 
        Swal.fire({
          icon: 'success',
          title: 'สำเร็จ',
          text: data.message,
        }).then(() => {
          window.location.href = 'regis.php';
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: data.message,
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: error.message,
      });
    });
  }
</script>

<script type="text/javascript">

function check() {
  const d = document;
  const myArray = Array.from({length: 6}, (_, i) =>
    Number(d.getElementById(`number${i + 1}`).value)
  );
  const values = {
    1: [1],
    2: [2],
    3: [3],
    4: [4],
    5: [5],
    6: [6, 7, 8]
  };
  for (let a = 0; a < 8; a++) {
    const select = d.getElementById(`number${a + 1}`);
    for (let b = select.options.length - 1; b >= 1; b--) {
      const value = Number(select.options[b].value);
      const shouldHide = myArray.some(
        num => values[value] && values[value].includes(num)
      );
      select.options[b].style.display = shouldHide ? "none" : "block";
    }
  }
}
    function ckeckboxOP() {
        var checkBox = document.getElementById("agreeTerms");
        if (checkBox.checked == true) {
            document.getElementById("btn_register").disabled = false;
        } else {
            document.getElementById("btn_register").disabled = true;
        }
    }


  $(document).ready(function(){
  $('#citizenid').on('keyup',function(){
    if($.trim($(this).val()) != '' && $(this).val().length == 17){
      id = $(this).val().replace(/-/g,"");
      var result = Script_checkID(id);
      if(result === false){
        $('span.error').removeClass('true').text('เลขบัตรผิด');
      }else{
        $('span.error').addClass('true').text('เลขบัตรถูกต้อง');
      }
    }else{
      $('span.error').removeClass('true').text('');
    
    }
  })
});


$(document).ready(function() {
    $('#old_school_province').empty().append('<option value="">เลือก</option>');
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.province.php',
        success: function (data) {
            $.each(data, function (key, val) {
                $('#old_school_province').append('<option value=' + val.code + '>' + val .name+ '</option>');
            });
            $('#old_school_province').select2();
            $('#old_school_district').select2();
            $('#old_school_subdistrict').select2();
        }
    });
    
    $('#now_province').empty().append('<option value="">เลือก</option>');
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.province.php',
        success: function (data) {
            $.each(data, function (key, val) {
                $('#now_province').append('<option value=' + val.code + '>' + val .name+ '</option>');
            });
            $('#now_province').select2();
            $('#now_district').select2();
            $('#now_subdistrict').select2();
        }
    });
  
    $('#old_province').empty().append('<option value="">เลือก</option>');
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.province.php',
        success: function (data) {
            $.each(data, function (key, val) {
                $('#old_province').append('<option value=' + val.code + '>' + val .name+ '</option>');
            });
            $('#old_province').select2();
            $('#old_district').select2();
            $('#old_subdistrict').select2();
        }
    });
  
    /****** เลือกจังหวัด *******/
    $('#old_school_province').on('change',function(){
        if($('#old_school_district').length > 0){
            call_old_school_District($(this).val(),null);
        }
    });
  
    /****** เลือกอำเภอ *******/
    $('#old_school_district').on('change',function(){
        if($('#old_school_subdistrict').length > 0){
            call_old_school_SubDistrict($(this).val(),null);
        }
    });
  
    $('#now_province').on('change',function(){
        if($('#now_district').length > 0){
            call_now_District($(this).val(),null);
        }
    });
  
    /****** เลือกอำเภอ *******/
    $('#now_district').on('change',function(){
        if($('#now_subdistrict').length > 0){
            call_now_SubDistrict($(this).val(),null);
        }
    });
  
    $('#old_province').on('change',function(){
        if($('#old_district').length > 0){
            call_old_District($(this).val(),null);
        }
    });
  
    /****** เลือกอำเภอ *******/
    $('#old_district').on('change',function(){
        if($('#old_subdistrict').length > 0){
            call_old_SubDistrict($(this).val(),null);
        }
    });
  
  });
  
  function call_old_school_District(proVinceId, selector) {
      $('#old_school_district').empty().append('<option value="">เลือก</option>');
      if($('#old_school_subdistrict').length > 0){
          $('#old_school_subdistrict').empty().append('<option value="">เลือก</option>');
      }
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.district.php',
        data: {
            'id': proVinceId
        },
        success: function (data) {
            $.each(data, function (key, val) {
                selected = (val.code == selector) ? 'selected' : '';
                $('#old_school_district').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#old_school_district').select2();
        }
    });
  }
  
  function call_old_school_SubDistrict(disTrictId, selector ){
    $('#old_school_subdistrict').empty().append('<option value="">เลือก</option>');
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.subdistrict.php',
        data: {
            'id': disTrictId
        },
        success: function (data) {
            $.each(data, function (key, val) {
              selected = (val.code == selector) ? 'selected' : '';
                $('#old_school_subdistrict').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#old_school_subdistrict').select2();
        }
    });
  }
  
  function call_now_District(proVinceId, selector) {
      $('#now_district').empty().append('<option value="">เลือก</option>');
      if($('#now_subdistrict').length > 0){
          $('#now_subdistrict').empty().append('<option value="">เลือก</option>');
      }
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.district.php',
        data: {
            'id': proVinceId
        },
        success: function (data) {
            $.each(data, function (key, val) {
                selected = (val.code == selector) ? 'selected' : '';
                $('#now_district').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#now_district').select2();
        }
    });
  }
  
  function call_now_SubDistrict(disTrictId, selector ){
    $('#now_subdistrict').empty().append('<option value="">เลือก</option>');
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.subdistrict.php',
        data: {
            'id': disTrictId
        },
        success: function (data) {
            $.each(data, function (key, val) {
              selected = (val.code == selector) ? 'selected' : '';
                $('#now_subdistrict').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#now_subdistrict').select2();
        }
    });
  }
  
  function call_old_District(proVinceId, selector) {
      $('#old_district').empty().append('<option value="">เลือก</option>');
      if($('#old_subdistrict').length > 0){
          $('#old_subdistrict').empty().append('<option value="">เลือก</option>');
      }
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.district.php',
        data: {
            'id': proVinceId
        },
        success: function (data) {
            $.each(data, function (key, val) {
                selected = (val.code == selector) ? 'selected' : '';
                $('#old_district').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#old_district').select2();
        }
    });
  }
  
  function call_old_SubDistrict(disTrictId, selector ){
    $('#old_subdistrict').empty().append('<option value="">เลือก</option>');
  
    $.ajax({
        dataType: "json",
        type: 'POST',
        url: 'services/ajax.subdistrict.php',
        data: {
            'id': disTrictId
        },
        success: function (data) {
            $.each(data, function (key, val) {
              selected = (val.code == selector) ? 'selected' : '';
                $('#old_subdistrict').append('<option value=' + val.code +' '+ selected + '>' + val .name+ '</option>');
            });
            $('#old_subdistrict').select2();
        }
    });
  }
</script>

<?php require_once('scirpt.php');?>
</body>
</html>
