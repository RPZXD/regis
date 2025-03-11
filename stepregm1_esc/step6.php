<h4><label class="text-primary text-center">ขั้นตอนที่ 6 : </label></h4>

<h4 class="text-success">ผู้ปกครองที่สามารถติดต่อได้ในกรณีฉุกเฉิน</h4>
<div class="row mb-3">
    <!-- Guardian Prefix -->
    <div class="col-sm-2">
        <label for="parent_prefix">คำนำหน้า</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="parent_prefix" 
                name="parent_prefix" 
                required 
                aria-label="Guardian's Prefix">
                <option value="" disabled selected>เลือกคำนำหน้า</option>
                <?php 
                    $prefix_names = [
                        'นาย', 
                        'นางสาว', 
                        'นาง', 
                        'ว่าที่ รต.', 
                        'ว่าที่ รต. หญิง', 
                        'ว่าที่ร้อยตรี', 
                        'ว่าที่ร้อยตรี หญิง', 
                        'ศ.ดร.', 
                        'ดร.', 
                        'นาวาอากาศตรี'
                    ];
                    foreach ($prefix_names as $prefix) {
                        echo "<option class='text-center' value='{$prefix}'>{$prefix}</option>";
                    }
                ?>
            </select>
        </div>
    </div>

    <!-- Guardian Name -->
    <div class="col-sm-3">
        <label for="parent_name">ชื่อผู้ปกครอง</label>
        <div class="input-group">
            <input 
                type="text" 
                id="parent_name" 
                name="parent_name" 
                class="form-control text-center" 
                placeholder="ชื่อผู้ปกครอง" 
                maxlength="150" 
                required 
                aria-label="Guardian's First Name">
        </div>
    </div>

    <!-- Guardian Last Name -->
    <div class="col-sm-3">
        <label for="parent_lastname">นามสกุลผู้ปกครอง</label>
        <div class="input-group">
            <input 
                type="text" 
                id="parent_lastname" 
                name="parent_lastname" 
                class="form-control text-center" 
                placeholder="นามสกุลผู้ปกครอง" 
                maxlength="150" 
                required 
                aria-label="Guardian's Last Name">
        </div>
    </div>

    <!-- Guardian Phone -->
    <div class="col-sm-2">
        <label for="parent_tel">เบอร์โทรศัพท์ผู้ปกครอง</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="parent_tel" 
                name="parent_tel" 
                class="form-control text-center" 
                placeholder="เบอร์โทรศัพท์ผู้ปกครอง" 
                pattern="[0-9]{10}" 
                maxlength="10" 
                required 
                aria-label="Guardian's Phone Number">
        </div>
    </div>

    <!-- Guardian Relationship -->
    <div class="col-sm-2">
        <label for="parent_relation">ความสัมพันธ์เป็น...</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="parent_relation" 
                name="parent_relation" 
                required 
                aria-label="Relationship to Student">
                <option value="" disabled selected>เลือกความสัมพันธ์</option>
                <?php 
                    $relations = [
                        'บิดา', 
                        'มารดา', 
                        'พี่ชาย', 
                        'พี่สาว', 
                        'ปู่', 
                        'ย่า', 
                        'ตา', 
                        'ยาย', 
                        'ลุง', 
                        'ป้า', 
                        'น้า', 
                        'อา'
                    ];
                    foreach ($relations as $relation) {
                        echo "<option class='text-center' value='{$relation}'>{$relation}</option>";
                    }
                ?>
            </select>
        </div>
    </div>
</div>
