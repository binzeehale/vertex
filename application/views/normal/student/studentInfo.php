<div class="container student">

	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li class="active">
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
				<?php if($userWeight == 1):?>
				<li>
					<a href="/vertex/student/create">
						<i class="icon-chevron-right"></i>
						新增学生
					</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<div class="span10">

			<div>
				<p class="lead">单学生信息</p>
				<hr />
			</div>
			<div class="row-fluid">
				<div class="span2">
					<div class="row-fluid">
						<div class="single-left">
							<p class="lead">
								<?=$student['name']?></p>
							<input id="studentId" type="hidden" value="<?=$student['id']?>" /></div>
					</div>
					<?php if($userWeight == 1):?>
					<div class="row-fluid single-index">
						<ul class="nav nav-list">
							<li class="nav-header">操作列表</li>
							<li>
								<a id='editStudent' href="javascript:void(0);">充值</a>
							</li>
							<li>
								<a id='deleteStudent' href="javascript:void(0);">删除学生</a>
							</li>
							<li class="divider"></li>
						</ul>
					</div>
					<?php endif;?>
				</div>
				<div class="span10">

					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#profile" data-toggle="tab">账户信息</a>
						</li>
						<li>
							<a href="#home" data-toggle="tab">个人信息</a>
						</li>
						<li>
							<a href="#messages" data-toggle="tab">考勤信息</a>
						</li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade in active" id="profile">
							<?php if($userWeight == 1):?>
							<div class="account">
								<div>
									<p>
										<a id="rechargeBtn" class="btn btn-warning pay">充值</a>
										<a id="debitBtn" class="btn btn-danger pay">扣款</a>
										<a id="scholarshipBtn" class="btn btn-success pay">奖学金</a>
										<a id="shiftBtn" class="btn btn-info pay">班级金额转移</a>
										<a id="writeoffsBtn" class="btn pay">销账</a>
									</p>
								</div>
							</div>
							<?php endif;?>
							<div>
								<table class='table'>
									<thead>
										<tr>
											<th width='20%'>所在班级名称</th>
											<th width="20%">所在年级</th>
											<th width="20%">课时费</th>
											<th width='20%'>班级余额</th>
											<th width='20%'>班级奖学金余额</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($tClasses as $class):?>
										<tr>
											<td><?=$class['name']?></td>
											<td><?=$class['classFee']['grade']?></td>
											<td><?=$class['classFee']['cost']?></td>
											<td><?=$class['banlance']?></td>
											<td><?=$class['scholarship']?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<div class="history pull-right">
								<span class="income"> <strong>历史总收入</strong>
									<span>
										<?=$history['income']?></span>
									元
								</span>
								<span class="expenses">
									<strong>历史总支出</strong>
									<span>
										<?=$history['expenses']?></span>
									元
								</span>
							</div>
							<div>
								<div class='clearfix'></div>
								<table id="transactionTable" class="table">
									<thead>
										<tr>
											<th>日期</th>
											<th>类型</th>
											<th>收入</th>
											<th>支出</th>
											<th>班级</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($transactions as $transaction):?>
										<tr>
											<td>
												<?=date('Y-m-d',strtotime($transaction['last_update_time']))?></td>
											<td>
												<?=student_transaction_type2str($transaction['type'])?></td>
											<td>
												<?=$transaction['income']?></td>
											<td>
												<?=$transaction['cost']?></td>
											<td>
												<?=$transaction['classname']?>
											</td>
										</tr>
										<?php endforeach; ?></tbody>
								</table>
								<div id='transactionLink' class="pagination pagination-small pull-right"></div>
							</div>
						</div>
						<div class="tab-pane fade" id="home">
							<div class="personal">
								<div class="row-fluid">
									<div class="span3 info-box">
										<p>
											<strong>性别</strong>
										</p>
										<p>
											<?=$student['sex']?></p>
									</div>
									<div class="span3 info-box">
										<p>
											<strong>学校</strong>
										</p>
										<p>
											<?=$student['school']?></p>
									</div>
									<div class="span3 info-box">
										<p>
											<strong>年级</strong>
										</p>
										<p>
											<?=$student['grade']?></p>
									</div>
									<div class="span3 info-box">
										<p>
											<strong>班级</strong>
										</p>
										<p>
											<?=$student['class']?></p>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4 info-box">
										<p>
											<strong>母亲电话</strong>
										</p>
										<p>
											<?=$student['mather_phone']?></p>
									</div>
									<div class="span4 info-box">
										<p>
											<strong>父亲电话</strong>
										</p>
										<p>
											<?=$student['father_phone']?></p>
									</div>
									<div class="span4 info-box">
										<p>
											<strong>座机电话</strong>
										</p>
										<p>
											<?=$student['landline']?></p>
									</div>
								</div>

							</div>
						</div>
						<div class="tab-pane fade" id="messages">

							<table id='attendanceTable' class="table">
								<thead>
									<tr>
										<th>日期</th>
										<th>考勤班级</th>
										<th>课时费</th>
										<th>操作人</th>
									</tr>
								</thead>
								<tbody>
									<? foreach ($attendances as $attendance): ?>
									<tr>
										<td>
											<?=$attendance['sign_date']?></td>
										<td>
											<?=$attendance['class_name']?></td>
										<td>
											<?=$attendance['student_cost']?></td>
										<td>
											<?=$attendance['user_name']?></td>
									</tr>
									<? endforeach; ?></tbody>
							</table>
							<div id='attendanceLink' class="pagination pagination-small pull-right"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id='myModal' class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><span class='modal-title'>账户管理</span></h3>
  </div>
  <div class="modal-body">
  	<input type='hidden' class='modal-type' value="" />
  	<label>时间：
		<div class="input-append date account-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
			<input id='account-time' size="16" type="text" value="<?=date('Y-m-d')?>" readonly>				
			<span class="add-on"> <i class="icon-remove"></i>
			</span>
			<span class="add-on"> <i class="icon-th"></i>
			</span>
		</div>
	</label>
  	<label>
  		班级：
	  	<select class='modal-class'>
	  		<?php foreach($tClasses as $class): ?>
	  		<option value="<?=$class['id']?>"><?=$class['name']?></option>
	  		<?php endforeach;?>
	  	</select>
  	</label>
  	<label>
  		金额：
    	<input class='modal-money' type="text" value="">
    </label>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary" aria-hidden="true">保存</button>
  </div>
