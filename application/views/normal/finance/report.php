<style>
.tab-content {
	overflow: visible;
}
</style>
<div class="container attendance">
	<div  class="row-fluid">
		<div class="span2">
			<ul class="nav nav-list bs-docs-sidenav affix">
				<li>
					<a href="/vertex/finance"> <i class="icon-chevron-right"></i>
						财务一览
					</a>
				</li>
				<li   class="active">
					<a href="/vertex/finance/report"> <i class="icon-chevron-right"></i>
						财务报表
					</a>
				</li>
				<?php if($userWeight == 1):?>
				<li>
					<a href="/vertex/finance/create">
						<i class="icon-chevron-right"></i>
						新增收支
					</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<div class="span10">
			<div>
				<p class="lead">财务报表</p>
				<hr />
			</div>
			<div>
				<form class="form-horizontal">
					<div class="control-group">
						<label class="control-label">开始时间</label>
						<div class="controls input-append date start-datetime" data-date="<?=date('Y-m-d')?>
							" data-date-format="yyyy MM dd" data-link-field="dtp_input1">
							<input id='startTime' size="16" type="text" value="<?=date('Y-m-d')?>" readonly>
							<span class="add-on">
								<i class="icon-remove"></i>
							</span>
							<span class="add-on">
								<i class="icon-th"></i>
							</span>
						</div>
						<input type="hidden" id="dtp_input1" value="<?=date('Y-m-d')?>" />
						<br/>
					</div>
					<div class="control-group">
						<label class="control-label">结束时间</label>
						<div class="controls input-append date end-datetime" data-date="<?=date('Y-m-d')?>
							" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
							<input id='endTime' size="16" type="text" value="<?=date('Y-m-d')?>" readonly>
							<span class="add-on">
								<i class="icon-remove"></i>
							</span>
							<span class="add-on">
								<i class="icon-th"></i>
							</span>
						</div>
						<input type="hidden" id="dtp_input2" value="<?=date('Y-m-d')?>" />
						<br/>
					</div>
					<div class="control-group">
						<div class="controls">
							<a id="addAttandance" class="btn btn-success">显示报表</a>
						</div>
					</div>
				</form>
				<hr/>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#total" data-toggle="tab">总报表</a>
					</li>
					<li>
						<a href="#incomeReport" data-toggle="tab">收入报表</a>
					</li>
					<li>
						<a href="#expenseReport" data-toggle="tab">支出报表</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="total">
						<div style='margin:5px 0;' class="history pull-right">
							<span class="income"> <strong>收入</strong>
								<span id="totalIncome">0</span>
								元
							</span>
							<span class="expenses"> <strong>支出</strong>
								<span id="totalExpense">0</span>
								元
							</span>
						</div>
						<div style="clear:both;"></div>
						<table id='reportTable' class='table'>
							<thead>
								<tr>
									<th>日期</th>
									<th>学生</th>
									<th>班级</th>
									<th>收入</th>
									<th>支出</th>
									<th>类型</th>
									<th>备注</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>

					</div>
					<div class="tab-pane fade" id="incomeReport">
						<table id='incomeReportTable' class='table'>
							<thead>
								<tr>
									<th>日期</th>
									<th>学生</th>
									<th>班级</th>
									<th>收入</th>
									<th>支出</th>
									<th>类型</th>
									<th>备注</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="expenseReport">
						<table id='expenseReportTable' class='table'>
							<thead>
								<tr>
									<th>日期</th>
									<th>学生</th>
									<th>班级</th>
									<th>收入</th>
									<th>支出</th>
									<th>类型</th>
									<th>备注</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>

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
	    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
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
		"aaSorting": [[ 0, "desc" ]]
	} );

var incomeTable = $('#incomeReportTable').dataTable( {
		//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
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
		"aaSorting": [[ 0, "desc" ]]
	} );

var expenseTable = $('#expenseReportTable').dataTable( {
		//"sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
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
		"aaSorting": [[ 0, "desc" ]]
	} );

	$('#addAttandance').click(function(){

		var startTime = $('#startTime').val();
		var endTime = $('#endTime').val();

		if(startTime == "" || endTime == ""){
			bootbox.alert('请选择开始或结束时间');
			return false;
		}

		$.get('/vertex/finance/ajaxGetReport' , { 'start_time' :  startTime , 'end_time' : endTime } ,function(data){
			oTable.fnClearTable();
	        oTable.fnAddData(data['all']);
	        oTable.fnDraw();

	        incomeTable.fnClearTable();
	        incomeTable.fnAddData(data['income']);
	        incomeTable.fnDraw();

	        expenseTable.fnClearTable();
	        expenseTable.fnAddData(data['expenses']);
	        expenseTable.fnDraw();

	        $('#totalIncome').html(data['totalIncome']);
	        $('#totalExpense').html(data['totalExpenses']);
		},'json');

	});


</script>