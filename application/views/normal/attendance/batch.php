<div class="container attendance">
	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li>
					<a href="/vertex/attendance"> <i class="icon-chevron-right"></i>
						考勤一览
					</a>
				</li>
				<li  class="active">
					<a href="/vertex/attendance/batch"> <i class="icon-chevron-right"></i>
						批量删除
					</a>
				</li>
				<li>
					<a href="/vertex/attendance/create"> <i class="icon-chevron-right"></i>
						新增考勤
					</a>
				</li>
			</ul>
		</div>
		<div class="span10">
			<div>
				<p class="lead">批量删除</p>
				<hr />
			</div>
			<div>
				<form class="form-horizontal">
					<div class="control-group">
						<label class="control-label">开始时间</label>
						<div class="controls input-append date start-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy MM dd" data-link-field="dtp_input1">
							<input id='startTime' size="16" type="text" value="<?=date('Y-m-d')?>" readonly>				
							<span class="add-on"> <i class="icon-remove"></i>
							</span>
							<span class="add-on"> <i class="icon-th"></i>
							</span>
						</div>
						<input type="hidden" id="dtp_input1" value="<?=date('Y-m-d')?>" />				
						<br/>				
					</div>
					<div class="control-group">
						<label class="control-label">结束时间</label>
						<div class="controls input-append date end-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
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
						<div class="controls">
							<a id="addAttandance" class="btn btn-success">显示考勤</a>
						</div>
					</div>
				</form>
				<hr />

				<button id='deleteBtn' class='btn btn-primary pull-right'>删除选中</button>
				<table id='reportTable' class='table'>
					<thead>
						<tr>
							<th><a id='selectAll' href="javascript:void(0)">反选</a></th>
							<th>日期</th>
							<th>班级</th>
							<th>学生</th>
							<th>类型</th>
							<th>备注</th>
							<th>操作人</th>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script src="/vertex/resources/public/bootstrap-timepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/vertex/resources/public/bootstrap-timepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>

<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/TableTools.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>

<script type="text/javascript">
 $('.start-datetime , .end-datetime').datetimepicker({
        language:  'zh-CN',
        format: 'yyyy-mm-dd',
        todayBtn:  1,
		autoclose: 1,
		minView: 'month'
    });

var oTable = $('#reportTable').dataTable( {
		//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	    //"sPaginationType": "bootstrap",
	    "oLanguage": {
	      "sLengthMenu": "_MENU_ records per page",
	      "sUrl": "/vertex/resources/language/chinese.lag"
	    },
	    "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false
	} );

	$('#addAttandance').click(function(){

		var startTime = $('#startTime').val();
		var endTime = $('#endTime').val();

		if(startTime == "" || endTime == ""){
			bootbox.alert('请选择开始或结束时间');
			return false;
		}

		$.get('/vertex/attendance/ajaxGetAttendances' , { 'start_time' :  startTime , 'end_time' : endTime } ,function(data){
			oTable.fnClearTable();
	        oTable.fnAddData(data);
	        oTable.fnDraw();
		},'json');

	});

	$('#deleteBtn').click(function(){

		var ids = [];
		$(':checkbox').prop('checked' , function (i ,value){
			if(value){
				ids.push($(this).val());
			}
		});
		$.post('/vertex/attendance/mutiDelete' , {'attendanceIds' :JSON.stringify(ids)} , function(data){
			window.location.reload();
		} , 'json');
	});

	$('#selectAll').click(function(){
		$(':checkbox').prop('checked', function (i, value) {
		    return !value;
		})
	});
</script>