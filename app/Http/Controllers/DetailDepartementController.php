<?php

namespace App\Http\Controllers;

use App\Models\DetailDepartement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailDepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detail_dept = DetailDepartement::all();
        return view('departments.detail_departemen.index', compact('detail_dept'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.detail_departemen.create');
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
            'name' => 'required',
            'code' => 'required',
            'departement_id' => 'required',
            // Add any other validation rules for the input fields
        ]);
    
        // Create and save the new resource
        $resource = new DetailDepartement();
        $resource->name = $validatedData['name'];
        $resource->code = $validatedData['code'];
        $resource->departement_id = $validatedData['departement_id'];
        // Set other properties as needed
    
        $resource->save();
    
        // Optionally, you can redirect the user to a specific route or return a response
        return redirect()->route('detail_departments.index')->with('success', 'Detail department created successfully');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailDepartement  $detailDepartement
     * @return \Illuminate\Http\Response
     */
    public function show(DetailDepartement $detailDepartement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailDepartement  $detailDepartement
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailDepartement $detailDepartement, $id)
    {
        $detail_dept = DetailDepartement::findOrFail($id);
        return view('departments.detail_departemen.edit', compact('detail_dept'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetailDepartement  $detailDepartement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailDepartement $detailDepartement, $id)
    {
        // Find the resource by its ID
        $resource = DetailDepartement::findOrFail($id);

        // Update the resource with the new data
        $resource->update($request->all());

        // Optionally, you can redirect the user to a specific route or return a response
        return redirect()->route('detail_departments.index')->with('success', 'detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailDepartement  $detailDepartement
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailDepartement $detailDepartement, $id)
    {
        $resource = detailDepartement::findOrFail($id);
        $resource->delete();
        return redirect()->route('detail_departments.index')->withSuccess(__('Detail Department deleted successfully.'));
    }
    
}
