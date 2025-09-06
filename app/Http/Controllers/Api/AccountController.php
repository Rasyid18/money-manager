<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use AuthorizesRequests;

    protected AccountRepositoryInterface $accounts;

    public function __construct(AccountRepositoryInterface $accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['key' => 'string']);

        $search = [];
        if (!$request->key) {
            $search[] = ['name', 'like', "%$request->key%"];
        }

        $accounts = $this->accounts->get($search);
        return response()->json(AccountResource::collection($accounts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'parent' => 'numeric', 'starting' => 'numeric', 'notes' => 'string']);
        $user = $request->user();

        $account = $this->accounts->create(['name' => $request->name, 'parent_id' => $request->parent ?? null, 'starting_balance' => $request->starting ?? 0, 'current_balance' => $request->starting ?? 0, 'notes' => $request->notes ?? null, 'user_id' => $user->id]);
        return response()->json(new AccountResource($account));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = $this->accounts->findByID($id);
        if (!$account) return response()->json(['message' => 'Account not found'], 404);
        $this->authorize('view', $account);

        return response()->json(new AccountResource($account));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'string|max:255', 'parent' => 'numeric', 'starting' => 'numeric', 'notes' => 'string']);
        $account = $this->accounts->findByID($id);
        if (!$account) return response()->json(['message' => 'Account not found'], 404);
        $this->authorize('update', $account);

        $param = [];
        if ($request->name) $param["name"] = $request->name;
        if ($request->parent) $param["parent_id"] = $request->parent;
        if ($request->starting) $param["starting_balance"] = $request->starting;
        if ($request->notes) $param["notes"] = $request->notes;
        $account = $this->accounts->update($account, $param);
        return response()->json(new AccountResource($account));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = $this->accounts->findByID($id);
        if (!$account) return response()->json(['message' => 'Account not found'], 404);
        $this->authorize('delete', $account);

        $this->accounts->remove($account);
        return response()->json(['message' => 'Account has been deleted']);
    }

    public function restore(string $id)
    {
        $account = $this->accounts->findTrashedByID($id);
        if (!$account) return response()->json(['message' => 'Account not found'], 404);
        $this->authorize('restore', $account);

        $this->accounts->restore($account);
        return response()->json(['message' => 'Account has been restored']);
    }

    public function remove(string $id)
    {
        $account = $this->accounts->findTrashedByID($id);
        if (!$account) return response()->json(['message' => 'Account not found'], 404);
        $this->authorize('forceDelete', $account);

        $this->accounts->forceDelete($account);
        return response()->json(['message' => 'Account has been permanently removed']);
    }
}
