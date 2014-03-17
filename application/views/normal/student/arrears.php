<div class="container student">
<!--   <div id="studentOpera">
    <button id='addStudent' class="btn btn-success"> <i class="icon-plus"></i>
      <span class="add-student">&nbsp;新增学生</span>
    </button>
  <div class="btn-group" data-toggle="buttons-checkbox">
    <button id="arrearsStudent" class="btn btn-danger"> <i class=" icon-exclamation-sign"></i>
      <span class="arrears">&nbsp;欠费学生</span>
    </button>
  </div>

  </div> -->
  <div  class="row-fluid">
    <div class="span2">
      <ul class="nav nav-list bs-docs-sidenav affix">
        <li>
          <a href="/vertex/student"> <i class="icon-chevron-right"></i>
            学生信息
          </a>
        </li>
        <li>
              <a href="/vertex/student/overage"> <i class="icon-chevron-right"></i>
              缴费信息
              </a>
            </li>
         <li class="active">
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
        <p class="lead">欠费学生</p>
        <hr />
      </div>
      <table id="studentTable" class="table">
        <thead>
          <tr>
            <th>姓名</th>
            <th>班级</th>
            <th title="额度为正，说明即将在三次考勤内欠费">
              班级额度
              <i class="icon-question-sign"></i>
            </th>
            <th>母亲电话</th>
            <th>父亲电话</th>
            <th>座机</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $tClasses as $tclass):?>    
          <tr>
            <td><a href="/vertex/student/single/<?=$tclass['student']['id']?>"><?=$tclass['student']['name']?></a></td>
            <td><?=$tclass['class']['name']?></td>
            <td><?php echo $tclass['banlance'] - $tclass['scholarship'] ?></td>
            <td><?=$tclass['student']['mather_phone']?></td>
            <td><?=$tclass['student']['father_phone']?></td>
            <td><?=$tclass['student']['landline']?></td>
          </tr>
          <?php endforeach;?></tbody>
      </table>
      <form style="display:none;" id="editForm" method="post" action="/vertex/student/edit">
        <input name="id" type="hidden" value="0" />    
      </form>
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
    }
  } );
</script>