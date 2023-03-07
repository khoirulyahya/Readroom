<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; //import use DB
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
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
        return view('admin.author');
    }

    public function api()
    {
        $authors = Author::with('books')->orderBy('created_at','DESC')->get();
        //cara pertama manggil function helpers di controller
        // foreach ($authors as $key => $author) {
        //     $author->date = convert_date($author->created_at);
        // }

        //cara kedua(dari yajra) manggil function helpers di controller
        $datatables = datatables()->of($authors)
                                    ->addColumn('dateBy_yajra', function($author) {
                                        return convert_date($author->created_at);
                                    })
                                    ->addIndexColumn();

        return $datatables->make(true);
    }

    public function print($id)
    {
        // $author = Author::all();
        // $authors = Author::with('books')->get();

        $authors = Author::select('*')
                        // ->where('authors.id', $id)
                        ->get();

        // return $authors;
        return view('admin.author.print', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //message validation
        // $messages = [
        //     'required' => 'Please, fill out this field!!!',
        //     'email' => 'Please, fill email with valid formate example@email.com!!!',
        //     'numeric' => 'Please, fill phone number with numeric!!!'
        // ];
        //validation of required
        $this->validate($request, [
            'name'  => ['required'],
            'email'  => ['required','email'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required']
        ]);

        //cara pertama
        $author = new Author;
        $author->name = $request->name;
        $author->email = $request->email;
        $author->phone_number = $request->phone_number;
        $author->address = $request->address;
        $author->save();

        // Author::create($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('authors');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $this->validate($request, [
            'name'  => ['required'],
            'email'  => ['required','email'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required']
        ]);

        //cara pertama
        $author = Author::find($author->id);
        $author->name = $request->name;
        $author->email = $request->email;
        $author->phone_number = $request->phone_number;
        $author->address = $request->address;
        $author->save();

        // Author->update($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('authors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();
    }
}
