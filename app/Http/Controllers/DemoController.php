<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $demos = Demo::All();
        // return view();

        $demos = Demo::latest()->paginate(10);

        return view('demo.index', compact('demos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('demo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Demo::create(array_merge($request->only('nip', 'nama', 'alamat')));

        return redirect()->route('demo.index')
            ->withSuccess(__('Demo created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function show(Demo $demo)
    {
        return view('demo.show', [
            'demo' => $demo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function edit(Demo $demo)
    {
        return view('demo.edit', [
            'demo' => $demo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demo $demo)
    {
        $demo->update($request->only('nip', 'nama', 'alamat'));

        return redirect()->route('demo.index')
            ->withSuccess(__('Demo updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Demo  $demo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Demo $demo)
    {
        // print_r($demo);
        // echo $demo;
        $demo->delete();

        // dd($demo->delete());

        // $query=Demo::where('id',$demo)->delete();
        // $query=Demo::find('id')->delete();
        // dd($query);

        return redirect()->route('demo.index')
            ->withSuccess(__('Demo deleted successfully.'));
    }
}
