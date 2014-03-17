<div class="container">
  <div  class="row-fluid">
    <div class="span2">
      <ul class="nav nav-list bs-docs-sidenav affix">
        <li class="active">
          <a href="/vertex/classroom"> <i class="icon-chevron-right"></i>
            班级一览
          </a>
        </li>
        <?php if($userWeight == 1):?>
        <li>
          <a href="/vertex/classroom/create">
            <i class="icon-chevron-right"></i>
            新建班级
          </a>
        </li>
        <?php endif;?>
      </ul>
    </div>
    <div class='span10'>
      <div class="">
        <p class="lead">班级一览</p>
        <hr />
      </div>
      <div>
        <table id="classTable" class="table">
          <thead>
            <tr>
              <?php if($userWeight == 1):?>
              <th>#</th>
              <?php endif;?>
              <th>班级名称</th>
              <th>现有学生人数</th>
              <th>班级任课老师</th>
              <th>教师课时费</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($classes as $class): ?>
            <tr>
              <?php if($userWeight == 1):?>
              <td>
                <a href="javascript:void(0)">
                  <i class="icon-list-alt" title="考勤"></i>
                </a>
                <a href="javascript:void(0)">
                  <i class="icon-trash" title="删除"></i>
                </a>
                <input type="hidden" value="<?=$class['id']?>" /></td>
              <?php endif;?>
              <td>
                <a href="/vertex/classroom/single/<?=$class['name']?>
                  ">
                  <?=$class['name']?></a>
              </td>
              <td>
                <?=$class['studentsCount']?></td>
              <td>
                <?php if($class['teacher'] == "" ):?>
                <span>暂无</span>
                <?php else :?>
                <?=$class['teacher']?>
                <?php endif; ?></td>
              <td>
                <?=$class['teacher_fee']?></td>
            </tr>
            <? endforeach; ?></tbody>
        </table>
        <div id="pagination" class="pagination"></div>
      </div>
    </div>
  </div>
</div>
<script src="/vertex/resources/public/jquery-1.10.1.min.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootstrap.js"></script>
<script src="/vertex/resources/public/bootstrap/js/bootbox.min.js"></script>
<script src="/vertex/resources/public/jquery.dataTable.js"></script>
<script src="/vertex/resources/public/dataTables.bootstrap.js"></script>

<script type="text/javascript">

    /* Table initialisation */
    $(document).ready(function() {
      $('#classTable').dataTable( {
         "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>><'row-fluid'<'span12 pull-right'T>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
          "sUrl": "/vertex/resources/language/chinese.lag"
        },
        "bFilter": true,
      });
    });

    $(document).on('click','.icon-list-alt' , function(){

        var className = $(this).parent().parent().siblings('td').first().children('a').html();
        window.location.href = "/vertex/attendance/create/" + $.trim(className);
    });


    $(document).on('click' , '.icon-trash' , function(){

        var tr = $(this).parent().parent().parent();
        var classId = $(this).parent().siblings('input').val();
        bootbox.confirm('确认删除班级？',function(result){
          if(result){
             $.post('/vertex/classroom/delete' , { classId :  classId } , function(data){
                  $(tr).fadeOut('slow',function(){
                      $(tr).remove();
                  });
              },'json');
          }
        });
    });


    $(".add-class").click(function(){
      $.get('/vertex/teacher/ajaxGetList',function(data){

        if(data.teachers.length == 0 ){
          bootbox.alert('目前没有教师，请先添加教师');
        }else{
          window.location.href = "/vertex/classroom/create";
        }

      },'json');

    });
</script>