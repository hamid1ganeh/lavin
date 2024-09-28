<?php

namespace App\Http\Resources\Website\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\ImageCollection;
use App\Http\Resources\Website\Collections\VideoCollection;
use App\Http\Resources\Website\Collections\BranchCollection;
use App\Http\Resources\Website\Collections\DoctorCollection;
use App\Http\Resources\Website\Collections\ReviewCollection;
use App\Http\Resources\Website\Collections\ArticleCollection;
class ServiceDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'parent'=>$this->service->name,
            'price'=>$this->price,
            'porsant'=>$this->porsant,
            'point'=>$this->point,
            'breif'=>$this->breif,
            'desc'=>$this->desc,
            'reviews'=> new ReviewCollection($this->reviews()),
            'images'=> new ImageCollection($this->images),
            'videos'=> new VideoCollection($this->videos),
            'doctors'=> new DoctorCollection($this->doctors),
            'branches'=> new BranchCollection($this->activeBranches),
            'articles'=> new ArticleCollection($this->service->publishedArticles),
        ];
    }
}