</div>

<div id='scholarshipModal' class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><span class='modal-title'>奖学金管理</span></h3>
  </div>
  <div class="modal-body">
  	<input type='hidden' class='modal-type' value="" />
  	<form id='scholarForm' class="form-horizontal">
  		<fieldset>
  			<input name='studentId' type="hidden" value="<?=$student['id']?>" />
  			<div class='control-group'>
  				<label class="control-label">时间</label>
				<div style="margin-left: 20px;" class="controls input-append date scholarship-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
					<input id='time' name="time" size="16" type="text" value="<?=date('Y-m-d')?>" readonly>				
					<span class="add-on"> <i class="icon-remove"></i>
					</span>
					<span class="add-on"> <i class="icon-th"></i>
					</span>
				</div>
				<input type="hidden" id="dtp_input2" value="<?=date('Y-m-d')?>" />				
				<br/>
  			</div>
  			<div class="control-group">
  				<label class="control-label">班级</label>
  				<div class="controls">
  					<select name='classId' class='modal-class input-xlarge'>
				  		<?php foreach($tClasses as $class): ?>
				  		<option value="<?=$class['id']?>"><?=$class['name']?></option>
				  		<?php endforeach;?>
				  	</select>
  				</div>

  			</div>
  			<div class="control-group">
  				<label class="control-label">操作类型</label>
  				<div class="controls">	
  					<label class="radio inline">
  						<input type="radio" value="1" checked="checked" name="type">充值</label>
  					<label class="radio inline">
  						<input type="radio" value="2" name="type">扣款</label>
  				</div>
  			</div>
  			<div class="control-group">

  				<!-- Text input-->  	
  				<label class="control-label" for="">金额</label>
  				<div class="controls">
  					<input type="text" name='money' placeholder="" class="input-xlarge">
  				</div>
  			</div>
  		</fieldset>
  	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary" aria-hidden="true">保存</button>
  </div>
</div>

<div id='shiftModal' class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><span class='modal-title'>班级金额转移</span></h3>
  </div>
  <div class="modal-body">
  	<input type='hidden' class='modal-type' value="" />
  	<form id='shiftForm' class="form-horizontal">
  		<fieldset>
  			<input name='studentId' type="hidden" value="<?=$student['id']?>" />
  			<div class="control-group">
  				<label class="control-label">转出班级</label>
  				<div class="controls">
  					<select name='outclassId' class='modal-class input-xlarge'>
				  		<?php foreach($tClasses as $class): ?>
				  		<option value="<?=$class['id']?>"><?=$class['name']?></option>
				  		<?php endforeach;?>
				  	</select>
  				</div>
  			</div>
  			<div class="control-group">
  				<label class="control-label">转入班级</label>
  				<div class="controls">
  					<select name='inclassId' class='modal-class input-xlarge'>
				  		<?php foreach($tClasses as $class): ?>
				  		<option value="<?=$class['id']?>"><?=$class['name']?></option>
				  		<?php endforeach;?>
				  	</select>
  				</div>
  			</div>
  			<div class="control-group">
  				<label class="control-label" for="">金额</label>
  				<div class="controls">
  					<input type="text" name='money' placeholder="" class="input-xlarge">
  				</div>
  			</div>
  		</fieldset>
  	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary" aria-hidden="true">保存</button>
  </div>
</div>

