<div class="container teacher">
	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li>
					<a href="/vertex/teacher"> <i class="icon-chevron-right"></i>
						教师信息
					</a>
				</li>
				<li  class="active">
					<a href="/vertex/teacher/create"> <i class="icon-chevron-right"></i>
						新增教师
					</a>
				</li>
			</ul>
		</div>
		<div class="span10">
			<div class="">
				<p class="lead">新增教师</p>
				<hr />
			</div>
			<div>
				<form class="form-horizontal">
					<div class="control-group">
						<label class="control-label" for="inputTeacherName">姓名</label>
						<div class="controls">
							<input type="text" id="inputTeacherName" placeholder="张三"></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputPhone">电话</label>
						<div class="controls">
							<input type="text" id="inputPhone" placeholder="18323902222"></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="inputAddress">地址</label>
						<div class="controls">
							<input type="text" id="inputAddress" placeholder=""></div>
					</div>
					<!--
			<div class="control-group">
					<label class="control-label">任课班级</label>
					<div class="controls">
						<select id="teachClass">
							<option value="0">无</option>
							<?php foreach($classes as $class): ?>
							<option value="<?=$class['id']?>
								">
								<?=$class['name']?></option>
							<?php endforeach; ?></select>
						<div class="input-prepend" style="display:none;">
							<span class="add-on">课时费（元/次）</span>
							<input id="teacherFeeInput" class="span2" type="text" placeholder="10" />
						</div>
					</div>
				</div>
				-->
				<div class="control-group">
					<div class="controls">
						<a id="addTeacher" class="btn btn-success">创建</a>
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

		$('#teachClass').change(function(){
			if($(this).val() == 0 ){
				$(this).siblings('div').hide();
			}else{
				$(this).siblings('div').show();
			}
		});

		$('#addTeacher').click(function(){
			
			var teacherName = $.trim($('#inputTeacherName').val());
			var phone = $.trim($('#inputPhone').val());
			var teacherClass = $('#teachClass').val();
			var teacherFeeInput = $('#teacherFeeInput').val();
			var address = $('#inputAddress').val();

			var result = true;
			$.ajax({
				url : '/vertex/teacher/checkName',
				data: { name : teacherName},
				async: false,
				dataType: 'json',
				success: function(data){
					result = data.result;
				}
			});

			if(!teacherName.match(/^([\w\u4e00-\u9fa5])+$/)){
				bootbox.alert('请输入正确的教师姓名');
				return false;
			}

			if(!result){
				bootbox.alert('教师姓名重复，请重新输入');
				return false;
			}

			if(!phone.match(/^\d*$/)){
				bootbox.alert('请在电话一栏输入数字');
				return false;
			}

			if(!address.match(/^([\w\u4e00-\u9fa5])+$/)){
				bootbox.alert('请输入合法地址，只能包括字母、数字、下划线、汉字');
				return false;
			}

			var postArr = { name : teacherName , phone : phone , address: address, classId : 0 , teacherFee: 0 };

			$.post('/vertex/teacher/insert',postArr, function(data){
				var className = data.className;
				window.location.href = '/vertex/teacher/';
			},'json');

		});
</script>