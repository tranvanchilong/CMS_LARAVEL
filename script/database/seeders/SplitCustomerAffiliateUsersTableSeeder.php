<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\AffiliateUser;

class SplitCustomerAffiliateUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = Customer::get();
        foreach($customers as $customer){
            $data=[
                'user_id'=>$customer->created_by,
                'customer_id'=>$customer->id,
                'status'=>1,
            ];
            AffiliateUser::create($data);
        }
    }
}
