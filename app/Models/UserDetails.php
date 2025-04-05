<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = 'user_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'gross_salary',
        'remaining_salary',
    ];

    public function reduce()
    {
        $request = request();

        $details = GetDetails();
        $remaining_salary = $details->remaining_salary;

        $calculated_salary = $remaining_salary - $request->money_spent;

        return $this->where('user_id',Auth::id())->update([
            'remaining_salary' => $calculated_salary,
        ]);
        
    }

    public function addUser($id)
    {   
        $request = request();

        return $this->create([
            'user_id' => $id,
            'gross_salary' => $request->gross_salary,
            'remaining_salary' => $request->gross_salary,
        ]);
    }

    public function GetLowerFunds()
    {
        $data = $this->where('remaining_salary','<',500)->get();

        return $data;
    }
}
