<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BudgetResource;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    use AuthorizesRequests;

    protected BudgetRepositoryInterface $budgets;

    public function __construct(BudgetRepositoryInterface $budgets)
    {
        $this->budgets = $budgets;
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

        $budgets = $this->budgets->get($search);
        return response()->json(BudgetResource::collection($budgets));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string', 'amount' => 'numeric', 'notes' => 'string']);
        $user = $request->user();

        $budget = $this->budgets->create(['name' => $request->name, 'amount' => $request->amount ?? 0, 'notes' => $request->notes ?? null, 'user_id' => $user->id]);
        return response()->json(new BudgetResource($budget));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $budget = $this->budgets->findByID($id);
        if (!$budget) return response()->json(['message' => 'Budget not found'], 404);
        $this->authorize('view', $budget);
        return response()->json(new BudgetResource($budget));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'string', 'amount' => 'numeric', 'notes' => 'string']);
        $budget = $this->budgets->findByID($id);
        if (!$budget) return response()->json(['message' => 'Budget not found'], 404);
        $this->authorize('update', $budget);

        $param = [];
        if ($request->name) $param["name"] = $request->name;
        if ($request->amount) $param["amount"] = $request->amount;
        if ($request->notes) $param["notes"] = $request->notes;
        $budget = $this->budgets->update($budget, $param);
        return response()->json(new BudgetResource($budget));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $budget = $this->budgets->findByID($id);
        if (!$budget) return response()->json(['message' => 'Budget not found'], 404);
        $this->authorize('delete', $budget);

        $this->budgets->remove($budget);
        return response()->json(['message' => 'Budget has been deleted']);
    }

    public function restore(string $id)
    {
        $budget = $this->budgets->findTrashedByID($id);
        if (!$budget) return response()->json(['message' => 'Budget not found'], 404);
        $this->authorize('restore', $budget);

        $this->budgets->restore($budget);
        return response()->json(['message' => 'Budget has been restored']);
    }

    public function remove(string $id)
    {
        $budget = $this->budgets->findTrashedByID($id);
        if (!$budget) return response()->json(['message' => 'Budget not found'], 404);
        $this->authorize('forceDelete', $budget);

        $this->budgets->forceDelete($budget);
        return response()->json(['message' => 'Budget has been permanently removed']);
    }
}
