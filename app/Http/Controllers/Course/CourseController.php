<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\ModelCourseModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class CourseController extends Controller
{
    public function index()
    {
        return view('course/index');
    }
}