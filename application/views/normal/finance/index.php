<div class="container finance">
	<!-- <div class="top-btn">
	<button class="btn btn-success"> <i class="icon-plus"></i>
		<span id="addFinance" class="add-class">&nbsp;新增收支</span>
	</button>
</div>
-->
<div  class="row-fluid">
	<div class="span2">
		<ul class="nav nav-list bs-docs-sidenav affix">
			<li class="active">
				<a href="/vertex/finance"> <i class="icon-chevron-right"></i>
					财务一览
				</a>
			</li>
			<li>
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
			<p class="lead">财务一览</p>
			<hr />
		</div>
		<table id="financeTable" class="table">
			<thead>
				<tr>
					<?php if($userWeight == 1):?>
					<th width="5%">#</th>
					<?php endif;?>
					<th width="20%">时间</th>
					<th width="10%">类型</th>
					<th width="10%">收入</th>
					<th width="10%">支出</th>
					<th width="30%">备注</th>
					<th width="15%">操作人</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($finances as $finance):?>
				<tr>
					<?php if($userWeight == 1):?>
					<td><a href="javascript:deleteFinance(<?=$finance['id']?>)"><i class="icon-trash"></i></a></td>
					<?php endif;?>
					<td>
						<?=date('Y-m-d',strtotime($finance['last_update_time']))?></td>
					<td>
						<?=$finance['type']?></td>
					<td>
						<?=$finance['income']?></td>
					<td>
						<?=$finance['expenses']?></td>
					<td>
						<?=$finance['reason']?></td>
					<td>
						<?=$finance['user_name']?></td>
				</tr>
				<?php endforeach;?></tbody>
		</table>
	</div>
</div>
</div>

<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>

<script src="/vertex/resources/public/jquery.dataTable.js"></script>

<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="/vertex/resources/public/tableTool/media/js/TableTools.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>
<script type="text/javascript">

function deleteFinance(id){

	bootbox.confirm('确认删除该记录？（会恢复该次充值/扣款）',function(result){
		if(result){
			$.post('<?=base_url("finance/delete")?>', { id : id }, function(response){
				window.location.reload();
			},'json');
		}
	});
}


/* Table initialisation */
$(document).ready(function() {
	$('#financeTable').dataTable( {
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
		"aaSorting": [[ 1, "desc" ]]
	} );
} );


$('#addFinance').click(function(){
	window.location.href = "/vertex/finance/create";

})
</script>