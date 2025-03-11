<h4><label class="text-primary text-center">ขั้นตอนที่ 4 : </label></h4>

<h4 class="text-success">ที่อยู่ตามทะเบียนบ้าน</h4>

<div class="row mb-3">
    <!-- House Number -->
    <div class="col-sm-2">
        <label for="old_addr">บ้านเลขที่</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="old_addr" 
                name="old_addr" 
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
        <label for="old_moo">หมู่ที่</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="old_moo" 
                name="old_moo" 
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
        <label for="old_soy">ซอย</label>
        <div class="input-group">
            <input 
                type="text" 
                id="old_soy" 
                name="old_soy" 
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
        <label for="old_street">ถนน</label>
        <div class="input-group">
            <input 
                type="text" 
                id="old_street" 
                name="old_street" 
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
        <label for="old_tel">โทรศัพท์</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="old_tel" 
                name="old_tel" 
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
        <label for="old_province">จังหวัด</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="old_province" 
                name="old_province" 
                required 
                aria-label="Province"
            >
                <option value="" disabled selected>เลือกจังหวัด</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- District -->
    <div class="col-sm-3">
        <label for="old_district">อำเภอ/เขต</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="old_district" 
                name="old_district" 
                required 
                aria-label="District"
            >
                <option value="" disabled selected>เลือกอำเภอ</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- Subdistrict -->
    <div class="col-sm-3">
        <label for="old_subdistrict">ตำบล/แขวง</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="old_subdistrict" 
                name="old_subdistrict" 
                required 
                aria-label="Subdistrict"
            >
                <option value="" disabled selected>เลือกตำบล</option>
                <!-- Dynamic Options Here -->
            </select>
        </div>
    </div>

    <!-- Postal Code -->
    <div class="col-sm-2">
        <label for="old_post">รหัสไปรษณีย์</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="old_post" 
                name="old_post" 
                class="form-control text-center" 
                placeholder="รหัสไปรษณีย์" 
                maxlength="5" 
                required 
                aria-label="Postal Code"
            >
        </div>
    </div>
</div>
