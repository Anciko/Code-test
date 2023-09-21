<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieRepository implements MovieRepositoryInterface
{
    public function allMovies(Request $request)
    {
        $query = Movie::orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q1) use ($request) {
                $q1->where('title', 'like', '%' . $request->search . '%');
            });
        }

        return $query->paginate(10);
    }
    public function storeMovie(Request $request, $cover_img_name, $pdf_download_link_name)
    {
        DB::beginTransaction();

        try {

            $movie = new Movie();
            $movie->title = $request->title;
            $movie->summary = $request->summary;
            $movie->cover_image = $cover_img_name;
            $movie->author = $request->author;
            $movie->imdb_ratings = $request->imdb_ratings;
            $movie->pdf_download_link = $pdf_download_link_name;

            $movie->save();

            $movie->genres()->sync($request->genres);
            $movie->tags()->sync($request->tags);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function showMovie($id)
    {
        return Movie::with('comments', 'genres', 'tags')->find($id);
    }
    public function updateMovie(Request $request, $cover_img_name, $pdf_download_link_name, $id)
    {
        DB::beginTransaction();

        try {
            $movie = Movie::find($id);
            $movie->title = $request->title;
            $movie->summary = $request->summary;
            $movie->cover_image = $cover_img_name;
            $movie->author = $request->author;
            $movie->imdb_ratings = $request->imdb_ratings;
            $movie->pdf_download_link = $pdf_download_link_name;

            $movie->update();

            $movie->genres()->sync($request->genres);
            $movie->tags()->sync($request->tags);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }
    public function destroyMovie($id)
    {
        $movie = Movie::find($id);
        $movie->delete();
    }
}
