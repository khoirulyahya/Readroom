<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;


class PublisherController extends Controller
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
        // $publishers = Publisher::all();
        // $publishers = Publisher::with('books')->get();

        // return $publisher;
        return view('admin.publisher');
    }

    public function api()
    {
        $publishers = Publisher::all();
        //cara pertama manggil function helpers di controller
        foreach ($publishers as $key => $publisher) {
            $publisher->dateBy_helpers = convert_date($publisher->created_at);
        }

        $datatables = datatables()->of($publishers)->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.publisher.create');
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
        $messages = [
            'required' => 'Please, fill out this field!!!',
            'email' => 'Please, fill email with valid formate example@email.com!!!',
            'numeric' => 'Please, fill phone number with numeric!!!'
        ];
        //validation of required
        $this->validate($request, [
            'name'  => ['required'],
            'email'  => ['required','email'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required']
        ], $messages);

        //cara pertama
        $publisher = new Publisher;
        $publisher->name = $request->name;
        $publisher->email = $request->email;
        $publisher->phone_number = $request->phone_number;
        $publisher->address = $request->address;
        $publisher->save();

        // Publisher::create($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('publishers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function edit(Publisher $publisher)
    {
        // return view('admin.publisher.edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publisher $publisher)
    {
        //validation of required
        $this->validate($request, [
            'name'  => ['required'],
            'email'  => ['required','email'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required']
        ]);

        //cara pertama
        $publisher = Publisher::find($publisher->id);
        $publisher->name = $request->name;
        $publisher->email = $request->email;
        $publisher->phone_number = $request->phone_number;
        $publisher->address = $request->address;
        $publisher->save();

        // Publisher->update($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('publishers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect('publishers');
    }
}
