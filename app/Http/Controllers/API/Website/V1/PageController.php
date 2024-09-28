<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\FaqCollection;
use App\Http\Resources\Website\Collections\SocialMediaCollection;
use App\Http\Resources\Website\Collections\PhoneCollection;
use App\Models\FAQ;
use App\Models\Phone;
use App\Models\Socialmedia;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function faq()
    {
        $faq = FAQ::where('display',Status::Active)->orderBy('created_at','asc')->get();
        return response()->json(['faq'=> new FaqCollection($faq)],200);
    }

    public function contact()
    {
        $socialmedias = Socialmedia::where('status',Status::Active)->get();
        $phones = Phone::where('status',Status::Active)->get();
        return response()->json(['socialmedias'=> new SocialMediaCollection($socialmedias),
                                    'phones'=> new PhoneCollection($phones)],200);
    }
}
