<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sign in &middot; Twitter Bootstrap</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Le styles -->
  <link href="resources/public/bootstrap/css/bootstrap.css" rel="stylesheet">
  <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        font-family: "Microsoft YaHei";
      }

      .form-signin {
        max-width: 550px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }

      .form-signin-heading{
        text-align: center;
        margin: 0 auto;
        margin-bottom: 50px !important;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

      .bottom30 {
        margin-bottom: 30px;
      }

      .signUp {
        text-decoration: underline;
        vertical-align: bottom;
        
      }

      .alert-box,.info-box {
        position: absolute;
        top: 0px;
        left:45%;
        z-index: 1;
        display: none;
      }

      .info-box {
        left: 40%;
      }
    </style>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="../assets/js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="../assets/ico/favicon.png"></head>

<body>

  <div class="container">
    <!--
    <form class="form-signin">
    <h2 class="form-signin-heading">请登录</h2>
    <span>账号:</span>
    <input type="text" />
    <span>密码:</span>
    <input type="password" />
    <label class="checkbox">
      <input type="checkbox" value="remember-me">记住密码</label>
    <button class="btn btn-large btn-primary" type="submit">Sign in</button>
  </form>
  -->
  <div class="form-signin">
    <blockquote class="pull-right">
      <p>教务管理系统</p>
      <small>顶点英语学校</small>
    </blockquote>
    <div class="clearfix bottom30"></div>
    <form id="login-form" class="form-horizontal" method="post" action="/vertex/login/signIn">
      <div class="control-group">
        <label class="control-label" for="inputAccount">账号</label>
        <div class="controls">
          <input type="text" id="inputAccount" name="username" placeholder="账号"></div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputPassword">密码</label>
        <div class="controls">
          <input type="password" id="inputPassword" name="password" placeholder="密码"></div>
      </div>
      <div class="control-group">
        <div class="controls">
          <?php if(isset($redirect)):?>
          <input type="hidden" name="redirect" value="<?=$redirect?>" />
          <?php endif; ?>
          <label class="checkbox">
            <input type="checkbox" name="remember">记住密码</label>
          <a id="submit_btn" class="btn">登录</a>
          <a href="#signUpModal" role="button" class="signUp" data-toggle="modal">注册</a>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- /container -->

<!-- SignUp Modal -->
<div id="signUpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">新用户注册</h3>
  </div>
  <div class="modal-body">
    <form class="form-horizontal">
      <div class="control-group">
        <label class="control-label" for="signUpUsername">账号</label>
        <div class="controls">
          <input type="text" id="signUpUsername" placeholder="账号"></div>
      </div>
      <div class="control-group">
        <label class="control-label" for="signUpPassword">密码</label>
        <div class="controls">
          <input type="password" id="signUpPassword" placeholder="密码"></div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    <button id="signUp_btn" class="btn btn-primary">注册</button>
  </div>
</div>
<!-- /SignUp Modal -->

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="resources/public/jquery-1.10.1.min.js"></script>
<script src="resources/public/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">

function showAlert(content){

  $('.info-box').remove();
  $('.alert-box').remove();

  var tpl = [
    '<div class="alert-box">',
      '<div class="alert alert-error">',
        '<button type="button" class="close" data-dismiss="alert">&times;</button>',
        '$info$',
      '</div>',
    '</div>'
    ];
  var alert = tpl.join(" ").replace(/\$info\$/ , content );
  $('body').append(alert);

  $('.alert-box').fadeIn('show');
}

function showInfo(content){

  $('.info-box').remove();
  $('.alert-box').remove();

  var tpl = [
    '<div class="info-box">',
      '<div class="alert alert-success">',
        '<button type="button" class="close" data-dismiss="alert">&times;</button>',
        '$info$',
      '</div>',
    '</div>'
    ];
  var alert = tpl.join(" ").replace(/\$info\$/ , content );
  $('body').append(alert);

  $('.info-box').fadeIn('show');
}

function signUp(){

      var username = $('#inputAccount').val();
      var password = $("#inputPassword").val();
     
      if(username == ""){
        showAlert("请输入用户名!");
        return false;
      }

      if(password == ""){
        showAlert("请输入用户密码!");
        return false;
      }

      $.post('/vertex/login/verifyAccount' ,  { username : username , password : password } ,function(data){

          var result = data.result;
          if(!result){
            showAlert("账号或密码错误!");
            return false;
          }
          $('#login-form').submit();
      },'json');
}

$().ready(function(){

  $('#submit_btn').click(function(){
      signUp();   
  });

  $("#signUpUsername").blur(function(){

      $.post('/vertex/login/checkUserName' , { username : $(this).val() } , function(data){
          var exist = data.exist;
          $('#signUpUsername').siblings('.help-inline').remove();
          if(exist){
            $('#signUpUsername').after('<span style="color:red" class=\'help-inline\'>用户名重复，请重新输入</span>');
          }
      },'json');

  });

  $("#signUp_btn").click(function(){

      if($('.help-inline').length > 0){
        return false;
      }
      
      var username = $('#signUpUsername').val();
      var password = $('#signUpPassword').val();

      $.post('/vertex/login/signUp' , { username :  username , password : password },function(data){

        var result = data.result;
        if(!result){
          showAlert("注册失败，可能原因为用户名重复");
        }else{
          $('#signUpModal').modal('hide');
          $('#signUpUsername').val("");
          $('#signUpPassword').val("");
          showInfo("注册成功，欢迎使用教务管理系统");
        }

      },'json');
  });

   $(document).bind('keyup',function(event) {
          if(event.keyCode==13){  
            signUp();   
        }  
    });  
});
</script>
</html>