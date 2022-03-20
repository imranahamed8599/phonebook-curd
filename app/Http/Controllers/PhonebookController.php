<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phonebook;
use Illuminate\Support\Facades\Auth;

class PhonebookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Phonebook::where('user_id','=',Auth::user()->id)->get();
        return view('home',compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add_contact_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        
        if($request->photo != null){
            $request->validate([
                'photo' => 'required|mimes:jpeg,jpg,png|max:5000',
            ]);

            $image = $request->file('photo');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('img');
            $image->move($destinationPath, $name);
            $photo = 'img/'.$name;
        }else{
            $photo = '';
        }
        
        // return $request->all();
        $status = Phonebook::create($request->except('user_id','photo') + [
            'user_id'=> Auth::user()->id,
            'photo'  => $photo
        ]);
        
        if($status){
            return redirect()->route('phonebook.index')->with('message-success','Contact added successful');
        }else{
            return redirect()->back()->with('message-danger','Something wrong !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Phonebook::find($id);       
        // return $contact; 
        return view('add_contact_form', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Phonebook::find($id);  
        
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        
        if($request->photo != null){
            $request->validate([
                'photo' => 'required|mimes:jpeg,jpg,png|max:5000',
            ]);

            $photo = $request->file('photo');
            $name = time().'.'.$photo->getClientOriginalExtension();
            $destinationPath = public_path('img');
            $photo->move($destinationPath, $name);
            $photo = 'img/'.$name;

            if($contact->photo != ''){
                unlink($contact->photo);
            }
        }else{
            $photo = $contact->photo;
        }

        $status = $contact->update($request->except('photo') + [
            'photo' => $photo
        ]);

        if($status){
            return redirect()->route('phonebook.index')->with('message-success','Contact update successful');
        }else{
            return redirect()->back()->with('message-danger','Something wrong !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Phonebook::find($id);  
        if($contact->photo != ''){
            unlink($contact->photo);
        }
        $status = $contact->delete();

        if($status){
            return redirect()->route('phonebook.index')->with('message-success','Contact delete successful');
        }else{
            return redirect()->back()->with('message-danger','Something wrong !');
        }
    }
}
