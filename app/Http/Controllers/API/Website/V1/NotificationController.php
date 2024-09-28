<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\NotificationCollection;
use App\Http\Resources\Website\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('admin')->where('status',Status::Active)->whereHas('users',function($q){
            $q->where('user_id',Auth('sanctum')->id());
        })->filter()
        ->orderBy('created_at','desc')
        ->get();

        return response()->json(['notifications'=> new NotificationCollection($notifications)],200);
    }

    public function show(Notification $notification)
    {
        return response()->json(['notification'=> new NotificationResource($notification)],200);
    }
}
