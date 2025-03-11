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
  
  
  
  