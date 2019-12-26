<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\CourseModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use DB;
class CourseController extends Controller
{
    //管理课程
    public function index()
    {
        return view('course/index');
    }

    public function list()
    {
        $pageSize = config('app.paginate');
        $data = DB::table('course')->get();
        return view('course.list',['data'=>$data]);
    }
    //查看课程
    public function add()
    {
        return view('course/add');
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $res = DB::table('course')->insert($data);
        if($res){
            return redirect('/course/list');
        }else{
            return redirect('/course/list');

        }
    }

    public function edit($id)
    {
        $data=CourseModel::find($id);
        return view('course/edit',['data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $data=$request->except('_token');
        //单文件上传

        $res=CourseModel::where('id',$id)->update($data);
        return redirect('/course/list');
    }
}