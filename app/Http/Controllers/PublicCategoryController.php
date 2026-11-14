<?php

namespace App\Http\Controllers;

use App\Models\Category;


class PublicCategoryController extends Controller
{
    public function publicCategory(){
        $publicCat = Category::select('name', 'post_count')->get();
        return response()->json([
            'allCategories'=>$publicCat
        ],200);
    }
}
