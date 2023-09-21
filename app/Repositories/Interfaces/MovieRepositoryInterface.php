<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface MovieRepositoryInterface
{
    public function allMovies(Request $request);
    public function storeMovie(Request $request, $cover_img_name,$pdf_download_link_name);
    public function showMovie($id);
    public function updateMovie(Request $request, $cover_img_name,$pdf_download_link_name, $id);
    public function destroyMovie($id);
}
