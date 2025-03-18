<h4><label class="text-primary text-center">ขั้นตอนที่ 7 : </label></h4>

<h4 class="text-primary">ผลการเรียน รวมเฉลี่ย (ปพ.7)</h4>

<div class="row mb-3">

  <div class="col-sm-4">
    <label>GPAX : รวม</label>
    <div class="input-group">
        <input type="number" id="gpa_total" name="gpa_total" class="form-control text-center" max="4.00" min="0" step="0.01" required value=""> 
    </div>
  </div>


</div>


<hr>

<h4 class="mb-3 text-primary">นักเรียนมีความประสงค์จะเข้าเรียนในแผนการเรียน (ให้นักเรียนเลือกห้อง/แผน ในแต่ละลำดับตามกำหนด) </h4>
<h4 class="mb-3 text-danger">*** เลือกเรียงลำดับจาก 1 - 6  (โดยในลำดับ 1-6 ห้ามมีห้องที่ซ้ำกัน !!! )*** </h4>


<div class="row mb-3">
<h5><label>ลำดับที่ 1</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number1" name="number1" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>

<div class="row mb-3">
<h5><label>ลำดับที่ 2</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number2" name="number2" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 3</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number3" name="number3" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 4</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number4" name="number4" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 5</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number5" name="number5" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 6</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number6" name="number6" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 2 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 3 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 4 : วิทยาศาสตร์ คณิตศาสตร์</option>
     <option class="text-center"  value="4">ห้อง 5 : สังคมศาสตร์และภาษาไทย</option>
     <option class="text-center"  value="5">ห้อง 6 : ภาษาศาสตร์ (ภาษาอังกฤษ, ภาษาจีน)</option>
     <option class="text-center"  value="6">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอาหาร</option>
     <option class="text-center"  value="7">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการเกษตร</option>
     <option class="text-center"  value="8">ห้อง 7 : บริหารอุตสาหกรรม แผน การจัดการอุตสาหกรรม</option>
     </select> 
 </div>
</div>
</div>
