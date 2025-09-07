<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCategoryResource;
use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryInterface;
use App\Rules\BelongsToUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionCategoryController extends Controller
{
    use AuthorizesRequests;

    protected TransactionCategoryRepositoryInterface $categories;

    public function __construct(TransactionCategoryRepositoryInterface $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['key' => 'string']);
        $user = $request->user();

        $search = [];
        if (!$request->key) {
            $search[] = ['name', 'like', "%$request->key%"];
        }
        $search[] = ['user_id', '=', $user->id];

        $categories = $this->categories->get($search);
        return response()->json(TransactionCategoryResource::collection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'parent' => ['numeric', 'nullable', new BelongsToUser(TransactionCategory::class)], 'type' => ['required', Rule::in(TransactionCategory::TYPES)], 'icon' => 'string', 'color' => 'string']);
        $user = $request->user();

        $sortOrder = $this->categories->getCurrentSortOrder($user->id);

        $category = $this->categories->create(['name' => $request->name, 'parent_id' => $request->parent ?? null, 'type' => $request->type, 'icon' => $request->icon ?? null, 'color' => $request->color ?? null, 'sort_order' => $sortOrder, 'user_id' => $user->id]);
        return response()->json(new TransactionCategoryResource($category));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categories->findByID($id);
        if (!$category) return response()->json(['message' => 'Transaction Category not found'], 404);
        $this->authorize('view', $category);

        return response()->json(new TransactionCategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'string|max:255', 'parent' => ['numeric', 'nullable', new BelongsToUser(TransactionCategory::class)], 'type' => Rule::in(TransactionCategory::TYPES), 'icon' => 'string', 'color' => 'string']);
        $category = $this->categories->findByID($id);
        if (!$category) return response()->json(['message' => 'Transaction Category not found'], 404);
        $this->authorize('update', $category);

        $param = [];
        if ($request->name) $param["name"] = $request->name;
        if ($request->parent) $param["parent_id"] = $request->parent;
        if ($request->type) $param["type"] = $request->type;
        if ($request->icon) $param["icon"] = $request->icon;
        if ($request->color) $param["color"] = $request->color;
        $category = $this->categories->update($category, $param);
        return response()->json(new TransactionCategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->categories->findByID($id);
        if (!$category) return response()->json(['message' => 'Transaction Category not found'], 404);
        $this->authorize('delete', $category);

        $this->categories->remove($category);
        return response()->json(['message' => 'Transaction Category has been deleted']);
    }

    public function restore(string $id)
    {
        $category = $this->categories->findTrashedByID($id);
        if (!$category) return response()->json(['message' => 'Transaction Category not found'], 404);
        $this->authorize('restore', $category);

        $this->categories->restore($category);
        return response()->json(['message' => 'Transaction Category has been restored']);
    }

    public function remove(string $id)
    {
        $category = $this->categories->findTrashedByID($id);
        if (!$category) return response()->json(['message' => 'Transaction Category not found'], 404);
        $this->authorize('forceDelete', $category);

        $this->categories->forceDelete($category);
        return response()->json(['message' => 'Transaction Category has been permanently removed']);
    }
}
