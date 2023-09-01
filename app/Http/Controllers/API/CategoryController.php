<?php

namespace App\Http\Controllers\API;


use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;




class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
    }
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'دسته بندی‌ای با آیدی موزد نظر یافت نشد'
            ]);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        } else {
            $category = new Category;
            $category->meta_title = $request->meta_title;
            $category->meta_keyword = $request->meta_keyword;
            $category->meta_description = $request->meta_description;
            $category->slug = $request->slug;
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status == true ? '1' : '0';
            if($request->hasFile('image')){
                $file=$request->file('image');
                $extension=$file->getClientOriginalExtension();
                $filename=time().'.'.$extension;
                $file->move('uploads/category/',$filename);
                $category->image='uploads/category/'.$filename;
            }
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'دسته بندی جدید اضافه شد'
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
                
            ]);
        } else {
            $category = Category::find($id);
            if ($category) {
                $category->meta_title = $request->meta_title;
                $category->meta_keyword = $request->meta_keyword;
                $category->meta_description = $request->meta_description;
                $category->slug = $request->slug;
                $category->name = $request->name;
                $category->description = $request->description;
                $category->status = $request->status == true ? '1' : '0';
                if($request->hasFile('image')){
                    $path=$category->image;
                    if(File::exists($path)){
                        File::delete($path);
                    }
                    $file=$request->file('image');
                    $extension=$file->getClientOriginalExtension();
                    $filename=time().'.'.$extension;
                    $file->move('uploads/category/',$filename);
                    $category->image='uploads/category/'.$filename;
                }
                $category->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'دسته بندی مورد نظر آپدیت شد'
                ]);
            }
            else{
                return response()->json([
                    'status' => 404,
                    'message' => 'دسته بندی‌ای با آیدی موزد نظر یافت نشد'
                ]);
            }
        }
    }
    public function destroy($id){
        $category=Category::find($id);
        if($category){
            $category->delete();
            return response()->json([
                'status'=>200,
                'message'=>'دسته بندی مورد نظر حذف شد'
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'دسته بندی‌ای باآیدی مورد نظر یافت نشد'
            ]);
        }
    }
    public function allCategory(){
        $category = Category::where('status','0')->get();
        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
    }
}
