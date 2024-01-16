<?php

namespace App\Models\AppValidator;

use App\Models\Payment;
use Illuminate\Validation\Rule;

class PaymentValidator
{
    public function validate(Payment $payment, array $attributes)
    {
        return validator($attributes, [
            'payment_date' => [Rule::when($payment->exists, 'sometimes'), 'required', 'date:y-m-d'],
            'payment_amount' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'memo' => [Rule::when($payment->exists, 'sometimes'), 'required', 'string'],
            'payment_form_number' => [Rule::when($payment->exists, 'sometimes'), 'required', 'string'],
            'date_deposited' => [Rule::when($payment->exists, 'sometimes'), 'required', 'date:y-m-d'],
            'date_confirmed' => [Rule::when($payment->exists, 'sometimes'), 'required', 'date:y-m-d'],
            'confirmed_by' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'tenant_id' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'property_id' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'created_by' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'form_of_payment_id' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'created_by' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'payment_received' => [Rule::when($payment->exists, 'sometimes'), 'required', 'date:y-m-d'],
            'deposite_by' => [Rule::when($payment->exists, 'sometimes'), 'required', 'integer'],
            'deposite_by_type' => [Rule::when($payment->exists, 'sometimes'), 'required', 'string', Rule::in(['staff', 'tenant'])]
        ])->validate();
    }
}
