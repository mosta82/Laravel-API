<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class SearchProducts
{
    /**
     * Handle product search requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // শুধুমাত্র API রিকুয়েস্ট এবং search প্যারামিটার থাকলে প্রসেস করবে
        if ($request->is('api/*') && $request->has('search')) {
            try {
                $searchTerm = trim($request->input('search'));
                
                // Validate search term
                if (strlen($searchTerm) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Search term must be at least 2 characters long',
                        'data' => []
                    ], 400);
                }

                // Perform search with icon
                $products = Product::query()
                    ->where(function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%'.$searchTerm.'%')
                              ->orWhere('description', 'like', '%'.$searchTerm.'%');
                    })
                    ->select(['id', 'name', 'description'])
                    ->limit(10)
                    ->get()
                    ->map(function($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'icon' => $product->icon // Make sure you have icon accessor in Product model
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'message' => 'Search results retrieved',
                    'data' => $products,
                    'count' => $products->count()
                ]);

            } catch (\Exception $e) {
                Log::error('Product search failed: '.$e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Internal server error',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return $next($request);
    }
}