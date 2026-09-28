<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display the landing page with business profile & featured packages.
     */
    public function index(): View
    {
        $featuredPackages = Package::active()
            ->featured()
            ->take(3)
            ->get();

        $allPackages = Package::active()
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->take(6)
            ->get();

        $categories = Package::active()
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('pages.home', [
            'featuredPackages' => $featuredPackages,
            'allPackages' => $allPackages,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the comprehensive catalog with filtering & sorting.
     */
    public function catalog(Request $request): View
    {
        $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'string', 'in:price_asc,price_desc,latest'],
        ]);

        $query = Package::active();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'latest' => $query->latest(),
            default => $query->orderBy('is_featured', 'desc')->orderBy('price', 'asc'),
        };

        $packages = $query->paginate(9)->withQueryString();

        $categories = Package::active()
            ->select('category')
            ->distinct()
            ->pluck('category');

        $allActivePackages = Package::active()
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug', 'category', 'price', 'image_path', 'items']);

        return view('pages.catalog', [
            'packages' => $packages,
            'categories' => $categories,
            'allActivePackages' => $allActivePackages,
            'selectedCategory' => $request->string('category')->toString(),
            'currentSearch' => $request->string('search')->toString(),
            'currentSort' => $request->string('sort')->toString(),
        ]);
    }

    /**
     * Display the package detail page with interactive calendar & specifications.
     */
    public function show(string $slug): View
    {
        $package = Package::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPackages = Package::active()
            ->where('id', '!=', $package->id)
            ->where('category', $package->category)
            ->take(3)
            ->get();

        if ($relatedPackages->count() < 3) {
            $additionalPackages = Package::active()
                ->where('id', '!=', $package->id)
                ->whereNotIn('id', $relatedPackages->pluck('id'))
                ->take(3 - $relatedPackages->count())
                ->get();

            $relatedPackages = $relatedPackages->merge($additionalPackages);
        }

        return view('pages.detail', [
            'package' => $package,
            'relatedPackages' => $relatedPackages,
        ]);
    }
}
