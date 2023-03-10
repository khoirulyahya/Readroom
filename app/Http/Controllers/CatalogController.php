<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Class constructor, optional security.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $catalog = Catalog::all();
        $catalogs = Catalog::with('books')->get();

        // return $catalog;
        return view('admin.catalog.index', compact('catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.catalog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation of required
        $this->validate($request, [
            'name'  => ['required'],
        ]);

        //cara pertama
        // $catalog = new Catalog;
        // $catalog->name = $request->name;
        // $catalog->save();

        Catalog::create($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('catalogs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function show(Catalog $catalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function edit(Catalog $catalog)
    {
        return view('admin.catalog.edit', compact('catalog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catalog $catalog)
    {
        //validation of required
        $this->validate($request, [
            'name'  => ['required'],
        ]);

        //cara pertama
        // $catalog = new Catalog;
        // $catalog->name = $request->name;
        // $catalog->save();

        $catalog->update($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('catalogs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalog $catalog)
    {
        $catalog->delete();

        return redirect('catalogs');
    }
}
