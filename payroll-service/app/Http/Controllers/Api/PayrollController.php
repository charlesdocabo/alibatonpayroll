<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $payrolls
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        $basicSalary = (float) $validated['basic_salary'];
        $overtimeHours = (float) ($validated['overtime_hours'] ?? 0);
        $allowances = (float) ($validated['allowances'] ?? 0);
        $incentives = (float) ($validated['incentives'] ?? 0);
        $otherDeductions = (float) ($validated['other_deductions'] ?? 0);

        // Overtime: basic salary / 22 working days / 8 hours × 1.25
        $overtimePay = ($basicSalary / 22 / 8) * 1.25 * $overtimeHours;

        // SSS
        $sssBase = min(max($basicSalary, 5000), 35000);
        $sssDeduction = $sssBase * 0.05;

        // PhilHealth
        $philhealthBase = min(max($basicSalary, 10000), 100000);
        $philhealthDeduction = $philhealthBase * 0.025;

        // Pag-IBIG
        $pagibigBase = min($basicSalary, 5000);
        $pagibigDeduction = $pagibigBase * 0.02;

        // Totals (Gross includes basic salary + overtime pay + allowances + approved incentives/bonuses)
        $grossPay = $basicSalary + $overtimePay + $allowances + $incentives;

        $totalDeductions =
            $sssDeduction +
            $philhealthDeduction +
            $pagibigDeduction +
            $otherDeductions;

        $netPay = $grossPay - $totalDeductions;

        $payroll = Payroll::create([
            'employee_id' => $validated['employee_id'],
            'basic_salary' => $basicSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_pay' => $overtimePay,
            'allowances' => $allowances,
            'incentives' => $incentives,
            'sss_deduction' => $sssDeduction,
            'philhealth_deduction' => $philhealthDeduction,
            'pagibig_deduction' => $pagibigDeduction,
            'other_deductions' => $otherDeductions,
            'gross_pay' => $grossPay,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'pay_date' => $validated['pay_date'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll created successfully.',
            'data' => $payroll
        ], 201);
    }

    public function show(Payroll $payroll)
    {
        return response()->json([
            'success' => true,
            'data' => $payroll
        ]);
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'employee_id' => 'sometimes|required|string|max:50',
            'basic_salary' => 'sometimes|required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'sometimes|required|date',
        ]);

        $basicSalary = isset($validated['basic_salary'])
            ? (float) $validated['basic_salary']
            : (float) $payroll->basic_salary;

        $overtimeHours = array_key_exists('overtime_hours', $validated)
            ? (float) ($validated['overtime_hours'] ?? 0)
            : (float) $payroll->overtime_hours;

        $allowances = array_key_exists('allowances', $validated)
            ? (float) ($validated['allowances'] ?? 0)
            : (float) $payroll->allowances;

        $incentives = array_key_exists('incentives', $validated)
            ? (float) ($validated['incentives'] ?? 0)
            : (float) ($payroll->incentives ?? 0);

        $otherDeductions = array_key_exists('other_deductions', $validated)
            ? (float) ($validated['other_deductions'] ?? 0)
            : (float) $payroll->other_deductions;

        // Recalculate overtime
        $overtimePay = ($basicSalary / 22 / 8) * 1.25 * $overtimeHours;

        // Recalculate government deductions
        $sssBase = min(max($basicSalary, 5000), 35000);
        $sssDeduction = $sssBase * 0.05;

        $philhealthBase = min(max($basicSalary, 10000), 100000);
        $philhealthDeduction = $philhealthBase * 0.025;

        $pagibigBase = min($basicSalary, 5000);
        $pagibigDeduction = $pagibigBase * 0.02;

        // Recalculate totals
        $grossPay = $basicSalary + $overtimePay + $allowances + $incentives;

        $totalDeductions =
            $sssDeduction +
            $philhealthDeduction +
            $pagibigDeduction +
            $otherDeductions;

        $netPay = $grossPay - $totalDeductions;

        $payroll->update([
            'employee_id' => $validated['employee_id'] ?? $payroll->employee_id,
            'basic_salary' => $basicSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_pay' => $overtimePay,
            'allowances' => $allowances,
            'incentives' => $incentives,
            'sss_deduction' => $sssDeduction,
            'philhealth_deduction' => $philhealthDeduction,
            'pagibig_deduction' => $pagibigDeduction,
            'other_deductions' => $otherDeductions,
            'gross_pay' => $grossPay,
            'total_deductions' => $totalDeductions,
            'net_pay' => $netPay,
            'pay_date' => $validated['pay_date'] ?? $payroll->pay_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll updated successfully.',
            'data' => $payroll->fresh()
        ]);
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payroll deleted successfully.'
        ]);
    }
}