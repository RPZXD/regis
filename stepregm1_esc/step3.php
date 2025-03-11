<h4><label class="text-primary text-center">ขั้นตอนที่ 3 : </label></h4>

<div class="row mb-3">
    <!-- Previous School -->
    <div class="col-sm-5">
        <label for="old_school">
            สำเร็จการศึกษาหรือกำลังศึกษาชั้นประถมศึกษาปีที่ 6 จากโรงเรียน 
            <span class="right badge badge-danger">(ไม่ต้องพิมพ์คำว่า โรงเรียน / ร.ร. ให้พิมพ์ชื่อเต็มโรงเรียนเท่านั้น เช่น พิชัย)</span>
        </label>
        <div class="input-group">
            <input 
                type="text" 
                id="old_school" 
                name="old_school" 
                class="form-control text-center" 
                placeholder="กรอกชื่อเต็มเท่านั้น ห้ามใช้อักษรย่อ" 
                maxlength="150" 
                required 
                aria-label="Old School Name"
            >
        </div>
    </div>

    <!-- Province of Previous School -->
    <div class="col-sm-3">
        <label for="old_school_province">จังหวัด (โรงเรียนเดิม)</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="old_school_province" 
                name="old_school_province" 
                required 
                aria-label="Province of Old School"
            >
                <option value="" disabled selected>เลือกจังหวัด</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- District of Previous School -->
    <div class="col-sm-3">
        <label for="old_school_district">อำเภอ (โรงเรียนเดิม)</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="old_school_district" 
                name="old_school_district" 
                required 
                aria-label="District of Old School"
            >
                <option value="" disabled selected>เลือกอำเภอ</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>
</div>

<hr>
<h4 class="text-success">ที่อยู่ปัจจุบัน</h4>

<div class="row mb-3">
    <!-- House Number -->
    <div class="col-sm-2">
        <label for="now_addr">บ้านเลขที่</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="now_addr" 
                name="now_addr" 
                class="form-control text-center" 
                placeholder="บ้านเลขที่" 
                maxlength="10" 
                required 
                aria-label="House Number"
            >
        </div>
    </div>

    <!-- Moo -->
    <div class="col-sm-2">
        <label for="now_moo">หมู่ที่</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="now_moo" 
                name="now_moo" 
                class="form-control text-center" 
                placeholder="หมู่ที่" 
                maxlength="10" 
                required 
                aria-label="Moo"
            >
        </div>
    </div>

    <!-- Soy -->
    <div class="col-sm-2">
        <label for="now_soy">ซอย</label>
        <div class="input-group">
            <input 
                type="text" 
                id="now_soy" 
                name="now_soy" 
                class="form-control text-center" 
                placeholder="ซอย" 
                maxlength="10" 
                required 
                aria-label="Soy"
            >
        </div>
    </div>

    <!-- Street -->
    <div class="col-sm-2">
        <label for="now_street">ถนน</label>
        <div class="input-group">
            <input 
                type="text" 
                id="now_street" 
                name="now_street" 
                class="form-control text-center" 
                placeholder="ถนน" 
                maxlength="50" 
                required 
                aria-label="Street"
            >
        </div>
    </div>

    <!-- Phone -->
    <div class="col-sm-2">
        <label for="now_tel">โทรศัพท์</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="now_tel" 
                name="now_tel" 
                class="form-control text-center" 
                placeholder="ไม่ต้องใส่เครื่องหมาย - " 
                maxlength="10" 
                required 
                aria-label="Phone Number"
            >
        </div>
    </div>
</div>

<div class="row mb-3">
    <!-- Province -->
    <div class="col-sm-3">
        <label for="now_province">จังหวัด</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="now_province" 
                name="now_province" 
                required 
                aria-label="Current Province"
            >
                <option value="" disabled selected>เลือกจังหวัด</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- District -->
    <div class="col-sm-3">
        <label for="now_district">อำเภอ/เขต</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="now_district" 
                name="now_district" 
                required 
                aria-label="Current District"
            >
                <option value="" disabled selected>เลือกอำเภอ</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- Subdistrict -->
    <div class="col-sm-3">
        <label for="now_subdistrict">ตำบล/แขวง</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="now_subdistrict" 
                name="now_subdistrict" 
                required 
                aria-label="Current Subdistrict"
            >
                <option value="" disabled selected>เลือกตำบล</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- Postal Code -->
    <div class="col-sm-2">
        <label for="now_post">รหัสไปรษณีย์</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="now_post" 
                name="now_post" 
                class="form-control text-center" 
                placeholder="รหัสไปรษณีย์" 
                maxlength="5" 
                required 
                aria-label="Postal Code"
            >
        </div>
    </div>
</div>
