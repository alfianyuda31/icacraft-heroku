<?php

namespace App\Http\Controllers;

use App\Http\Requests\CraftRequest;
use App\Models\craft;
use Illuminate\Http\Request;

class CraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $craft = craft::paginate(1);
        
        return view('craft.index',[
            'craft' => $craft
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('craft.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CraftRequest $request)
    {
        $data = $request->all();

        $data['picturePath'] = $request->file('picturePath')->store('assets/craft', 'public');

        craft::create($data);

        return redirect()->route('craft.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(craft $craft)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(craft $craft)
    {
        return view('craft.edit',[
            'item' => $craft
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CraftRequest $request, craft $craft)
    {
        $data = $request->all();

        if ($request->file('picturePath')) 
        {
            $data['picturePath'] = $request->file('picturePath')->store('assets/craft', 'public');
        }

        $craft->update($data);
        return redirect()->route('craft.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(craft $craft)
    {
        $craft->delete();

        return redirect()->route('craft.index');
    }
}
