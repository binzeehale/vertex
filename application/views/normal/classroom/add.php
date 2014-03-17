<div class="container classroom">
	<div class="container teacher">
		<div  class="row-fluid">
			<div class="span2">
				<ul class="nav nav-list bs-docs-sidenav affix">
					<li>
						<a href="/vertex/classroom"> <i class="icon-chevron-right"></i>
							班级一览
						</a>
					</li>
					<li  class="active">
						<a href="/vertex/classroom/create"> <i class="icon-chevron-right"></i>
							新建班级
						</a>
					</li>
				</ul>
			</div>
			<div class='span10'>
				<div class="">
					<p class="lead">新建班级</p>
					<hr />
				</div>
				<div>
					<form class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="inputClassName">班级名称</label>
							<div class="controls">
								<input type="text" id="inputClassName" placeholder="新概念一班" />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="inputTeacher">任课老师</label>
							<div class="controls">

								<select id="inputTeacher">
									<option value="0">无</option>
									<?php foreach($teachers as $teacher):?>
									<option value="<?=$teacher['id']?>
										">
										<?=$teacher['name']?></option>
									<?php endforeach; ?></select>
								<div class="input-prepend" style="display:none">
									<span class="add-on">课时费（次/人）</span>
									<input id="teacherFeeInput" type="text" placeholder="10" />
								</div>
							</div>
						</div>
						<div id="class-charges" class="control-group">
							<label class="control-label">收费标准</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on">年级</span>
									<input class="class-grade" type="text" placeholder="初一"></div>
								<div class="input-prepend">
									<span class="add-on">费用</span>
									<input class="class-fee" type="text" placeholder="45.00"></div>
								<a href="javascript:addCharges();" class="btn">
									<i class="icon-plus"></i>
								</a>
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<a id="class-submit" class="btn btn-success" >创建</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/json2.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script type="text/javascript">
	function addCharges(){
		var tpl = [
			'<div class="control-group">',
				'<label class="control-label"></label>',
				'<div class="controls">',
					'<div class="input-prepend">',
					  '<span class="add-on">年级</span>',
					  '<input class="class-grade" type="text" placeholder="初一">',
					'</div>',
					'<div class="input-prepend">',
					  '<span class="add-on">费用</span>',
					  '<input class="class-fee" type="text" placeholder="45.00">',
					'</div>',
					'<a class="btn remove-changes"><i class="icon-minus"></i></a>',
				'</div>',
			'</div>'
		];

		$('#class-charges').after(tpl.join(' '));
	};

	function submit(){

		if($('.help-inline').length > 0){
			return false;
		}

		var teacherId = $('#inputTeacher').val();
		var className = $('#inputClassName').val();


		var charges = [];
		$('.class-grade').each(function(){

			var grade = $(this).val();
			var fee = $(this).parent().siblings().children('.class-fee').val();

			charges.push( { grade: grade , fee : fee} );
		});
		if(className == ""){
			bootbox.alert("班级名称不能为空");
			return false;
		}

		if(teacherId != 0 ){
			var teacherFee = $('#teacherFeeInput').val();
			if( !teacherFee.match(/^\d+(\.\d*)?$/) ){
				bootbox.alert('课时费输入非法，请输入数字（可带小数点）');
				return false;
			}
		}

		for( var i = 0 ; i<charges.length ; i++){
			var charge = charges[i];

			if(charge.grade == ""){
				bootbox.alert("年级不能为空");
				return false;
			}

			if( !charge.fee.match(/^\d+(\.\d*)?$/) ){
				bootbox.alert('费用输入非法，请输入数字（可带小数点）');
				return false;
			}
		}

		var postArr = { teacher : teacherId , className : className , teacherFee:  $('#teacherFeeInput').val(), charges : JSON.stringify(charges) }

		$.post('/vertex/classroom/insertClass' , postArr , function(data){

			if(data.success){
				window.location.href = '/vertex/classroom/addStudent/' +　data.className + '/pass';
			}else{
				showAlert("系统出错!请联系管理员");
			}
		},'json');
		
	}

	function showAlert( content ){
		$('.alert-box').remove();
		var tpl = [
		'<div class="alert-box">',
		  '<div class="alert alert-error">',
		    '<button type="button" class="close" data-dismiss="alert">&times;</button>',
		    '$info$',
		  '</div>',
		'</div>'
		];
		var alert = tpl.join(" ").replace(/\$info\$/ , content );
		$('body').append(alert);

		$('.alert-box').fadeIn('show');
	}

	$(document).on("click" , ".remove-changes" , function(){
		$(this).parent().parent().remove();
	});

	$('#class-submit').click(function(){

		submit();

	});

	$('#inputTeacher').change(function(){

		$('#teacherFeeInput').val("");
		var teacherId = $(this).val();
		if(teacherId != 0 ){
			$(this).siblings('div').show();
		}else{
			$(this).siblings('div').hide();
		}
	});

	$("#inputClassName").blur(function(){

		var name = $(this).val();
		if(name != ""){
			$.post('/vertex/classroom/checkClassName' , { className : name },function(data){
				$('.help-inline').remove();
				if(data.success){
					if(!data.pass){
						$('#inputClassName').after("<span style='color:red;' class='help-inline'>班级名称已存在!</span>");
					}
				}
			},'json');
		}

	});
</script>