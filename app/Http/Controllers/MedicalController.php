<?php

namespace App\Http\Controllers;

use App\Models\Dependents;
use App\Models\Employee;
use App\Models\MasterDisease;
use App\Models\HealthPlan;
use App\Models\HealthCoverage;
use App\Models\MasterMedical;
use App\Models\Company;
use App\Models\Location;
use App\Models\MasterPlafond;
use App\Models\MasterBusinessUnit;
use App\Models\CATransaction;
use App\Models\BusinessTrip;
use App\Models\Hotel;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Imports\ImportHealthCoverage;
use App\Exports\MedicalExport;
use App\Exports\MedicalDetailExport;
use App\Mail\MedicalNotification;


class MedicalController extends Controller
{
    protected $permissionLocations;
    protected $permissionCompanies;
    protected $permissionGroupCompanies;

    public function medical()
    {
        $employee_id = Auth::user()->employee_id;
        $family = Dependents::orderBy('date_of_birth', 'asc')->where('employee_id', $employee_id)->get();
        $medical = HealthCoverage::orderBy('created_at', 'desc')->where('employee_id', $employee_id)->get();
        $medical_plan = HealthPlan::orderBy('period', 'desc')->where('employee_id', $employee_id)->get();
        $medicalGroup = HealthCoverage::select(
            'no_medic',
            'date',
            'period',
            'hospital_name',
            'patient_name',
            'disease',
            DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance ELSE 0 END) as maternity_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance ELSE 0 END) as inpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance ELSE 0 END) as outpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance ELSE 0 END) as glasses_total'),
            'status',
            DB::raw('MAX(created_at) as latest_created_at')

        )
            ->where('employee_id', $employee_id)
            ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        $medicalGroup->map(function ($item) {
            $item->total_per_no_medic = $item->maternity_total + $item->inpatient_total + $item->outpatient_total + $item->glasses_total;
            return $item;
        });

        $rejectMedic = HealthCoverage::where('employee_id', $employee_id)
            ->where('status', 'Rejected')
            ->get()
            ->keyBy('no_medic');

        $employeeIds = $rejectMedic->pluck('employee_id')->merge($rejectMedic->pluck('rejected_by'))->unique();

        $employees = Employee::whereIn('employee_id', $employeeIds)
            ->pluck('fullname', 'employee_id');

        $rejectMedic->transform(function ($item) use ($employees) {
            $item->employee_fullname = $employees->get($item->employee_id);
            $item->rejected_by_fullname = $employees->get($item->rejected_by);
            return $item;
        });

        $medical = $medicalGroup->map(function ($item) use ($employee_id) {
            $usageId = HealthCoverage::where('no_medic', $item->no_medic)
                ->where('employee_id', $employee_id)
                ->value('usage_id');

            $item->usage_id = $usageId;

            return $item;
        });

        $master_medical = MasterMedical::where('active', 'T')->get();

        $formatted_data = [];
        foreach ($medical_plan as $plan) {
            $formatted_data[$plan->period][$plan->medical_type] = $plan->balance;
        }

        $employees_cast = Employee::where('employee_id', $employee_id)->get();
        $currentYear = date('Y');

        foreach ($employees_cast as $employee) {
            $startDate = $employee->date_of_joining;
            $job_level = $employee->job_level;
            $endDate = date('Y-12-31');

            $startDate = date_create($startDate);
            $endDate = date_create($endDate);
            $difference = date_diff($startDate, $endDate);
            $yearsWorked = $difference->y;

            $plafond_list = MasterPlafond::where('group_name', $job_level)->get();

            if ($yearsWorked > 0) {
                foreach ($plafond_list as $plafond_lists) {
                    $existingHealthPlan = HealthPlan::where('employee_id', $employee->employee_id)
                        ->where('period', $currentYear)
                        ->where('medical_type', $plafond_lists->medical_type)
                        ->first();

                    if (!$existingHealthPlan) {
                        $newHealthPlan = HealthPlan::create([
                            'plan_id' => (string) Str::uuid(),
                            'employee_id' => $employee->employee_id,
                            'medical_type' => $plafond_lists->medical_type,
                            'balance' => $plafond_lists->balance,
                            'period' => $currentYear,
                            'created_by' => $employee_id,
                            'created_at' => now(),
                        ]);

                        if ($newHealthPlan) {
                            session()->flash('refresh', true);
                        }
                    }
                }
            } elseif ($yearsWorked == 0) {
                $startDate = date_create($employee->date_of_joining);
                $bulan_awal = date_format($startDate, "m");
                $bulan_akhir = date('m');
                $bulan = ($bulan_akhir - $bulan_awal) + 1;

                foreach ($plafond_list as $plafond_lists) {
                    $balance = 0;

                    $existingHealthPlan = HealthPlan::where('employee_id', $employee->employee_id)
                        ->where('period', $currentYear)
                        ->where('medical_type', $plafond_lists->medical_type)
                        ->first();

                    if (!$existingHealthPlan) {
                        if ($plafond_lists->medical_type == 'Maternity') {
                            $balance = $plafond_lists->balance * ($bulan / 12);
                        } elseif ($plafond_lists->medical_type == 'Inpatient') {
                            $balance = $plafond_lists->balance * ($bulan / 12);
                        } elseif ($plafond_lists->medical_type == 'Outpatient') {
                            $balance = $plafond_lists->balance * ($bulan / 12);
                        } elseif ($plafond_lists->medical_type == 'Glasses') {
                            $balance = $plafond_lists->balance * ($bulan / 12);
                        }

                        $newHealthPlan = HealthPlan::create([
                            'plan_id' => (string) Str::uuid(),
                            'employee_id' => $employee->employee_id,
                            'medical_type' => $plafond_lists->medical_type,
                            'balance' => $balance,
                            'period' => $currentYear,
                            'created_by' => $employee_id,
                            'created_at' => now(),
                        ]);

                        if ($newHealthPlan) {
                            session()->flash('refresh', true);
                        }
                    }
                }
            }
        }

        $parentLink = 'Reimbursement';
        $link = 'Medical';

        return view('hcis.reimbursements.medical.medical', compact('family', 'medical_plan', 'medical', 'parentLink', 'link', 'rejectMedic', 'employees', 'master_medical', 'formatted_data', 'medicalGroup'));
    }

    public function medicalForm()
    {
        $employee_id = Auth::user()->employee_id;
        $currentYear = now()->year;
        $families = Dependents::orderBy('date_of_birth', 'desc')->where('employee_id', $employee_id)->get();
        $medical_type = MasterMedical::orderBy('id', 'desc')->where(
            'active',
            'T'
        )->get();

        $medicalBalances = HealthPlan::where('employee_id', $employee_id)
            ->where('period', $currentYear)
            ->get();
        $balanceData = [];
        foreach ($medicalBalances as $balance) {
            // Assuming `medical_type` is a property of `HealthPlan`
            $balanceData[$balance->medical_type] = $balance->balance;
        }

        $employee_name = Employee::select('fullname')
            ->where('employee_id', $employee_id)
            ->first();

        $diseases = MasterDisease::orderBy('disease_name', 'asc')->where('active', 'T')->get();
        $parentLink = 'Medical';
        $link = 'Add Medical Coverage Usage';

        return view('hcis.reimbursements.medical.form.medicalForm', compact('diseases', 'medical_type', 'families', 'parentLink', 'link', 'employee_name', 'balanceData'));
    }

    public function medicalCreate(Request $request)
    {
        $employee_id = Auth::user()->employee_id;
        $no_medic = $this->generateNoMedic();

        $statusValue = $request->has('action_draft') ? 'Draft' : 'Pending';

        $medical_proof_path = null;
        if ($request->hasFile('medical_proof')) {
            $file = $request->file('medical_proof');
            $medical_proof_path = $file->store('public/storage/proofs');
        }

        $medical_costs = $request->input('medical_costs', []);
        $date = Carbon::parse($request->date);
        $period = $date->year;

        foreach ($medical_costs as $medical_type => $cost) {
            $cost = (int) str_replace('.', '', $cost);

            $medical_plan = HealthPlan::where('employee_id', $employee_id)
                ->where('period', $period)
                ->where('medical_type', $medical_type)
                ->first();

            if (!$medical_plan) {
                continue;
            }

            $healthCoverage = HealthCoverage::create([
                'usage_id' => (string) Str::uuid(),
                'employee_id' => $employee_id,
                'no_medic' => $no_medic,
                'no_invoice' => $request->no_invoice,
                'hospital_name' => $request->hospital_name,
                'patient_name' => $request->patient_name,
                'disease' => $request->disease,
                'date' => $date,
                'coverage_detail' => $request->coverage_detail,
                'period' => $period,
                'medical_type' => $medical_type,
                'balance' => $cost,
                'created_by' => $employee_id,
                'balance_uncoverage' => 0,
                'status' => $statusValue,
                'medical_proof' => $medical_proof_path,
            ]);
        
            $CANotificationLayer = Employee::where('employee_id', $employee_id)->pluck('email')->first();
            if ($CANotificationLayer) {
                // Kirim email ke pengguna transaksi (employee pada layer terakhir)
                Mail::to($CANotificationLayer)->send(new MedicalNotification( $healthCoverage));
            }
        }

        return redirect()->route('medical')->with('success', 'Medical successfully added.');
    }


    public function medicalFormUpdate($id)
    {
        $employee_id = Auth::user()->employee_id;
        $currentYear = now()->year;
        // Fetch the HealthCoverage record by ID
        $medic = HealthCoverage::findOrFail($id);
        $medical_type = MasterMedical::orderBy('id', 'desc')->where(
            'active',
            'T'
        )->get();

        $medicalBalances = HealthPlan::where('employee_id', $employee_id)
            ->whereYear('period', $currentYear)
            ->get();
        $balanceData = [];
        foreach ($medicalBalances as $balance) {
            // Assuming `medical_type` is a property of `HealthPlan`
            $balanceData[$balance->medical_type] = $balance->balance;
        }

        // Find all records with the same no_medic (group of medical types)
        $medicGroup = HealthCoverage::where('no_medic', $medic->no_medic)
            ->where('employee_id', $employee_id)
            ->get();

        // Extract the medical types from medicGroup
        $selectedMedicalTypes = $medicGroup->pluck('medical_type')->unique();
        $balanceMapping = $medicGroup->pluck('balance', 'medical_type');
        $selectedDisease = $medic->disease;

        // Fetch related data as before
        $families = Dependents::orderBy('date_of_birth', 'desc')->where('employee_id', $employee_id)->get();
        $employee_name = Employee::select('fullname')->where('employee_id', $employee_id)->first();
        $diseases = MasterDisease::orderBy('disease_name', 'asc')->where('active', 'T')->get();

        $parentLink = 'Medical';
        $link = 'Edit Medical Coverage Usage';

        return view('hcis.reimbursements.medical.form.medicalEditForm', compact('selectedDisease', 'balanceMapping', 'medic', 'medical_type', 'diseases', 'families', 'parentLink', 'link', 'employee_name', 'medicGroup', 'selectedMedicalTypes', 'balanceData'));
    }

    public function medicalUpdate(Request $request, $id)
    {
        $employee_id = Auth::user()->employee_id;
        $existingMedical = HealthCoverage::where('usage_id', $id)->first();

        if (!$existingMedical) {
            return redirect()->route('medical')->with('error', 'Medical record not found.');
        }

        $no_medic = $existingMedical->no_medic;

        // Handle status value
        $statusValue = $request->has('action_draft') ? 'Draft' : 'Pending';

        // Handle medical proof file upload
        $medical_proof_path = null;
        if ($request->hasFile('medical_proof')) {
            $file = $request->file('medical_proof');
            $medical_proof_path = $file->store('public/storage/proofs');
        }

        $medical_costs = $request->input('medical_costs', []);
        $date = Carbon::parse($request->date);
        $period = $date->year;

        // Fetch all existing health coverages for this no_medic
        $existingCoverages = HealthCoverage::where('no_medic', $no_medic)->get();

        // Update common fields for all records with the same no_medic
        $commonUpdateData = [
            'no_invoice' => $request->no_invoice,
            'hospital_name' => $request->hospital_name,
            'patient_name' => $request->patient_name,
            'disease' => $request->disease,
            'date' => $date,
            'coverage_detail' => $request->coverage_detail,
            'status' => $statusValue,
            'medical_proof' => $medical_proof_path ?? $existingMedical->medical_proof,
            'created_by' => $employee_id,
            'balance_verif' => null,
            'verif_by' => null,
            'reject_info' => null,
            'rejected_at' => null,
            'rejected_by' => null,
        ];

        HealthCoverage::where('no_medic', $no_medic)->update($commonUpdateData);

        // Process each medical type
        foreach ($medical_costs as $medical_type => $cost) {
            $cost = (int) str_replace('.', '', $cost); // Clean the currency format
            Log::info("Existing cost: " . $cost);

            // Find existing coverage for this medical type
            $existingCoverage = $existingCoverages->where('medical_type', $medical_type)->first();

            if ($existingCoverage) {
                // Update balance for existing coverage
                $existingCoverage->update([
                    'balance' => $cost,
                    'balance_uncoverage' => 0,
                ]);
            } else {
                // Create new coverage for new medical type
                HealthCoverage::create(array_merge($commonUpdateData, [
                    'usage_id' => (string) Str::uuid(),
                    'employee_id' => $employee_id,
                    'no_medic' => $no_medic,
                    'period' => $period,
                    'medical_type' => $medical_type,
                    'balance' => $cost,
                    'created_by' => $employee_id,
                    'balance_uncoverage' => 0,
                    'balance_verif' => null,
                    'verif_by' => null,
                    'reject_info' => null,
                    'rejected_at' => null,
                    'rejected_by' => null,

                ]));
            }
        }
        // Remove any coverages that are no longer present in the update
        $existingCoverages->whereNotIn('medical_type', array_keys($medical_costs))->each(function ($coverage) {
            $coverage->delete();
        });

        return redirect()->route('medical')->with('success', 'Medical data successfully updated.');
    }


    public function medicalDelete($id)
    {
        $medical = HealthCoverage::findOrFail($id);
        $noMedic = $medical->no_medic; // Get the no_medic value from the record
        HealthCoverage::where('no_medic', $noMedic)->delete();

        // Redirect back with a success message
        return redirect()->route('medical')->with('success', 'Medical Draft Deleted');
    }

    public function medicalAdminTable()
    {
        // Fetch all dependents, no longer filtered by employee_id
        $family = Dependents::orderBy('date_of_birth', 'desc')->get();
        // Fetch grouped medical data for all employees
        $medicalGroup = HealthCoverage::select(
            'no_medic',
            'date',
            'period',
            'hospital_name',
            'patient_name',
            'disease',
            DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance ELSE 0 END) as maternity_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance ELSE 0 END) as inpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance ELSE 0 END) as outpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance ELSE 0 END) as glasses_total'),
            'status'
        )
            ->where('status', '!=', 'Draft')
            ->whereNull('verif_by')
            ->whereNull('balance_verif')
            ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status')
            ->orderBy('created_at', 'desc')
            ->get();

        // Add usage_id for each medical record without filtering by employee_id
        $medical = $medicalGroup->map(function ($item) {
            // Fetch the usage_id based on no_medic (for any employee)
            $usageId = HealthCoverage::where('no_medic', $item->no_medic)->value('usage_id');

            // Add usage_id to the current item
            $item->usage_id = $usageId;

            return $item;
        });

        // Fetch medical plans for all employees
        $medical_plan = HealthPlan::orderBy('period', 'desc')->get();

        $parentLink = 'Reimbursement';
        $link = 'Medical';

        return view('hcis.reimbursements.medical.admin.medicalAdmin', compact('family', 'medical_plan', 'medical', 'parentLink', 'link'));
    }


    public function medicalAdminForm($id)
    {
        // Fetch the HealthCoverage record by ID
        $medic = HealthCoverage::findOrFail($id);
        $currentYear = now()->year;
        $medical_type = MasterMedical::orderBy('id', 'desc')->get();

        // Find all records with the same no_medic (group of medical types)
        $medicGroup = HealthCoverage::where('no_medic', $medic->no_medic)
            ->get();

        // Extract the medical types from medicGroup
        $selectedMedicalTypes = $medicGroup->pluck('medical_type')->unique();
        $balanceMapping = $medicGroup->pluck('balance', 'medical_type');
        $selectedDisease = $medic->disease;

        $medicalBalances = HealthPlan::where('employee_id', $medic->employee_id)
            ->whereYear('period', $currentYear)
            ->get();
        $balanceData = [];
        foreach ($medicalBalances as $balance) {
            // Assuming `medical_type` is a property of `HealthPlan`
            $balanceData[$balance->medical_type] = $balance->balance;
        }

        // Fetch related data as before
        $families = Dependents::orderBy('date_of_birth', 'desc')->get();
        $employee_name = Employee::select('fullname')->first();
        $diseases = MasterDisease::orderBy('disease_name', 'asc')->where('active', 'T')->get();

        $parentLink = 'Medical (Admin)';
        $link = 'Medical Details';
        $key = 'key';

        return view('hcis.reimbursements.medical.admin.medicalAdminForm', compact('balanceData', 'key', 'selectedDisease', 'balanceMapping', 'medic', 'medical_type', 'diseases', 'families', 'parentLink', 'link', 'employee_name', 'medicGroup', 'selectedMedicalTypes'));
    }

    public function medicalAdminUpdate(Request $request, $id)
    {
        $employee_id = Auth::user()->employee_id;
        $existingMedical = HealthCoverage::where('usage_id', $id)->first();

        if (!$existingMedical) {
            return redirect()->route('medical.admin')->with('error', 'Medical record not found.');
        }

        $no_medic = $existingMedical->no_medic;

        // Handle medical proof file upload (if needed)
        $medical_proof_path = null;
        if ($request->hasFile('medical_proof')) {
            $file = $request->file('medical_proof');
            $medical_proof_path = $file->store('public/storage/proofs');
        }

        // Process the medical verification costs
        $medical_costs = $request->input('medical_costs', []);
        $existingCoverages = HealthCoverage::where('no_medic', $no_medic)->get();
        $medicalEmployee = HealthCoverage::where('no_medic', $no_medic)->first();

        // Update the medical proof and common fields (if needed)
        $commonUpdateData = [
            'medical_proof' => $medical_proof_path ?? $existingMedical->medical_proof,
        ];

        HealthCoverage::where('no_medic', $no_medic)->update($commonUpdateData);

        // Process each medical type and update balance_verif
        foreach ($medical_costs as $medical_type => $verif_cost) {
            $verif_cost = (int) str_replace('.', '', $verif_cost);
            $date = Carbon::parse($request->date);
            $period = $date->year;

            $medical_plan = HealthPlan::where('employee_id', $medicalEmployee->employee_id)
                ->where('period', $period)
                ->where('medical_type', $medical_type)
                ->first();

            // dd($medical_plan);

            if (!$medical_plan) {
                continue;
            }

            // Find existing coverage for this medical type
            $existingCoverage = $existingCoverages->where('medical_type', $medical_type)->first();

            if ($existingCoverage) {
                $existingCoverage->update([
                    'balance_verif' => $verif_cost,
                    'verif_by' => $employee_id,
                    'status' => 'Pending',
                ]);
                if ($medical_plan->balance < $verif_cost) {
                    $old_balance_total = $medical_plan->balance + $existingCoverage->balance; // Combine balances
                    $balance_diff = $old_balance_total - $verif_cost;
                    $balance_diff_formatted = abs($balance_diff);

                    $existingCoverage->update([
                        'balance_uncoverage' => $balance_diff_formatted,
                    ]);
                }
            } else {
                Log::info("No existing coverage found for medical_type: $medical_type");
            }
        }

        return redirect()->route('medical.confirmation')->with('success', 'Medical verification data successfully updated.');
    }


    public function medicalAdminDelete($id)
    {
        $medical = HealthCoverage::findOrFail($id);
        $noMedic = $medical->no_medic; // Get the no_medic value from the record
        HealthCoverage::where('no_medic', $noMedic)->delete();

        // Redirect back with a success message
        return redirect()->route('medical')->with('success', 'Medical Deleted');
    }

    public function medicalApproval()
    {
        // Fetch all dependents, no longer filtered by employee_id
        $user = Auth::user();
        $family = Dependents::orderBy('date_of_birth', 'desc')->get();
        $employeeId = auth()->user()->employee_id;
        $employee = Employee::where('employee_id', $employeeId)->first();

        // Check if the user has approval rights
        $hasApprovalRights = DB::table('master_bisnisunits')
            ->where('approval_medical', $employee->employee_id)
            ->where('nama_bisnis', $employee->group_company)
            ->exists();

        if ($hasApprovalRights) {
            $medicalGroup = HealthCoverage::select(
                'no_medic',
                'date',
                'period',
                'hospital_name',
                'patient_name',
                'disease',
                DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance_verif ELSE 0 END) as maternity_balance_verif'),
                DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance_verif ELSE 0 END) as inpatient_balance_verif'),
                DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance_verif ELSE 0 END) as outpatient_balance_verif'),
                DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance_verif ELSE 0 END) as glasses_balance_verif'),
                'status'
            )
                ->whereNotNull('verif_by')   // Only include records where verif_by is not null
                ->whereNotNull('balance_verif')
                ->where('status', 'Pending')
                ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            // Add usage_id for each medical record without filtering by employee_id
            $medical = $medicalGroup->map(function ($item) {
                // Fetch the usage_id based on no_medic (for any employee)
                $usageId = HealthCoverage::where('no_medic', $item->no_medic)->value('usage_id');
                $item->usage_id = $usageId;

                // Calculate total per no_medic
                $item->total_per_no_medic = $item->maternity_balance_verif + $item->inpatient_balance_verif + $item->outpatient_balance_verif + $item->glasses_balance_verif;

                return $item;
            });
        } else {
            $medical = collect(); // Empty collection if user doesn't have approval rights
        }

        $totalMDCCount = $medical->count();

        // Fetch medical plans for all employees
        $medical_plan = HealthPlan::orderBy('period', 'desc')->get();
        $master_medical = MasterMedical::where('active', 'T')->get();

        $totalMDCCount = $medical->count();

        $totalPendingCount = CATransaction::where(function ($query) use ($employeeId) {
            $query->where('status_id', $employeeId)->where('approval_status', 'Pending')
                ->orWhere('sett_id', $employeeId)->where('approval_sett', 'Pending')
                ->orWhere('extend_id', $employeeId)->where('approval_extend', 'Pending');
        })->count();

        $totalBTCount = BusinessTrip::where(function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('manager_l1_id', $user->employee_id)
                    ->whereIn('status', ['Pending L1', 'Declaration L1']);
            })->orWhere(function ($q) use ($user) {
                $q->where('manager_l2_id', $user->employee_id)
                    ->whereIn('status', ['Pending L2', 'Declaration L2']);
            });
        })->count();

        $hotelNumbers = Hotel::where('hotel_only', 'Y')
            ->where('approval_status', '!=', 'Draft')
            ->pluck('no_htl')->unique();

        // Fetch all tickets using the latestTicketIds
        $transactions_htl = Hotel::whereIn('no_htl', $hotelNumbers)
            ->with('businessTrip')
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter tickets based on manager and approval status
        $hotels = $transactions_htl->filter(function ($hotel) use ($employee) {
            // Get the employee who owns the ticket
            $ticketOwnerEmployee = Employee::where('id', $hotel->user_id)->first();

            if ($hotel->approval_status == 'Pending L1' && $ticketOwnerEmployee->manager_l1_id == $employee->employee_id) {
                return true;
            } elseif ($hotel->approval_status == 'Pending L2' && $ticketOwnerEmployee->manager_l2_id == $employee->employee_id) {
                return true;
            }
            return false;
        });

        $totalHTLCount = $hotels->count();

        $ticketNumbers = Tiket::where('tkt_only', 'Y')
            ->where('approval_status', '!=', 'Draft')
            ->pluck('no_tkt')->unique();
        $transactions_tkt = Tiket::whereIn('no_tkt', $ticketNumbers)
            ->with('businessTrip')
            ->orderBy('created_at', 'desc')
            ->get();
        $totalTKTCount = $transactions_tkt->filter(function ($ticket) use ($employee) {
            $ticketOwnerEmployee = Employee::where('id', $ticket->user_id)->first();
            return ($ticket->approval_status == 'Pending L1' && $ticketOwnerEmployee->manager_l1_id == $employee->employee_id) ||
                ($ticket->approval_status == 'Pending L2' && $ticketOwnerEmployee->manager_l2_id == $employee->employee_id);
        })->count();

        $parentLink = 'Reimbursement';
        $link = 'Medical Approval';

        return view('hcis.reimbursements.medical.approval.medicalApproval', compact('family', 'medical_plan', 'medical', 'parentLink', 'link', 'master_medical', 'totalBTCount', 'totalPendingCount', 'totalHTLCount', 'totalTKTCount', 'totalMDCCount'));
    }


    public function medicalApprovalForm($id)
    {
        // Fetch the HealthCoverage record by ID
        $medic = HealthCoverage::findOrFail($id);
        $currentYear = now()->year;
        $medical_type = MasterMedical::orderBy('id', 'desc')->where(
            'active',
            'T'
        )->get();

        // Find all records with the same no_medic (group of medical types)
        $medicGroup = HealthCoverage::where('no_medic', $medic->no_medic)
            ->get();

        $medicalBalances = HealthPlan::where('employee_id', $medic->employee_id)
            ->whereYear('period', $currentYear)
            ->get();

        $balanceData = [];
        foreach ($medicalBalances as $balance) {
            // Assuming `medical_type` is a property of `HealthPlan`
            $balanceData[$balance->medical_type] = $balance->balance;
        }

        // Extract the medical types from medicGroup
        $selectedMedicalTypes = $medicGroup->pluck('medical_type')->unique();
        $balanceMapping = $medicGroup->pluck('balance_verif', 'medical_type');
        $selectedDisease = $medic->disease;

        // Fetch related data as before
        $families = Dependents::orderBy('date_of_birth', 'desc')->get();
        $employee_name = Employee::select('fullname')->first();
        $diseases = MasterDisease::orderBy('disease_name', 'asc')->where('active', 'T')->get();

        $parentLink = 'Medical Approval';
        $link = 'Medical Details';

        return view('hcis.reimbursements.medical.approval.medicalApprovalDetail', compact('selectedDisease', 'balanceMapping', 'medic', 'medical_type', 'diseases', 'families', 'parentLink', 'link', 'employee_name', 'medicGroup', 'selectedMedicalTypes', 'balanceData'));
    }

    public function medicalApprovalUpdate($id, Request $request)
    {
        // Find the medical record by ID
        $employee_id = Auth::user()->employee_id;
        $medical = HealthCoverage::findOrFail($id);

        // Determine the new status based on the action
        $action = $request->input('status_approval');
        $rejectInfo = $request->input('reject_info');

        if ($action == 'Rejected') {
            $statusValue = 'Rejected';

            // Fetch all records with the same 'no_medic'
            $healthCoverages = HealthCoverage::where('no_medic', $medical->no_medic)->get();

            // Loop through each health coverage record and update accordingly
            foreach ($healthCoverages as $coverage) {
                // Update the health coverage record to reflect rejection
                $coverage->update([
                    'status' => $statusValue,
                    'reject_info' => $rejectInfo,
                    'rejected_by' => $employee_id,
                    'rejected_at' => now(),
                ]);
            }

            return redirect()->route('medical.approval')->with('success', 'Medical request rejected and balances updated.');
        } elseif ($action == 'Done') {
            $statusValue = 'Done';

            // Fetch all records with the same 'no_medic'
            $healthCoverages = HealthCoverage::where('no_medic', $medical->no_medic)->get();

            // Loop through each health coverage record and update accordingly
            foreach ($healthCoverages as $coverage) {
                $medicalType = $coverage->medical_type;
                $balanceVerif = $coverage->balance_verif;
                $employeeId = $coverage->employee_id;
                $date = Carbon::parse($request->date);
                $period = $date->year;

                // Fetch the health plan for this employee and medical type
                $healthPlan = HealthPlan::where('employee_id', $employeeId)
                    ->where('medical_type', $medicalType)
                    ->where('period', $period)
                    ->first();

                if ($healthPlan) {
                    $healthPlan->balance -= $balanceVerif;
                    $healthPlan->save();
                }
                // Update the medical record to mark it as done and store verification info
                $coverage->update([
                    'status' => $statusValue,
                    'verif_by' => Auth::user()->employee_id,
                    'approved_by' => $employee_id,
                    'approved_at' => now(),
                ]);
            }

            return redirect()->route('medical.approval')->with('success', 'Medical request approved and balances updated.');
        }
    }


    public function generateNoMedic()
    {
        $currentYear = date('y');
        // Fetch the last no_medic number
        $lastCoverage = HealthCoverage::withTrashed() // Include soft-deleted records
            ->orderBy('no_medic', 'desc')
            ->first();

        // Determine the next no_medic number
        if ($lastCoverage && substr($lastCoverage->no_medic, 2, 2) == $currentYear) {
            // Extract the last 6 digits (the sequence part) and increment it by 1
            $lastNumber = (int) substr($lastCoverage->no_medic, 4); // Extract the last 6 digits
            $nextNumber = $lastNumber + 1;
        } else {
            // If no records for this year or no records at all, start from 000001
            $nextNumber = 1;
        }

        // Format the next number as a 9-digit number starting with '6'
        $newNoMedic = 'MD' . $currentYear . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return $newNoMedic;
    }

    public function medicalAdmin(Request $request)
    {
        $parentLink = 'Reimbursement';
        $link = 'Medical Data Employee';
        $userId = Auth::id();
        $companies = Company::orderBy('contribution_level')->get();
        $locations = Location::orderBy('area')->get();

        $currentYear = date('Y');

        $query = Employee::with(['employee', 'statusReqEmployee', 'statusSettEmployee']);

        $med_employee = collect();
        $hasFilter = false;

        if (request()->get('stat') == '') {
        } else {
            if ($request->has('stat') && $request->input('stat') !== '') {
                $status = $request->input('stat');
                $query->where('work_area_code', $status);
                $hasFilter = true;
            }
        }

        if (request()->get('customsearch') == '') {
        } else {
            if ($request->has('customsearch') && $request->input('customsearch') !== '') {
                $customsearch = $request->input('customsearch');
                $query->where('fullname', 'LIKE', '%' . $customsearch . '%');
                $hasFilter = true;
            }
        }

        // Hanya jalankan query jika ada salah satu filter
        if ($hasFilter) {
            $med_employee = $query->orderBy('created_at', 'desc')->get();
        }

        $medical_plans = HealthPlan::where('period', $currentYear)->get();

        $balances = [];
        foreach ($medical_plans as $plan) {
            $balances[$plan->employee_id][$plan->medical_type] = $plan->balance;
        }

        foreach ($med_employee as $transaction) {
            $transaction->ReqName = $transaction->statusReqEmployee ? $transaction->statusReqEmployee->fullname : '';
            $transaction->settName = $transaction->statusSettEmployee ? $transaction->statusSettEmployee->fullname : '';

            $employeeMedicalPlan = $medical_plans->where('employee_id', $transaction->employee_id)->first();
            $transaction->period = $employeeMedicalPlan ? $employeeMedicalPlan->period : '-';
        }

        return view('hcis.reimbursements.medical.adminMedical', [
            'link' => $link,
            'parentLink' => $parentLink,
            'userId' => $userId,
            'med_employee' => $med_employee,
            'companies' => $companies,
            'locations' => $locations,
            'master_medical' => MasterMedical::where('active', 'T')->get(),
            'balances' => $balances, // Kirim balances ke view
        ]);
    }

    public function medicalReportAdmin(Request $request)
    {
        $parentLink = 'Reimbursement';
        $link = 'Medical Data Employee';
        $userId = Auth::id();
        $companies = Company::orderBy('contribution_level')->get();
        $locations = Location::orderBy('area')->get();
        $unit = MasterBusinessUnit::get();

        $currentYear = date('Y');

        $med_employee = collect();
        $hasFilter = false;
        $medicalGroup = [];

        $healthCoverageQuery = HealthCoverage::query();

        // Filter berdasarkan start_date dan end_date hanya untuk HealthCoverage
        if (request()->get('start_date') == '') {
        } else {
            if ($request->has(['start_date', 'end_date']) && $request->input('start_date') != '' && $request->input('end_date') != '') {
                $startDate = $request->input('start_date');
                $endDate = Carbon::parse($request->input('end_date'))->addDay();
                $healthCoverageQuery->whereBetween('created_at', [$startDate, $endDate]);
                $hasFilter = true;
            }
        }

        $medicalGroup = $healthCoverageQuery->where('status', 'Done')->get()->groupBy('employee_id');

        $query = Employee::with(['employee', 'statusReqEmployee', 'statusSettEmployee']);

        if (request()->get('stat') == '') {
        } else {
            if ($request->has('stat') && $request->input('stat') !== '') {
                $status = $request->input('stat');
                $query->where('group_company', $status);
                $hasFilter = true;
            }
        }

        if (request()->get('customsearch') == '') {
        } else {
            if ($request->has('customsearch') && $request->input('customsearch') !== '') {
                $customsearch = $request->input('customsearch');
                $query->where('fullname', 'LIKE', '%' . $customsearch . '%');
                $hasFilter = true;
            }
        }

        if ($hasFilter) {
            $med_employee = $query->orderBy('created_at', 'desc')->get();
        }

        $medical_plans = HealthPlan::where('period', $currentYear)->get();

        $balances = [];
        foreach ($medical_plans as $plan) {
            $balances[$plan->employee_id][$plan->medical_type] = $plan->balance;
        }

        foreach ($med_employee as $transaction) {
            $transaction->ReqName = $transaction->statusReqEmployee ? $transaction->statusReqEmployee->fullname : '';
            $transaction->settName = $transaction->statusSettEmployee ? $transaction->statusSettEmployee->fullname : '';

            $employeeMedicalPlan = $medical_plans->where('employee_id', $transaction->employee_id)->first();
            $transaction->period = $employeeMedicalPlan ? $employeeMedicalPlan->period : '-';

            if (isset($medicalGroup[$transaction->employee_id])) {
                $transaction->medical_coverage = $medicalGroup[$transaction->employee_id];
            }
        }

        return view('hcis.reimbursements.medical.admin.reportMedicalAdmin', [
            'link' => $link,
            'parentLink' => $parentLink,
            'userId' => $userId,
            'med_employee' => $med_employee,
            'companies' => $companies,
            'locations' => $locations,
            'master_medical' => MasterMedical::where('active', 'T')->get(),
            'balances' => $balances, // Kirim balances ke view
            'unit' => $unit,
            'medicalGroup' => $medicalGroup,
        ]);
    }

    public function medicalAdminDetail(Request $request, $key)
    {
        // Gunakan findByRouteKey untuk mendekripsi $key
        // dd($key);
        $employee_id = decrypt($key);

        // Ambil data dependents, medical, dan medical_plan berdasarkan employee_id
        $family = Dependents::orderBy('date_of_birth', 'desc')->where('employee_id', $employee_id)->get();
        $medical = HealthCoverage::orderBy('created_at', 'desc')->where('employee_id', $employee_id)->get();
        $medical_plan = HealthPlan::orderBy('period', 'desc')->where('employee_id', $employee_id)->get();
        $medicalGroup = HealthCoverage::select(
            'no_medic',
            'date',
            'period',
            'hospital_name',
            'patient_name',
            'disease',
            DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance ELSE 0 END) as maternity_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance ELSE 0 END) as inpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance ELSE 0 END) as outpatient_total'),
            DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance ELSE 0 END) as glasses_total'),
            'status',
            DB::raw('MAX(created_at) as latest_created_at')

        )
            ->where('employee_id', $employee_id)
            ->where('status', '!=', 'Draft')
            ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        $medicalGroup->map(function ($item) {
            $item->total_per_no_medic = $item->maternity_total + $item->inpatient_total + $item->outpatient_total + $item->glasses_total;
            return $item;
        });

        $rejectMedic = HealthCoverage::where('employee_id', $employee_id)
            ->where('status', 'Rejected')  // Filter for rejected status
            ->get()
            ->keyBy('no_medic');

        // Get employee IDs from both 'employee_id' and 'rejected_by'
        $employeeIds = $rejectMedic->pluck('employee_id')->merge($rejectMedic->pluck('rejected_by'))->unique();

        // Fetch employee names for those IDs
        $employees = Employee::whereIn('employee_id', $employeeIds)
            ->pluck('fullname', 'employee_id');

        // Now map the full names to the respective HealthCoverage records
        $rejectMedic->transform(function ($item) use ($employees) {
            $item->employee_fullname = $employees->get($item->employee_id);
            $item->rejected_by_fullname = $employees->get($item->rejected_by);
            return $item;
        });

        // dd($rejectMedic);
        $medical = $medicalGroup->map(function ($item) use ($employee_id) {
            // Fetch the usage_id based on no_medic
            $usageId = HealthCoverage::where('no_medic', $item->no_medic)
                ->where('employee_id', $employee_id)
                ->value('usage_id'); // Assuming there's one usage_id per no_medic

            // Add usage_id to the current item
            $item->usage_id = $usageId;

            return $item;
        });

        $master_medical = MasterMedical::where('active', 'T')->get();

        // Format data medical_plan
        $formatted_data = [];
        foreach ($medical_plan as $plan) {
            $formatted_data[$plan->period][$plan->medical_type] = $plan->balance;
        }

        $parentLink = 'Reimbursement';
        $link = 'Medical';

        // Kirim data ke view
        return view('hcis.reimbursements.medical.admin.medicalAdmin', compact('family', 'medical_plan', 'medical', 'parentLink', 'link', 'rejectMedic', 'employees', 'employee_id', 'master_medical', 'formatted_data'));
    }

    public function medicalAdminDetailForm($key)
    {
        // Gunakan findByRouteKey untuk mendekripsi $key
        $employee_id = decrypt($key);

        // $employee_id = Auth::user()->employee_id;
        $currentYear = now()->year;
        $families = Dependents::orderBy('date_of_birth', 'desc')->where('employee_id', $employee_id)->get();
        $medical_type = MasterMedical::orderBy('id', 'desc')->where(
            'active',
            'T'
        )->get();

        $medicalBalances = HealthPlan::where('employee_id', $employee_id)
            ->where('period', $currentYear)
            ->get();
        $balanceData = [];
        foreach ($medicalBalances as $balance) {
            // Assuming `medical_type` is a property of `HealthPlan`
            $balanceData[$balance->medical_type] = $balance->balance;
        }

        $employee_name = Employee::select('fullname')
            ->where('employee_id', $employee_id)
            ->first();

        $diseases = MasterDisease::orderBy('disease_name', 'asc')->where('active', 'T')->get();
        $parentLink = 'Medical';
        $link = 'Add Medical Coverage Usage';

        return view('hcis.reimbursements.medical.admin.form.medicalFormAdmin', compact('diseases', 'medical_type', 'families', 'parentLink', 'link', 'employee_name', 'balanceData', 'employee_id'));
    }

    public function medicalAdminDetailCreate(Request $request, $key)
    {
        $employee_id = decrypt($key);
        $no_medic = $this->generateNoMedic();

        $statusValue = $request->has('action_draft') ? 'Draft' : 'Pending';

        $medical_proof_path = null;
        if ($request->hasFile('medical_proof')) {
            $file = $request->file('medical_proof');
            $medical_proof_path = $file->store('public/storage/proofs');
        }

        $medical_costs = $request->input('medical_costs', []);
        $date = Carbon::parse($request->date);
        $period = $date->year;

        foreach ($medical_costs as $medical_type => $cost) {
            $cost = (int) str_replace('.', '', $cost);

            $medical_plan = HealthPlan::where('employee_id', $employee_id)
                ->where('period', $period)
                ->where('medical_type', $medical_type)
                ->first();

            if (!$medical_plan) {
                continue;
            }

            HealthCoverage::create([
                'usage_id' => (string) Str::uuid(),
                'employee_id' => $employee_id,
                'no_medic' => $no_medic,
                'no_invoice' => $request->no_invoice,
                'hospital_name' => $request->hospital_name,
                'patient_name' => $request->patient_name,
                'disease' => $request->disease,
                'date' => $date,
                'coverage_detail' => $request->coverage_detail,
                'period' => $period,
                'medical_type' => $medical_type,
                'balance' => $cost,
                'balance_verif' => $cost,
                'balance_uncoverage' => 0,
                'created_by' => Auth::user()->employee_id,
                'verif_by' => Auth::user()->employee_id,
                'status' => $statusValue,
                'medical_proof' => $medical_proof_path,
            ]);
        }

        return redirect()->route('medical.detail', ['key' => encrypt($employee_id)])->with('success', 'Medical successfully added.');
    }

    public function medicalAdminConfirmation(Request $request)
    {
        // Ambil data dependents, medical, dan medical_plan berdasarkan employee_id
        $locations = Location::orderBy('area')->get();
        $family = Dependents::orderBy('date_of_birth', 'desc')->get();
        $medical = HealthCoverage::orderBy('created_at', 'desc')->get();
        $hasFilter = false;
        $query = HealthCoverage::query();
        $medical_plan = HealthPlan::orderBy('period', 'desc')->get();

        if ($request->has('stat') && $request->input('stat') !== '') {
            $status = $request->input('stat');
            $query->whereHas('employee', function ($q) use ($status) {
                $q->where('work_area_code', $status);
            });
            $hasFilter = true;
        }

        if ($hasFilter) {
            $medicalGroup = $query->select(
                'no_medic',
                'date',
                'employee_id',
                'period',
                'hospital_name',
                'patient_name',
                'disease',
                DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance ELSE 0 END) as maternity_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance ELSE 0 END) as inpatient_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance ELSE 0 END) as outpatient_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance ELSE 0 END) as glasses_total'),
                'status',
                DB::raw('MAX(created_at) as latest_created_at')
            )
                ->where('status', '!=', 'Draft')
                ->where('status', '!=', 'Done')
                ->whereNull('verif_by')
                ->whereNull('balance_verif')
                ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status', 'employee_id')
                ->orderBy('latest_created_at', 'desc')
                ->get();
        } else {
            $medicalGroup = HealthCoverage::select(
                'no_medic',
                'date',
                'employee_id',
                'period',
                'hospital_name',
                'patient_name',
                'disease',
                DB::raw('SUM(CASE WHEN medical_type = "Maternity" THEN balance ELSE 0 END) as maternity_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Inpatient" THEN balance ELSE 0 END) as inpatient_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Outpatient" THEN balance ELSE 0 END) as outpatient_total'),
                DB::raw('SUM(CASE WHEN medical_type = "Glasses" THEN balance ELSE 0 END) as glasses_total'),
                'status',
                DB::raw('MAX(created_at) as latest_created_at')
            )
                ->where('status', '!=', 'Draft')
                ->where('status', '!=', 'Done')
                ->whereNull('verif_by')
                ->whereNull('balance_verif')
                ->groupBy('no_medic', 'date', 'period', 'hospital_name', 'patient_name', 'disease', 'status', 'employee_id')
                ->orderBy('latest_created_at', 'desc')
                ->get();
        }

        $rejectMedic = HealthCoverage::where('status', 'Rejected')  // Filter for rejected status
            ->get()
            ->keyBy('no_medic');

        // Get employee IDs from both 'employee_id' and 'rejected_by'
        $employeeIds = $rejectMedic->pluck('employee_id')->merge($rejectMedic->pluck('rejected_by'))->unique();
        $status = $request->input('stat');
        $employees = Employee::whereIn('employee_id', $employeeIds)
            ->where('work_area_code', $status)
            ->pluck('fullname', 'employee_id');

        // Now map the full names to the respective HealthCoverage records
        $rejectMedic->transform(function ($item) use ($employees) {
            $item->employee_fullname = $employees->get($item->employee_id);
            $item->rejected_by_fullname = $employees->get($item->rejected_by);
            return $item;
        });

        // dd($rejectMedic);
        $medical = $medicalGroup->map(function ($item) {
            $usageId = HealthCoverage::where('no_medic', $item->no_medic)
                ->value('usage_id');
            $item->usage_id = $usageId;
            return $item;
        });

        $medical = $medical->map(function ($item) {
            $item->total_per_no_medic = $item->maternity_total + $item->inpatient_total + $item->outpatient_total + $item->glasses_total;
            $item->employee_fullname = $item->employee->fullname ?? 'N/A';
            return $item;
        });

        $master_medical = MasterMedical::where('active', 'T')->get();

        // Format data medical_plan
        $formatted_data = [];
        foreach ($medical_plan as $plan) {
            $formatted_data[$plan->period][$plan->medical_type] = $plan->balance;
        }

        $parentLink = 'Reimbursement';
        $link = 'Medical';

        // Kirim data ke view
        return view('hcis.reimbursements.medical.admin.medicalAdmin', compact(
            'family',
            'medical_plan',
            'medical',
            'parentLink',
            'link',
            'rejectMedic',
            'employees',
            'master_medical',
            'formatted_data',
            'locations',
            'hasFilter',
            'medicalGroup'
        ));
    }

    public function importAdminExcel(Request $request)
    {
        $userId = Auth::id();

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Attempt to import data
            Excel::import(
                new ImportHealthCoverage,
                $request->file('file')
            );

            return redirect()->route('medical.report')->with('success', 'Transaction successfully added from Excel.');
        } catch (\App\Exceptions\ImportDataInvalidException $e) {
            // Catch custom exception and redirect back with error message
            return redirect()->route('medical.report')->withErrors(['import_error' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Catch any other unexpected exceptions and redirect with a generic error message
            return redirect()->route('medical.report')->withErrors(['import_error' => 'An error occurred during import. Please check the file format.']);
        }
    }


    public function exportAdminExcel(Request $request)
    {
        $stat = $request->input('stat');
        $customSearch = $request->input('customsearch');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        return Excel::download(new MedicalExport($stat, $customSearch, $start_date, $end_date), 'medical_report.xlsx');
    }

    public function exportDetailExcel($employee_id)
    {
        return Excel::download(new MedicalDetailExport($employee_id), 'medical_detail.xlsx');
    }
}
