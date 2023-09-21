<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Genre;
use App\Models\GenreMovie;
use App\Models\Movie;
use App\Models\MovieTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create();
        Movie::factory()->count(100)->create();
        Genre::factory()->count(5)->create();
        Tag::factory()->count(5)->create();
        GenreMovie::factory()->count(50)->create();
        MovieTag::factory()->count(50)->create();
        Comment::factory()->count(30)->create();
    }
}
