<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    private $expense_category;

    public function __construct()
    {
        $this->expense_category = new ExpenseCategory();
    }

    public function Index(Request $request)
    {
        try {
            if ($request->ajax()) {
                try {
                    $data =  $this->expense_category->list();
                    $datatables = DataTables::of($data['data'])
                        ->addIndexColumn()
                        ->addColumn('status',function($row){
                            $text = '';
                            if($row->status == 1)
                            {
                                $text = '<span class="badge bg-success">Active</span>';
                            }

                            return $text;
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '';
                            $btn .= '<a href="' . admin_url('expense-category/delete/' . encryptId($row->id)) . '" style="margin-right: 5px;" title="PDF">
                        <i class="fas fa-trash"  style="color: #e67265;" aria-hidden="true"></i>
                    </a>';
                            return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->setFilteredRecords($data['recordsFiltered'])
                        ->setTotalRecords($data['recordsTotal'])
                        ->skipPaging()
                        ->make(true);
                    return $datatables;
                } catch (Exception $e) {
                    report($e);
                    return response()->json(['error' => 'Something went wrong!'], 500);
                }
            }

            return view('expenses_category.list');
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Something went wrong!'], 406);
        }
    }

    public function Store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_name' => 'required|string|max:255|unique:expense_categories,category_name',
            ]);

            $this->expense_category->store();

            flash()->success('Category Added Successfully');
            return redirect(admin_url('expense-category/list'));
        } catch (Exception $ex) {
            report($ex);
            flash()->error('Something went wrong !');
            return redirect(admin_url('expense-category/list'));
        }
    }

    public function Delete(Request $request)
    {
        try{
            $id = decryptId($request->id);

            $this->expense_category->status_change($id);
            flash()->success('Category Deleted Successfully');
            return redirect(admin_url('expense-category/list'));
        }
        catch(Exception $ex)
        {
            report($ex);
            flash()->error('Something went wrong !');
            return redirect(admin_url('expense-category/list'));
        }
    }
}
