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
<h4 class="mb-3 text-danger">*** เลือกเรียงลำดับจาก 1 - 10  (โดยในลำดับ 1-10 ห้ามมีห้องที่ซ้ำกัน !!! )*** </h4>


<div class="row mb-3">
<h5><label>ลำดับที่ 1</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number1" name="number1" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
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
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
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
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
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
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
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
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
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
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 7</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number7" name="number7" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 8</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number8" name="number8" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 9</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number9" name="number9" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
     </select> 
 </div>
</div>
</div>
<div class="row mb-3">
<h5><label>ลำดับที่ 10</label></h5>
<div class="col-sm-6">
 <div class="input-group">
     <select class="form-control text-center" id="number10" name="number10" onchange="check();" required>
     <option class="text-center" value="" selected>โปรดระบุแผนการเรียน</option>
     <option class="text-center"  value="1">ห้อง 3 : วิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี (Coding)</option>
     <option class="text-center"  value="2">ห้อง 4 : วิทยาศาสตร์พลังสิบ</option>
     <option class="text-center"  value="3">ห้อง 5 : ภาษาต่างประเทศ (ภาษาอังกฤษ)</option>
     <option class="text-center"  value="4">ห้อง 6 : ภาษาต่างประเทศ (ภาษาจีน)</option>
     <option class="text-center"  value="5">ห้อง 7 : ภาษาไทย</option>
     <option class="text-center"  value="6">ห้อง 8 : สังคมศึกษา</option>
     <option class="text-center"  value="7">ห้อง 9 : แผน - อุตสาหกรรม</option>
     <option class="text-center"  value="8">ห้อง 9 : แผน - พาณิชยกรรม</option>
     <option class="text-center"  value="9">ห้อง 10 : แผน - เกษตรกรรม</option>
     <option class="text-center"  value="10">ห้อง 10 : แผน – คหกรรม</option>
     <option class="text-center"  value="11">ห้อง 11 : แผน - ศิลปะ</option>
     <option class="text-center"  value="12">ห้อง 11 : แผน - ดนตรี</option>
     <option class="text-center"  value="13">ห้อง 11 : แผน - นาฏศิลป์</option>
     <option class="text-center"  value="14">ห้อง 12 :  กีฬา</option>
     </select> 
 </div>
</div>
</div>

