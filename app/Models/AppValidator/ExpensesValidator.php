<?php

namespace App\Models\AppValidator;

use App\Models\Expense;
use Illuminate\Validation\Rule;

class ExpensesValidator
{
    public function validate(Expense $expenses, array $attributes)
    {
        return validator(
            $attributes,
            [
                'date_paid' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'date:y-m-d'],
                'description' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'string'],
                'expense_amount' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'expenses_category_id' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'is_reimbursement_necessary' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'reimbursement_date' => [Rule::when($expenses->exists, 'sometimes'), 'date:y-m-d'],
                'paid_by' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'paid_to' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'property_id' => [Rule::when($expenses->exists, 'sometimes'), 'required', 'integer'],
                'created_by' => [Rule::when($expenses->exists, 'sometimes'), 'integer'],
            ]
        )->validate();
    }
}
