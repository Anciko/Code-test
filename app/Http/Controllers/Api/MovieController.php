<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use App\Helpers\Responder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SingleMovieResource;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieController extends Controller
{

    private $movie_repository;

    public function __construct(MovieRepositoryInterface $movie_repository)
    {
        $this->movie_repository = $movie_repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $movies = $this->movie_repository->allMovies($request);

        if ($request->page > $movies->lastPage()) {
            return Responder::fail("Movies Not Found");
        }

        return Responder::success('success', MovieResource::collection($movies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'summary' => 'required',
            'author' => 'required',
            'imdb_ratings' => 'required',
            'genres' => 'required',
            'tags' => 'required'
        ]);

        if ($validator->fails()) {
            return Responder::fail('Validation error!', $validator->errors()->all(), 422);
        }

        $cover_img_name = null;
        $pdf_download_link_name = null;

        if ($request->hasFile('cover_image')) {
            $cover_img_file = $request->file('cover_image');
            $cover_img_name = time() . '-' . uniqid() . '-' . $cover_img_file->getClientOriginalName();

            Storage::disk('public')->put(
                'images/' . $cover_img_name,
                file_get_contents($cover_img_file)
            );
        }


        if ($request->hasFile('pdf_download_link')) {
            $pdf_download_link_file = $request->file('pdf_download_link');
            $pdf_download_link_name = time() . '-' . uniqid() . '-' . $pdf_download_link_file->getClientOriginalName();

            Storage::disk('public')->put(
                'pdfs/' . $pdf_download_link_name,
                file_get_contents($pdf_download_link_file)
            );
        }


        $this->movie_repository->storeMovie($request, $cover_img_name, $pdf_download_link_name);


        return Responder::success('New Movie is created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movie = $this->movie_repository->showMovie($id);
        return Responder::success('success', new SingleMovieResource($movie));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'summary' => 'required',
            'author' => 'required',
            'imdb_ratings' => 'required',
            'genres' => 'required',
            'tags' => 'required'
        ]);

        if ($validator->fails()) {
            return Responder::fail('Validation error!', $validator->errors()->all(), 422);
        }

        $movie = Movie::find($id);
        $cover_img_name = $movie->cover_image;
        $pdf_download_link_name = $movie->pdf_download_link;

        if ($request->hasFile('cover_image')) {
            $cover_img_file = $request->file('cover_image');
            $cover_img_name = time() . '-' . uniqid() . '-' . $cover_img_file->getClientOriginalName();

            Storage::disk('public')->put(
                'images/' . $cover_img_name,
                file_get_contents($cover_img_file)
            );
            Storage::disk('public')->delete('images/' . $cover_img_name);
        } else {

            $cover_img_name = null;
        }


        if ($request->hasFile('pdf_download_link')) {
            $pdf_download_link_file = $request->file('pdf_download_link');
            $pdf_download_link_name = time() . '-' . uniqid() . '-' . $pdf_download_link_file->getClientOriginalName();

            Storage::disk('public')->put(
                'pdfs/' . $pdf_download_link_name,
                file_get_contents($pdf_download_link_file)
            );
            Storage::disk('public')->delete('pdfs/' . $pdf_download_link_name);
        } else {

            $pdf_download_link_name = null;
        }
        $this->movie_repository->updateMovie($request, $cover_img_name, $pdf_download_link_name, $id);

        return Responder::success('Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // return $id;
        $this->movie_repository->destroyMovie($id);


        return Responder::success('Deleted Successfully.');
    }
}
