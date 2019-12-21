<?php
namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WeiXinModel;

class IndexController extends Controller
{
    //商品详情
    public function detail()
    {
        return view('goods.detail');
    }
}