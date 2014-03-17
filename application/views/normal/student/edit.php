<div class="container classroom">
  <div class="row-fluid">
    <div class="span2">
      <ul class="nav nav-list bs-docs-sidenav affix">
        <li  class="active">
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
        <li>
          <a href="/vertex/student/create">
            <i class="icon-chevron-right"></i>
            新增学生
          </a>
        </li>
      </ul>
    </div>
    <div class="span10">
      <div class="">
        <p class="lead">修改学生</p>
        <hr />
      </div>
      <div>
        <input id="studentId" type="hidden" value="<?=$student['id']?>
        ">
        <form class="form-horizontal">
          <div class="control-group">
            <label class="control-label" for="inputName">姓名</label>
            <div class="controls">
              <span><?=$student['name']?></span> 
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputSex">性别</label>
            <div class="controls">
              <div id="inputSex">
                <?php if($student['sex'] == "男"):?>
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="男" checked >男</label>
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="女" >女</label>
                <?php else: ?>
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="男"  >男</label>
                <label class="radio">
                  <input type="radio" name="optionsRadios" value="女" checked>女</label>
                <?php endif; ?></div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputAge">年龄</label>
            <div class="controls">
              <input type="text" id="inputAge" placeholder="15" value="<?=$student['age']?>"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputSchool">学校</label>
            <div class="controls">
              <input type="text" id="inputSchool" placeholder="一中" value="<?=$student['school']?>"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputGrade">年级</label>
            <div class="controls">
              <input type="text" id="inputGrade" placeholder="初一" value="<?=$student['grade']?>"></div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputPhone">电话</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on">母亲</span>
                <input id="inputMatherPhone" type="text" placeholder="母亲" value="<?=$student['mather_phone']?>"></div>
              <div class="input-prepend">
                <span class="add-on">父亲</span>
                <input id="inputFatherPhone" type="text" placeholder="父亲" value="<?=$student['father_phone']?>"></div>
              <div class="input-prepend" style="margin-top:20px;">
                <span class="add-on">座机</span>
                <input id="inputLaneline" type="text" placeholder="座机" value="<?=$student['landline']?>"></div>
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <a id="insertBtn" class="btn btn-success">修改</a>
              <a class="btn" href='javascript:history.go(-1)'>返回</a>
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

    var id = $('#studentId').val();
    //var name = $('#inputName').val();
    var sex = $('#inputSex').find('input[type="radio"]:checked').val();
    var age = $('#inputAge').val();
    var school = $('#inputSchool').val();
    var grade = $('#inputGrade').val();
    var classId = $('#inputClass').val();
    var classFeeId = $('#inputClassFee').find('input[type="radio"]:checked').val();
    var mPhone = $("#inputMatherPhone").val();
    var fPhone = $('#inputFatherPhone').val();
    var laneline = $('#inputLaneline').val();

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
    var postArr = {
          id : id,
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

    $.post('/vertex/student/editStudent' , postArr , function(data){
        //window.location.href = '/vertex/student/single/' + data.studentName;
        window.location.href = '/vertex/student/single/' + id;
    } , 'json');

  }

  $('#insertBtn').click(function(){
    checkAndSubmit();
  });
</script>