<div id='writeoffsModal' class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><span class='modal-title'>销账</span></h3>
  </div>
  <div class="modal-body">
  	<input type='hidden' class='modal-type' value="" />
  	<form id='writeoffsForm' class="form-horizontal">
  		<fieldset>
  			<input name='studentId' type="hidden" value="<?=$student['id']?>" />
  			<div class="control-group">
  				<label class="control-label">销账班级</label>
  				<div class="controls">
  					<select name='classId' class='modal-class input-xlarge'>
				  		<?php foreach($classes as $class): ?>
				  		<option value="<?=$class['id']?>"><?=$class['name']?></option>
				  		<?php endforeach;?>
				  	</select>
				  	<p class="muted">
				  		该操作可以将该学生某个班级的的充值和<br/>消费记录清零
				  	</p>
  				</div>

  			</div>
  		</fieldset>
  	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button class="btn btn-primary" aria-hidden="true">保存</button>
  </div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>

<script src="/vertex/resources/public/bootstrap-timepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/vertex/resources/public/bootstrap-timepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>

<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/TableTools.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>
<script type="text/javascript">

	 $('.scholarship-datetime , .account-datetime').datetimepicker({
        language:  'zh-CN',
        format: 'yyyy-mm-dd',
        todayBtn:  1,
		autoclose: 1,
		minView: 'month'
    });

	var studentId =$('#studentId').val();
	var transactionOTable = $('#transactionTable').dataTable( {
    //"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
     "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
    "sPaginationType": "bootstrap",
    "oLanguage": {
      "sLengthMenu": "_MENU_ records per page",
      "sUrl": "/vertex/resources/language/chinese.lag"
    },
    "aaSorting": [[ 0, "desc" ]],
    "iDisplayLength": 25,
    "bFilter": true,
    "oTableTools": {
      "sSwfPath": "/vertex/resources/public/tableTool/media/swf/copy_csv_xls_pdf.swf",
      "aButtons": [
               {
                    "sExtends":    "xls",
                    "sButtonText": "导出为XLS"
                }
              ]
    }
  } );

	function submit(){
		return function(type){
			if(type == 'on'){
				recharge();
			}else{
				debit();
			}
		}
	}

	function recharge(){

		var classId = $('#myModal .modal-class').val();
		var money = $('#myModal .modal-money').val();
		var time = $('#account-time').val();
		if(!money.match(/^\d+(\.\d+)?$/)){
			bootbox.alert('请输入合法的充值金额');
		}else{
			$.get('/vertex/student/recharge' , { id : studentId , classId: classId , money: money , time:time} , function(data){
					window.location.reload();
			},'json');
		}
	}

	function debit(){

		var classId = $('#myModal .modal-class').val();
		var money = $('#myModal .modal-money').val();
		var time = $('#account-time').val();
		if(!money.match(/^\d+(\.\d+)?$/)){
			bootbox.alert('请输入合法的扣款金额');
		}else{
			$.get('/vertex/student/debit' , { id : studentId , classId: classId , money: money , time: time }, function(data){
					window.location.reload();
			} ,'json');
		}
	}

	$('#editStudent,#rechargeBtn').click(function(){
		$('#myModal .modal-title').html('请选择充值班级，输入充值金额');
		$('#myModal .modal-type').val('on');
		$('#myModal').modal('show');
	});

	$('#debitBtn').click(function(){
		$('#myModal .modal-title').html('请选择扣款班级，输入扣款金额');
		$('#myModal .modal-type').val('off');
		$('#myModal').modal('show');
	});

	$('#myModal .btn-primary').click(function(){

		var type = $('#myModal .modal-type').val();
		var func = submit();
		func(type);

	});

	$('#deleteStudent').click(function(){

		bootbox.confirm('确定要删除此学生吗？',function(result){

			if(result){
				$.get('/vertex/student/deleteStudent',{studentId: studentId},function(data){
					window.location.href="/vertex/student";
				},'json');
			}
		})
	});

	$('#scholarshipBtn').click(function(){
		$('#scholarshipModal').modal('show');
	});

	$('#scholarshipModal .btn-primary').click(function(){

		var money = $('#scholarForm input[name="money"]').val();
		if(!money.match(/^\d+(\.\d+)?$/)){
			bootbox.alert('请输入合法的金额');
		}else{
			$.get('/vertex/student/scholarship' , $('#scholarForm').serialize() , function(data){
					window.location.reload();
			} ,'json');
		}
	});

	$('#shiftBtn').click(function(){
		$("#shiftModal").modal('show');
	});

	$('#shiftModal .btn-primary').click(function(){
		var money = $('#shiftForm input[name="money"]').val();
		if(!money.match(/^\d+(\.\d+)?$/)){
			bootbox.alert('请输入合法的金额');
		}else{
			$.post('/vertex/student/shiftMoney' , $('#shiftForm').serialize() , function(data){
				window.location.reload();
			} ,'json');
		}
	});

	$('#writeoffsBtn').click(function(){
		$("#writeoffsModal").modal('show');
	});

	$('#writeoffsModal .btn-primary').click(function(){
		$.post('/vertex/student/writeoffs' , $('#writeoffsForm').serialize() , function(data){
			window.location.reload();
		} ,'json');
	});

</script>