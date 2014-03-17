<div class="container teacher">
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
					<a href="/vertex/teacher/create"> <i class="icon-chevron-right"></i>
						新增教师
					</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<div class="span10">
			<div class="">
				<p class="lead">单教师信息</p>
				<hr />
			</div>
			<div>
				<div class='row-fluid'>
					<div class='span2 single-left'>
						<p class="lead">
							<?=$teacher['name']?></p>
					</div>
					<div class='span10'>
						<p>电话：<?=$teacher['phone']?></p>
						<p>地址：<?=$teacher['address']?></p>
					</div>
				</div>
				<div class='row-fluid'>
					<table class='table'>
						<thead>
							<tr>
								<th>任课班级</th>
								<th>课时费（元/人次）</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($classes) == 0):?>
							<tr><td colspan="2">无数据</td></tr>	
							<?php endif; ?>
							<?php foreach($classes as $class):?>
							<tr>
								<td><?=$class['name']?></td>
								<td><?=$class['fee']?></td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
				<div class='row-fluid'>
					<p class='lead' style='margin-top:20px'>上课记录</p>
					<form class="form-horizontal">
						<div class="control-group">
							<label style="width:auto;" class="control-label">时间范围</label>
							<div class="controls input-append date start-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy MM dd" data-link-field="dtp_input1">
								<input id='startTime' size="16" type="text" value="" readonly>				
								<span class="add-on"> <i class="icon-remove"></i>
								</span>
								<span class="add-on"> <i class="icon-th"></i>
								</span>
								<input type="hidden" id="dtp_input1" value="" />
							</div>
							
							<div class="controls input-append date end-datetime" data-date="<?=date('Y-m-d')?>" data-date-format="yyyy-MM-dd" data-link-field="dtp_input2">
								<input id='endTime' size="16" type="text" value="" readonly>				
								<span class="add-on"> <i class="icon-remove"></i>
								</span>
								<span class="add-on"> <i class="icon-th"></i>
								</span>
								<input type="hidden" id="dtp_input2" value="" />
							</div>
							<div style="margin-left:20px;display: inline-block;" class="controls">
								<a id="searchReport" class="btn btn-success">
									<i class="icon-search"></i>
								</a>
							</div>
											
							<br/>				
						</div>
					</form>

					<table id='aTable' class='table'>
						<thead>
							<tr>
								<th width='40%'>考勤时间</th>
								<th width='30%'>考勤班级</th>
								<th width='30%'>课时费</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($attendances as $date =>$classes):?>
							<?php foreach($classes as $name => $fee):?>
							<tr>
								<td><?=$date?></td>
								<td><?=$name?></td>
								<td><?=$fee?></td>
							</tr>
							<?php endforeach;?>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
	
	$('.start-datetime , .end-datetime').datetimepicker({
        language:  'zh-CN',
        format: 'yyyy-mm-dd',
        todayBtn:  1,
		autoclose: 1,
		minView: 'month'
    });
	
	var oTable = $('#aTable').dataTable( {
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
		"aaSorting": [[ 0, "desc" ]]
	} );

	$('#searchReport').click(function(){

		var startTime = $('#startTime').val();
		var endTime = $('#endTime').val();

		if(startTime == "" || endTime == ""){
			bootbox.alert('请选择开始或结束时间');
			return false;
		}

		$.get('/vertex/teacher/ajaxGetReport' , { 'teacherId':<?=$teacher['id']?> ,'start_time' :  startTime , 'end_time' : endTime } ,function(data){
			oTable.fnClearTable();
	        oTable.fnAddData(data);
	        oTable.fnDraw();
		},'json');

	});

</script>