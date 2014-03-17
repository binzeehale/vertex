<div class="container classroom">
	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li class="active">
					<a href="/vertex/classroom"> <i class="icon-chevron-right"></i>
						班级一览
					</a>
				</li>
				<?php if($userWeight == 1):?>
				<li>
					<a href="/vertex/classroom/create"> <i class="icon-chevron-right"></i>
						新建班级
					</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<div class='span10'>
			<div>
				<p class="lead">班级详细信息</p>
				<hr />
			</div>
			<div class="row-fluid">
				<div class="span2">

					<div class="row-fluid">
						<div class="single-left">
							<p class="lead">
								<?=$class['name']?></p>
							<div class="detail">
								<?=$class['teacherName']?></div>
							<?php if($class['teacher_fee'] != "" ): ?>
							<div class="detail">
								<?=$class['teacher_fee']?>元/人次</div>
							<?php endif; ?>
							<div class="detail">
								<?=$classStudentCount?>人</div>
						</div>
					</div>
					<?php if($userWeight == 1):?>
					<div class="row-fluid single-index">
						<ul class="nav nav-list">
							<li class="nav-header">操作列表</li>
							<li>
								<a href="/vertex/classroom/addStudent/<?=$urlClassName?>">新增学生</a>
							</li>
							<li>
								<a id="editCharges" href="#">修改收费标准</a>
							</li>
							<li>
								<a id="editTeacher" href="#">修改任课老师</a>
							</li>
							<li>
								<a id="editClassName" href="#">修改班级名称</a>
							</li>
							<li class="divider"></li>
							<li>
								<a id="deleteStudents" href="#">移除学生</a>
							</li>
						</ul>
					</div>
					<?php endif;?>
				</div>
				<div class="span10">
					<div class="accordion" id="accordion2">
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">收费标准</a>
							</div>
							<div id="collapseOne" class="accordion-body collapse">
								<div class="accordion-inner">
									<table class="table">
										<thead>
											<tr>
												<th>年级</th>
												<th>费用</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($charges) == 0) : ?>
											<tr>
												<td colspan="2">
													<center>暂无</center>
												</td>
											</tr>
											<?php endif; ?>
											<?php foreach($charges as $charge):?>
											<tr>
												<td>
													<?=$charge['grade']?></td>
												<td>
													<?=$charge['cost']?>元/次</td>
											</tr>
											<?php endforeach;?></tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">学生列表</a>
							</div>
							<div id="collapseTwo" class="accordion-body collapse in">
								<div class="accordion-inner">
									<table id="studentTable" class="table">
										<thead>
											<tr>
												<th>姓名</th>
												<th>年级</th>
												<th>收费标准</th>
												<th>本班考勤次数</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($students) == 0) : ?>
											<tr>
												<td colspan="4">
													<center>暂无</center>
												</td>
											</tr>
											<?php endif; ?>
											<?php foreach($students as $student):?>
											<tr>
												<td>
													<?=$student['name']?></td>
												<td>
													<?=$student['grade']?></td>
												<td>
													<?=$student['cost']?>元/次</td>
												<td>
													<?=$student['attendanceCount']?>
												</td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
									<div class="pagination pull-right"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade classroom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">编辑班级</h3>
  </div>
  <div id="modalContext" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">返回</button>
    <button id="modalSubmit" class="btn btn-primary">保存</button>
  </div>
</div>

<div id="teacherModal" class="modal hide fade classroom" tabindex="-1" role="dialog" aria-labelledby="teacherModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="teacherModalLabel">编辑班级</h3>
  </div>
  <div id="teachermodalContext" class="modal-body">
  	<form class="form-horizontal">
  		<div class="control-group">
  			<label class="control-label" for="inputTeacher">任课老师</label>
  			<div class="controls">
  				<select id="inputTeacher">
  					<option value="0">无</option>
  					<?php foreach($teachers as $teacher) :?>
  					<?php if($teacher['name'] == $class['teacherName']): ?>
  						<option selected="selected" value="<?=$teacher['id']?>"><?=$teacher['name']?></option>
  					<?php else: ?>
  						<option value="<?=$teacher['id']?>"><?=$teacher['name']?></option>
  					<?php endif; ?>
  					<?php endforeach;?>
  				</select>
  			</div>
  		</div>
  		<div class="control-group">
  			<label class="control-label" for="inputTeacher">课时费</label>
	  		<div class="controls">
				<input id="teacherFeeInput" class="span2 class-grade" type="text" placeholder="10" value="<?=$class['teacher_fee']?>"/>  	
				元/人次
			</div>
  		</div>
  	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">返回</button>
    <button id="teacherModalSubmit" class="btn btn-primary">保存</button>
  </div>
