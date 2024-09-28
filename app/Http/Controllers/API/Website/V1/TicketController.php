<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Enums\TicketPriority;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\DepartmantCollection;
use App\Http\Resources\Website\Collections\TicketCollection;
use App\Http\Resources\Website\Resources\TicketResource;
use App\Models\Department;
use App\Models\Number;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\FunctionService;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;
use Validator;

class TicketController extends Controller
{
    private $fuctionService;
    public function __construct()
    {
        $this->fuctionService = new FunctionService();
    }

    public function index()
    {
        $tickets = Ticket::with('department')
            ->where('user_id',Auth('sanctum')->id())
            ->paginate(10)
            ->withQueryString();

        return response()->json(['tickets'=>new TicketCollection($tickets)],200);
    }

    public function info()
     {
         $departments = Department::where('status',Status::Active)->orderBy('name','asc')->get();
         $priorities = TicketPriority::getTicketPriorityList;
         return response()->json(['departments'=>new DepartmantCollection($departments),
                                  'priorities'=>$priorities],200);
     }

     public  function store(Request $request)
     {
         $validator = Validator::make(request()->all(),
             [
                 'title' => ['required', 'string', 'max:255'],
                 'priority' => ['required'],
                 'department' => ['required','exists:departments,id'],
                 'content' => ['required','string'],
                 'attach' => ['max:4096'],
             ] ,[
                 'title.required'=> 'عنوان تیکت الزامی است.',
                 'title.max'=> 'حداکثر طول مجاز عنوان تیکت 255 کارکتر می باشد.',
                 'priority.required'=> 'انتخاب اولویت الزامی است.',
                 'content.required'=> 'متن تیکت الزامی است.',
                 'attach.max'=> 'حداکثر حجم فایل مجاز 4 مگابایت می باشد.'
             ]
         );

         if ($validator->fails()) {
             return response()->json([
                 'errors' => $validator->errors(),
                 'status' => Response::HTTP_BAD_REQUEST,
             ], Response::HTTP_BAD_REQUEST);
         }

         if(in_array($request->priority,[TicketPriority::Low,TicketPriority::Medium,TicketPriority::High]))
         {
             //ایجاد شماره تیکت
             $number = Ticket::max("number");
             $number==null?$number=1000:++$number;
             $user = Auth('sanctum')->user();
             $ticket = Ticket::create([
                 'department_id' => $request->department,
                 'user_id'=> $user->id,
                 'number'=>$number,
                 'title'=>$request->title,
                 'priority'=>$request->priority,
             ]);

             if($request->file('attach'))
             {
                 $attach = $this->fuctionService->uploadFile($request->file('attach'));
             }
             else
             {
                 $attach = null;
             }

             TicketMessage::create([
                 'ticket_id' => $ticket->id,
                 'content' => $request->content,
                 'attach' => $attach,
                 'sender_type'=>'App\Models\User',
                 'sender_id' => Auth::id()
             ]);

             if(!Number::where('mobile',$user->mobile)->exists()){
                 Number::create(["firstname" => $user->firstname ,
                     "lastname" => $user->lastname,
                     "mobile" => $user->mobile]);
             }
             return response()->json(['message'=>"تیکت شما ثبت شد."],200);
         }
     }


     public function show($id)
     {
         $ticket = Ticket::with('department','messages.senderable')->find($id);
         return response()->json(['ticket'=> new TicketResource($ticket)],200);
     }
}
