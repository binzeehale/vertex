<div class="container student">
  <div  class="row-fluid">
    <div class="span2">
      <ul class="nav nav-list bs-docs-sidenav affix">
        <li>
          <a href="/vertex/student"> <i class="icon-chevron-right"></i>
            学生信息
          </a>
        </li>
        
        <li  class="active">
          <a href="/vertex/student/overage"> <i class="icon-chevron-right"></i>
          缴费信息
          </a>
        </li>
         <li>
          <a href="/vertex/student/arrears"> <i class="icon-chevron-right"></i>
            欠费学生
          </a>
        </li>
        <?php if($userWeight == 1):?>
        <li>
          <a href="/vertex/student/create"> <i class="icon-chevron-right"></i>
            新增学生
          </a>
        </li>
        <?php endif;?>
      </ul>
    </div>
    <div class="span10">
      <div class="">
        <p class="lead">缴费信息</p>
        <hr />
      </div>
      <div>
        <table id="studentTable" class="table">
          <thead>
            <tr>
              <th>名称</th>
              <th>账户余额</th>
              <th>奖学金余额</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($students as $student):?>
            <tr>
              <td><a href="<?=base_url('/student/single').'/'.$student['id']?>"><?=$student['name']?></a></td>
              <td><?=$student['banlance']?></td>
              <td><?=$student['scholarship']?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
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
  var oTable = $('#studentTable').dataTable( {
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
  "aaSorting": [[ 1, "asc" ]]
} );

</script>