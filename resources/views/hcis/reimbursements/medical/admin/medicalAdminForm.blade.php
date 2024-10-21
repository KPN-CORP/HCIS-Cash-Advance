@extends('layouts_.vertical', ['page_title' => 'Medical'])

@section('css')
    <style>
        th {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('medical.admin') }}">{{ $parentLink }}</a></li>
                            <li class="breadcrumb-item active">{{ $link }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $link }}</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex bg-primary text-white justify-content-between">
                        <h4 class="mb-0">Medical Data - {{ $medic->no_medic }}</h4>
                        <a href="/medical/admin" type="button" class="btn-close btn-close-white" aria-label="Close"></a>
                    </div>
                    <div class="card-body">
                        <form id="medicForm" action="/medical/admin/form-update/update/{{ $medic->usage_id }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-2">
                                <div class="col-md-4 mb-2">
                                    <label for="patient_name" class="form-label">Patient Name</label>
                                    <select class="form-select form-select-sm select2" id="patient_name" name="patient_name"
                                        disabled>
                                        <option value="{{ $medic->patient_name }}" disabled selected>
                                            {{ $medic->patient_name }}</option>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="nama" class="form-label">Hospital Name</label>
                                    <input type="text" class="form-control form-control-sm bg-light" id="hospital_name"
                                        name="hospital_name" placeholder="ex: RS. Murni Teguh"
                                        value="{{ $medic->hospital_name }}" readonly>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="disease" class="form-label">Disease</label>
                                    <select class="form-select form-select-sm select2" id="disease" name="disease"
                                        disabled>
                                        <option value="" disabled selected>--- Choose Disease ---</option>
                                        @foreach ($diseases as $disease)
                                            <option value="{{ $disease->disease_name }}"
                                                {{ $disease->disease_name === $selectedDisease ? 'selected' : '' }}>
                                                {{ $disease->disease_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="keperluan" class="form-label">No. Invoice</label>
                                    <input type="text" class="form-control form-control-sm bg-light" id="no_invoice"
                                        name="no_invoice" rows="3" placeholder="Please add your invoice number ..."
                                        value="{{ $medic->no_invoice }}" readonly></input>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="medical_date" class="form-label">Medical Date</label>
                                    <input type="date" class="form-control form-control-sm bg-light" id="date"
                                        name="date" value="{{ $medic->date }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="medical_type" class="form-label">Medical Type</label>
                                    <select class="form-select form-select-sm select2" id="medical_type"
                                        name="medical_type[]" multiple disabled>
                                        {{-- <option value="" selected>--- Choose Medical Type ---</option> --}}
                                        @foreach ($medical_type as $type)
                                            <option value="{{ $type->name }}"
                                                @if ($selectedMedicalTypes->contains($type->name)) selected @endif>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- Dynamic Forms --}}
                            <div id="dynamicForms" class="row"></div>

                            <div class="row mb-2">
                                <div class="col-md-12 mt-2">
                                    <label for="" class="form-label">Detail Information</label>
                                    <textarea class="form-control form-control-sm bg-light" id="coverage_detail" name="coverage_detail" rows="3"
                                        placeholder="Please add more detail of disease ..." readonly>{{ $medic->coverage_detail }}</textarea>
                                </div>
                            </div>
                            @php
                                use Illuminate\Support\Facades\Storage;
                            @endphp
                            <input type="hidden" name="status" value="Pending" id="status">

                            <div class="d-flex justify-content-end mt-4">
                                @if (isset($medic->medical_proof) && $medic->medical_proof)
                                    <a href="{{ Storage::url($medic->medical_proof) }}" target="_blank"
                                        class="btn btn-outline-primary rounded-pill" style="margin-right: 10px;">
                                        <!-- Adjusted margin-right to "mr-3" -->
                                        View
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary rounded-pill submit-button"
                                    name="action_submit" value="Pending" id="submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/medical/medical-edit.js') }}"></script>
    <script>
        var medicalTypeData = @json($medical_type);
        var balanceMapping = @json($balanceMapping);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.submit-button').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();

                    const form = document.getElementById('medicForm');

                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    let hasInvalidCosts = false;
                    document.querySelectorAll('[name^="medical_costs["]').forEach(input => {
                        let value = input.value.replace(/\D/g,
                            ""); // Remove non-digit characters
                        let parsedValue = parseInt(value, 10) || 0;

                        if (parsedValue === 0) {
                            hasInvalidCosts = true;
                        }
                    });

                    // If invalid costs exist, show a simple alert and stop submission
                    if (hasInvalidCosts) {
                        Swal.fire({
                            title: "Invalid Medical Costs",
                            text: "Please fill value for the medical type you selected.",
                            icon: "error",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#AB2F2B",
                        });
                        return; // Prevent form submission
                    }

                    // Calculate total cost
                    let totalCost = 0;
                    document.querySelectorAll('[name^="medical_costs["]').forEach(input => {
                        let value = input.value.replace(/\D/g,
                            ""); // Remove non-digit characters
                        totalCost += parseInt(value, 10) || 0;
                    });

                    console.log('Total Cost:', totalCost); // Debug log

                    const inputSummary = `
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <tr>
                                <td text-align: left; padding: 2px;">Total Cost</td>
                                <td style="width: 10%; text-align: right; padding: 8px;">:</td>
                                <td style="width: 50%; text-align: left; padding: 8px;">Rp. <strong>${totalCost.toLocaleString('id-ID')}</strong></td>
                            </tr>
                        </table>
                    `;

                    Swal.fire({
                        title: "Do you want to submit this request?",
                        html: `You won't be able to revert this!<br><br>${inputSummary}`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#AB2F2B",
                        cancelButtonColor: "#CCCCCC",
                        confirmButtonText: "Yes, submit it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = button.name;
                            input.value = button.value;

                            form.appendChild(input);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection