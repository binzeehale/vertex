<div class="container classroom">
	<div>
		<p class="lead">添加学生</p>
		<hr />
	</div>
	<div class="add-student">
		<button id="addStudentBtn" class="btn btn-success"> <i class="icon-plus"></i>
			<span class="add-class">&nbsp;新建学生</span>
		</button>
	</div>
	<div class="alert alert-success"> <strong>已选班级：</strong>
		<?=$selectedClassName?>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<p class="lead">第一步</p>
			<p class="text-success">请选择加入该班级的学生</p>
		</div>
		<div class="span9">
			<!--
			<div class="input-append pull-right">
				<input id="appendedInputButtons" type="text">
				<button id="studentSelectBtn" class="btn font-fix" type="button">学生查询</button>
			</div>
		-->
			<table id="studentTable" class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>学生</th>
						<th>现在班级</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($students as $student): ?>
					<tr>
						<td>
							<input class="student-selection" name="checkList" type="checkbox" value="<?=$student['id']?>"/>
						</td>
						<td><?=$student['name']?></td>
						<td><?=$student['class'] ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div id="pagination" class="pagination"></div>
		</div>
	</div>
	<div class="row-fluid">
		<div id="selected-student" class="alert alert-success"> <strong>已选学生：</strong>
			<!--
			<?php foreach($selectedStudents as $selectedStudent):?>
				<span class="label label-info"><?=$selectedStudent['name']?><input type="hidden" value="<?=$selectedStudent['id']?>"></span>
			<?php endforeach;?>
		-->
		</div>
	</div>

	<div class="row-fluid">
		<div class="span3">
			<p class="lead">第二步</p>
			<p class="text-success">请选择收费标准</p>
		</div>
		<div class="span6">
			<?php foreach($charges as $key => $charge):?>
			<label class="radio">
				<input type="radio" name="optionsRadios" value="<?=$charge['id']?>" <?php echo $key==0?"checked":"" ?> />
				<?=$charge['cost']?>元/次
				<small class="muted"><?=$charge['grade']?></small>
			</label>
			<?php endforeach; ?>
		</div>
	</div>
	<hr />
	<div class="row-fluid">
		<div class="span3"></div>
		<div class="span9">
			<button id="submitBtn" class="btn btn-primary font-fix">添加</button>
			<?php if(isset($passBtn)): ?>
				<a href="/vertex/classroom/single/<?=$urlClassName?>" class="btn btn-success font-fix">跳过</a>
			<?php else:?>
				<a href="/vertex/classroom/single/<?=$urlClassName?>" class="btn font-fix">返回</a>
			<?php endif;?>
		</div>
	</div>
</div>

<script src="/vertex/resources/public/json2.js"></script>
<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/vertex/resources/js/pager.js"></script>
<script src="/vertex/resources/public/jquery.dataTable.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>
<script type="text/javascript">
	Array.prototype.indexOf = function(val) {              
    for (var i = 0; i < this.length; i++) {  
	        if (this[i] == val) return i;  
	    }  
	    return -1;  
	};  

	Array.prototype.remove = function(val) {  
	    var index = this.indexOf(val);  
	    if (index > -1) {  
	        this.splice(index, 1);  
	    }  
	};  
</script>
<script type="text/javascript">

	var studentBox = [];

	// $.ajax({
	// 	url: '/vertex/classroom/ajaxGetInClassStudents',
	// 	async: false,
	// 	data : { className : "<?=$selectedClassName?>" },
	// 	dataType : 'json',
	// 	success: function(data){
	// 		studentBox = data.students;
	// 	}
	// });

	//pager.init({ tableId : 'studentTable' , openCheck : { checkBoxClass:'student-selection' , outsideArr:'studentBox'} });

	$('#studentTable').dataTable( {
         "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sUrl": "/vertex/resources/language/chinese.lag"
        },
        "bFilter": true
      });

	$(document).on('click' , "input[type='checkbox']" , function(){

		var studentId = $(this).val();
		var studentName = $(this).parent().next().html();

		if($(this).is(':checked')){
			studentBox.push({id:studentId,name:studentName});
			$('#selected-student').append(
				'<span class="label label-info">'+studentName+'<input type="hidden" value="'+studentId+'" /></span>'
			);
		}else{
			for(var i = 0 ; i< studentBox.length ; i++){
				var student = studentBox[i];
				if(student['id'] == studentId){
					studentBox.remove(student);
				}
			}

			$('#selected-student').find('input[type="hidden"][value="' + studentId +'"]').each(function(){
				var dom = $(this).parent();
				$(dom).fadeOut('slow',function(){
					$(dom).remove();
				});
			});
		}
	});

	$(document).on('click' , '#selected-student > span' ,  function(){

		var studentId = $(this).children('input[type="hidden"]').val();
		$.grep(studentBox , function(key , val){
				return val['id'] != studentId;
			});

		$('.student-selection:checked').each(function(){
				if($(this).val() == studentId) {
					$(this).attr('checked',false);
				}
		});
		$(this).fadeOut('slow',function(){
			$(this).remove();
		});
	});

	$('#studentSelectBtn').click(function(){

		var context = $('#appendedInputButtons').val();
		var tpl = [
					'<tr>',
						'<td>',
							'<input class="student-selection" type="checkbox" value="$id$"/>',
						'</td>',
						'<td>$name$</td>',
						'<td>$class$</td>',
					'</tr>'
					];
		var selectedTpl = [
					'<tr>',
						'<td>',
							'<input class="student-selection" type="checkbox" checked="checked" value="$id$"/>',
						'</td>',
						'<td>$name$</td>',
						'<td>$class$</td>',
					'</tr>'
					];

		$.post('/vertex/student/search',{ context : context } , function(data){

			var students = data.students;
			var html = "";
			for( i in students){
				var student = students[i];
				var shtml = tpl.join(' ')
							.replace(/\$id\$/, student.id)
							.replace(/\$name\$/, student.name)
							.replace(/\$class\$/, student.class);

				for ( j in studentBox){
					var selectedStudent = studentBox[j];
					if(selectedStudent.id == student.id){
						shtml = selectedTpl.join(' ')
							.replace(/\$id\$/, student.id)
							.replace(/\$name\$/, student.name)
							.replace(/\$class\$/, student.class);
						break;
					}
				}
				html += shtml;
			}
			$('#studentTable > tbody').html(html);
		},'json');
	});

	$('#submitBtn').click(function(){

		var feeId = $('input[type="radio"]:checked').val();

		$.post('/vertex/classroom/insertStudent' , { classId : '<?=$classId?>' , students : JSON.stringify(studentBox) , feeId : feeId },function(data){

			if(data.success){
				window.location.href = "/vertex/classroom/single/<?=$selectedClassName?>";
			}

		},'json');

	});


	$('#addStudentBtn').click(function(){

		window.location.href = "/vertex/student/create";
	});
</script>