<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleMovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'cover_image' => $this->cover_image,
            'author' => $this->author,
            'imdb_ratings' => $this->imdb_ratings,
            'pdf_download_link' => $this->pdf_download_link,
            'comments' => $this->comments,
            'genres' => $this->genres,
            'tags' => $this->tags
        ];
    }
}
