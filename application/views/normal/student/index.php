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
        <li class="active">
          <a href="/vertex/student"> <i class="icon-chevron-right"></i>
            学生信息
          </a>
        </li>
        <li>
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
        <p class="lead">学生信息</p>
        <hr />
      </div>
      <table id="studentTable" class="table">
        <thead>
          <tr>
            <?php if($userWeight == 1):?>
            <th width="40px">#</th>
            <?php endif;?>
            <th>姓名</th>
            <th>性别</th>
            <th>学校</th>
            <th>年级</th>
            <th>现属班级</th>
            <th>母亲电话</th>
            <th>父亲电话</th>
            <th>座机</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $students as $student):?>    
          <tr>
            <?php if($userWeight == 1):?>
            <td>
              <a href="javascript:void(0);"> <i title="修改" class="icon-edit"></i>
              </a>
              <a href="javascript:void(0);"> <i title="删除" class="icon-trash"></i>
              </a>
              <input type="hidden" value="<?=$student['id']?>" /></td>
            <?php endif;?>
            <td>
              <a href="/vertex/student/single/<?=$student['id']?>
                ">
                <?=$student['name']?></a>
            </td>
            <td>
              <?=$student['sex']?></td>
            <td>
              <?=$student['school']?></td>
            <td>
              <?=$student['grade']?></td>
            <td>
              <?=$student['class']?></td>
            <td>
              <?=$student['mather_phone']?></td>
            <td>
              <?=$student['father_phone']?></td>
            <td>
              <?=$student['landline']?></td>
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
    "iDisplayLength": 25,
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

  var query = "";

  var tableTpl = [
          '<tr>',
            '<td>',
              '<a href="javascript:void(0);">',
                '<i title="修改" class="icon-edit"></i>',
              '</a>',
              '<a href="javascript:void(0);">',
                '<i title="删除" class="icon-trash"></i>',
              '</a>',
              '<input type="hidden" value="$id$" />',
            '</td>',
            '<td>',
             '<a href="/vertex/student/single/$urlName$">$name$</a>',
            '</td>',
            '<td>$sex$</td>',
            '<td>$school$</td>',
            '<td>$grade$</td>',
            '<td>$class$</td>',
            '<td>$mPhone$</td>',
            '<td>$fPhone$</td>',
            '<td>$landline$</td>',
            '<td>',
              '<a href="#">$balance$</a>',
            '</td>',
          '</tr>'
          ];


  $('#addStudent').click(function(){
      window.location.href = '/vertex/student/create';
  });

  var toggle = false;
  $('#arrearsStudent').click(function(){

      toggle = !toggle;
      if(toggle){
        $.get('/vertex/student/arrears' , function(data){

          oTable.fnClearTable();
          oTable.fnAddData(data);
          oTable.fnDraw();
        },'json');
      }else{
        $.get('/vertex/student/ajaxGetAllList' , function(data){

          oTable.fnClearTable();
          oTable.fnAddData(data);
          oTable.fnDraw();
        },'json');
      }
  });

  $('#searchBtn').click(function(){

      var searchStr = $(this).siblings('input').val();
      $.get('/vertex/student/search',{ context : searchStr } , function(data){
          var students = data.students;
          var html = "";
          if(students.length == 0){
            html = "<tr><td colspan='10'><center>暂无学生</center></td></tr>";
          }else{

            for (var i = 0 ;i<students.length ; i++){
              var student = students[i];
              html += tableTpl.join(' ').replace(/\$id\$/,student.id)
                                        .replace(/\$name\$/,student.name)
                                        .replace(/\$urlName\$/,student.urlName)
                                        .replace(/\$sex\$/,student.sex)
                                        .replace(/\$school\$/,student.school)
                                        .replace(/\$grade\$/,student.grade)
                                        .replace(/\$class\$/,student.class)
                                        .replace(/\$mPhone\$/,student.mather_phone)
                                        .replace(/\$fPhone\$/,student.father_phone)
                                        .replace(/\$landline\$/,student.landline)
                                        .replace(/\$balance\$/,student.banlance);
            }
          }
          $('#studentTable').find('tbody').html(html);
      },'json');
    });

    $(document).on('click' , '.icon-trash' , function(){

        var sId = $(this).parent().siblings('input[type="hidden"]').val();
        var tr = $(this).parent().parent().parent();
        bootbox.confirm('确认删除学生?(会同时删除与这个学生有关的考勤、交扣费等全部信息，请慎重!)',function(result){
          if(result){
            $.post('/vertex/student/deleteStudent' , { studentId : sId } ,function(data){
                $(tr).fadeOut('slow',function(){
                  $(tr).remove();
                });
            });
          }
        });
    });

     $(document).on('click' , '.icon-edit' , function(){
        var id = $(this).parent().siblings('input[type="hidden"]').val();
        
        $('#editForm').find('input').val(id);
        $('#editForm').submit();

     });
</script>