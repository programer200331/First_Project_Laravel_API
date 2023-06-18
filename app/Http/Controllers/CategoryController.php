<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //SQL: SELECT * FROM categories
        // $data = DB::select('SELECT * FROM categories');

        //Query Builder 
        // $data = DB::table('categories')->get();

        //Eloquent
        // $data = Category::all();
        // $data = Category::where('id','>',5)->get();
        // $data = Category::simplePaginate(5);
        // $data = Category::withCount('products')->get();
        $data = Category::with('products')
                ->withCount('products')
                ->get();
    
        //Closure
        // $data = Category::with(['products'=>function($query){
        //     $query->where('price','>',300);
        // }])
        // ->withCount(['products'=>function($query){
        //     $query->where('price','>',300);
        // }])
        // ->get();

        // $data = Category::withCount('products')
        // ->has('products','>=',3)->get();

        // $data = Category::withCount(['products'=>function($query){
        //     $query->where('price','>','300');
        // }])->with(['products'=>function($query){
        //     $query->where('price','>','300');
        // }])->whereHas('products',function($query){
        //     $query->where('price','>','300');
        // },'>=',1)
        // ->get();

        // $data = Category::doesntHave('products')->get();
        // $data = Category::with('products')
        // ->whereDoesntHave('products',function($query){
        //     $query->where('price','<','300');
        // })
        // ->withCount('products')
        // ->get();

        return response()->json(['status'=>true,'message'=>'success','data'=>$data], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->all(),[
            'name'=>'required|string|min:3|max:30',
            'info'=>'required|string|max:150',
            'visible'=>'required|boolean',
        ]);

        if(!$validator->fails()){
        // SQL: 
        // INSERT INTO categories (c1, c2, c3) VALUES (v1, v2, v3);
        // $inserted = DB::insert('INSERT INTO categories (name, info, visible) VALUES (?, ?, ?)',[$request->input('name'),$request->input('name'),$request->input('name'),$request->input('info'),$request->input('visible')]);
        // return response()->json(['status'=>$inserted,'message'=> $inserted ? 'Success' : 'Failed'],$inserted ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

        // Query Builder
        // $inserted = DB::table('categories')->insert([
        //     'name' => $request->input('name'),$request->input('name'),$request->input('name'),
        //     'info' => $request->input('info'),
        //     'visible' => $request->input('visible'),
        // ]);
        // $inserted = DB::table('categories')->insert($request->all());
        // return response()->json(['status'=>$inserted,'message'=> $inserted ? 'Success' : 'Failed'],$inserted ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

        // Eloquent
        // $category = new Category();
        // $category->name = $request->input('name');
        // $category->info = $request->input('info');
        // $category->visible = $request->input('visible');
        // $saved = $category->save();
        // return response()->json(['status'=>$saved,'message'=> $saved ? 'Success' : 'Failed'], $saved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

        $category = Category::create($request->all());
        return response()->json(['status'=>!is_null($category),'message'=> $category ? 'Success' : 'Failed', 'object'=>$category], $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

        }else{
            return response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first(),],Response::HTTP_BAD_REQUEST);
        }

    } 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // SQL:
        // $category = DB::selectOne('SELECT * FROM categories Where id = ?',[$id]);
        // return response()->json(['status'=>!is_null($category),'message'=> $category ? 'Success' : 'Not Found','object'=>$category], $category ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

        // Query Builder
        // $category = DB::table('categories')->find($id,'*'); |* THE Best and Eesy *|
        // $category = DB::table('categories')->where('id','=',$id)->first(['*']);
        // return response()->json(['status'=>!is_null($category),'message'=> $category ? 'Success' : 'Not Found','object'=>$category], $category ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

        // Eloquent
        // $category = Category::where('id','=',$id)->first();
        // $category = Category::find($id);
        // $category = Category::findOrFail($id);  

        $category = Category::findOrFail($id);
        return response()->json(['status'=>true,'message'=>'Success','object'=>$category],Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator($request->all(),[
            'name'=>'required|string|min:3|max:30',
            'info'=>'required|string|max:150',
            'visible'=>'required|boolean',
        ]);

        if(!$validator->fails()){
            // SQL:
            // $updatedRowsCount = DB::update('UPDATE categories SET name = ?, info = ?, visible = ? WHERE id = ?',[$request->input('name'),$request->input('info'),$request->input('visible'),$id]);
            // return response()->json(['status'=> $updatedRowsCount == 1,'message'=> $updatedRowsCount == 1 ? 'Success' : 'Failed'], $updatedRowsCount == 1 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

            // Query Builder
            // $updatedRowsCount = DB::table('categories')->where('id','=',$id)->update($request->all());
            // return response()->json(['status'=> $updatedRowsCount == 1,'message'=> $updatedRowsCount == 1 ? 'Success' : 'Failed'], $updatedRowsCount == 1 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

            // Eloquent
            $category = Category::findOrFail($id);
            $category->name = $request->input('name');
            $category->info =  $request->input('info');
            $category->visible = $request->input('visible');
            $updated = $category->save();
            return response()->json(['status'=> $updated,'message'=> $updated ? 'Success' : 'Failed'], $updated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

        }else{
            return response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first()],Response::HTTP_BAD_REQUEST);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // SQL: DELETE FROM categories WHERE id = ?
        // $deletedRowsCount = DB::delete('DELETE FROM categories WHERE id = ?',[$id]);
        // $deleted = $deletedRowsCount == 1;
        // return response()->json(['status'=> $deleted,'message'=> $deleted ? "Deleted Successfully" : 'Deleted Failed'], $deleted ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

        // Query Builder
        // $deletedRowsCount = DB::table('categories')->where('id','=',$id)->delete();
        // $deletedRowsCount = DB::table('categories')->delete($id);

        // $deletedRowsCount = DB::table('categories')->delete();
        // $deleted = $deletedRowsCount > 0;
        
        // $deleted = $deletedRowsCount == 1;
        // return response()->json(['status'=> $deleted,'message'=> $deleted ? "Deleted Successfully" : 'Deleted Failed'], $deleted ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);


        // Eloquent
        // $deletedRowsCount = Category::destroy($id);
        // $deleted = $deletedRowsCount == 1;

        $category = Category::findOrFail($id);
        $deleted = $category->delete();
        return response()->json(['status'=> $deleted,'message'=> $deleted ? "Deleted Successfully" : 'Deleted Failed'], $deleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

    }
}
