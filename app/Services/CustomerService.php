<?php
namespace App\Services;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerService {
    /**
     * Create a new class instance.
     */
    public function __construct() {
        //
    }
    public function create($data) {
        $data['uuid'] = (string) Str::uuid();
        return Customer::create($data);
    }
    public function all() {
        return Customer::all();
    }
    public function find($id) {
        return Customer::find($id);
    }
    public function update($id, $data) {
        $customer = $this->find($id);
        $customer->update($data);
        return $customer;
    }
    public function delete($id) {
        $customer = $this->find($id);
        $customer->delete();
        return $customer;
    }
}
