<?php

namespace App\Http\Controllers;

use App\Models\Cast_movies;
use Illuminate\Http\Request;

class CastMoviesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }
    public function index()
    {
        $castMovies = Cast_movies::get();

        return response([
            "message" => "Berhasil Tampil cast Movie",
            "data" => $castMovies
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'exist' => 'inputan :attribute tidak ditemukan di table users',
        ]);
        
        Cast_movies::create([
            'name' => $request->input('name'),
            'cast_id' => $request->input('cast_id'),
            'movie_id' => $request->input('movie_id')
        ]);

        return response([
            "message" => "Berhasil tambah cast Movie",
        ],201);
    }

    public function show(string $id)
    {
        $cast_movies = Cast_movies::with('movie','cast')->find($id);

        if(!$cast_movies){
            return response([
                "message" => "Data Tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Berhasil Tampil cast Movie",
            "data" => $cast_movies
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'exist' => 'inputan :attribute tidak ditemukan di table users',
        ]);

        $cast_movie = Cast_movies::find($id);

        if(!$cast_movie){
            return response([
                "message" => "data tidak ditemukan"
            ]);
        }       
        $cast_movie->name = $request->input('name');        
        $cast_movie->cast_id = $request->input('cast_id');        
        $cast_movie->movie_id = $request->input('movie_id');      
        $cast_movie->save();

        return response([
            "message" => "Berhasil Update cast Movie",
        ],201);
    }

    public function destroy(string $id)
    {
        $cast_movie = Cast_movies::find($id);
        if (!$cast_movie){
            return response([
                "message" => "data tidak ditemukan",
            ],404);
        }

        $cast_movie->delete($id);
        return response([
            "message" => "Berhasil Delete cast Movie"
        ],200);
    }
}
