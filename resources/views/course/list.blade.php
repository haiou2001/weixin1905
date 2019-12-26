<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bootstrap 实例 - 条纹表格</title>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<table class="table table-striped">
    <caption>条纹表格布局</caption>
    <thead>
    <tr>
        <th>编号</th>
        <th>第一节课</th>
        <th>第二节课</th>
        <th>第三节课</th>
        <th>第四节课</th>
        <th>编辑</th>
    </tr>
    </thead>
    @foreach($data as $v)
    <tbody>
    <tr>
        <td>{{$v->id}}</td>
        <td>{{$v->onename}}</td>
        <td>{{$v->twoname}}</td>
        <td>{{$v->threename}}</td>
        <td>{{$v->fourname}}</td>
        <td><a href="{{url('/course/edit/'.$v->id)}}">编辑</a></td>
    </tr>
    </tbody>
    @endforeach
</table>

</body>
</html>