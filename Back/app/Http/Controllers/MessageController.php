<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $AllMessage = array(
            ['assets/img/chat-author1.png','Ronald Richards','Hello there, here is the a...','10:34PM',4],
            ['assets/img/chat-author2.png','Marvin McKinney','Hello there, here is the a...','12:34PM',3],
            ['assets/img/chat-author3.png','Bessie Cooper','Hello there, here is the a...','12:34PM',7],
            ['assets/img/chat-author4.png','Savannah Nguyen','Hello there, here is the a...','12:34PM',19],
            ['assets/img/chat-author5.png','Dianne Russell','Hello there, here is the a...','12:34PM',3],
            ['assets/img/chat-author6.png','Albert Flores','Hello there, here is the a...','12:34PM',8],
            ['assets/img/chat-author7.png','Devon Lane','Hello there, here is the a...','12:34PM',9],
            ['assets/img/chat-author8.png','Eleanor Pena','Hello there, here is the a...','12:34PM',10],
            ['assets/img/chat-author9.png','Cameron Williamson','Hello there, here is the a...','12:34PM',3],
            ['assets/img/chat-author10.png','Cameron Williamson','Hello there, here is the a...','12:34PM',12],
        );
        return view('Message',['AllMessage'=>$AllMessage]);
    }
}
