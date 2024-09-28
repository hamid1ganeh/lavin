<?php

namespace App\Http\Resources\Website\Resources;

use App\Http\Resources\Website\Collections\ArticleCategoryCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Website\Collections\CommentCollection;
class ArticleResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'content'=>$this->content,
            'publishDateTime'=>$this->publish_date_time('thumbnail'),
            'thumbnail'=>$this->get_thumbnail('thumbnail'),
            'tags'=>$this->tags,
            'author'=>$this->autor->fullname,
            'categories'=> new ArticleCategoryCollection($this->categories),
            'comments'=> new CommentCollection($this->comments)
        ];
    }
}