</div>

<div id="classNameModal" class="modal hide fade classroom" tabindex="-1" role="dialog" aria-labelledby="classNameModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="classNameModalLabel">编辑班级</h3>
  </div>
  <div id="classNameModalContext" class="modal-body">
    <form class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="inputClassName">班级名称</label>
			<div class="controls">
				<input type="text" id="inputClassName" placeholder="新概念一班" value="<?=$class['name']?>" />
			</div>
		</div>
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">返回</button>
    <button id="classNameModalSubmit" class="btn btn-primary">保存</button>
  </div>
</div>

<div id="studentNameModal" class="modal hide fade classroom" tabindex="-1" role="dialog" aria-labelledby="studentNameModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="studentNameModalLabel">编辑班级</h3>
  </div>
  <div id="studentNameModalContext" class="modal-body">
    <table class="table">
    	<thead>
    		<tr>
    			<th><a id="studentSelectAll" href="#">全选</a></th>
    			<th>学生姓名</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php if(count($students) == 0): ?>
    			<tr>
    				<td colspan="2"><center>暂无学生</center></td>
    			</tr>
    		<?php endif; ?>
    		<?php foreach ($students as $student):?>
    		<tr>
    			<td><input type="checkbox" value="<?=$student['id']?>"/></td>
    			<td><?=$student['name']?></td>
    		</tr>
    		<?php endforeach;?>
    	</tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">返回</button>
    <button id="studentNameModalSubmit" class="btn btn-primary">删除</button>
  </div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script type="text/javascript">
	
	$('#editCharges').click(function(){
		var html = '<form class="form-horizontal">';
		var tpl = [
			'<div id="editClassCharges" class="control-group">',
				'<div class="controls">',
					'<div class="input-prepend">',
					  '<span class="add-on">年级</span>',
					  '<input class="span2 class-grade" type="text" placeholder="初一" value="$grade$">',
					'</div>',
					'<div class="input-prepend">',
					  '<span class="add-on">费用</span>',
					  '<input class="span2 class-fee" type="text" placeholder="45.00" value="$fee$">',
					'</div>',
					'<a href="javascript:addCharges();" class="btn"><i class="icon-plus"></i></a>',
					'<input type="hidden" value="$id$" />',
				'</div>',
			'</div>'

		];
		var superTpl = [
			'<div class="control-group">',
				'<div class="controls">',
					'<div class="input-prepend">',
					  '<span class="add-on">年级</span>',
					  '<input class="span2 class-grade" type="text" placeholder="初一" value="$grade$">',
					'</div>',
					'<div class="input-prepend">',
					  '<span class="add-on">费用</span>',
					  '<input class="span2 class-fee" type="text" placeholder="45.00" value="$fee$">',
					'</div>',
					'<a class="btn remove-changes"><i class="icon-minus"></i></a>',
					'<input type="hidden" value="$id$" />',
				'</div>',
			'</div>'
		];
		$.get('/vertex/classroom/ajaxGetClassCharges' , { classId : <?=$class['id']?> } ,function(data){

			var charges = data.charges;
			for( i in charges){
				var charge = charges[i];
				if(i == 0)
				html += tpl.join(' ').replace(/\$grade\$/ , charge['grade'])
							.replace(/\$fee\$/ , charge['cost'])
							.replace(/\$id\$/ , charge['id']);
				else{
					html += superTpl.join(' ').replace(/\$grade\$/ , charge['grade'])
							.replace(/\$fee\$/ , charge['cost'])
							.replace(/\$id\$/ , charge['id']);
				}
			}

			html +='</form>';
			$('#modalContext').html(html);
			$('#myModal').modal('show');

		},'json');
		
	});

	$('#modalSubmit').click(function(){

		var charges = [];
		$('#myModal .class-grade').each(function(){

			var grade = $(this).val();
			var fee = $(this).parent().siblings().children('.class-fee').val();
			var id = $(this).parent().siblings('input[type="hidden"]').val();

			charges.push( { id: id , grade: grade , fee : fee} );
		});

		for( var i = 0 ; i<charges.length ; i++){
			var charge = charges[i];

			if(charge.grade == ""){
				bootbox.alert("年级不能为空","错误");
				return false;
			}

			if( !charge.fee.match(/^\d+(\.\d*)?$/) ){
				bootbox.alert('费用输入非法，请输入数字（可带小数点）',"错误");
				return false;
			}
		}

		$.post('/vertex/classroom/editCharges', { classId : <?=$class['id']?>  , charges : JSON.stringify(charges) } , function(data){
			window.location.reload();
		} , 'json');

	});

	function addCharges(){
		var tpl = [
			'<div class="control-group">',
				'<div class="controls">',
					'<div class="input-prepend">',
					  '<span class="add-on">年级</span>',
					  '<input class="span2 class-grade" type="text" placeholder="初一">',
					'</div>',
					'<div class="input-prepend">',
					  '<span class="add-on">费用</span>',
					  '<input class="span2 class-fee" type="text" placeholder="45.00">',
					'</div>',
					'<a class="btn remove-changes"><i class="icon-minus"></i></a>',
					'<input type="hidden" value="0" />',
				'</div>',
			'</div>'
		];

		$('#editClassCharges').after(tpl.join(' '));
	};

	$(document).on("click" , ".remove-changes" , function(){
		var dom = $(this);
		var id = $(this).siblings('input[type="hidden"]').val();
		$.get('/vertex/classroom/ajaxCheckStudentCharges' , { id : id } , function(data){

			if(data.hasStudent){
				bootbox.alert('已有学生使用本收费标准，如需删除请先将使用此标准的学生移除本班级');
			}else{
				$(dom).parent().parent().remove();
			}
		} , 'json');

		
	});

	$('#editTeacher').click(function(){

		$.post('/vertex/classroom/ajaxGetTeacherInfo',{ classId : "<?=$class['id']?>" }, function(data){

			$('#inputTeacher').find("option").each(function(){
				if($(this).val()  == data.teacherId){
					$(this).attr('selected',true);
				}
			});
			$('#teacherFeeInput').val(data.teacherFee);

		},'json');

		$('#teacherModal').modal('show');
	});

	$('#inputTeacher').change(function(){
		if($('#inputTeacher').val() == 0){
		 	$('#teacherFeeInput').val("");
		}
	});

	$('#teacherModalSubmit').click(function(){
		var teacherId = $('#inputTeacher').val();
		
		var teacherFee = $('#teacherFeeInput').val();
		if( teacherFee != "" && !teacherFee.match(/^\d+(\.\d*)?$/) ){
			bootbox.alert('课时费输入非法，请输入数字（可带小数点','错误');
			return false;
		}
		var postArr = { classId : "<?=$class['id']?>" , teacherId : teacherId , teacherFee : teacherFee };
		$.post('/vertex/classroom/editTeacher' , postArr , function(data){
			window.location.reload();
		} ,'json');
	});

	$('#editClassName').click(function(){
		$("#classNameModal").modal('show');
	});

	$('#classNameModalSubmit').click(function(){

		var className = $('#inputClassName').val();
		if(className == ""){
			bootbox.alert('班级名称不能为空','警告');
		}else{
			$.post('/vertex/classroom/checkClassName' , { className : className },function(data){
				$('.help-inline').remove();
				if(data.success){
					if(!data.pass){
						$('#inputClassName').after("<span style='color:red;' class='help-inline'>班级名称已存在!</span>");
					}else{
						$.post('/vertex/classroom/editClass' , {  classId : <?=$class['id']?>  , className : className} , function(data){
							window.location.href = "/vertex/classroom/single/" + className;
						} ,'json');
					}
				}
			},'json');
		}
	});


	$('#deleteStudents').click(function(){
		$("#studentNameModal").modal('show');
	});

	$('#studentSelectAll').click(function(){

		$('#studentNameModal').find('input[type="checkbox"]').each(function(){
			$(this).attr('checked' , 'checked');
		});
	})

	$('#studentNameModalSubmit').click(function(){
		var sIds = [];
		$('#studentNameModal').find('input[type="checkbox"]:checked').each(function(){
			sIds.push($(this).val());
		});

		$.post('/vertex/classroom/deleteStudents' , { classId : <?=$class['id']?> , studentIds : sIds },function(data){
			window.location.reload();
		});
	});

	$("#removeClass").click(function(){
		jConfirm('确定移除班级吗？', '确认', function(result) {
		    if(result){
		    	$.post('/vertex/classroom/delete' ,{ classId: <?=$class['id']?> } ,function(data){
		    		window.location.href = '/vertex/classroom';
		    	});
		    }
		});
	});

</script>