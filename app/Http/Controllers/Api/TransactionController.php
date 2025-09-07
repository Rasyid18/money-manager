<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    protected TransactionRepositoryInterface $transactions;

    public function __construct(TransactionRepositoryInterface $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m', 'key' => 'string']);
        $user = $request->user();
        $month = Carbon::createFromFormat('Y-m', $request->month);

        $search = $searchOr = [];
        if (!$request->key) {
            $searchOr[] = ['items', 'like', "%$request->key%"];
            $searchOr[] = ['place', 'like', "%$request->key%"];
        }
        $search[] = ['user_id', '=', $user->id];
        $search[] = ['date', '>=', $month->copy()->startOfMonth()];
        $search[] = ['date', '<=', $month->copy()->endOfMonth()];

        $transactions = $this->transactions->get($search);
        return response()->json(TransactionResource::collection($transactions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $param = [];
        $param['transaction_category_id'] = $validated['category'];
        $param['type'] = $validated['type'];
        $param['from_account_id'] = $validated['from'];
        $param['to_account_id'] = $validated['to'] ?? null;
        $param['budget_id'] = $validated['budget'] ?? null;
        $param['date'] = $validated['date'];
        $param['amount'] = $validated['amount'];
        $param['items'] = $validated['items'] ?? null;
        $param['place'] = $validated['place'] ?? null;
        $param['notes'] = $validated['notes'] ?? null;
        $param['user_id'] = $user->id;

        $transaction = $this->transactions->create($param);
        return response()->json(new TransactionResource($transaction));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = $this->transactions->findByID($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);
        $this->authorize('view', $transaction);
        return response()->json(new TransactionResource($transaction));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, string $id)
    {
        $validated = $request->validated();
        $transaction = $this->transactions->findByID($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);
        $this->authorize('update', $transaction);

        $mapping = [
            'category' => 'transaction_category_id',
            'type'     => 'type',
            'from'     => 'from_account_id',
            'to'       => 'to_account_id',
            'budget'   => 'budget_id',
            'date'     => 'date',
            'amount'   => 'amount',
            'items'    => 'items',
            'place'    => 'place',
            'notes'    => 'notes',
        ];

        $param = [];
        foreach ($mapping as $requestKey => $dbColumn) {
            if (array_key_exists($requestKey, $validated)) {
                $param[$dbColumn] = $validated[$requestKey];
            }
        }
        $transaction = $this->transactions->update($transaction, $param);
        return response()->json(new TransactionResource($transaction));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = $this->transactions->findByID($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);
        $this->authorize('delete', $transaction);

        $this->transactions->remove($transaction);
        return response()->json(['message' => 'Transaction has been deleted']);
    }

    public function restore(string $id)
    {
        $transaction = $this->transactions->findTrashedByID($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);
        $this->authorize('restore', $transaction);

        $this->transactions->restore($transaction);
        return response()->json(['message' => 'Transaction has been restored']);
    }

    public function remove(string $id)
    {
        $transaction = $this->transactions->findTrashedByID($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);
        $this->authorize('forceDelete', $transaction);

        $this->transactions->forceDelete($transaction);
        return response()->json(['message' => 'Transaction has been permanently removed']);
    }
}
