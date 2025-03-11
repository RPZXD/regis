<h4><label class="text-primary text-center">ขั้นตอนที่ 5 : </label></h4>

<h4 class="text-success">ข้อมูลบิดา</h4>
<div class="row mb-3">
    <!-- Father's Prefix -->
    <div class="col-sm-2">
        <label for="dad_prefix">คำนำหน้า</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="dad_prefix" 
                name="dad_prefix" 
                required 
                aria-label="Father's Prefix">
                <option value="" disabled selected>เลือกคำนำหน้า</option>
                <?php 
                    $prefix_name = [
                        'นาย', 
                        'ว่าที่ รต.', 
                        'ว่าที่ร้อยตรี', 
                        'ศ.ดร.', 
                        'ดร.', 
                        'นาวาอากาศตรี'
                    ];
                    foreach ($prefix_name as $prefix) {
                        echo "<option class='text-center' value='{$prefix}'>{$prefix}</option>";
                    }
                ?>
            </select>
        </div>
    </div>

    <!-- Father's Name -->
    <div class="col-sm-3">
        <label for="dad_name">ชื่อบิดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="dad_name" 
                name="dad_name" 
                class="form-control text-center" 
                placeholder="ชื่อบิดา" 
                maxlength="150" 
                required 
                aria-label="Father's First Name">
        </div>
    </div>

    <!-- Father's Last Name -->
    <div class="col-sm-3">
        <label for="dad_lastname">นามสกุลบิดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="dad_lastname" 
                name="dad_lastname" 
                class="form-control text-center" 
                placeholder="นามสกุลบิดา" 
                maxlength="150" 
                required 
                aria-label="Father's Last Name">
        </div>
    </div>

    <!-- Father's Job -->
    <div class="col-sm-2">
        <label for="dad_job">อาชีพบิดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="dad_job" 
                name="dad_job" 
                class="form-control text-center" 
                placeholder="อาชีพบิดา" 
                maxlength="100" 
                required 
                aria-label="Father's Occupation">
        </div>
    </div>

    <!-- Father's Phone -->
    <div class="col-sm-2">
        <label for="dad_tel">เบอร์โทรศัพท์บิดา</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="dad_tel" 
                name="dad_tel" 
                class="form-control text-center" 
                placeholder="เบอร์โทรศัพท์บิดา" 
                pattern="[0-9]{10}" 
                maxlength="10" 
                required 
                aria-label="Father's Phone Number">
        </div>
    </div>
</div>

<hr>
<h4 class="text-success">ข้อมูลมารดา</h4>
<div class="row mb-3">
    <!-- Mother's Prefix -->
    <div class="col-sm-2">
        <label for="mom_prefix">คำนำหน้า</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="mom_prefix" 
                name="mom_prefix" 
                required 
                aria-label="Mother's Prefix">
                <option value="" disabled selected>เลือกคำนำหน้า</option>
                <?php 
                    $prefix_name = [
                        'นางสาว', 
                        'นาง', 
                        'ว่าที่ รต. หญิง', 
                        'ว่าที่ร้อยตรี หญิง', 
                        'ศ.ดร.', 
                        'ดร.', 
                        'นาวาอากาศตรี หญิง'
                    ];
                    foreach ($prefix_name as $prefix) {
                        echo "<option class='text-center' value='{$prefix}'>{$prefix}</option>";
                    }
                ?>
            </select>
        </div>
    </div>

    <!-- Mother's Name -->
    <div class="col-sm-3">
        <label for="mom_name">ชื่อมารดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="mom_name" 
                name="mom_name" 
                class="form-control text-center" 
                placeholder="ชื่อมารดา" 
                maxlength="150" 
                required 
                aria-label="Mother's First Name">
        </div>
    </div>

    <!-- Mother's Last Name -->
    <div class="col-sm-3">
        <label for="mom_lastname">นามสกุลมารดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="mom_lastname" 
                name="mom_lastname" 
                class="form-control text-center" 
                placeholder="นามสกุลมารดา" 
                maxlength="150" 
                required 
                aria-label="Mother's Last Name">
        </div>
    </div>

    <!-- Mother's Job -->
    <div class="col-sm-2">
        <label for="mom_job">อาชีพมารดา</label>
        <div class="input-group">
            <input 
                type="text" 
                id="mom_job" 
                name="mom_job" 
                class="form-control text-center" 
                placeholder="อาชีพมารดา" 
                maxlength="100" 
                required 
                aria-label="Mother's Occupation">
        </div>
    </div>

    <!-- Mother's Phone -->
    <div class="col-sm-2">
        <label for="mom_tel">เบอร์โทรศัพท์มารดา</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="mom_tel" 
                name="mom_tel" 
                class="form-control text-center" 
                placeholder="เบอร์โทรศัพท์มารดา" 
                pattern="[0-9]{10}" 
                maxlength="10" 
                required 
                aria-label="Mother's Phone Number">
        </div>
    </div>
</div>
