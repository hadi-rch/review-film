<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }
    public function index()
    {
        // $genreWithMovie=Genres::with('genresToMovie')->get();
        $genres = Genres::get();

        return response()->json([
            "message" => "Berhasil Tampil semua genre",
            "data" => $genres
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' 
        ]);

        Genres::create([
            'name' => $request->input('name')
        ]);

        return response([
            "message" => "Berhasil Tambah Genre"
        ], 201);
    }

    public function show(string $id)
    {
        $genres = Genres::with('listMovies')->find($id);

        if(!$genres){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $genres
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' 
        ]);
        $genres = Genres::find($id);
        
        if(!$genres){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $genres->name = $request->input('name');
        
        $genres->save();

        return response([
            "message" => "Berhasil melakukan update Genre id $id",
        ],201);
    }

    public function destroy(string $id)
    {
        $genres = Genres::find($id);

        if(!$genres){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $genres->delete($id);
        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ],200);
    }
}
