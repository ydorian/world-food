<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class FoodController extends Controller {
    public function index(Request $request): JsonResponse
    {
        $category = $request->input('category');
        $tags = $request->input('tags');
        $language = $request->input('lang');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $with = $request->input('with');
        $diffTime = $request->input('diff_time');

        $request->validate([
            'lang' => 'required'
        ]);

        $query = Food::query()
            ->with(['category', 'ingredients'])
            ->when($category, function ($query) use ($category) {
                return $query->whereHas('category', function ($query) use ($category) {
                    $query->where('name', $category);
                });
            })
            ->when($tags, function ($query) use ($tags) {
                $flattenedTags = Arr::flatten(Arr::wrap($tags));
                return $query->whereIn('tags', $flattenedTags);
            });
//            ->withTranslations();

        $totalMeals = $query->count();
        $foods = $query->forPage($page, $perPage)->get();

        $response = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalMeals,
            'total_pages' => ceil($totalMeals / $perPage),
            'foods' => $foods->map(function ($food) {
                $food->load(['category', 'tags', 'ingredients']);
                return [
                    'id' => $food->id,
                    'category' => [
                        'id' => $food->category->id,
                        'name' => $food->category->name,
                        'slug' => $food->category->slug,
                    ],
                    'tags' => $food->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                        ];
                    }),
                    'ingredients' => $food->ingredients->map(function ($ingredient) {
                        return [
                            'id' => $ingredient->id,
                            'name' => $ingredient->name,
                            'slug' => $ingredient->slug,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($response);
    }

}
