<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\MessageRequest;
use App\User;
use App\Message;
use App\UserMessages;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'dashboardAccess']);
    }
    public function create(){
        $users=User::where('type',"User")->paginate(20);
        return view('admin.messages.create_message',compact('users'));
    }
    public function store(MessageRequest $request){
        //dd($request->all());
     $msg=Message::create($request->all());
      foreach ($request->users as $user){
          UserMessages::create([
              'user_id'=>$user,
              'message_id'=>$msg->id
          ]);
      }
     return redirect()->back()->with(['success' => 'Message Created successfully.']);
    }// end store
}// end class