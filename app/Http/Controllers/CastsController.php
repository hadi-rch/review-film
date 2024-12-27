<?php

namespace App\Http\Controllers;

use App\Models\Casts;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast;

class CastsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }
    public function index()
    {
        $casts = Casts::get();

        return response([
            "message" => "Berhasil Tampil semua cast",
            "data" => $casts
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
            'age' => 'required|integer|max:110',
            'bio' => 'min:5',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' ,
            'integer' => 'inputan harus berupa angka' 
        ]);

        Casts::create([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
        ]);

        return response([
            "message" => "Berhasil tambah cast"
        ], 201);
    }

    public function show(string $id)
    {
        $dataCasts = Casts::with('listMovie')->find($id);

        if(!$dataCasts){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id : $id",
            "data" => $dataCasts
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
            'age' => 'required',
            'bio' => 'min:5',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter' 
        ]);
        $casts = Casts::find($id);
        
        if(!$casts){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $casts->name = $request->input('name');
        $casts->age = $request->input('age');
        $casts->bio = $request->input('bio');
        
        $casts->save();

        return response([
            "message" => "Berhasil melakukan update Cast id : $id",
        ],201);
    }

    public function destroy(string $id)
    {
        $casts = Casts::find($id);

        if(!$casts){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $casts->delete($id);
        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ],200);
    }
}
