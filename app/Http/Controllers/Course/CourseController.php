<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\ModelCourseModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class CourseController extends Controller
{
    //管理课程
    public function index()
    {
        return view('course/index');
    }

    //查看课程
    public function add()
    {
        return view('course/add');
    }
}