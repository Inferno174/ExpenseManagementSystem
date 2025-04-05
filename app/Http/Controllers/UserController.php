<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Expenses;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Yajra\DataTables\DataTables;
use App\Mail\User\ExpenseReminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private $expenses;
    private $expense_category;
    private $users;
    private $user_details;

    public function __construct()
    {
        $this->expenses = new Expenses();
        $this->expense_category = new ExpenseCategory();
        $this->users = new User();
        $this->user_details = new UserDetails();
    }

    public function Index(Request $request)
    {
        try {
            if ($request->ajax()) {
                try {
                    $data =  $this->expenses->list();
                    $datatables = DataTables::of($data['data'])
                        ->addIndexColumn()
                        ->addColumn('action', function ($row) {
                            $btn = '';
                            $btn .= '<a href="' . admin_url('fire/hose-box-inspection/exportViewPdf/' . encryptId($row->id)) . '" style="margin-right: 5px;" title="PDF">
                        <i class="fas fa-file-pdf"  style="color: #e67265;" aria-hidden="true"></i>
                    </a>';
                            return $btn;
                        })
                        ->rawColumns(['action'])
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

            $category = $this->expense_category->GetSelected();

            $data = array(
                'category' => $category,
            );
            return view('expenses.list', $data);
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Something went wrong!'], 406);
        }
    }


    public function Store(Request $request)
    {
        try {
            $request->validate([
                'expense_category' => 'required|string',
                'money_spent' => 'required|numeric|min:0.01',
                'date' => 'required|date',
            ]);

            $this->expenses->store();
            $this->user_details->reduce();

            flash()->success('Expenses Added Successfully');
            return redirect(admin_url('home'));
        } catch (Exception $ex) {
            report($ex);
            flash()->error('Something went wrong !');
            return redirect(admin_url('home'));
        }
    }

    public function CheckRemaining(Request $request)
    {
        try {
            if ($request->ajax()) {
                $expense = $request->expense;
                $details = GetDetails();

                $remaining_salary = $details->remaining_salary;

                if ($expense > $remaining_salary) {
                    return response()->json([
                        'no_funds' => 'Expense exceeds remaining salary'
                    ]);
                }

                return response()->json([
                    'funds' => 'Available'
                ]);
            }

            return response()->json(['error' => 'Invalid request'], 400);
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }


    public function ExpenseReminder()
    {
        try {
            $data = $this->user_details->GetLowerFunds();

            foreach ($data as $details) {
                $user_id = $details->id;

                $email = getUseremail($user_id);
                $name = getUsername($user_id);

                Mail::to($email)->queue(new ExpenseReminder($name));
            }
        } catch (Exception $ex) {
            report($ex);
        }
    }

    public function BarChart(Request $request)
    {
        try {
            $user_id = Auth::id();

            if ($request->ajax()) {
                $expenses = $this->expenses->GetExpenses($user_id);

                return response()->json([
                    'success' => true,
                    'data' => $expenses
                ], 200);
            }
        } catch (Exception $ex) {
            report($ex);
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!'
            ], 500);
        }
    }

    public function LineChart(Request $request)
{
    try {
        $user_id = Auth::id();

        if ($request->ajax()) {
            $details = GetDetails();
            $gross_salary = $details->gross_salary;

            $expenses = $this->expenses->where('user_id', $user_id)
                ->orderBy('date', 'asc')
                ->get();

            $remaining = $gross_salary;
            $data = [];

            foreach ($expenses as $expense) {
                $remaining -= $expense->expense_rs;

                $data[] = [
                    'date' => $expense->date,
                    'category_name' => GetCategoryName($expense->category_id),
                    'remaining_salary' => $remaining < 0 ? 0 : $remaining, 
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'gross_salary' => $gross_salary,
            ], 200);
        }
    } catch (Exception $ex) {
        report($ex);
        return response()->json([
            'success' => false,
            'error' => 'Something went wrong!'
        ], 500);
    }
}

}
