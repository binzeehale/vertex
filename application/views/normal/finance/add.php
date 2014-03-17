<div class="container finance">
  <!-- <div class="top-btn">
  <button class="btn btn-success"> <i class="icon-plus"></i>
    <span id="addFinance" class="add-class">&nbsp;新增收支</span>
  </button>
</div>
-->
<div  class="row-fluid">
  <div class="span2">
    <ul class="nav nav-list bs-docs-sidenav affix">
      <li>
        <a href="/vertex/finance"> <i class="icon-chevron-right"></i>
          财务一览
        </a>
      </li>
      <li>
        <a href="/vertex/finance/report">
          <i class="icon-chevron-right"></i>
          财务报表
        </a>
      </li>
      <li  class="active">
        <a href="/vertex/finance/create">
          <i class="icon-chevron-right"></i>
          新增收支
        </a>
      </li>
    </ul>
  </div>
  <div class="span10">
    <div class="">
      <p class="lead">新增财务</p>
      <hr />
    </div>
    <div>
      <form class="form-horizontal">
        <fieldset>
          <div class="control-group">
            <label class="control-label">收支类型</label>
            <div class="controls">

              <!-- Inline Radios -->
              <label class="radio inline">
                <input type="radio" value="1" checked="checked" name="group">收入</label>
              <label class="radio inline">
                <input type="radio" value="2" name="group">支出</label>
            </div>
          </div>
          <div class="control-group">

            <!-- Text input-->
            <label class="control-label" for="charge">金额</label>
            <div class="controls">
              <input id='charge' type="text" class="input-xlarge">
              <p class="help-block" style="color:red;display:none">请输入正确的金额</p>
            </div>
          </div>

          <div class="control-group">

            <!-- Textarea -->
            <label class="control-label">备注</label>
            <div class="controls">
              <div class="textarea">
                <textarea id='remark' rows="10" style="width:500px"></textarea>
                <p class="help-block" style="color:red;display:none">备注不能为空</p>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label"></label>

            <!-- Button -->
            <div class="controls">
              <a id="submitBtn" class="btn btn-success">保存</a>
              <a id="cancleBtn" class="btn">取消</a>
            </div>
          </div>

        </fieldset>
      </form>
    </div>
  </div>
</div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>

<script type="text/javascript">

  function checkForm(){

    $('.help-block').hide();

    var charge = $('#charge').val();
    var remark = $('#remark').val();

    var result =true;
    if(!charge.match(/^\d+(\.\d+)?$/)){
      $('#charge').siblings('.help-block').show();
      result = false;
    }
    if( remark == ""){
      $('#remark').siblings('.help-block').show();
      result = false;
    }
    return result;
  }

  $('#submitBtn').click(function(){
    var result = checkForm();
    if(!result) return;

    $.post('/vertex/finance/insert' , { bigType : $('input[type="radio"]:checked').val() , charge : $('#charge').val() , remark : $("#remark").val() }, function(){
      window.location.href="/vertex/finance";
    },'json');
  });
      

  $('#cancleBtn').click(function(){
    history.go(-1);
  });
</script>