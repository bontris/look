<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyCollectionController extends Controller
{
    public function index()
    {

        $collection = array(
            ['assets/img/col-1.png','assets/img/col-2.png','assets/img/col-3.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-4.png','assets/img/col-5.png','assets/img/col-6.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-7.png','assets/img/col-8.png','assets/img/col-9.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-10.png','assets/img/col-11.png','assets/img/col-12.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-12.png','assets/img/col-13.png','assets/img/col-14.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-6.png','assets/img/col-7.png','assets/img/col-8.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-1.png','assets/img/col-2.png','assets/img/col-3.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-1.png','assets/img/col-2.png','assets/img/col-3.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-1.png','assets/img/col-2.png','assets/img/col-3.png','assets/img/market-author-1.png','Photography',32],
            ['assets/img/col-1.png','assets/img/col-2.png','assets/img/col-3.png','assets/img/market-author-1.png','Photography',32],
        );

        return view('MyCollection',['collection'=>$collection]);
    }
}
