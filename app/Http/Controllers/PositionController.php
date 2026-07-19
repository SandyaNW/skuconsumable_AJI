<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::all();
        return view('departments.position.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.position.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'code' => 'required',
            'position' => 'required',
            // Add any other validation rules for the input fields
        ]);
    
        // Create and save the new resource
        $resource = new Position();
        $resource->code = $validatedData['code'];
        $resource->position = $validatedData['position'];
        // Set other properties as needed
    
        $resource->save();
    
        // Optionally, you can redirect the user to a specific route or return a response
        return redirect()->route('position.index')->with('success', 'Position created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        return view('departments.position.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Find the resource by its ID
         $resource = Position::findOrFail($id);

         // Update the resource with the new data
         $resource->update($request->all());
 
         // Optionally, you can redirect the user to a specific route or return a response
         return redirect()->route('position.index')->with('success', 'position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $resource = Position::findOrFail($id);
        $resource->delete();
        return redirect()->route('position.index')->withSuccess(__('Position deleted successfully.'));
    }
}
