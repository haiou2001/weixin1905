<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>管理课程</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<form action="{{url('course/update'.$data->id)}}" method="post" class="form-horizontal" role="form">
    {{csrf_field()}}
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">第一节课:</label>
        <div class="col-sm-10">
            <select name="onename" class="form-control" value="{{$data->onename}}" id="firstname"
                    placeholder="请输入名字">
                <option value="PHP">PHP</option>
                <option value="JS">JS</option>
                <option value="JAVA">JAVA</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">第二节课:</label>
        <div class="col-sm-10">
            <select name="twoname" class="form-control" value="{{$data->twoname}}" id="firstname"
                    placeholder="请输入名字">
                <option value="语文">语文</option>
                <option value="历史">历史</option>
                <option value="生物">生物</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">第三节课:</label>
        <div class="col-sm-10">
            <select name="threename" class="form-control" value="{{$data->threename}}" id="firstname"
                    placeholder="请输入名字">
                <option value="数学">数学</option>
                <option value="科学">科学</option>
                <option value="物理">物理</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">第四节课:</label>
        <div class="col-sm-10">
            <select name="fourname" class="form-control" value="{{$data->fourname}}" id="firstname"
                    placeholder="请输入名字">
                <option value="英语">英语</option>
                <option value="汉语">汉语</option>
                <option value="舞蹈">舞蹈</option>
            </select>
        </div>
    </div>
    <center><button type="submit"  class="btn btn-default">修改</button></center>
</form>
</body>
</html>