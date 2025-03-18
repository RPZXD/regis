<?php require_once('header.php'); ?>
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
                                <div class="form-group mt-2">
                                    <label for="document1">1. ใบสมัครของโรงเรียนพิชัย 
                                        <span class="text-danger">ที่กรอกข้อมูลในระบบถูกต้องครบถ้วน และพิมพ์ใบสมัคร จากระบบ พร้อมติดรูปถ่ายหน้าตรง ขนาด ๑.๕ นิ้ว พร้อมลงลายมือชื่อนักเรียนและผู้ปกครองในใบสมัครที่พิมพ์ ออกจากระบบให้ครบถ้วน</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document1" name="document1">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document2">2. สำเนาบัตรประจำตัวประชาชนของนักเรียน 
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document2" name="document2">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document3">3. สำเนาทะเบียนบ้านของนักเรียน
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document3" name="document3">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document4">4. สำเนาทะเบียนบ้านของบิดา
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document4" name="document4">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document5">5. สำเนาทะเบียนบ้านของมารดา
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document5" name="document5">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document6">6. ใบรับรองผลการเรียนเฉลี่ยรวม
                                        <span class="text-danger">ในระดับชั้นประถมศึกษาปีที่ ๔ และ ๕ (ปพ.๗) หรือระเบียนแสดงผลการเรียน (ปพ.๑) จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document6" name="document6">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document7">7. รูปถ่ายสี
                                    <span class="text-danger">(แต่งกายเครื่องแบบนักเรียน) หน้าตรง ไม่สวมหมวก ไม่ใส่แว่นตาดำ ขนาด ๓ x ๔ เซนติเมตร หรือ ๑.๕ นิ้ว (ถ่ายไว้ไม่เกิน ๖ เดือน) จำนวน ๑ รูป</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document7" name="document7">
                                </div>
                                <span class="text-danger text-lg">หมายเหตุ ให้นักเรียนอัปโหลดเอกสารในข้อ (๑ - ๗) ในระบบการรับสมัครออนไลน์</span><br>
                                <button type="submit" class="btn btn-success my-2 form-control">อัพโหลด</button>
                            </form>

                            <!-- Form for level 1 -->
                            <form id="uploadFormLevel4" enctype="multipart/form-data">
                                <div class="form-group mt-2">
                                    <label for="document1">๑) ใบสมัครของโรงเรียนพิชัย 
                                        <span class="text-danger">ที่กรอกข้อมูลในระบบถูกต้องครบถ้วน และพิมพ์ใบสมัคร จากระบบ พร้อมติดรูปถ่ายหน้าตรง ขนาด ๑.๕ นิ้ว พร้อมลงลายมือชื่อนักเรียนและผู้ปกครองในใบสมัคร ที่พิมพ์ออกจากระบบให้ครบถ้วน</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document1" name="document1">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document2">๒) สำเนาบัตรประจำตัวประชาชนของนักเรียน 
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document2" name="document2">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document3">๓) สำเนาทะเบียนบ้านของนักเรียน
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document3" name="document3">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document4">๔) สำเนาทะเบียนบ้านของบิดา
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document4" name="document4">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document5">๕) สำเนาทะเบียนบ้านของมารดา
                                        <span class="text-danger">พร้อมลงลายมือชื่อรับรองสำเนาถูกต้อง หรือแบบรับรองรายการทะเบียนราษฎร (ท.ร. ๑๔) ที่ได้รับการรับรองจากหน่วยงานราชการ จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document5" name="document5">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document6">๖) ระเบียนแสดงผลการเรียนหลักสูตรแกนกลางการศึกษาขั้นพื้นฐาน
                                        <span class="text-danger">(ปพ.๑) ๕ ภาคเรียน จำนวน ๑ ฉบับ</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document6" name="document6">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="document7">๗) รูปถ่ายสี
                                    <span class="text-danger">(แต่งกายเครื่องแบบนักเรียน) หน้าตรง ไม่สวมหมวก ไม่ใส่แว่นตาดำ ขนาด ๓ x ๔ เซนติเมตร หรือ ๑.๕ นิ้ว (ถ่ายไว้ไม่เกิน ๖ เดือน) จำนวน ๑ รูป</span>
                                    </label>
                                    <input type="file" accept="image/*" class="form-control" id="document7" name="document7">
                                </div>
                                <span class="text-danger text-lg">หมายเหตุ ให้นักเรียนอัปโหลดเอกสารในข้อ (๑ - ๗) ในระบบการรับสมัครออนไลน์</span><br>
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
    var citizenid = document.getElementById('search_input').value; // Get the citizenid from the search input
    formData.append('citizenid', citizenid); // Append the citizenid to the formData

    // Append name parameters for each file input
    formData.append('document1_name', 'ใบสมัครของโรงเรียนพิชัย');
    formData.append('document2_name', 'สำเนาบัตรประจำตัวประชาชนของนักเรียน');
    formData.append('document3_name', 'สำเนาทะเบียนบ้านของนักเรียน');
    formData.append('document4_name', 'สำเนาทะเบียนบ้านของบิดา');
    formData.append('document5_name', 'สำเนาทะเบียนบ้านของมารดา');
    formData.append('document6_name', 'ใบรับรองผลการเรียนเฉลี่ยรวม');
    formData.append('document7_name', 'รูปถ่ายสี');

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
    formData.append('document6_name', 'ระเบียนแสดงผลการเรียนหลักสูตรแกนกลางการศึกษาขั้นพื้นฐาน');
    formData.append('document7_name', 'รูปถ่ายสี');

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
