<div class="container attendance">
	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li class="active">
					<a href="/vertex/attendance"> <i class="icon-chevron-right"></i>
						考勤一览
					</a>
				</li>
				<?php if($userWeight == 1):?>
				<li>
					<a href="/vertex/attendance/batch"> <i class="icon-chevron-right"></i>
						批量删除
					</a>
				</li>
				<li>
					<a href="/vertex/attendance/create"> <i class="icon-chevron-right"></i>
						新增考勤
					</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<div class="span10">
			<div>
				<p class="lead">考勤一览</p>
				<hr />
			</div>
			<table id="attendanceTable" class="table">
				<thead>
					<tr>
						<?php if($userWeight == 1):?>
						<th>#</th>
						<?php endif;?>
						<th width="20%">时间</th>
						<th width="10%">班级</th>
						<th width="10%">学生姓名</th>
						<th width="10%">任课老师</th>
						<th width="25%">备注</th>
						<th width="15%">操作人</th>
						<th width="10%">状态</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($attendances as $attendance):?>
					<tr>
						<?php if($userWeight == 1):?>
						<?php if($attendance['aviliable'] != 0):?>
						<td><a href="javascript:void(0)" class="flag"><i class="icon-trash"></i></a><input type="hidden" value="<?=$attendance['id']?>"></td>
						<?php else: ?>
						<td>#</td>
						<?php endif;?>
						<?php endif;?>
						<td><?=$attendance['sign_date']?></td>
						<td><?=$attendance['class_name']?></td>
						<td><?=$attendance['student_name']?></td>
						<td><?=$attendance['teacher_name']?></td>
						<td>课费:<?=$attendance['student_cost']?>元/次;课时费:<?=$attendance['teacher_earnings']?>元/次</td>
						<td><?=$attendance['user_name']?></td>
						<?php if($attendance['aviliable'] == 1): ?>
						<td>有效</td>
						<?php elseif($attendance['aviliable'] == 0): ?>
						<td>无效</td>
						<?php else: ?>
						<td>过期</td>
						<?php endif; ?>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
<!--
		<div class="attend-radio">
			<label class="radio inline">
	          <input type="radio" name="optionsRadios" value="2" checked>
	          全部考勤
	        </label>
			<label class="radio inline">
	          <input type="radio" name="optionsRadios" value="1"  >
	          有效考勤
	        </label>
	        <label class="radio inline">
	          <input type="radio" name="optionsRadios" value="0" >
	          无效考勤
	        </label>
    	</div>
		<table id="attendanceTable" class="table">
			<thead>
				<tr>
					<th>#</th>
					<th width="20%">时间</th>
					<th width="10%">班级</th>
					<th width="10%">学生姓名</th>
					<th width="10%">任课老师</th>
					<th width="25%">备注</th>
					<th width="20%">操作人</th>
					<th width="5%">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($attendances as $attendance):?>
				<tr>
					<?php if($attendance['aviliable'] == 1):?>
					<td><a href="javascript:void(0)" class="flag"><i class="icon-edit"></i></a><input type="hidden" value="<?=$attendance['id']?>"></td>
					<?php else: ?>
					<td>#</td>
					<?php endif;?>
					<td><?=$attendance['sign_date']?></td>
					<td><?=$attendance['class_name']?></td>
					<td><?=$attendance['student_name']?></td>
					<td><?=$attendance['teacher_name']?></td>
					<td>课费:<?=$attendance['student_cost']?>元/次;课时费:<?=$attendance['teacher_earnings']?>元/次</td>
					<td><?=$attendance['user_name']?></td>
					<?php if($attendance['aviliable'] == 1): ?>
					<td>有效</td>
					<?php elseif($attendance['aviliable'] == 0): ?>
					<td>无效</td>
					<?php else: ?>
					<td>过期</td>
					<?php endif; ?>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>-->

</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>

<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/TableTools.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>
<script type="text/javascript">
	
	var oTable = $('#attendanceTable').dataTable( {
	    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>><'row-fluid'<'span12'T>>",
	    "sPaginationType": "bootstrap",
	    "oLanguage": {
	      "sLengthMenu": "_MENU_ records per page",
	      "sUrl": "/vertex/resources/language/chinese.lag"
	    },
	    "bFilter": true,
	    "oTableTools": {
	      "sSwfPath": "/vertex/resources/public/tableTool/media/swf/copy_csv_xls_pdf.swf",
	      "aButtons": [
	               {
	                    "sExtends":    "xls",
	                    "sButtonText": "导出为XLS"
	                }
	              ]
	    },
		"aaSorting": [[ 1, "desc" ]]
	} );

	$('#addAttendance').click(function(){

		window.location.href = "/vertex/attendance/create";
	});

	$("input[type='radio']").click(function(){
		
		$.get('/vertex/attendance/ajaxGetAttendancesByFlag' , { flag : $(this).val() } , function(data){

			oTable.fnClearTable();
			oTable.fnAddData(data);
			oTable.fnDraw();

		},'json');

	});

	$(document).on('click' ,'.flag' ,  function(){

		var td = $(this).parent();
		var flagDom = $(this).parent().siblings('td').last();
		var id = $(this).siblings('input').val();

		bootbox.confirm("你确定要将删除本次考勤吗?", function(result) {
			if(result){
				$.get('/vertex/attendance/deleteAttendance' , { id: id ,flag : 0 } , function(data){
					$(td).parent().hide();
					//$(flagDom).html('无效');
				},'json');
			}
		});
	});

</script>