<h4><label class="text-primary text-center">ขั้นตอนที่ 2 : </label></h4>

<div class="row mb-3">
    <!-- Prefix -->
    <div class="col-sm-2">
        <label for="stu_prefix">คำนำหน้า</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="stu_prefix" 
                name="stu_prefix" 
                required 
                aria-label="Prefix"
            >
                <option value="" disabled selected>เลือกคำนำหน้า</option>
                <option class="text-center" value="เด็กชาย">เด็กชาย</option>
                <option class="text-center" value="เด็กหญิง">เด็กหญิง</option>
                <option class="text-center" value="นาย">นาย</option>
                <option class="text-center" value="นางสาว">นางสาว</option>
            </select>
        </div>
    </div>

    <!-- First Name -->
    <div class="col-sm-3">
        <label for="stu_name">ชื่อ (ภาษาไทย)</label>
        <div class="input-group">
            <input 
                type="text" 
                id="stu_name" 
                name="stu_name" 
                class="form-control text-center" 
                placeholder="กรอกชื่อ" 
                maxlength="50" 
                required 
                aria-label="First Name"
            >
        </div>
    </div>

    <!-- Last Name -->
    <div class="col-sm-3">
        <label for="stu_lastname">นามสกุล (ภาษาไทย)</label>
        <div class="input-group">
            <input 
                type="text" 
                id="stu_lastname" 
                name="stu_lastname" 
                class="form-control text-center" 
                placeholder="กรอกนามสกุล" 
                maxlength="100" 
                required 
                aria-label="Last Name"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender -->
    <div class="col-sm-2">
        <label for="stu_sex">เพศ</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="stu_sex" 
                name="stu_sex" 
                required 
                aria-label="Gender"
            >
                <option value="" disabled selected>เลือกเพศ</option>
                <option class="text-center" value="ชาย">ชาย</option>
                <option class="text-center" value="หญิง">หญิง</option>
            </select>
        </div>
    </div>

    <!-- Religion -->
    <div class="col-sm-2">
        <label for="stu_religion">ศาสนา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="stu_religion" 
                name="stu_religion" 
                class="form-control text-center" 
                placeholder="กรอกศาสนา" 
                maxlength="150" 
                required 
                aria-label="Religion"
            >
        </div>
    </div>

    <!-- Ethnicity -->
    <div class="col-sm-2">
        <label for="stu_ethnicity">เชื้อชาติ</label>
        <div class="input-group">
            <input 
                type="text" 
                id="stu_ethnicity" 
                name="stu_ethnicity" 
                class="form-control text-center" 
                placeholder="กรอกเชื้อชาติ" 
                maxlength="150" 
                required 
                aria-label="Ethnicity"
            >
        </div>
    </div>

    <!-- Nationality -->
    <div class="col-sm-2">
        <label for="stu_nationality">สัญชาติ</label>
        <div class="input-group">
            <input 
                type="text" 
                id="stu_nationality" 
                name="stu_nationality" 
                class="form-control text-center" 
                placeholder="กรอกสัญชาติ" 
                maxlength="150" 
                required 
                aria-label="Nationality"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
    </div>
</div>
