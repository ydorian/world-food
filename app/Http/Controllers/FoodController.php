<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class FoodController extends Controller {
    public function index(Request $request): JsonResponse
    {
        $category = $request->input('category');
        $tag = $request->input('tag');
        $language = $request->input('lang');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $with = $request->input('with');
        $diffTime = $request->input('diff_time');

        $query = Food::query()
            ->when($category, function ($query) use ($category) {
                return $query->whereHas('category', function ($query) use ($category) {
                    $query->where('name', $category);
                });
            })
            ->when($tag, function ($query) use ($tag) {
                return $query->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name', $tag);
                });
            })
            ->with(['category', 'tags', 'ingredients']);
//            ->withTranslations();

        $totalMeals = $query->count();
        $foods = $query->forPage($page, $perPage)->get();

        $response = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalMeals,
            'total_pages' => ceil($totalMeals / $perPage),
            'meals' => $foods->map(function ($food) {
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
