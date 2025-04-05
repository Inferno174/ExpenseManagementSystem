<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'expense_categories';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'category_name',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];


    public function list()
    {
        $request = request();

        $query = DB::table('expense_categories')
            ->select(
                'id',
                'category_name',
                'status',
                'created_at',
                'updated_at'
            )->where('status',1);


        if (!empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->orWhere('category_name', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }


        if ($request->filled('category')) {
            $query->where('category_name', 'LIKE', "%{$request->category}%");
        }


        if (isset($request->order)) {
            $columnIndex = $request->order[0]['column'];
            $columnOrder = $request->order[0]['dir'];

            $columns = [
                0 => 'id',
                1 => 'category_name',
                2 => 'status',
                3 => 'created_at',
            ];

            if (isset($columns[$columnIndex])) {
                $query->orderBy($columns[$columnIndex], $columnOrder);
            }
        } else {
            $query->orderBy('id', 'DESC');
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
            'category_name' => $request->category_name,
            'created_by' => Auth::id(),
        );

        return $this->create($data);
    }

    public function status_change($id)
    {
        return $this->where('id',$id)->update([
            'status' => 0,
        ]);
    }

    public function GetSelected()
    {
        return $this->where('status',1)->get();
    }
}


