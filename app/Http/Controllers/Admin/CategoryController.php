<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public function fetchCategory()
    {
        $allCat = Category::select('id','name','icon','slug','post_count')->get();
        $categories = Category::latest()->paginate(12);
        return response()->json([
            'category'=>$categories,
            'allCat'=>$allCat
        ]);
    }


    public function addCategory(StoreCategoryRequest $request)
    {
        Category::create([
            'name' => $request->cat_name, 'slug' => $request->slug,
            'description' => $request->description, 'icon' => $request->icon_name,
        ]);
        return response()->json(['message' => 'Category added successfully'], 200);
    }

    public function fetchSingle(int $id){
        $category = Category::select('id','name','icon','slug','description')->where('id',$id)->first();

        if(!$category){
            return response()->json([
                'message'=>'Category not found'
            ],404);
        }

        return response()->json([
            'category'=>$category
        ],200);
    }

    public function updateCategory(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Category not found'], 404);

        $category->update([
            'name' => $request->cat_name, 'slug' => $request->slug,
            'description' => $request->description, 'icon' => $request->icon,
        ]);
        return response()->json(['message' => 'Category Updated']);
    }

    public function deleteCategory(string $id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json(['message' => 'Category not found'], 404);

        if (Post::where('category_id', $id)->exists()) {
            return response()->json(['message' => 'This category contains posts. Please move or delete those posts before deleting the category.'], 422);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
