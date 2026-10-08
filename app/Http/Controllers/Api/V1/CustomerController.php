<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller {

    public function __construct(private readonly CustomerService $customerService) {}

    public function store(CustomerRequest $request) {
        $customer = $this->customerService->create($request->validated());
        return response()->json([
            'data'    => $customer,
            'message' => 'تم إنشاء العميل بنجاح',
        ], 201);
    }
    public function update(CustomerRequest $request, Customer $customer) {
        $customer = $this->customerService->update($customer, $request->validated());
        return response()->json([
            'data'    => $customer,
            'message' => 'تم تحديث العميل بنجاح',
        ]);
    }
    public function destroy(Customer $customer) {
        $customer = $this->customerService->delete($customer);
        return response()->json([
            'message' => 'تم حذف العميل بنجاح',
        ]);
    }
    public function index() {
        Log::info('Customer Index visited');

        $customers = $this->customerService->all();
        return response()->json([
            'data'    => $customers,
            'message' => 'تم الحصول على العملاء بنجاح',
        ]);
    }
}
