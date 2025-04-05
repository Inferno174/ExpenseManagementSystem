<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table = 'expenses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'expense_rs',
        'date',
        'created_at',
        'updated_at'
    ];

    public function list()
    {
        $request = request();

        $query = DB::table('expenses')
            ->select(
                'expenses.id',
                'expenses.expense_rs as amount',
                'expenses.created_at as date',
                'expenses.description',
                'expense_categories.category_name as category',
                'users.name as created_by'
            )
            ->join('users', 'expenses.user_id', '=', 'users.id')
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->where('expenses.user_id', Auth::id());

        if (!empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->orWhere('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('expense_categories.category_name', 'LIKE', "%{$search}%")
                    ->orWhere('expenses.description', 'LIKE', "%{$search}%")
                    ->orWhere('expenses.expense_rs', 'LIKE', "%{$search}%");
            });
        }


        if ($request->filled('category')) {
            $query->where('expenses.category_id', $request->category);
        }


        if ($request->filled('expense_rs')) {
            $query->where('expenses.expense_rs', $request->expense_rs);
        }


        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('expenses.created_at', [$request->from, $request->to]);
        }


        if (isset($request->order)) {
            $columnIndex = $request->order[0]['column'];
            $columnOrder = $request->order[0]['dir'];

            $columns = [
                0 => 'expenses.id',
                1 => 'expenses.description',
                2 => 'expense_categories.category_name',
                3 => 'expenses.expense_rs',
                4 => 'expenses.created_at',
            ];

            if (isset($columns[$columnIndex])) {
                $query->orderBy($columns[$columnIndex], $columnOrder);
            }
        } else {
            $query->orderBy('expenses.id', 'DESC');
        }

        $totalRecords = $query->count();


        if ($request->filled('length') && $request->length != -1) {
            $query->offset($request->start)->limit($request->length);
        }

        $data = $query->get();

        return [
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
        ];
    }

    public function store()
    {
        $request = request();

        $data = array(
            'category_id' => decryptId($request->expense_category),
            'user_id' => Auth::id(),
            'expense_rs' => $request->money_spent,
            'date' => $request->date,
        );

        return $this->create($data);
    }

    public function GetExpenses($id)
    {
        $data = $this->where('user_id', $id)
            ->select('category_id', DB::raw('SUM(expense_rs) as total_expense'))
            ->groupBy('category_id')
            ->get();
        $refined_data = [];

        foreach ($data as $details) {
            $category_name = GetCategoryName($details->category_id);
            $refined_data[] = [
                'category_name' => $category_name,
                'expenses' => $details->total_expense,
            ];
        }

        return $refined_data;
    }

    public function GetMoneySpent($id)
    {
        return $this->where('user_id', $id)
            ->orderBy('date', 'asc')
            ->select('category_id', 'expense_rs', 'date')
            ->get();
    }
}
