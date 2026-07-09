<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Award;
use App\Models\Post;
use App\Models\Policy;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the dynamic homepage with V2 editorial aesthetics.
     */
    public function home()
    {
        $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->get();
        $products = Product::where('is_active', true)->with('variants')->take(4)->get();
        $awards = Award::orderBy('date', 'desc')->get();
        $posts = Post::where('is_active', true)->orderBy('published_at', 'desc')->take(3)->get();

        return view('pages.home', compact('banners', 'products', 'awards', 'posts'));
    }

    /**
     * Display the collections catalog page with filtering and sorting.
     */
    public function collections(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('order', 'asc')->get();

        $query = Product::where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tasting_notes', 'like', "%{$search}%");
            });
        }

        // Filter by cocoa intensity (low: 40-55%, medium: 60-75%, high: 80-100%)
        if ($request->filled('intensity')) {
            $intensities = $request->intensity;
            if (!is_array($intensities)) {
                $intensities = [$intensities];
            }
            $query->where(function ($q) use ($intensities) {
                foreach ($intensities as $intensity) {
                    if ($intensity === 'low') {
                        $q->orWhere('name', 'like', '%40%')
                          ->orWhere('name', 'like', '%45%')
                          ->orWhere('name', 'like', '%50%')
                          ->orWhere('name', 'like', '%55%');
                    } elseif ($intensity === 'medium') {
                        $q->orWhere('name', 'like', '%60%')
                          ->orWhere('name', 'like', '%65%')
                          ->orWhere('name', 'like', '%70%')
                          ->orWhere('name', 'like', '%75%');
                    } elseif ($intensity === 'high') {
                        $q->orWhere('name', 'like', '%80%')
                          ->orWhere('name', 'like', '%85%')
                          ->orWhere('name', 'like', '%90%')
                          ->orWhere('name', 'like', '%95%')
                          ->orWhere('name', 'like', '%100%');
                    }
                }
            });
        }

        // Sorting by price
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->select('products.*')
                    ->selectSub(function ($q) {
                        $q->select('price')->from('product_variants')
                            ->whereColumn('product_variants.product_id', 'products.id')
                            ->orderBy('price', 'asc')
                            ->limit(1);
                    }, 'min_price')
                    ->orderBy('min_price', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->select('products.*')
                    ->selectSub(function ($q) {
                        $q->select('price')->from('product_variants')
                            ->whereColumn('product_variants.product_id', 'products.id')
                            ->orderBy('price', 'asc')
                            ->limit(1);
                    }, 'min_price')
                    ->orderBy('min_price', 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with('variants')->get();
        $totalProducts = $products->count();

        return view('pages.collections', compact('categories', 'products', 'totalProducts'));
    }

    /**
     * Display the product details page.
     */
    public function productDetail($slug)
    {
        $product = Product::where('is_active', true)
            ->where('slug', $slug)
            ->with(['variants' => function ($q) {
                $q->where('is_active', true);
            }])
            ->firstOrFail();

        // Fetch related products
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with('variants')
            ->take(4)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->with('variants')
                ->take(4)
                ->get();
        }

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Display the about us (Nuestra Historia) page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the origin and process page.
     */
    public function origin()
    {
        $posts = Post::where('is_active', true)->orderBy('published_at', 'desc')->get();
        $timelineEvents = \App\Models\TimelineEvent::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('pages.origin', compact('posts', 'timelineEvents'));
    }

    /**
     * Display the contact form page.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Display the complaints book form page.
     */
    public function claimBook()
    {
        return view('pages.claim-book');
    }

    /**
     * Display the legal policies page.
     */
    public function policies()
    {
        $policies = Policy::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('pages.policies', compact('policies'));
    }
}
