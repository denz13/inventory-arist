<?php

namespace App\Http\Controllers\categories;

use App\Http\Controllers\Controller;
use App\Models\tbl_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = tbl_category::all();
        return view('categories.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:tbl_category,category_name',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            tbl_category::create([
                'category_name' => $request->category_name,
                'status' => $request->status
            ]);

            return redirect()->route('categories.categories')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = tbl_category::findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $category
                ]);
            }
            
            return view('categories.categories', compact('category'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }
            
            return redirect()->route('categories.categories')
                ->with('error', 'Category not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:tbl_category,category_name,' . $id,
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = tbl_category::findOrFail($id);
            
            $category->update([
                'category_name' => $request->category_name,
                'status' => $request->status
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully!'
                ]);
            }

            return redirect()->route('categories.categories')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating category: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error updating category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = tbl_category::findOrFail($id);
            
            // Check if category is being used by any inventory items
            if ($category->inventories()->exists()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete category. It is being used by inventory items.'
                    ], 400);
                }
                
                return redirect()->route('categories.categories')
                    ->with('error', 'Cannot delete category. It is being used by inventory items.');
            }
            
            $category->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category deleted successfully!'
                ]);
            }

            return redirect()->route('categories.categories')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting category: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('categories.categories')
                ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}
