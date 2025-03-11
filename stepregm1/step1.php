<h4><label class="text-primary text-center">ขั้นตอนที่ 1 : </label></h4>

<h4><label class="text-primary">ประเภทการสมัคร</label></h4>
                  <div class="row mb-3">
                    <div class="custom-control custom-radio my-2"><h5>
                      <input class="custom-control-input" type="radio" name="typeregis" id="typeregis1" value="ในเขต" checked>
                      <label class="custom-control-label" for="typeregis1">ในเขต</label></h5>
                    </div>
                    <div class="custom-control custom-radio my-2 ml-3"><h5>
                      <input class="custom-control-input" type="radio" name="typeregis" id="typeregis2" value="นอกเขต">
                      <label class="custom-control-label" for="typeregis2">นอกเขต</label></h5>
                    </div>
                  </div>
<div class="row mb-3">

    <!-- Citizen ID -->
    <div class="col-sm-3">
        <label for="citizenid">เลขบัตรประชาชน 13 หลัก</label>
        <div class="input-group">
            <input 
                type="tel" 
                id="citizenid" 
                name="citizenid" 
                class="form-control text-center" 
                placeholder="เลขบัตรประชาชน 13 หลักของนักเรียน" 
                onkeyup="autoTab(this)" 
                minlength="13" 
                maxlength="13" 
                required
                aria-label="Citizen ID"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Date of Birth: Day -->
    <div class="col-sm-2">
        <label for="date_birth">วันเกิด</label>
        <select 
            class="form-control text-center" 
            id="date_birth" 
            name="date_birth" 
            required 
            aria-label="Day of Birth"
        >
            <option value="" disabled selected>เลือกวัน</option>
            <?php for ($i = 1; $i <= 31; $i++) : ?>
                <option class="text-center" value="<?= $i; ?>"><?= $i; ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <!-- Date of Birth: Month -->
    <div class="col-sm-2">
        <label for="month_birth">เดือนเกิด</label>
        <select 
            class="form-control text-center" 
            id="month_birth" 
            name="month_birth" 
            required 
            aria-label="Month of Birth"
        >
            <option value="" disabled selected>เลือกเดือน</option>
            <?php 
                $months = [
                    1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
                    4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
                    7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
                    10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                ];
                foreach ($months as $key => $month) : 
            ?>
                <option class="text-center" value="<?= $key; ?>"><?= $month; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Date of Birth: Year -->
    <div class="col-sm-2">
        <label for="year_birth">ปีเกิด</label>
        <div class="input-group">
            <select 
                class="form-control text-center" 
                id="year_birth" 
                name="year_birth" 
                required 
                aria-label="Year of Birth"
            >
                <option value="" disabled selected>เลือกปี</option>
                <?php 
                    $currentYear = date('Y') + 543; // Convert to Thai year
                    for ($i = $currentYear - 20; $i <= $currentYear; $i++) : 
                ?>
                    <option class="text-center" value="<?= $i; ?>"><?= $i; ?></option>
                <?php endfor; ?>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-calendar"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Blood Group -->
    <div class="col-sm-2">
        <label for="stu_blood_group">กรุ๊ปเลือด</label>
        <select 
            class="form-control text-center" 
            id="stu_blood_group" 
            name="stu_blood_group" 
            required 
            aria-label="Blood Group"
        >
            <option value="" disabled selected>เลือกกรุ๊ปเลือด</option>
            <option class="text-center" value="A">A</option>
            <option class="text-center" value="B">B</option>
            <option class="text-center" value="O">O</option>
            <option class="text-center" value="AB">AB</option>
            <option class="text-center" value="-">ไม่ทราบกรุ๊ปเลือด</option>
        </select>
    </div>

</div>
