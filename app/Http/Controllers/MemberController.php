<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
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
        return view('admin.member');
    }

    public function api(Request $request)
    {
        // $members = Member::all();

        if ($request->gender) {
            $members = Member::where('gender', $request->gender)->get();
        } else {
            $members = Member::all();
        }

         //cara pertama manggil function helpers di controller
        foreach ($members as $key => $member) {
            $member->dateBy_helpers = convert_date($member->created_at);
        }

        $datatables = datatables()->of($members)->addIndexColumn();

        return $datatables->make(true);
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
        $this->validate($request, [
            'name'  => ['required'],
            'gender'  => ['required','min:1','max:1'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required'],
            'email'  => ['required','email']
        ]);

        //cara pertama
        $member = new Member;
        $member->name = $request->name;
        $member->gender = $request->gender;
        $member->phone_number = $request->phone_number;
        $member->address = $request->address;
        $member->email = $request->email;
        $member->save();

        // Members::create($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('members');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name'  => ['required'],
            'gender'  => ['required'],
            'phone_number'  => ['required','numeric'],
            'address'  => ['required'],
            'email'  => ['required','email']
        ]);

        //cara pertama
        $member = Member::find($member->id);
        $member->name = $request->name;
        $member->gender = $request->gender;
        $member->phone_number = $request->phone_number;
        $member->address = $request->address;
        $member->email = $request->email;
        $member->save();

        // Members->update($request->all()); //cara kedua TAPI harus mendaftarkan $fillable yg isinya data yg dikirim ke file MODELNYA

        return redirect('members');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
    }
}
