<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use DB;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;
        if ($dept_id == 0) { // Admin
            $categories = Category::latest()->paginate(10);
        } else {
            $categories = Category::where('created_by',$user_id)->latest()->paginate(10);
        }        
        return view('categories.index', compact('categories'));
    }

    public function categorytree()
    {
        // DB::enableQueryLog();

        // $parentCategories = \App\Category::where('parent_id',0)->get();
        // $parentCategories = Category::where('parent_id',0)->get();
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;
        if ($dept_id == 0) { // Admin
            $parentCategories = Category::where('parent_id',0)->get();
        } else {
            $parentCategories = Category::where('parent_id',0)->where('created_by',$user_id)->get();
            // $parentCategories = Category::where('created_by',$user_id)->get();
        }

        // $parentCategories = DB::getQueryLog();
        // dd($parentCategories); exit();

        return view('categories.categoryTreeview', compact('parentCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::all();
        $user_id = auth()->user()->id;
        $dept_id = auth()->user()->dept_id;
        if ($dept_id == 0) { // Admin
            $categories = Category::all();
        } else {
            $categories = Category::where('created_by',$user_id)->get();
        }
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parent_id = $request->category_id;
        $created_by = auth()->user()->id;
        // echo $parent_id; exit();
        // Category::create(array_merge($request->only('name', 'parent_id','created_by')));

        Category::create([
            'name' => $request->name,
            'parent_id' => $parent_id,
            'created_by' => $created_by,
        ]);

        return redirect()->route('categories.index')
            ->withSuccess(__('Category created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    public function show(Category $category)
    {
        return view('categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    public function edit(Category $category, $post)
    {
        $category = Category::findOrFail($post);
       
        return view('categories.edit', compact('category'));
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
        $validator = Validator::make($request->all(), [
            'name' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', $validator->errors()->all());
        } else {
            $selection = Category::find($id);
            
            DB::beginTransaction();
            try{
                $selection->update($request->all());
                DB::commit();
            }catch (\Throwable $th){
                // echo $th->getMessage();
                DB::rollback();
                return redirect()->back()->with('fail', "gagal edit category");
            }
              return redirect()->route('categories.index')
            ->withSuccess(__('Category updated successfully.'));
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
        $cat = Category::find($id);
        
        // deleted by
        // update kolom deleted_by dengan auth->id

        $cat->delete();
        // NEW TASK
        // Jika categoryu di-delete, maka file yg berkategorikan cat yg di-delete tersebut ikut ter-delete jg


        return redirect()->route('categories.index')
            ->withSuccess(__('Category deleted successfully.'));
    }
}
