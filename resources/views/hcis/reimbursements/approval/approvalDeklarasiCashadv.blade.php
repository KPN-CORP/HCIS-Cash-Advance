@extends('layouts_.vertical', ['page_title' => 'Cash Advanced'])

@section('css')
<style>
    .table {
        border-collapse: separate;
        width: 100%;
        position: relative;
        overflow: auto;
    }

    .table thead th {
        position: -webkit-sticky !important;
        /* For Safari */
        position: sticky !important;
        top: 0 !important;
        z-index: 2 !important;
        background-color: #fff !important;
        border-bottom: 2px solid #ddd !important;
        padding-right: 6px;
        box-shadow: inset 2px 0 0 #fff;
    }

    .table tbody td {
        background-color: #fff !important;
        padding-right: 10px;
        position: relative;
    }

    .table th.sticky-col-header {
        position: -webkit-sticky !important;
        /* For Safari */
        position: sticky !important;
        left: 0 !important;
        z-index: 3 !important;
        background-color: #fff !important;
        border-right: 2px solid #ddd !important;
        padding-right: 10px;
        box-shadow: inset 2px 0 0 #fff;
    }

    .table td.sticky-col {
        position: -webkit-sticky !important;
        /* For Safari */
        position: sticky !important;
        left: 0 !important;
        z-index: 1 !important;
        background-color: #fff !important;
        border-right: 2px solid #ddd !important;
        padding-right: 10px;
        box-shadow: inset 6px 0 0 #fff;
    }
</style>
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <!-- Breadcrumb Navigation -->
            <div class="col-md-6 mt-3">
                <div class="page-title-box d-flex align-items-center">
                    <ol class="breadcrumb mb-0" style="display: flex; align-items: center; padding-left: 0;">
                        <li class="breadcrumb-item" style="font-size: 25px; display: flex; align-items: center;">
                            <a href="/reimbursements" style="text-decoration: none;" class="text-primary">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item" style="font-size: 24px; display: flex; align-items: center; margin-left: 10px;">
                            {{ $parentLink }}
                        </li>
                        <li class="breadcrumb-item" style="font-size: 24px; display: flex; align-items: center; margin-left: 10px;">
                            {{ $link }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="card-title">{{ $link }}</h3>
                        <div class="input-group" style="width: 30%;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-dark-subtle"><i class="ri-search-line"></i></span>
                            </div>
                            <input type="text" name="customsearch" id="customsearch" class="form-control w-  border-dark-subtle border-left-0" placeholder="search.." aria-label="search" aria-describedby="search" >
                        </div>
                    </div>
                    @include('hcis.reimbursements.approval.navigation.navigationApproval')
                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive nowrap" id="scheduleTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th class="sticky-col-header" style="background-color: white">Cash Advance No</th>
                                    <th>Type</th>
                                    <th>Requestor</th>
                                    <th>Company</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Total CA</th>
                                    <th>Total Settlement</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ca_transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td style="background-color: white;" class="sticky-col">{{ $transaction->no_ca }}</td>
                                        @if($transaction->type_ca == 'dns')
                                            <td>Business Trip</td>
                                        @elseif($transaction->type_ca == 'ndns')
                                            <td>Non Business Trip</td>
                                        @elseif($transaction->type_ca == 'entr')
                                            <td>Entertainment</td>
                                        @endif
                                        <td>{{ $transaction->employee->fullname }}</td>
                                        <td>{{ $transaction->contribution_level_code }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->start_date)->format('d-M-y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->end_date)->format('d-M-y') }}</td>
                                        <td>Rp. {{ number_format($transaction->total_ca) }}</td>
                                        <td>Rp. {{ number_format($transaction->total_real) }}</td>
                                        <td>
                                            @if ($transaction->total_cost < 0)
                                                <span class="text-danger">Rp. -{{ number_format(abs($transaction->total_cost)) }}</span>
                                            @else
                                                <span class="text-success">Rp. {{ number_format($transaction->total_cost) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="badge rounded-pill text-bg-{{ $transaction->approval_sett == 'Approved' ? 'success' : ($transaction->approval_sett == 'Declaration' ? 'info' : ($transaction->approval_sett == 'Pending' ? 'warning' : ($transaction->approval_sett == 'Rejected' ? 'danger' : ($transaction->approval_sett == 'Draft' ? 'secondary' : 'success')))) }}"
                                                style="font-size: 12px; padding: 0.5rem 1rem;" title="Waiting Approve by: {{ isset($fullnames[$transaction->sett_id]) ? $fullnames[$transaction->sett_id] : 'Unknown Employee' }}">
                                                {{ $transaction->approval_sett }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('approval.cashadvancedFormDeklarasi', encrypt($transaction->id)) }}" class="btn btn-outline-info" title="Approve" ><i class="bi bi-card-checklist"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Periksa apakah ada pesan sukses
    var successMessage = "{{ session('success') }}";

    // Jika ada pesan sukses, tampilkan sebagai alert
    if (successMessage) {
        alert(successMessage);
    }
</script>
@endpush
