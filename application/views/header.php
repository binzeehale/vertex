
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Top English TMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Le styles -->
  <link href="/vertex/resources/public/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="/vertex/resources/css/dataTable.bootstrap.css" rel="stylesheet">
  <link href="/vertex/resources/css/global.css" rel="stylesheet">
  <link href="/vertex/resources/public/icheck/skins/all.css" rel="stylesheet">
  <link href="/vertex/resources/public/bootstrap-timepicker/css/datetimepicker.css" rel="stylesheet">
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="/vertex/resources/js/html5shiv.js"></script>
  <![endif]-->

</head>
<body>

  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="brand" href="#">Top English</a>
        <div class="nav-collapse collapse">
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$username?>,您好 <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a id='changePws' href="#">修改密码</a></li>
                <?php if($userWeight == 1): ?>
                  <li><a id='manageUser' href="#">用户管理</a></li>
                <?php endif; ?>
                <li><a href="/vertex/main/backup">备份数据</a></li>
                <li class="divider"></li>
                <li><a id="logout" href="#">注销</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav">
            <!--
            <li id="main-page">
              <a href="/vertex/main">首页</a>
            </li>
          -->
            <li id="attendance-page">
              <a href="/vertex/attendance">考勤管理</a>
            </li>
            <li id="classroom-page">
              <a href="/vertex/classroom">班级管理</a>
            </li>
            <li id="student-page">
              <a href="/vertex/student">学生管理</a>
            </li>
            <li id="teacher-page">
              <a href="/vertex/teacher">教师管理</a>
            </li>
            <li id="finance-page">
              <a href="/vertex/finance">财务管理</a>
            </li>
          </ul>
        </div>
        <script type="text/javascript">
          (function(){
              var menuTitle = document.getElementById('<?=$pageName?>');
              menuTitle.className += 'active';
          })();
        </script>
        <!--/.nav-collapse --> 
      </div>
    </div>
  </div>