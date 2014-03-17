<div class="container classroom">
  <div class="row-fluid">
    <div class="span2">
      <ul class="nav nav-list bs-docs-sidenav affix">
        <li>
          <a href="/vertex/student"> <i class="icon-chevron-right"></i>
            学生信息
          </a>
        </li>
        <li>
              <a href="/vertex/student/overage"> <i class="icon-chevron-right"></i>
              缴费信息
              </a>
            </li>
         <li>
          <a href="/vertex/student/arrears"> <i class="icon-chevron-right"></i>
            欠费学生
          </a>
        </li>
        <li  class="active">
          <a href="/vertex/student/create"> <i class="icon-chevron-right"></i>
            新增学生
          </a>
        </li>
      </ul>
    </div>
    <div class="span10">

      <div class="">
        <p class="lead">新增学生</p>
        <hr />
      </div>
      <div>
        <form class="form-horizontal">
          <div class="control-group">
            <label class="control-label" for="inputName">姓名</label>
            <div class="controls">
              <input type="text" id="inputName" placeholder="张三"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputSex">性别</label>
            <div class="controls">
              <div id="inputSex">
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="男" checked >男</label>
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="女" >女</label>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputAge">年龄</label>
            <div class="controls">
              <input type="text" id="inputAge" placeholder="15"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputSchool">学校</label>
            <div class="controls">
              <input type="text" id="inputSchool" placeholder="一中"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputGrade">年级</label>
            <div class="controls">
              <input type="text" id="inputGrade" placeholder="初一"></div>
          </div>
          <!--
      <div class="control-group">
          <label class="control-label" for="inputClass">班级</label>
          <div class="controls">
            <select id="inputClass">
              <option value="0">无</option>
              <?php foreach ($classes as $class):?>
              <option value="<?=$class['id']?>
                ">
                <?=$class['name']?></option>
              <?php endforeach;?></select>
          </div>
        </div>
        <div id="feeBox" class="control-group" style="display:none">
          <label class="control-label" for="inputClassFee">课时费</label>
          <div class="controls">
            <div id="inputClassFee"></div>
          </div>
        </div>
        -->
        <div class="control-group">
          <label class="control-label" for="inputPhone">电话</label>
          <div class="controls">
            <div class="input-prepend" style="display:block;margin-bottom:10px;">
              <span class="add-on">母亲</span>
              <input id="inputMatherPhone" type="text" placeholder=""></div>
            <div class="input-prepend" style="display:block;margin-bottom:10px;">
              <span class="add-on">父亲</span>
              <input id="inputFatherPhone" type="text" placeholder=""></div>
            <div class="input-prepend" style="display:block;margin-bottom:10px;">
              <span class="add-on">座机</span>
              <input id="inputLaneline" type="text" placeholder=""></div>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <a id="insertBtn" class="btn btn-success">创建</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script type="text/javascript">
  function checkAndSubmit(){

    var name = $('#inputName').val();
    var sex = $('#inputSex').find('input[type="radio"]:checked').val();
    var age = $('#inputAge').val();
    var school = $('#inputSchool').val();
    var grade = $('#inputGrade').val();
    var classId = $('#inputClass').val();
    var classFeeId = $('#inputClassFee').find('input[type="radio"]:checked').val();
    var mPhone = $("#inputMatherPhone").val();
    var fPhone = $('#inputFatherPhone').val();
    var laneline = $('#inputLaneline').val();

    if(!name.match(/^[\u4E00-\u9FFF\w]+$/)){
       bootbox.alert('请输入正确的学生姓名，只能包含字母或数字或下划线或汉字');
      return false;
    }

    var checkName = true;
    $.ajax({
      url : '/vertex/student/checkName',
      async: false,
      data: {name : name},
      dataType: 'json',
      success: function(data){
          checkName = data.result;
          if(!data.result){
            bootbox.alert('学生姓名重复，请重新输入');
          }
      }
    });
    if(!checkName) return false;

    if(!age.match(/^\d{1,3}$/)){
      bootbox.alert('请输入正确的学生年龄，三位数字之内');
      return false;
    }
    if(school == ''){
      bootbox.alert('请输入学生学校');
      return false;
    }
    if(grade == ""){
      bootbox.alert('请输入学生年级');
      return false;
    }
    if(mPhone == "" &&　fPhone == "" && laneline == ""){
      bootbox.alert('请至少输入一个联系方式');
      return false;
    }

    if(!mPhone.match(/^\d*$/)||!fPhone.match(/^\d*$/)||!laneline.match(/^\d*$/)){
      bootbox.alert('联系方式必须为数字');
      return false;
    }
    /*
    if(classId == 0){
      classFeeId = 0;
    }
*/
    var data = {
          name : name,
          age : age,
          sex : sex,
          school : school,
          grade : grade,
          classId : 0,
          classFeeId : 0,
          mPhone :　mPhone,
          fPhone : fPhone,
          laneline : laneline
    }

    $.post('/vertex/student/insert' , data , function(data){
        window.location.href = '/vertex/student/single/' + data.studentId;
    } , 'json');

  }

  $('#insertBtn').click(function(){
    checkAndSubmit();
  });
/*
  $('#inputClass').change(function(){

      var tpl = [
          '<label class="radio">',
              '<input type="radio" name="feeRadios" value="$id$" checked/>',
              '$fee$元/次',
              '<small class="muted">$grade$</small>',
           '</label>'
      ];

      var classId = $(this).val();
      if(classId == 0 ){
        $("#feeBox").fadeOut('slow');
      }else{
        $.get('/vertex/classroom/ajaxGetClassCharges' , {classId : classId } , function(data){

          var html = "";
          var charges  = data.charges;
          for ( var i = 0 ; i<charges.length ; i++){
            var charge = charges[i];
            html += tpl.join(' ').replace(/\$id\$/,charge.id)
                                  .replace(/\$fee\$/,charge.cost)
                                  .replace(/\$grade\$/,charge.grade);
          }
          $('#inputClassFee').html(html);

          $('#inputClassFee').find('option').first().attr('checked',true);
          $('#feeBox').fadeIn('slow');
        } , 'json');
      }
  });
*/
</script>