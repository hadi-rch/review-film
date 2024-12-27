<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }

    public function index()
    {
        $movie = Movie::get();

        return response([
            "message" => "tampil data berhasil",
            "data" => $movie
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|min:2,max:255',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter', 
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'image',
        ])->getSecurePath();
        
        $movie = new Movie;

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->poster = $uploadedFileUrl;
        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');

        $movie->save();
        
        return response()->json([
            "message" => "Data berhasil ditambahkan",
        ], 201);


    }

    public function show(string $id)
    {
        $movie = Movie::with(['genre','listCast','listReviews'])->find($id);

        if(!$movie){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Data Detail ditampilkan",
            "data" => $movie
        ],200);



    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|min:2,max:255',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required',
        ],[
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter', 
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
        ]);


        $movie = Movie::find($id);
        
        if($request->hasFile('poster')){
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'image',
            ])->getSecurePath();
            $movie->poster = $uploadedFileUrl;
        }
        
        if(!$movie){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');

        $movie->save();

        return response([
            "message" => "Data berhasil diupdate",
        ],201);
    }

    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if(!$movie){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $movie->delete();
        return response([
            "message" => "berhasil Menghapus Movie"
        ],200);
    }
}
