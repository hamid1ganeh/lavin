<?php

namespace App\Http\Controllers\API\Website\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\Collections\HilightCollection;
use App\Http\Resources\Website\Collections\StoryCollection;
use App\Models\Highlight;
use App\Models\Story;
use Carbon\Carbon;

class StoryController extends Controller
{
    public function highlights()
    {
        $highlights = Highlight::where('status',Status::Active)
            ->whereHas('activeStories')
            ->orderBy('created_at','desc')
            ->get();

        return response()->json(['highlights'=> new HilightCollection($highlights)],200);
    }

    public function stories(Highlight $highlight)
    {
      $stories = Story::with('image')
                ->where('highlight_id',$highlight->id)
                ->where('status',Status::Active)
                ->orderBy('created_at','desc')
                ->get();

        return response()->json(['stories'=> new StoryCollection($stories)],200);
    }

    public function  daily_stories()
    {
        $stories = Story::with('image')
            ->whereNull('highlight_id')
            ->where('status',Status::Active)
            ->where('created_at','>=',Carbon::now('+3:30')->addHours(-24))
            ->orderBy('created_at','desc')
            ->get();

        return response()->json(['stories'=> new StoryCollection($stories)],200);
    }

    public function view(Story $story)
    {
        ++$story->views;
        $story->save();
        return response()->json(['views'=> $story->views],201);
    }


}
