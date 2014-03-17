<div class="container attendance">
	
	<div class="row-fluid">

		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li>
					<a href="/vertex/attendance"> <i class="icon-chevron-right"></i>
						考勤一览
					</a>
				</li>
				<li>
					<a href="/vertex/attendance/batch"> <i class="icon-chevron-right"></i>
						批量删除
					</a>
				</li>
				<li  class="active">
					<a href="/vertex/attendance/create"> <i class="icon-chevron-right"></i>
						新增考勤
					</a>
				</li>
			</ul>
		</div>
		<div class="span10">
			<div>
				<p class="lead">新增考勤</p>
				<hr />
			</div>
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label">时间</label>
					<div style="margin-left: 20px;" class="controls input-append date datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
						<input id='endTime' size="16" type="text" value="<?=date('Y-m-d')?>" readonly>				
						<span class="add-on"> <i class="icon-remove"></i>
						</span>
						<span class="add-on"> <i class="icon-th"></i>
						</span>
					</div>
					<input type="hidden" id="dtp_input2" value="<?=date('Y-m-d')?>" />				
					<br/>
				</div>
				<div class="control-group">
					<label class="control-label">任课班级</label>
					<div class="controls">
						<select id="teachClass">
							<option value="0"  <?=$classId==0?'selected':""?>>无</option>
							<?php foreach($classes as $class):?>
								<option value="<?=$class['id']?>" <?=$class['id']==$classId?'selected':""?>><?=$class['name']?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputTeacherName">任课老师</label>
					<div class="controls">
						<span id="teacherName"><?=$teacherName?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputTeacherName">学生列表</label>
					<div class="controls">
						<table id="studentTable" class="table">
							<thead>
								<tr>
									<td><a id="checkAll" href="#">反选</a></td>
									<td>学生姓名</td>
									<td>所在年级</td>
									<td>课时费</td>
								</tr>
							</thead>
							<tbody>
								<?php if(count($students) == 0 ):?>
								<tr>
									<td colspan="4"><center>暂无学生</center></td>
								</tr>
								<?php else: ?>
								
								<?php foreach($students as $student):?>
								<tr>
									<td>
										<input type="checkbox" value="<?=$student['id']?>"/>								
									</td>
									<td><?=$student['name']?></td>
									<td><?=$student['grade']?></td>
									<td class="fee"><?=$student['fee']?>元/次</td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<a id="addAttandance" class="btn btn-success">创建</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script src="/vertex/resources/public/icheck/jquery.icheck.min.js"></script>

<script src="/vertex/resources/public/bootstrap-timepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/vertex/resources/public/bootstrap-timepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>

<script type="text/javascript">
	
	 $('.datetime').datetimepicker({
        language:  'zh-CN',
        format: 'yyyy-mm-dd',
        todayBtn:  1,
		autoclose: 1,
		minView: 'month'
    });


	var classes = [];
	$.ajax({
			url:'/vertex/attendance/ajaxGetClassesDetail' ,
			async: false,
			dataType: 'json',
			success : function(data){
				classes = data;
		}
	});

	$('#teachClass').change(function(){

		var id = $(this).val();
		var _class = [];
		for ( var i = 0 ; i< classes.length ; i++){
			if(classes[i].classId == id){
				_class = classes[i];
				break;
			}
		}
		$('#teacherName').html(_class.teacherName);
		var tpl = [
			'<tr>',
				'<td>',
					'<input type="checkbox" value="$sId$"/>',
				'</td>',
				'<td>$sName$</td>',
				'<td>$sGrade$</td>',
				'<td class="fee">$sFee$元/次</td>',
			'</tr>'
		];
		var html = "";
		for( var i = 0; i < _class.students.length ; i++){
			var student = _class.students[i];
			html += tpl.join("").replace(/\$sId\$/ , student['id'])
						.replace(/\$sName\$/ , student['name'])
						.replace(/\$sGrade\$/ , student['grade'])
						.replace(/\$sFee\$/ , student['fee']);
		}
		if(html == ""){
			html = '<tr><td colspan="4"><center>暂无学生</center></td></tr>';
		}
		$('#studentTable').find('tbody').html(html);

		$('input[type="checkbox"]').iCheck({
			checkboxClass: 'icheckbox_minimal'
		});

	});

	$('#checkAll').click(function(){

		$('input[type="checkbox"]').each(function(){

			if($(this).parent().hasClass('checked')){
				$(this).iCheck('uncheck');
			}else{
				$(this).iCheck('check');
			}

		});
		return false;
	});

	$('#addAttandance').click(function(){

		var time = $('#endTime').val();
		var teacher = $('#teacherName').html();
		var classId = $('#teachClass').val();

		var students = [];
		$('input[type="checkbox"]').each(function(){
			if($(this).parent().hasClass('checked')){
				var cost = $(this).parent().parent().siblings('.fee').html();
				var name = $(this).parent().parent().siblings('td').html();
				var data = { id : $(this).val() , name: name , cost : cost };
				students.push(data);
			}
		});

		if(students.length == 0){
			bootbox.alert('本班级无学生，无法添加考勤');
			return false;
		}

		$.post('/vertex/attendance/insert',{ time : time , classId : classId ,teacherName: teacher , students: JSON.stringify(students) },function(data){
			window.location.href = '/vertex/attendance';
		},'json');

	});

	$('input[type="checkbox"]').iCheck({
		checkboxClass: 'icheckbox_minimal'
	});

</script>