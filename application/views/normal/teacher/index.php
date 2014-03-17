<div class="container teacher">
	<!-- 	<div id="teacherOpera">
	<button class="btn btn-success"> <i class="icon-plus"></i>
		<span id="addClass" class="add-class">&nbsp;新增教师</span>
	</button>
</div>
-->
<div  class="row-fluid">
	<div class="span2">
		<ul class="nav nav-list bs-docs-sidenav affix">
			<li class="active">
				<a href="/vertex/teacher"> <i class="icon-chevron-right"></i>
					教师信息
				</a>
			</li>
			<?php if($userWeight == 1):?>
			<li>
				<a href="/vertex/teacher/create">
					<i class="icon-chevron-right"></i>
					新增教师
				</a>
			</li>
			<?php endif;?>
		</ul>
	</div>
	<div class="span10">
		<div class="">
			<p class="lead">教师信息</p>
			<hr />
		</div>
		<table id="teacherTable" class="table">
			<thead>
				<tr>
					<?php if($userWeight == 1):?>
					<th width='10%'>#</th>
					<?php endif;?>
					<th width='10%'>姓名</th>
					<th width='30%'>电话</th>
					<th width='50%'>地址</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($teachers as $teacher): ?>
				<tr>
					<?php if($userWeight == 1):?>
					<td>
						<a href="javascript:void(0);">
							<i class="icon-edit" title="修改"></i>
						</a>
						<a href="javascript:void(0);">
							<i class="icon-trash" title="删除"></i>
						</a>
						<input type="hidden" value="<?=$teacher['id']?>" /></td>
					<?php endif;?>
					<td>
						<a href="/vertex/teacher/single/<?=$teacher['name']?>"><?=$teacher['name']?></a></td>
					<td>
						<?=$teacher['phone']?></td>
					<td>
						<?=$teacher['address']?></td>
				</tr>
				<?php endforeach; ?></tbody>
		</table>
	</div>
</div>
</div>
<div id="editModal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>编辑教师</h3>
	</div>
	<div class="modal-body">
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="inputTeacherName">姓名</label>
				<div class="controls">
					<span id="inputTeacherName"></span>
					<input id="teacherId" type="hidden" value="0" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputPhone">电话</label>
				<div class="controls">
					<input type="text" id="inputPhone" placeholder="18323902222">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputAddress">地址</label>
				<div class="controls">
					<input type="text" id="inputAddress" placeholder="">
				</div>
			</div>
		</form>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal" aria-hidden="true">退出</a>
			<a id="editSubmit" href="#" class="btn btn-primary">确认</a>
		</div>
	</div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>

<script type="text/javascript">

	$('#teacherTable').dataTable( {
    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
    "sPaginationType": "bootstrap",
    "oLanguage": {
      "sLengthMenu": "_MENU_ records per page",
      "sUrl": "/vertex/resources/language/chinese.lag"
    },
    "bFilter": true,
  } );



	$('#addClass').click(function(){
		window.location.href = '/vertex/teacher/create';
	})

	function loadTeacher(dom){
		
		var link = $(dom).parent();
		var id = $(dom).parent().siblings('input').val();

		var td = $(link).parent();
		var teacherName = $(td).siblings('td').eq(0).html();
		var teacherPhone = $(td).siblings('td').eq(1).html();
		var teacherAddress = $(td).siblings('td').eq(2).html();

		$('#inputTeacherName').html(teacherName);
		$('#inputPhone').val($.trim(teacherPhone));
		$('#inputAddress').val($.trim(teacherAddress));
		$('#teacherId').val(id);

		$('#editModal').modal('show');
	}

	$('#teachClass').change(function(){

		var teacherName = $('#inputTeacherName').html();
		var className = $('#teachClass').find("option:selected").text(); 

		if($(this).val() != 0 ){
			$.get('/vertex/teacher/getClassFee',{ teacherName : teacherName , className : className } ,function(data){
				var fee = data.classFee;
				$('#teacherFeeInput').val(fee);
				$('#feebox').show();
			});
		}else{
			$("#feebox").hide();
		}
	});


	$('.icon-edit').click(function(){

		loadTeacher($(this));

	})

	$('#editSubmit').click(function(){

		var phone = $('#inputPhone').val();
		var address = $('#inputAddress').val();
		if(!phone.match(/^\d*$/)){
			bootbox.alert('请在电话一栏输入数字');
			return false;
		}else{
			$.get('/vertex/teacher/editTeacher' , { id : $('#teacherId').val() , phone: $('#inputPhone').val()  , address: address } ,function(data){
				window.location.reload();
			},'json');
		}
	});

	$(document).on('click' ,'.icon-trash' , function(){

		var dom = $(this).parent();
		var id = $(this).parent().siblings('input').val();
		bootbox.confirm('确认删除教师吗？',function(result){
			if(result){
				$.post('/vertex/teacher/deleteTeacher',{teacherId : id},function(data){
					var tr = $(dom).parent().parent();
					$(tr).fadeOut('slow',function(){
						$(tr).remove();
					});
				});
			}	
		});
	});

	$('#searchBtn').click(function(){

		var context = $(this).siblings('input').val();
		$.get('/vertex/teacher/search',{ context : context },function(data){

			var tpl = [
				'<tr>',
					'<td>',
						'<a href="javascript:void(0);">',
							'<i class="icon-edit" title="修改"></i>',
						'</a>',
						'<a href="javascript:void(0);">',
							'<i class="icon-trash" title="删除"></i>',
						'</a>',
						'<input type="hidden" value="$teacherId$">',
					'</td>',
					'<td>$teacherName$</td>',
					'<td>$phone$</td>',
					'<td>$teachClass$</td>',
					'<td>$teachClassFee$</td>',
					'<td>$teachFee$</td>',
				'</tr>'
				];

			var html = "";
			if(data.teachers.length == 0){
				html="<tr><td colspan='6'><center>暂无教师</center></td></tr>";
			}

			for (var i =0;i<data.teachers.length;i++){
				var teacher = data.teachers[i];
				html += tpl.join(" ").replace(/\$teacherId\$/,teacher.id)
							.replace(/\$teacherName\$/,teacher.name)
							.replace(/\$phone\$/,teacher.phone)
							.replace(/\$teachClass\$/,teacher.class)
							.replace(/\$teachClassFee\$/,teacher.classFee)
							.replace(/\$teachFee\$/,teacher.costs);
			}

			$('table').find('tbody').html(html);

		},'json');
	});
</script>