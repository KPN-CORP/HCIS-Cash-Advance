<?php

namespace App\Imports;

use App\Models\HealthCoverage;
use App\Models\HealthPlan;
use App\Models\MasterMedical;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Exceptions\ImportDataInvalidException;
use App\Mail\MedicalNotification;

class ImportHealthCoverage implements ToModel
{
    private $batchRecords = [];

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

    public function model(array $row)
    {
        if ($row[0] == 'No' && $row[1] == 'Employee ID' && $row[2] == 'No Invoice') {
            return null;
        }

        $userId = Auth::id();
        $employeeId = Employee::where('id', $userId)->first();
        $newNoMedic = $this->generateNoMedic(); // Call the generateNoMedic() function
        $MedicType = MasterMedical::get();
        $isValidData = false;
        $expectedTypes = [];

        foreach ($MedicType as $type) {
            $expectedTypes[] = $type->name;
            if ($type->name == $row[9]) {
                $isValidData = true;
                break;
            }
        }

        if (!$isValidData) {
            $expectedTypesString = implode(", ", $expectedTypes);
            throw new ImportDataInvalidException("Value '{$row[10]}' does not match any expected Type Value ({$expectedTypesString}). Import canceled.");
        }

        if (!is_numeric($row[1])) {
            throw new ImportDataInvalidException("Invalid data format detected in column 1. Import canceled.");
        }
        if (!is_numeric($row[10])) {
            throw new ImportDataInvalidException("Invalid data format detected in column 10. Import canceled.");
        }

        if (is_numeric($row[6])) {
            $excelDate = intval($row[6]);
            $dateTime = Date::excelToDateTimeObject($excelDate);
            $formattedDate = $dateTime->format('Y-m-d');
        } else {
            $date = \DateTime::createFromFormat('d/m/Y', $row[7]);
            if (!$date) {
                throw new ImportDataInvalidException("Invalid date format detected. Import canceled.");
            }
            $formattedDate = $date->format('Y-m-d');
        }

        $healthCoverage = new HealthCoverage([
            'usage_id' => Str::uuid(),
            'employee_id' => $row[1],
            'no_medic' => $newNoMedic,
            'no_invoice' => $row[2],
            'contribution_level_code' => $row[3],
            'hospital_name' => $row[4],
            'patient_name' => $row[5],
            'disease' => $row[6],
            'date' => $formattedDate,
            'coverage_detail' => $row[8],
            'period' => $row[9],
            'medical_type' => $row[10],
            'balance' => $row[11],
            'balance_uncoverage' => '0',
            'balance_verif' => $row[11],
            'status' => 'Done',
            'submission_type' => 'F',
            'created_by' => $userId,
            'verif_by' => $employeeId->employee_id,
            'approved_by' => $employeeId->employee_id,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->performCalculations($healthCoverage);

        $this->batchRecords[] = $healthCoverage;

        return $healthCoverage;
    }

    public function afterImport()
    {
        // Group records by employee_id
        $groupedRecords = collect($this->batchRecords)->groupBy('employee_id');
        // dd($base64Image);

        // Send one email per employee with all their records
        foreach ($groupedRecords as $employeeId => $records) {
            $email = Employee::where('employee_id', $employeeId)->pluck('email')->first();

            if ($email) {
                $imagePath = public_path('images/kop.jpg');
                $imageContent = file_get_contents($imagePath);
                $base64Image = "data:image/png;base64," . base64_encode($imageContent);
                Mail::to($email)->send(new MedicalNotification(
                    $records,
                    $base64Image,
                ));
            }
        }

        // Clear the batch records
        $this->batchRecords = [];
    }

    private function performCalculations(HealthCoverage $healthCoverage)
    {
        $healthPlan = HealthPlan::where('employee_id', $healthCoverage->employee_id)
            ->where('medical_type', $healthCoverage->medical_type)
            ->first();

        if ($healthPlan) {
            $initialBalance = $healthPlan->balance;

            if ($initialBalance > 0) {
                $healthPlan->balance -= $healthCoverage->balance;
            }

            if ($initialBalance >= 0 && $healthCoverage->balance > $initialBalance) {
                $healthCoverage->balance_uncoverage = $healthCoverage->balance - $initialBalance;
            } elseif ($initialBalance < 0) {
                $healthCoverage->balance_uncoverage = $healthCoverage->balance;
            } else {
                $healthCoverage->balance_uncoverage = 0;
            }

            $healthPlan->save();
        }

        $healthCoverage->save();

        $this->calculateBalance($healthCoverage);
    }

    private function calculateBalance(HealthCoverage $healthCoverage)
    {
        $healthCoverage->save();
    }
}
