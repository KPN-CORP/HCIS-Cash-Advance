@extends('layouts_.vertical', ['page_title' => 'Cash Advanced'])

@section('css')
    <!-- Sertakan CSS Bootstrap jika diperlukan -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta3/css/bootstrap.min.css">
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('cashadvanced') }}">{{ $parentLink }}</a></li>
                            <li class="breadcrumb-item active">{{ $link }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $link }}</h4>
                </div>
            </div>
        </div>
        <div class="d-sm-flex align-items-center justify-content-center">
            <div class="card col-md-12">
                <div class="card-header d-flex bg-white justify-content-between">
                    <p></p>
                    <h4 class="modal-title" id="viewFormEmployeeLabel">Deklarasi Cash Advance -
                        <b>"{{ $transactions->no_ca }}"</b></h4>
                    <a href="{{ route('cashadvanced') }}" type="button" class="btn btn-close"></a>
                </div>
                <div class="card-body p-0" @style('overflow-y: auto;')>
                    <div class="container-fluid">
                        <form enctype="multipart/form-data" id="scheduleForm" method="post" action="{{ route('cashadvanced.declare', encrypt($transactions->id)) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="start">Employee ID</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $employee_data->employee_id }}" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="start">Employee Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $employee_data->fullname }}" class="form-control bg-light" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="start">Unit</label>
                                    <input type="text" name="unit" id="unit" value="{{ $employee_data->unit }}"
                                        class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="start">Job Level</label>
                                    <input type="text" name="grade" id="grade"
                                        value="{{ $employee_data->job_level }}" class="form-control bg-light" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="name">Costing Company</label>
                                    <select class="form-control bg-light" id="companyFilter" disabled>
                                        <option value="">Select Company...</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->contribution_level_code }}"
                                                {{ $company->contribution_level_code == $transactions->contribution_level_code ? 'selected' : '' }}>
                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="companyFilter" value="{{ $transactions->contribution_level_code }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="name">Destination</label>
                                    <select class="form-control bg-light" id="locationFilter"
                                        onchange="toggleOthers()" disabled>
                                        <option value="">Select location...</option>
                                        <p>{{ $transactions->destination }}</p>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->area }}"
                                                {{ $location->area == $transactions->destination ? 'selected' : '' }}>
                                                {{ $location->area . ' (' . $location->company_name . ')' }}
                                            </option>
                                        @endforeach
                                        <option value="Others"
                                            {{ $transactions->destination == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <input type="hidden" name="locationFilter" value="{{ $transactions->destination }}">
                                    <br><input type="text" name="others_location" id="others_location"
                                        class="form-control" placeholder="Other Location"
                                        value="{{ $transactions->others_location }}"
                                        style="{{ $transactions->destination == 'Others' ? 'display: block;' : 'display: none;' }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label class="form-label" for="name">CA Purposes</label>
                                    <textarea name="ca_needs" id="ca_needs" class="form-control bg-light" readonly>{{ $transactions->ca_needs }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="start">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control bg-light"
                                        value="{{ $transactions->start_date }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="start">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control bg-light"
                                        value="{{ $transactions->end_date }}" readonly>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="start">Total Days</label>
                                    <div class="input-group">
                                        <input class="form-control bg-light" id="totaldays" name="totaldays"
                                            type="text" min="0" value="{{ $transactions->total_days }}"
                                            readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">days</span>
                                        </div>
                                    </div>
                                    <input class="form-control" id="perdiem" name="perdiem" type="hidden"
                                        value="{{ $perdiem->amount }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="start">CA Date Required</label>
                                    <input type="date" name="ca_required" id="ca_required" class="form-control bg-light"
                                        value="{{ $transactions->date_required }}" readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="mb-2">
                                        <label class="form-label" for="start">Declaration Estimate</label>
                                        <input type="date" name="ca_decla" id="ca_decla" class="form-control bg-light" value="{{ $transactions->declare_estimate }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="type">CA Type</label>
                                    <select name="ca_type_disabled" id="ca_type" class="form-control bg-light" disabled>
                                        <option value="">-</option>
                                        <option value="dns" {{ $transactions->type_ca == 'dns' ? 'selected' : '' }}>
                                            Business Trip
                                        </option>
                                        <option value="ndns" {{ $transactions->type_ca == 'ndns' ? 'selected' : '' }}>
                                            Non Business Trip
                                        </option>
                                        <option value="entr" {{ $transactions->type_ca == 'entr' ? 'selected' : '' }}>
                                            Entertainment
                                        </option>
                                    </select>

                                    <input type="hidden" name="ca_type" value="{{ $transactions->type_ca }}">
                                </div>
                            </div>
                            @php
                                $detailCA = json_decode($transactions->detail_ca, true) ?? [];
                                $declareCA = json_decode($transactions->declare_ca, true) ?? [];
                            @endphp
                            <script>
                                // Pass the PHP array into a JavaScript variable
                                const initialDetailCA = @json($declareCA);
                            </script>
                            <br>
                            <div class="row" id="ca_bt" style="display: none;">
                                @if ($transactions->type_ca == 'dns')
                                    <div class="col-md-12">
                                        <div class="table-responsive-sm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Cash Advanced Request</div>
                                                    <div class="card">
                                                        <div id="perdiem-card-deklarasi" class="card-body p-0">
                                                            <div class="accordion mb-3" id="accordionPerdiem">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="enter-headingOneDec">
                                                                        <button class="accordion-button @if($detailCA['detail_perdiem'][0]['start_date'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#enter-collapseOneDec" aria-expanded="@if($detailCA['detail_perdiem'][0]['start_date'] !== null) true @else false @endif" aria-controls="enter-collapseOneDec">
                                                                            Perdiem Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="enter-collapseOneDec" class="accordion-collapse @if($detailCA['detail_perdiem'][0]['start_date'] !== null) show @endif" aria-labelledby="enter-headingOne">
                                                                        <div class="accordion-body mb-3">
                                                                            <div id="form-container-bt-perdiem-deklarasi">
                                                                                @foreach ($detailCA['detail_perdiem'] as $perdiem)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Company Code</label>
                                                                                        <select class="form-control bg-light" id="companyFilter" name="company_bt_perdiem_deklarasi[]" disabled>
                                                                                            <option value="">Select Company...</option>
                                                                                            @foreach($companies as $company)
                                                                                                <option value="{{ $company->contribution_level_code }}"
                                                                                                    @if($company->contribution_level_code == $perdiem['company_code']) selected @endif>
                                                                                                    {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Location Agency</label>
                                                                                        <select class="form-control location-select" name="location_bt_perdiem_deklarasi[]" disabled>
                                                                                            <option value="">Select location...</option>
                                                                                            @foreach($locations as $location)
                                                                                                <option value="{{ $location->area }}"
                                                                                                    @if($location->area == $perdiem['location']) selected @endif>
                                                                                                    {{ $location->area." (".$location->company_name.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                            <option value="Others" @if('Others' == $perdiem['location']) selected @endif>Others</option>
                                                                                        </select>
                                                                                        <br>
                                                                                        @if ($perdiem['location'] == 'Others')
                                                                                            <input type="text" name="other_location_bt_perdiem[]" class="form-control bg-light other-location" placeholder="Other Location" value="{{$perdiem['other_location']}}" readonly>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Start Perdiem</label>
                                                                                        <input type="date" name="start_bt_perdiem_deklarasi[]" class="form-control bg-light start-perdiem" value="{{$perdiem['start_date']}}" placeholder="mm/dd/yyyy" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">End Perdiem</label>
                                                                                        <input type="date" name="end_bt_perdiem_deklarasi[]" class="form-control bg-light end-perdiem" value="{{$perdiem['end_date']}}" placeholder="mm/dd/yyyy" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Total Days</label>
                                                                                        <div class="input-group">
                                                                                            <input class="form-control bg-light total-days-perdiem" id="total_days_bt_perdiem_deklarasi[]" name="total_days_bt_perdiem[]" type="text" min="0" value="{{$perdiem['total_days']}}" readonly>
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text">days</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- HTML -->
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Amount</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control bg-light" name="nominal_bt_perdiem_deklarasi[]" id="nominal_bt_perdiem" type="text" min="0" value="{{ number_format($perdiem['nominal'], 0, ',', '.') }}" readonly>
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Perdiem</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_perdiem_deklarasi[]" id="total_bt_perdiem[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-bt-perdiem" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="transport-card-deklarasi" class="card-body p-0">
                                                            <div class="accordion mb-3" id="accordionTransport">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingTransport">
                                                                        <button class="accordion-button @if($detailCA['detail_transport'][0]['tanggal'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTransport" aria-expanded="@if($detailCA['detail_transport'][0]['tanggal'] !== null) true @else false @endif" aria-controls="collapseTransport">
                                                                            Transport Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseTransport" class="accordion-collapse collapse @if($detailCA['detail_transport'][0]['tanggal'] !== null) show @endif" aria-labelledby="headingTransport" data-bs-parent="#accordionTransport">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-transport-deklarasi">
                                                                                @foreach ($detailCA['detail_transport'] as $transport)
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Transport Date</label>
                                                                                    <input type="date" name="tanggal_bt_transport_deklarasi[]" class="form-control bg-light" value="{{$transport['tanggal']}}" readonly>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label" for="name">Company Code</label>
                                                                                    <select class="form-control bg-light" id="companyFilter" name="company_bt_perdiem_deklarasi[]" disabled>
                                                                                        <option value="">Select Company...</option>
                                                                                        @foreach($companies as $company)
                                                                                            <option value="{{ $company->contribution_level_code }}"
                                                                                                @if($company->contribution_level_code == $transport['company_code']) selected @endif>
                                                                                                {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Information</label>
                                                                                    <textarea name="keterangan_bt_transport_deklarasi[]" class="form-control bg-light" readonly>{{$transport['keterangan']}}</textarea>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Amount</label>
                                                                                </div>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="nominal_bt_transport_deklarasi[]" id="nominal_bt_transport[]" type="text" min="0" value="{{number_format($transport['nominal'], 0, ',', '.')}}" readonly>
                                                                                </div>
                                                                                <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Transport</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_transport_deklarasi[]" id="total_bt_transport[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-bt-transport" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="penginapan-card-deklarasi" class="card-body p-0">
                                                            <div class="accordion" id="accordionPenginapan">
                                                                <div class="accordion-item mb-3">
                                                                    <h2 class="accordion-header" id="headingPenginapanDec">
                                                                        <button class="accordion-button @if($detailCA['detail_penginapan'][0]['start_date'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePenginapanDec" aria-expanded="@if($detailCA['detail_penginapan'][0]['start_date'] !== null) true @else false @endif" aria-controls="collapsePenginapanDec">
                                                                            Acommodation Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapsePenginapanDec" class="accordion-collapse collapse @if($detailCA['detail_penginapan'][0]['start_date'] !== null) show @endif" aria-labelledby="headingPenginapanDec">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-penginapan-deklarasi">
                                                                                @foreach($detailCA['detail_penginapan'] as $penginapan)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Acommodation Start Date</label>
                                                                                        <input type="date" name="start_bt_penginapan_deklarasi[]" class="form-control bg-light start-penginapan" value="{{$penginapan['start_date']}}" placeholder="mm/dd/yyyy" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Acommodation End Date</label>
                                                                                        <input type="date" name="end_bt_penginapan_deklarasi[]" class="form-control bg-light end-penginapan" value="{{$penginapan['end_date']}}" placeholder="mm/dd/yyyy" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Total Days</label>
                                                                                        <div class="input-group">
                                                                                            <input class="form-control bg-light total-days-penginapan" id="total_days_bt_penginapan_deklarasi[]" name="total_days_bt_penginapan[]" type="text" min="0" value="{{$penginapan['total_days']}}" readonly>
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text">days</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Hotel Name</label>
                                                                                        <input type="text" name="hotel_name_bt_penginapan_deklarasi[]" class="form-control bg-light" value="{{$penginapan['hotel_name']}}" placeholder="Hotel" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Company Code</label>
                                                                                        <select class="form-control select2" id="companyFilter" name="company_bt_penginapan_deklarasi[]" disabled>
                                                                                            <option value="">Select Company...</option>
                                                                                            @foreach($companies as $company)
                                                                                                <option value="{{ $company->contribution_level_code }}"
                                                                                                    @if($company->contribution_level_code == $penginapan['company_code']) selected @endif>
                                                                                                    {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Amount</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control bg-light" name="nominal_bt_penginapan_deklarasi[]" id="nominal_bt_penginapan[]" type="text" min="0" value="{{$penginapan['nominal']}}" readonly>
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Acommodation</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_penginapan_deklarasi[]" id="total_bt_penginapan" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-bt-penginapan" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="lainnya-card-deklarasi" class="card-body p-0" >
                                                            <div class="accordion" id="accordionLainnya">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingLainnya">
                                                                        <button class="accordion-button @if($detailCA['detail_lainnya'][0]['tanggal'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLainnya" aria-expanded="@if($detailCA['detail_lainnya'][0]['tanggal'] !== null) true @else false @endif" aria-controls="collapseLainnya">
                                                                            Others Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseLainnya" class="accordion-collapse collapse @if($detailCA['detail_lainnya'][0]['tanggal'] !== null) show @endif" aria-labelledby="headingLainnya">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-lainnya-deklarasi">
                                                                                @foreach ($detailCA['detail_lainnya'] as $perdiem)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Date</label>
                                                                                        <input type="date" name="tanggal_bt_lainnya_deklarasi[]" class="form-control bg-light" value="{{$perdiem['tanggal']}}" placeholder="mm/dd/yyyy" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Information</label>
                                                                                        <textarea name="keterangan_bt_lainnya_deklarasi[]" class="form-control bg-light" readonly>{{ $perdiem['keterangan'] }}</textarea>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Accommodation</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control bg-light" name="nominal_bt_lainnya_deklarasi[]" id="nominal_bt_lainnya_deklarasi" type="text" min="0" value="{{ number_format($perdiem['nominal'], 0, ',', '.') }}" readonly>
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Others</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_lainnya_deklarasi[]" id="total_bt_lainnya_deklarasi" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-bt-lainnya" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Cash Advanced Deklarasi</div>
                                                    <div class="card ">
                                                        <div id="perdiem-card" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionPerdiem">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="enter-headingOne">
                                                                        <button class="accordion-button @if($declareCA['detail_perdiem'][0]['start_date'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#enter-collapseOne" aria-expanded="@if($declareCA['detail_perdiem'][0]['start_date'] !== null) true @else false @endif" aria-controls="enter-collapseOne">
                                                                            Perdiem Plan Declaration
                                                                        </button>
                                                                    </h2>
                                                                    <div id="enter-collapseOne" class="accordion-collapse @if($declareCA['detail_perdiem'][0]['start_date'] !== null) show @endif" aria-labelledby="enter-headingOne">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-perdiem">
                                                                                @foreach ($declareCA['detail_perdiem'] as $perdiem)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Company Code</label>
                                                                                        <select class="form-control select2" id="companyFilter" name="company_bt_perdiem[]">
                                                                                            <option value="">Select Company...</option>
                                                                                            @foreach($companies as $company)
                                                                                                <option value="{{ $company->contribution_level_code }}"
                                                                                                    @if($company->contribution_level_code == $perdiem['company_code']) selected @endif>
                                                                                                    {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Location Agency</label>
                                                                                        <select class="form-control location-select" name="location_bt_perdiem[]">
                                                                                            <option value="">Select location...</option>
                                                                                            @foreach($locations as $location)
                                                                                                <option value="{{ $location->area }}"
                                                                                                    @if($location->area == $perdiem['location']) selected @endif>
                                                                                                    {{ $location->area." (".$location->company_name.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                            <option value="Others" @if('Others' == $perdiem['location']) selected @endif>Others</option>
                                                                                        </select>
                                                                                        <br>
                                                                                        <input type="text" name="other_location_bt_perdiem[]" class="form-control other-location" placeholder="Other Location" value="{{ $perdiem['other_location'] }}" @if('Others' != $perdiem['location']) style="display: none;" @endif >
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Start Perdiem</label>
                                                                                        <input type="date" name="start_bt_perdiem[]" class="form-control start-perdiem" value="{{$perdiem['start_date']}}" placeholder="mm/dd/yyyy">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">End Perdiem</label>
                                                                                        <input type="date" name="end_bt_perdiem[]" class="form-control end-perdiem" value="{{$perdiem['end_date']}}" placeholder="mm/dd/yyyy">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Total Days</label>
                                                                                        <div class="input-group">
                                                                                            <input class="form-control bg-light total-days-perdiem" id="total_days_bt_perdiem[]" name="total_days_bt_perdiem[]" type="text" min="0" value="{{$perdiem['total_days']}}" readonly>
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text">days</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- HTML -->
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Amount</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control" name="nominal_bt_perdiem[]" id="nominal_bt_perdiem" type="text" min="0" value="{{ number_format($perdiem['nominal'], 0, ',', '.') }}">
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Perdiem</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_perdiem[]" id="total_bt_perdiem[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" id="add-more-bt-perdiem" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="transport-card" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionTransport">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingTransportDec">
                                                                        <button class="accordion-button @if($declareCA['detail_transport'][0]['tanggal'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTransport" aria-expanded="@if($declareCA['detail_transport'][0]['tanggal'] !== null) true @else false @endif" aria-controls="collapseTransport">
                                                                            Transport Plan Declaration
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseTransport" class="accordion-collapse collapse @if($declareCA['detail_transport'][0]['tanggal'] !== null) show @endif" aria-labelledby="headingTransport" data-bs-parent="#accordionTransport">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-transport">
                                                                                @foreach ($declareCA['detail_transport'] as $transport)
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Tanggal Transport</label>
                                                                                    <input type="date" name="tanggal_bt_transport[]" class="form-control" value="{{$transport['tanggal']}}">
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label" for="name">Company Code</label>
                                                                                    <select class="form-control select2" id="companyFilter" name="company_bt_perdiem[]">
                                                                                        <option value="">Select Company...</option>
                                                                                        @foreach($companies as $company)
                                                                                            <option value="{{ $company->contribution_level_code }}"
                                                                                                @if($company->contribution_level_code == $transport['company_code']) selected @endif>
                                                                                                {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Keterangan</label>
                                                                                    <textarea name="keterangan_bt_transport[]" class="form-control">{{$transport['keterangan']}}</textarea>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Amount</label>
                                                                                </div>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control" name="nominal_bt_transport[]" id="nominal_bt_transport[]" type="text" min="0" value="{{number_format($transport['nominal'], 0, ',', '.')}}">
                                                                                </div>
                                                                                <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Transport</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_transport[]" id="total_bt_transport[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" id="add-more-bt-transport" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="penginapan-card" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionPenginapan">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingPenginapan">
                                                                        <button class="accordion-button @if($declareCA['detail_penginapan'][0]['start_date'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePenginapan" aria-expanded="@if($declareCA['detail_penginapan'][0]['start_date'] !== null) true @else false @endif" aria-controls="collapsePenginapan">
                                                                            Accomodation Plan Declaration
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapsePenginapan" class="accordion-collapse collapse @if($declareCA['detail_penginapan'][0]['start_date'] !== null) show @endif" aria-labelledby="headingPenginapan">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-penginapan">
                                                                                @foreach($declareCA['detail_penginapan'] as $penginapan)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Start Penginapan</label>
                                                                                        <input type="date" name="start_bt_penginapan[]" class="form-control start-penginapan" value="{{$penginapan['start_date']}}" placeholder="mm/dd/yyyy">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">End Penginapan</label>
                                                                                        <input type="date" name="end_bt_penginapan[]" class="form-control end-penginapan" value="{{$penginapan['end_date']}}" placeholder="mm/dd/yyyy">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Total Days</label>
                                                                                        <div class="input-group">
                                                                                            <input class="form-control bg-light total-days-penginapan" id="total_days_bt_penginapan[]" name="total_days_bt_penginapan[]" type="text" min="0" value="{{$penginapan['total_days']}}" readonly>
                                                                                            <div class="input-group-append">
                                                                                                <span class="input-group-text">days</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Hotel Name</label>
                                                                                        <input type="text" name="hotel_name_bt_penginapan[]" class="form-control" value="{{$penginapan['hotel_name']}}" placeholder="Hotel">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="name">Company Code</label>
                                                                                        <select class="form-control select2" id="companyFilter" name="company_bt_penginapan[]">
                                                                                            <option value="">Select Company...</option>
                                                                                            @foreach($companies as $company)
                                                                                                <option value="{{ $company->contribution_level_code }}"
                                                                                                    @if($company->contribution_level_code == $penginapan['company_code']) selected @endif>
                                                                                                    {{ $company->contribution_level." (".$company->contribution_level_code.")" }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Amount</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control" name="nominal_bt_penginapan[]" id="nominal_bt_penginapan[]" type="text" min="0" value="{{$penginapan['nominal']}}">
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Penginapan</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_penginapan[]" id="total_bt_penginapan" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" id="add-more-bt-penginapan" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="lainnya-card" class="card-body p-0">
                                                            <div class="accordion" id="accordionLainnya">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingLainnya">
                                                                        <button class="accordion-button @if($declareCA['detail_lainnya'][0]['tanggal'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLainnya" aria-expanded="@if($declareCA['detail_lainnya'][0]['tanggal'] !== null) true @else false @endif" aria-controls="collapseLainnya">
                                                                            Others Plan Declaration
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseLainnya" class="accordion-collapse collapse @if($declareCA['detail_lainnya'][0]['tanggal'] !== null) show @endif" aria-labelledby="headingLainnya">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-bt-lainnya">
                                                                                @foreach ($declareCA['detail_lainnya'] as $perdiem)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Tanggal</label>
                                                                                        <input type="date" name="tanggal_bt_lainnya[]" class="form-control" value="{{$perdiem['tanggal']}}" placeholder="mm/dd/yyyy">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Keterangan</label>
                                                                                        <textarea name="keterangan_bt_lainnya[]" class="form-control">{{ $perdiem['keterangan'] }}</textarea>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Accommodation</label>
                                                                                    </div>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control" name="nominal_bt_lainnya[]" id="nominal_bt_lainnya" type="text" min="0" value="{{ number_format($perdiem['nominal'], 0, ',', '.') }}">
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Lainnya</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_bt_lainnya[]" id="total_bt_lainnya" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" id="add-more-bt-lainnya" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row" id="ca_nbt" style="display: none;">
                                @if ($transactions->type_ca == 'ndns')
                                    <div class="col-md-12">
                                        <div class="table-responsive-sm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Cash Advanced</div>
                                                    <div class="card">
                                                        <div class="card-body p-0">
                                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="enter-headingOne">
                                                                        <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#enter-collapseOne" aria-expanded="true" aria-controls="enter-collapseOne">
                                                                            Non Business Trip
                                                                        </button>
                                                                    </h2>
                                                                    @foreach ($detailCA as $item)
                                                                    <div id="enter-collapseOne" class="accordion-collapse show" aria-labelledby="enter-headingOne">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container">
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Tanggal</label>
                                                                                    <input type="date" name="tanggal_nbt_decla[]" class="form-control bg-light" value="{{ $item['tanggal_nbt'] }}" readonly>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Keterangan</label>
                                                                                    <textarea name="keterangan_nbt_decla[]" class="form-control bg-light" readonly>{{ $item['keterangan_nbt'] }}</textarea>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <label class="form-label">Accommodation</label>
                                                                                </div>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="nominal_nbt_decla[]" id="nominal_nbt" type="text" min="0" value="{{ number_format($item['nominal_nbt'], 0, ',', '.') }}" readonly>
                                                                                </div>
                                                                                <hr class="border border-primary border-1 opacity-50">
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Cash Advanced Deklarasi</div>
                                                    <div class="card">
                                                        <div class="card-body p-0">
                                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="enter-headingOne">
                                                                        <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#enter-collapseOne" aria-expanded="true" aria-controls="enter-collapseOne">
                                                                            Deklarasi Non Business Trip
                                                                        </button>
                                                                    </h2>
                                                                    <div id="enter-collapseOne" class="accordion-collapse show" aria-labelledby="enter-headingOne">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-nbt"></div>
                                                                            <button type="button" id="add-more" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row" id="ca_e" style="display: none;">
                                @if ($transactions->type_ca == 'entr')
                                    <div class="col-md-12">
                                        <div class="table-responsive-sm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Entertainment</div>
                                                    <div class="card">
                                                        <div id="entertain-card-deklarasi" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionEntertain">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingEntertain">
                                                                        <button class="accordion-button @if($detailCA['detail_e'][0]['type'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEntertain" aria-expanded="@if($detailCA['detail_e'][0]['type'] !== null) true @else false @endif" aria-controls="collapseEntertain">
                                                                            Entertain Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseEntertain" class="accordion-collapse collapse @if($detailCA['detail_e'][0]['type'] !== null) show @endif" aria-labelledby="headingEntertain">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-e-detail-deklarasi">
                                                                                @foreach ($detailCA['detail_e'] as $detail)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Entertainment Type</label>
                                                                                        <select name="enter_type_e_detail_deklarasi[]" id="enter_type_e_detail_deklarasi[]" class="form-select bg-light" disabled>
                                                                                            <option value="">-</option>
                                                                                            <option value="food" {{ $detail['type'] == 'food' ? 'selected' : '' }}>Food/Beverages/Souvenir</option>
                                                                                            <option value="transport" {{ $detail['type'] == 'transport' ? 'selected' : '' }}>Transport</option>
                                                                                            <option value="accommodation" {{ $detail['type'] == 'accommodation' ? 'selected' : '' }}>Accommodation</option>
                                                                                            <option value="gift" {{ $detail['type'] == 'gift' ? 'selected' : '' }}>Gift</option>
                                                                                            <option value="fund" {{ $detail['type'] == 'fund' ? 'selected' : '' }}>Fund</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Entertainment Fee Detail</label>
                                                                                        <textarea name="enter_fee_e_detail_deklarasi[]" id="enter_fee_e_detail_deklarasi[]" class="form-control bg-light" readonly>{{ $detail['fee_detail'] }}<</textarea>
                                                                                    </div>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control bg-light" name="nominal_e_detail_deklarasi[]" id="nominal_e_detail_deklarasi[]" type="text" min="0" value="{{ number_format($detail['nominal'], 0, ',', '.') }}" readonly>
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Entertain</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_e_detail_deklarasi[]" id="total_e_detail_deklarasi[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-e-detail" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="relation-card-deklarasi" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionRelation">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingRelationDec">
                                                                        <button class="accordion-button @if($detailCA['relation_e'][0]['name'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRelationDec" aria-expanded="@if($detailCA['relation_e'][0]['name'] !== null) true @else false @endif" aria-controls="collapseRelationDec">
                                                                            Relation Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseRelationDec" class="accordion-collapse collapse @if($detailCA['relation_e'][0]['name'] !== null) show @endif" aria-labelledby="headingRelationDec">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-e-relation-deklarasi">
                                                                                @foreach($detailCA['relation_e'] as $relation)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Relation Type</label>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="checkbox" name="accommodation_e_relation-deklarasi[]" id="accommodation_e_relation-deklarasi[]" value="accommodation" {{ isset($relation['relation_type']['Accommodation']) && $relation['relation_type']['Accommodation'] ? 'checked' : '' }} disabled>
                                                                                            <label class="form-check-label" for="accommodation_e_relation-deklarasi[]">Accommodation</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="transport_e_relation_deklarasi[]" type="checkbox" id="transport_e_relation_deklarasi[]" value="transport" {{ isset($relation['relation_type']['Transport']) && $relation['relation_type']['Transport'] ? 'checked' : '' }} disabled>
                                                                                            <label class="form-check-label" for="transport_e_relation_deklarasi[]">Transport</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="gift_e_relation_deklarasi[]" type="checkbox" id="gift_e_relation_deklarasi[]" value="gift" {{ isset($relation['relation_type']['Gift']) && $relation['relation_type']['Gift'] ? 'checked' : '' }} disabled>
                                                                                            <label class="form-check-label" for="gift_e_relation_deklarasi[]">Gift</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="fund_e_relation_deklarasi[]" type="checkbox" id="fund_e_relation_deklarasi[]" value="fund" {{ isset($relation['relation_type']['Fund']) && $relation['relation_type']['Fund'] ? 'checked' : '' }} disabled>
                                                                                            <label class="form-check-label" for="fund_e_relation_deklarasi[]">Fund</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="food_e_relation_deklarasi[]" type="checkbox" id="food_e_relation_deklarasi[]" value="food" {{ isset($relation['relation_type']['Food']) && $relation['relation_type']['Food'] ? 'checked' : '' }} disabled>
                                                                                            <label class="form-check-label" for="food_e_relation_deklarasi[]">Food/Beverages/Souvenir</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Name</label>
                                                                                        <input type="text" name="rname_e_relation_deklarasi[]" id="rname_e_relation_deklarasi[]" value="{{ $relation['name'] }}" class="form-control bg-light" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Position</label>
                                                                                        <input type="text" name="rposition_e_relation_deklarasi[]" id="rposition_e_relation_deklarasi[]" value="{{ $relation['position'] }}" class="form-control bg-light" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Company</label>
                                                                                        <input type="text" name="rcompany_e_relation_deklarasi[]" id="rcompany_e_relation_deklarasi[]" value="{{ $relation['company'] }}" class="form-control bg-light" readonly>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Purpose</label>
                                                                                        <input type="text" name="rpurpose_e_relation_deklarasi[]" id="rpurpose_e_relation_deklarasi[]" value="{{ $relation['purpose'] }}" class="form-control bg-light" readonly>
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            {{-- <button type="button" id="add-more-e-relation" class="btn btn-primary mb-3">Add More</button> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-bg-danger mb-3 p-2" style="text-align:center">Estimated Entertainment Deklarasi</div>
                                                    <div class="card">
                                                        <div id="entertain-card" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionEntertain">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingEntertain">
                                                                        <button class="accordion-button @if($declareCA['detail_e'][0]['type'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEntertain" aria-expanded="@if($declareCA['detail_e'][0]['type'] !== null) true @else false @endif" aria-controls="collapseEntertain">
                                                                            Declaration Detail Entertain
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseEntertain" class="accordion-collapse collapse @if($declareCA['detail_e'][0]['type'] !== null) show @endif" aria-labelledby="headingEntertain">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-e-detail">
                                                                                @foreach ($declareCA['detail_e'] as $detail)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Entertainment Type</label>
                                                                                        <select name="enter_type_e_detail[]" id="enter_type_e_detail[]" class="form-select">
                                                                                            <option value="">-</option>
                                                                                            <option value="food" {{ $detail['type'] == 'food' ? 'selected' : '' }}>Food/Beverages/Souvenir</option>
                                                                                            <option value="transport" {{ $detail['type'] == 'transport' ? 'selected' : '' }}>Transport</option>
                                                                                            <option value="accommodation" {{ $detail['type'] == 'accommodation' ? 'selected' : '' }}>Accommodation</option>
                                                                                            <option value="gift" {{ $detail['type'] == 'gift' ? 'selected' : '' }}>Gift</option>
                                                                                            <option value="fund" {{ $detail['type'] == 'fund' ? 'selected' : '' }}>Fund</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Entertainment Fee Detail</label>
                                                                                        <textarea name="enter_fee_e_detail[]" id="enter_fee_e_detail[]" class="form-control">{{ $detail['fee_detail'] }}<</textarea>
                                                                                    </div>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">Rp</span>
                                                                                        </div>
                                                                                        <input class="form-control" name="nominal_e_detail[]" id="nominal_e_detail[]" type="text" min="0" value="{{ number_format($detail['nominal'], 0, ',', '.') }}">
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="form-label">Total Entertain</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">Rp</span>
                                                                                    </div>
                                                                                    <input class="form-control bg-light" name="total_e_detail[]" id="total_e_detail[]" type="text" min="0" value="0" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" id="add-more-e-detail" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="relation-card" class="card-body mb-3 p-0">
                                                            <div class="accordion" id="accordionRelation">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingRelation">
                                                                        <button class="accordion-button @if($declareCA['relation_e'][0]['name'] === null) collapsed @endif fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRelation" aria-expanded="@if($declareCA['relation_e'][0]['name'] !== null) true @else false @endif" aria-controls="collapseRelation">
                                                                            Relation Plan
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseRelation" class="accordion-collapse collapse @if($declareCA['relation_e'][0]['name'] !== null) show @endif" aria-labelledby="headingRelation">
                                                                        <div class="accordion-body">
                                                                            <div id="form-container-e-relation">
                                                                                @foreach($declareCA['relation_e'] as $relation)
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label">Relation Type</label>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="checkbox" name="accommodation_e_relation[]" id="accommodation_e_relation[]" value="accommodation" {{ isset($relation['relation_type']['Accommodation']) && $relation['relation_type']['Accommodation'] ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="accommodation_e_relation[]">Accommodation</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="transport_e_relation[]" type="checkbox" id="transport_e_relation[]" value="transport" {{ isset($relation['relation_type']['Transport']) && $relation['relation_type']['Transport'] ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="transport_e_relation[]">Transport</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="gift_e_relation[]" type="checkbox" id="gift_e_relation[]" value="gift" {{ isset($relation['relation_type']['Gift']) && $relation['relation_type']['Gift'] ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="gift_e_relation[]">Gift</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="fund_e_relation[]" type="checkbox" id="fund_e_relation[]" value="fund" {{ isset($relation['relation_type']['Fund']) && $relation['relation_type']['Fund'] ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="fund_e_relation[]">Fund</label>
                                                                                        </div>
                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" name="food_e_relation[]" type="checkbox" id="food_e_relation[]" value="food" {{ isset($relation['relation_type']['Food']) && $relation['relation_type']['Food'] ? 'checked' : '' }}>
                                                                                            <label class="form-check-label" for="food_e_relation[]">Food/Beverages/Souvenir</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Name</label>
                                                                                        <input type="text" name="rname_e_relation[]" id="rname_e_relation[]" value="{{ $relation['name'] }}" class="form-control">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Position</label>
                                                                                        <input type="text" name="rposition_e_relation[]" id="rposition_e_relation[]" value="{{ $relation['position'] }}" class="form-control">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Company</label>
                                                                                        <input type="text" name="rcompany_e_relation[]" id="rcompany_e_relation[]" value="{{ $relation['company'] }}" class="form-control">
                                                                                    </div>
                                                                                    <div class="mb-2">
                                                                                        <label class="form-label" for="start">Purpose</label>
                                                                                        <input type="text" name="rpurpose_e_relation[]" id="rpurpose_e_relation[]" value="{{ $relation['purpose'] }}" class="form-control">
                                                                                    </div>
                                                                                    <hr class="border border-primary border-1 opacity-50">
                                                                                @endforeach
                                                                            </div>
                                                                            <button type="button" id="add-more-e-relation" class="btn btn-primary mb-3">Add More</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="prove_declare" class="form-label">Upload Document</label>

                                    <!-- Input file -->
                                    <input type="file" id="prove_declare" name="prove_declare" accept="image/*, application/pdf" class="form-control" onchange="previewFile()" required>
                                    <input type="hidden" name="existing_prove_declare" value="{{ $transactions->prove_declare }}">

                                    <!-- Show existing file -->
                                    <div id="existing-file-preview" class="mt-2" style="display:none">
                                        @if($transactions->prove_declare)
                                            @php
                                                $extension = pathinfo($transactions->prove_declare, PATHINFO_EXTENSION);
                                            @endphp

                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <!-- Tampilkan gambar -->
                                                <a href="{{ asset('uploads/proofs/' . $transactions->prove_declare) }}" target="_blank">
                                                    <img id="existing-image" src="{{ asset('uploads/proofs/' . $transactions->prove_declare) }}" alt="Proof Image" style="max-width: 200px;">
                                                </a>
                                                <p>Click on the image to view the full size</p>
                                            @elseif($extension == 'pdf')
                                                <!-- Tampilkan tautan untuk PDF -->
                                                <a id="existing-pdf" href="{{ asset('uploads/proofs/' . $transactions->prove_declare) }}" target="_blank">
                                                    <img src="https://img.icons8.com/color/48/000000/pdf.png" alt="PDF File" style="max-width: 48px;">
                                                    <p>Click to view PDF</p>
                                                </a>
                                            @else
                                                <p>File type not supported.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Total Cash Advanced</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input class="form-control bg-light" name="totalca" id="totalca_declarasi"
                                            type="text" min="0" value="{{ number_format($transactions->total_ca, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Total Cash Advanced Deklarasi</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input class="form-control bg-light" name="totalca_deklarasi" id="totalca"
                                            type="text" min="0" value="{{ $transactions->total_cost }}" readonly>
                                    </div>

                                </div>
                            </div>
                    </div>
                    <input type="hidden" name="no_id" id="no_id" value="{{ $transactions->id }}"
                        class="form-control bg-light" readonly>
                    <input type="hidden" name="no_ca" id="no_ca" value="{{ $transactions->no_ca }}"
                        class="form-control bg-light" readonly>
                    <input type="hidden" name="bisnis_numb" id="bisnis_numb" value="{{ $transactions->no_sppd }}"
                        class="form-control bg-light" readonly>
                    <br>
                    <div class="row">
                        <div class="p-4 col-md d-md-flex justify-content-end text-center">
                            <input type="hidden" name="repeat_days_selected" id="repeatDaysSelected">
                            <a href="{{ route('cashadvanced') }}" type="button"
                                class="btn btn-outline-secondary px-4 me-2">Cancel</a>
                            <button type="submit" name="action_ca_draft" value="Draft" class=" btn btn-secondary btn-pill px-4 me-2">Draft</button>
                            <button type="submit" name="action_ca_submit" value="Pending" class=" btn btn-primary btn-pill px-4 me-2">Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
<!-- Tambahkan script JavaScript untuk mengumpulkan nilai repeat_days[] -->
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ca_type ca_nbt ca_e
            var ca_type = document.getElementById("ca_type");
            var ca_nbt = document.getElementById("ca_nbt");
            var ca_e = document.getElementById("ca_e");
            var div_bisnis_numb = document.getElementById("div_bisnis_numb");
            var bisnis_numb = document.getElementById("bisnis_numb");
            var div_allowance = document.getElementById("div_allowance");

            function toggleDivs() {
                if (ca_type.value === "dns") {
                    ca_bt.style.display = "block";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "block";
                    div_allowance.style.display = "block";
                } else if (ca_type.value === "ndns"){
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "block";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "none";
                    bisnis_numb.style.value = "";
                    div_allowance.style.display = "none";
                } else if (ca_type.value === "entr"){
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "block";
                    div_bisnis_numb.style.display = "block";
                } else{
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "none";
                    bisnis_numb.style.value = "";
                }
            }

            toggleDivs();
            ca_type.addEventListener("change", toggleDivs);
        });

        function toggleOthers() {
            var locationFilter = document.getElementById("locationFilter");
            var others_location = document.getElementById("others_location");

            if (locationFilter.value === "Others") {
                others_location.style.display = "block";
            } else {
                others_location.style.display = "none";
                others_location.value = "";
            }
        }

        function validateInput(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const totalDaysInput = document.getElementById('totaldays');
            const perdiemInput = document.getElementById('perdiem');
            const allowanceInput = document.getElementById('allowance');
            const othersLocationInput = document.getElementById('others_location');
            const transportInput = document.getElementById('transport');
            const accommodationInput = document.getElementById('accommodation');
            const otherInput = document.getElementById('other');
            const totalcaInput = document.getElementById('totalca');
            const nominal_1Input = document.getElementById('nominal_1');
            const nominal_2Input = document.getElementById('nominal_2');
            const nominal_3Input = document.getElementById('nominal_3');
            const nominal_4Input = document.getElementById('nominal_4');
            const nominal_5Input = document.getElementById('nominal_5');
            const caTypeInput = document.getElementById('ca_type');

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function parseNumber(value) {
                return parseFloat(value.replace(/\./g, '')) || 0;
            }

            function calculateTotalDays() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                if (startDate && endDate && !isNaN(startDate) && !isNaN(endDate)) {
                    const timeDiff = endDate - startDate;
                    const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                    const totalDays = daysDiff > 0 ? daysDiff + 1 : 0 + 1;
                    totalDaysInput.value = totalDays;

                    const perdiem = parseFloat(perdiemInput.value) || 0;
                    let allowance = totalDays * perdiem;

                    if (othersLocationInput.value.trim() !== '') {
                        allowance *= 1; // allowance * 50%
                    } else {
                        allowance *= 0.5;
                    }

                    allowanceInput.value = formatNumber(Math.floor(allowance));
                } else {
                    totalDaysInput.value = 0;
                    allowanceInput.value = 0;
                }
                calculateTotalCA();
            }

            startDateInput.addEventListener('change', calculateTotalDays);
            endDateInput.addEventListener('change', calculateTotalDays);
            othersLocationInput.addEventListener('input', calculateTotalDays);
            caTypeInput.addEventListener('change', calculateTotalDays);
            [transportInput, accommodationInput, otherInput, allowanceInput, nominal_1, nominal_2, nominal_3,
                nominal_4, nominal_5
            ].forEach(input => {
                input.addEventListener('input', () => formatInput(input));
            });
        });

        document.getElementById('end_date').addEventListener('change', function() {
            const endDate = new Date(this.value);
            const declarationEstimateDate = new Date(endDate);
            declarationEstimateDate.setDate(declarationEstimateDate.getDate() + 3);

            const year = declarationEstimateDate.getFullYear();
            const month = String(declarationEstimateDate.getMonth() + 1).padStart(2, '0');
            const day = String(declarationEstimateDate.getDate()).padStart(2, '0');

            document.getElementById('ca_decla').value = `${year}-${month}-${day}`;
        });
    </script>

    <script>
        function previewFile() {
            const fileInput = document.getElementById('prove_declare');
            const file = fileInput.files[0];
            const preview = document.getElementById('existing-file-preview');
            preview.innerHTML = ''; // Kosongkan preview sebelumnya

            if (file) {
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    const img = document.createElement('img');
                    img.style.maxWidth = '200px';
                    img.src = URL.createObjectURL(file);
                    preview.appendChild(img);
                } else if (fileExtension === 'pdf') {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(file);
                    link.target = '_blank';
                    const icon = document.createElement('img');
                    icon.src = "https://img.icons8.com/color/48/000000/pdf.png";
                    icon.style.maxWidth = '48px';
                    link.appendChild(icon);
                    const text = document.createElement('p');
                    text.textContent = "Click to view PDF";
                    preview.appendChild(link);
                    preview.appendChild(text);
                } else {
                    preview.textContent = 'File type not supported.';
                }
            }
        }


        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",

            });
        });

        $(document).ready(function() {
            function toggleCard(buttonId, cardId) {
                var $button = $(buttonId);
                var $card = $(cardId);
                var isVisible = $card.is(':visible');

                $card.slideToggle('fast', function() {
                    if (isVisible) {
                        $card.find('input[type="text"], input[type="date"], textarea').val('');
                        $card.find('select').prop('selectedIndex', 0);
                        $card.find('input[readonly]').val(0);
                        $card.find('input[type="number"]').val(0);

                        $button.html('<i class="bi bi-plus-circle"></i> ' + $button.text().split(' ')[1]);
                        $button.data('state', 'false');
                    } else {
                        $button.html('<i class="bi bi-dash-circle"></i> ' + $button.text().split(' ')[1]);
                        $button.data('state', 'true');
                    }
                });
            }

            $('#toggle-bt-perdiem').click(function() {
                toggleCard('#toggle-bt-perdiem', '#perdiem-card');
            });

            $('#toggle-bt-perdiem-deklarasi').click(function() {
                toggleCard('#toggle-bt-perdiem-deklarasi', '#perdiem-card-deklarasi');
            });

            $('#toggle-bt-transport').click(function() {
                toggleCard('#toggle-bt-transport', '#transport-card');
            });

            $('#toggle-bt-transport-deklarasi').click(function() {
                toggleCard('#toggle-bt-transport-deklarasi', '#transport-card-deklarasi');
            });

            $('#toggle-bt-penginapan').click(function() {
                toggleCard('#toggle-bt-penginapan', '#penginapan-card');
            });

            $('#toggle-bt-penginapan-deklarasi').click(function() {
                toggleCard('#toggle-bt-penginapan-deklarasi', '#penginapan-card-deklarasi');
            });

            $('#toggle-bt-lainnya').click(function() {
                toggleCard('#toggle-bt-lainnya', '#lainnya-card');
            });

            $('#toggle-bt-lainnya-deklarasi').click(function() {
                toggleCard('#toggle-bt-lainnya-deklarasi', '#lainnya-card-deklarasi');
            });

            $('#toggle-e-detail').click(function() {
                toggleCard('#toggle-e-detail', '#entertain-card');
            });

            $('#toggle-e-detail-deklarasi').click(function() {
                toggleCard('#toggle-e-detail-deklarasi', '#entertain-card-deklarasi');
            });

            $('#toggle-e-relation').click(function() {
                toggleCard('#toggle-e-relation', '#relation-card');
            });

            $('#toggle-e-relation-deklarasi').click(function() {
                toggleCard('#toggle-e-relation-deklarasi', '#relation-card-deklarasi');
            });

            var caType = $('input[name="ca_type"]').val();
            console.log(caType);

            if (caType === 'dns') {
                $('#toggle-bt-perdiem').click();
                $('#toggle-bt-transport').click();
                $('#toggle-bt-penginapan').click();
                $('#toggle-bt-lainnya').click();
                $('#toggle-bt-perdiem-deklarasi').click();
                $('#toggle-bt-transport-deklarasi').click();
                $('#toggle-bt-penginapan-deklarasi').click();
                $('#toggle-bt-lainnya-deklarasi').click();
            } else if (caType === 'entr') {
                $('#toggle-e-detail-deklarasi').click();
                $('#toggle-e-detail').click();
                $('#toggle-e-relation').click();
                $('#toggle-e-relation-deklarasi').click();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const formContainerBTPerdiem = document.getElementById('form-container-bt-perdiem');
            const formContainerBTTransport = document.getElementById('form-container-bt-transport');
            const formContainerBTPenginapan = document.getElementById('form-container-bt-penginapan');
            const formContainerBTLainnya = document.getElementById('form-container-bt-lainnya');

            function toggleOthersBT(selectElement) {
                const formGroup = selectElement.closest('.mb-2').parentElement;
                const othersInput = formGroup.querySelector('input[name="other_location_bt_perdiem[]"]');

                if (selectElement.value === "Others") {
                    othersInput.style.display = 'block';
                    othersInput.required = true;
                } else {
                    othersInput.style.display = 'none';
                    othersInput.required = false;
                    othersInput.value = "";
                }
            }

            document.querySelectorAll('.location-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    toggleOthersBT(this);
                });
            });

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function formatNumberPerdiem(num) {
                return num.toLocaleString('id-ID');
            }

            function parseNumber(value) {
                return parseFloat(value.replace(/\./g, '')) || 0;
            }

            function parseNumberPerdiem(value) {
                return parseFloat(value.replace(/\./g, '').replace(/,/g, '')) || 0;
            }

            function formatInput(input) {
                let value = input.value.replace(/\./g, '');
                value = parseFloat(value);
                if (!isNaN(value)) {
                    input.value = formatNumber(Math.floor(value));
                } else {
                    input.value = formatNumber(0);
                }
                calculateTotalNominalBTPerdiem();
                calculateTotalNominalBTPerdiemDeklarasi();
                calculateTotalNominalBTTransport();
                calculateTotalNominalBTTransportDeklarasi();
                calculateTotalNominalBTPenginapan();
                calculateTotalNominalBTPenginapanDeklarasi();
                calculateTotalNominalBTLainnya();
                calculateTotalNominalBTLainnyaDeklarasi();
                calculateTotalNominalBTTotal();
            }

            function calculateTotalNominalBTPerdiem() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_perdiem[]"]').forEach(input => {
                    total += parseNumberPerdiem(input.value);
                });
                console.log("Total Perdiem:", total); // Debugging
                document.querySelector('input[name="total_bt_perdiem[]"]').value = formatNumber(total);

                calculateTotalNominalBTTotal();
            }

            function calculateTotalNominalBTPerdiemDeklarasi() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_perdiem_deklarasi[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_perdiem_deklarasi[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTTransport() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_transport[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_transport[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTTransportDeklarasi() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_transport_deklarasi[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_transport_deklarasi[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTPenginapan() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_penginapan[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_penginapan[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTPenginapanDeklarasi() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_penginapan_deklarasi[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_penginapan_deklarasi[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTLainnya() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_lainnya[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_lainnya[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTLainnyaDeklarasi() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_bt_lainnya_deklarasi[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_bt_lainnya_deklarasi[]"]').value = formatNumber(total);
            }

            function calculateTotalNominalBTTotal() {
                let total = 0;
                document.querySelectorAll('input[name="total_bt_perdiem[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelectorAll('input[name="total_bt_transport[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelectorAll('input[name="total_bt_penginapan[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelectorAll('input[name="total_bt_lainnya[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="totalca_deklarasi"]').value = formatNumber(total);
            }

            function calculateTotalDaysPerdiem(input) {
                const formGroup = input.closest('.mb-2').parentElement;
                const startDateInput = formGroup.querySelector('input[name="start_bt_perdiem[]"]');
                const endDateInput = formGroup.querySelector('input[name="end_bt_perdiem[]"]');
                const totalDaysInput = formGroup.querySelector('input[name="total_days_bt_perdiem[]"]');
                const perdiemInput = document.getElementById('perdiem');
                const allowanceInput = formGroup.querySelector('input[name="nominal_bt_perdiem[]"]');
                const locationSelect = formGroup.querySelector('select[name="location_bt_perdiem[]"]');
                const otherLocationInput = formGroup.querySelector('input[name="other_location_bt_perdiem[]"]');

                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDaysInput.value = totalDays;

                    const perdiem = parseFloat(perdiemInput.value) || 0;
                    let allowance = totalDays * perdiem;

                    // Memeriksa lokasi untuk menentukan persentase allowance
                    if (locationSelect.value === "Others" || otherLocationInput.value.trim() !== '') {
                        allowance *= 1; // allowance * 100%
                    } else {
                        allowance *= 0.5; // allowance * 50%
                    }

                    allowanceInput.value = formatNumberPerdiem(allowance);
                    calculateTotalNominalBTPerdiem();
                } else {
                    totalDaysInput.value = 0;
                    allowanceInput.value = 0;
                }
            }

            function calculateTotalDaysPenginapan(input) {
                const formGroup = input.closest('.mb-2').parentElement;
                const startDate = new Date(formGroup.querySelector('input[name="start_bt_penginapan[]"]').value);
                const endDate = new Date(formGroup.querySelector('input[name="end_bt_penginapan[]"]').value);

                if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    formGroup.querySelector('input[name="total_days_bt_penginapan[]"]').value = diffDays;
                } else {
                    formGroup.querySelector('input[name="total_days_bt_penginapan[]"]').value = 0;
                }
            }

            function addNewPerdiemForm() {
                const newFormBTPerdiem = document.createElement('div');
                newFormBTPerdiem.classList.add('mb-2');

                newFormBTPerdiem.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Start Perdiem</label>
                        <input type="date" name="start_bt_perdiem[]" class="form-control start-perdiem" placeholder="mm/dd/yyyy" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label">End Perdiem</label>
                        <input type="date" name="end_bt_perdiem[]" class="form-control end-perdiem" placeholder="mm/dd/yyyy" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="start">Total Days</label>
                        <div class="input-group">
                            <input class="form-control bg-light total-days-perdiem" id="total_days_bt_perdiem[]" name="total_days_bt_perdiem[]" type="text" min="0" value="0" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">days</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="name">Location Agency</label>
                        <select class="form-control location-select" name="location_bt_perdiem[]">
                            <option value="">Select location...</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->area }}">{{ $location->area." (".$location->company_name.")" }}</option>
                            @endforeach
                            <option value="Others">Others</option>
                        </select>
                        <br>
                        <input type="text" name="other_location_bt_perdiem[]" class="form-control other-location" placeholder="Other Location" value="" style="display: none;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="name">Company Code</label>
                        <select class="form-control select2" name="company_bt_perdiem[]" >
                            <option value="">Select Company...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level." (".$company->contribution_level_code.")" }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Amount</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_bt_perdiem[]" type="text" min="0" value="0">
                    </div>
                    <button type="button" class="btn btn-danger remove-form">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                document.getElementById('form-container-bt-perdiem').appendChild(newFormBTPerdiem);

                formContainerBTPerdiem.appendChild(newFormBTPerdiem);

                newFormBTPerdiem.querySelector('.location-select').addEventListener('change', function() {
                    toggleOthersBT(this);
                });


                // Attach input event to the newly added nominal field
                newFormBTPerdiem.querySelector('input[name="nominal_bt_perdiem[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach change event to the date fields to calculate total days
                newFormBTPerdiem.querySelector('input[name="start_bt_perdiem[]"]').addEventListener('change', function() {
                    calculateTotalDaysPerdiem(this);
                });

                newFormBTPerdiem.querySelector('input[name="end_bt_perdiem[]"]').addEventListener('change', function() {
                    calculateTotalDaysPerdiem(this);
                });

                // Attach click event to the remove button
                newFormBTPerdiem.querySelector('.remove-form').addEventListener('click', function() {
                    newFormBTPerdiem.remove();
                    calculateTotalNominalBTPerdiem();
                    calculateTotalNominalBTTotal();
                });

                // Update the date constraints for the new 'start_bt_perdiem[]' and 'end_bt_perdiem[]' input fields
                const startDateInput = document.getElementById('start_date').value;
                const endDateInput = document.getElementById('end_date').value;

                newFormBTPerdiem.querySelectorAll('input[name="start_bt_perdiem[]"]').forEach(function(input) {
                    input.min = startDateInput;
                    input.max = endDateInput;
                });

                newFormBTPerdiem.querySelectorAll('input[name="end_bt_perdiem[]"]').forEach(function(input) {
                    input.min = startDateInput;
                    input.max = endDateInput;
                });
            }

            function addNewTransportForm() {
                const newFormBTTransport = document.createElement('div');
                newFormBTTransport.classList.add('mb-2');

                newFormBTTransport.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Tanggal Transport</label>
                        <input type="date" name="tanggal_bt_transport[]" class="form-control" placeholder="mm/dd/yyyy" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="name">Company Code</label>
                        <select class="form-control select2" name="company_bt_transport[]" >
                            <option value="">Select Company...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level." (".$company->contribution_level_code.")" }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan_bt_transport[]" class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Amount</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_bt_transport[]" type="text" min="0" value="0">
                    </div>
                    <button type="button" class="btn btn-danger remove-form">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                formContainerBTTransport.appendChild(newFormBTTransport);

                // Attach input event to the newly added nominal field
                newFormBTTransport.querySelector('input[name="nominal_bt_transport[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach click event to the remove button
                newFormBTTransport.querySelector('.remove-form').addEventListener('click', function() {
                    newFormBTTransport.remove();
                    calculateTotalNominalBTTransport();
                    calculateTotalNominalBTTotal();
                });
            }

            function addNewPenginapanForm() {
                const newFormBTPenginapan = document.createElement('div');
                newFormBTPenginapan.classList.add('mb-2');

                newFormBTPenginapan.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Start Penginapan</label>
                        <input type="date" name="start_bt_penginapan[]" class="form-control start-penginapan" placeholder="mm/dd/yyyy">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">End Penginapan</label>
                        <input type="date" name="end_bt_penginapan[]" class="form-control end-penginapan" placeholder="mm/dd/yyyy">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="start">Total Days</label>
                        <div class="input-group">
                            <input class="form-control bg-light total-days-penginapan" id="total_days_bt_penginapan[]" name="total_days_bt_penginapan[]" type="text" min="0" value="0" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">days</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="name">Hotel Name</label>
                        <input type="text" name="hotel_name_bt_penginapan[]" class="form-control" placeholder="Hotel">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="name">Company Code</label>
                        <select class="form-control select2" id="companyFilter" name="company_bt_penginapan[]">
                            <option value="">Select Company...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level." (".$company->contribution_level_code.")" }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Amount</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_bt_penginapan[]" id="nominal_bt_penginapan[]" type="text" min="0" value="0">
                    </div>
                    <button type="button" class="btn btn-danger remove-form">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                formContainerBTPenginapan.appendChild(newFormBTPenginapan);

                // Attach input event to the newly added nominal field
                newFormBTPenginapan.querySelector('input[name="nominal_bt_penginapan[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach change event to the date fields to calculate total days
                newFormBTPenginapan.querySelector('input[name="start_bt_penginapan[]"]').addEventListener('change', function() {
                    calculateTotalDaysPenginapan(this);
                });

                newFormBTPenginapan.querySelector('input[name="end_bt_penginapan[]"]').addEventListener('change', function() {
                    calculateTotalDaysPenginapan(this);
                });

                // Attach click event to the remove button
                newFormBTPenginapan.querySelector('.remove-form').addEventListener('click', function() {
                    newFormBTPenginapan.remove();
                    calculateTotalNominalBTPenginapan();
                    calculateTotalNominalBTTotal();
                });
            }

            function addNewLainnyaForm() {
                const newFormBTLainnya = document.createElement('div');
                newFormBTLainnya.classList.add('mb-2');

                newFormBTLainnya.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_bt_lainnya[]" class="form-control" placeholder="mm/dd/yyyy">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan_bt_lainnya[]" class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Accommodation</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_bt_lainnya[]" type="text" min="0" value="0">
                    </div>
                    <button type="button" class="btn btn-danger remove-form">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                formContainerBTLainnya.appendChild(newFormBTLainnya);

                // Attach input event to the newly added nominal field
                newFormBTLainnya.querySelector('input[name="nominal_bt_lainnya[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach click event to the remove button
                newFormBTLainnya.querySelector('.remove-form').addEventListener('click', function() {
                    newFormBTLainnya.remove();
                    calculateTotalNominalBTLainnya();
                    calculateTotalNominalBTTotal();
                });
            }

            document.getElementById('add-more-bt-perdiem').addEventListener('click', addNewPerdiemForm);
            document.getElementById('add-more-bt-transport').addEventListener('click', addNewTransportForm);
            document.getElementById('add-more-bt-penginapan').addEventListener('click', addNewPenginapanForm);
            document.getElementById('add-more-bt-lainnya').addEventListener('click', addNewLainnyaForm);

            // Attach input event to the existing nominal fields
            document.querySelectorAll('input[name="nominal_bt_perdiem[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            document.querySelectorAll('input[name="nominal_bt_transport[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            document.querySelectorAll('input[name="nominal_bt_penginapan[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            // Attach change event to the existing start and end date fields to calculate total days
            document.querySelectorAll('input[name="start_bt_perdiem[]"], input[name="end_bt_perdiem[]"]').forEach(input => {
                input.addEventListener('change', function() {
                    calculateTotalDaysPerdiem(this);
                });
            });

            document.querySelectorAll('input[name="start_bt_penginapan[]"], input[name="end_bt_penginapan[]"]').forEach(input => {
                input.addEventListener('change', function() {
                    calculateTotalDaysPenginapan(this);
                });
            });

            document.querySelectorAll('input[name="nominal_bt_lainnya[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            // Initial calculation for the total nominal
            calculateTotalNominalBTPerdiem();
            calculateTotalNominalBTPerdiemDeklarasi();
            calculateTotalNominalBTTransport();
            calculateTotalNominalBTTransportDeklarasi();
            calculateTotalNominalBTPenginapan();
            calculateTotalNominalBTPenginapanDeklarasi();
            calculateTotalNominalBTLainnya();
            calculateTotalNominalBTLainnyaDeklarasi();
            calculateTotalNominalBTTotal();

            document.getElementById('start_date').addEventListener('change', handleDateChange);
            document.getElementById('end_date').addEventListener('change', handleDateChange);

            function handleDateChange() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                // Set the min attribute of the end_date input to the selected start_date
                endDateInput.min = startDateInput.value;

                // Validate dates
                if (endDate < startDate) {
                    alert("End Date cannot be earlier than Start Date");
                    endDateInput.value = "";
                }

                // Update min and max values for all dynamic perdiem date fields
                document.querySelectorAll('input[name="start_bt_perdiem[]"]').forEach(function(input) {
                    input.min = startDateInput.value;
                    input.max = endDateInput.value;
                });

                document.querySelectorAll('input[name="end_bt_perdiem[]"]').forEach(function(input) {
                    input.min = startDateInput.value;
                    input.max = endDateInput.value;
                });

                document.querySelectorAll('input[name="total_days_bt_perdiem[]"]').forEach(function(input) {
                    calculateTotalDaysPerdiem(input);
                });
            }



            // Attach click event to the remove button for existing forms
            document.querySelectorAll('.remove-form').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.mb-2').remove();
                    calculateTotalNominalBTPerdiem();
                    calculateTotalNominalBTTransport();
                    calculateTotalNominalBTPenginapan();
                    calculateTotalNominalBTLainnya();
                    calculateTotalNominalBTTotal();
                });
            });
        });

        //

        document.addEventListener('DOMContentLoaded', function() {
            const formContainer = document.getElementById('form-container-nbt');

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function parseNumber(value) {
                return parseFloat(value.replace(/\./g, '')) || 0;
            }

            function formatInput(input) {
                let value = input.value.replace(/\./g, '');
                value = parseFloat(value);
                if (!isNaN(value)) {
                    input.value = formatNumber(Math.floor(value));
                } else {
                    input.value = formatNumber(0);
                }
                calculateTotalNominal();
            }

            function calculateTotalNominal() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_nbt[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.getElementById('totalca').value = formatNumber(total);
            }

            function addForm(data, isFirst = false) {
                const newForm = document.createElement('div');
                newForm.classList.add('mb-2', 'form-group');

                const dateValue = data ? data.tanggal_nbt : '';
                const keteranganValue = data ? data.keterangan_nbt : '';
                const nominalValue = data ? formatNumber(data.nominal_nbt) : "0";

                newForm.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_nbt[]" class="form-control" value="${dateValue}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan_nbt[]" class="form-control">${keteranganValue}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Accommodation</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_nbt[]" type="text" min="0" value="${nominalValue}">
                    </div>
                `;

                // Only add Remove button if it's not the first form
                if (!isFirst) {
                    newForm.innerHTML += `<button type="button" class="btn btn-danger remove-form">Remove</button>`;
                }

                newForm.innerHTML += `<hr class="border border-primary border-1 opacity-50">`;
                formContainer.appendChild(newForm);

                // Attach input event to the newly added nominal field
                newForm.querySelector('input[name="nominal_nbt[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach click event to the remove button if it exists
                if (!isFirst) {
                    newForm.querySelector('.remove-form').addEventListener('click', function() {
                        newForm.remove();
                        calculateTotalNominal();
                    });
                }
            }

            initialDetailCA.forEach((item, index) => {
                addForm(item, index === 0);  // Pass true if it's the first item
            });

            document.getElementById('add-more').addEventListener('click', function () {
                addForm(); // Add an empty form
            });

            // Attach input event to the existing nominal fields
            document.querySelectorAll('input[name="nominal_nbt[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            calculateTotalNominal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const formContainerEDetail = document.getElementById('form-container-e-detail');
            const formContainerERelation = document.getElementById('form-container-e-relation');

            // Function to update checkboxes visibility based on selected options
            function updateCheckboxVisibility() {
                // Gather all selected options from enter_type_e_detail
                const selectedOptions = Array.from(document.querySelectorAll('select[name="enter_type_e_detail[]"]'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                // Update visibility for each checkbox in enter_type_e_relation
                formContainerERelation.querySelectorAll('.form-check').forEach(checkDiv => {
                    const checkbox = checkDiv.querySelector('input.form-check-input');
                    const checkboxValue = checkbox.value.toLowerCase().replace(/\s/g, "_");
                    if (selectedOptions.includes(checkboxValue)) {
                        checkDiv.style.display = 'block';
                    } else {
                        checkDiv.style.display = 'none';
                    }
                });
            }

            function updateCheckboxVisibilityDeklarasi() {
                // Gather all selected options from enter_type_e_detail
                const selectedOptions = Array.from(document.querySelectorAll('select[name="enter_type_e_detail_deklarasi[]"]'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                // Update visibility for each checkbox in enter_type_e_relation
                formContainerERelation.querySelectorAll('.form-check').forEach(checkDiv => {
                    const checkbox = checkDiv.querySelector('input.form-check-input');
                    const checkboxValue = checkbox.value.toLowerCase().replace(/\s/g, "_");
                    if (selectedOptions.includes(checkboxValue)) {
                        checkDiv.style.display = 'block';
                    } else {
                        checkDiv.style.display = 'none';
                    }
                });
            }

            // Function to format number with thousands separator
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Function to parse number from formatted string
            function parseNumber(value) {
                return parseFloat(value.replace(/\./g, '')) || 0;
            }

            // Function to format input fields
            function formatInput(input) {
                let value = input.value.replace(/\./g, '');
                value = parseFloat(value);
                if (!isNaN(value)) {
                    input.value = formatNumber(Math.floor(value));
                } else {
                    input.value = formatNumber(0);
                }
                calculateTotalNominalEDetail();
                calculateTotalNominalEDetailDeklarasi();
            }

            // Function to calculate the total nominal value for EDetail
            function calculateTotalNominalEDetail() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_e_detail[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_e_detail[]"]').value = formatNumber(total);
                document.getElementById('totalca').value = formatNumber(total);
            }

            function calculateTotalNominalEDetailDeklarasi() {
                let total = 0;
                document.querySelectorAll('input[name="nominal_e_detail_deklarasi[]"]').forEach(input => {
                    total += parseNumber(input.value);
                });
                document.querySelector('input[name="total_e_detail_deklarasi[]"]').value = formatNumber(total);
            }

            // Function to add new EDetail form
            function addNewEDetailForm() {
                const newFormEDetail = document.createElement('div');
                newFormEDetail.classList.add('mb-2');

                newFormEDetail.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Entertainment Type</label>
                        <select name="enter_type_e_detail[]" class="form-select">
                            <option value="">-</option>
                            <option value="food">Food/Beverages/Souvenir</option>
                            <option value="transport">Transport</option>
                            <option value="accommodation">Accommodation</option>
                            <option value="gift">Gift</option>
                            <option value="fund">Fund</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Entertainment Fee Detail</label>
                        <textarea name="enter_fee_e_detail[]" class="form-control"></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_e_detail[]" type="text" min="0" value="0">
                    </div>
                    <button type="button" class="btn btn-danger remove-form-e-detail">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                formContainerEDetail.appendChild(newFormEDetail);

                // Attach input event to the newly added nominal field
                newFormEDetail.querySelector('input[name="nominal_e_detail[]"]').addEventListener('input', function() {
                    formatInput(this);
                });

                // Attach change event to update checkbox visibility
                newFormEDetail.querySelector('select[name="enter_type_e_detail[]"]').addEventListener('change', updateCheckboxVisibility);

                // Attach click event to the remove button
                newFormEDetail.querySelector('.remove-form-e-detail').addEventListener('click', function() {
                    newFormEDetail.remove();
                    updateCheckboxVisibility();
                    calculateTotalNominalEDetail();
                });
            }

            // Function to add new ERelation form
            function addNewERelationForm() {
                const newFormERelation = document.createElement('div');
                newFormERelation.classList.add('mb-2');

                newFormERelation.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">Relation Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="accommodation_e_relation[]" id="transport_e_relation[]" value="transport">
                            <label class="form-check-label" for="transport_e_relation[]">Transport</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="transport_e_relation[]" id="accommodation_e_relation[]" value="accommodation">
                            <label class="form-check-label" for="accommodation_e_relation[]">Accommodation</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="gift_e_relation[]" id="gift_e_relation[]" value="gift">
                            <label class="form-check-label" for="gift_e_relation[]">Gift</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="fund_e_relation[]" type="checkbox" id="fund_e_relation[]" value="fund">
                            <label class="form-check-label" for="fund_e_relation[]">Fund</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="food_e_relation[]" name="food_e_relation[]" value="food">
                            <label class="form-check-label" for="food_e_relation[]">Food/Beverages/Souvenir</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input type="text" name="rname_e_relation[]" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Position</label>
                        <input type="text" name="rposition_e_relation[]" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Company</label>
                        <input type="text" name="rcompany_e_relation[]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <input type="text" name="rpurpose_e_relation[]" class="form-control">
                    </div>
                    <button type="button" class="btn btn-danger remove-form-e-relation">Remove</button>
                    <hr class="border border-primary border-1 opacity-50">
                `;

                formContainerERelation.appendChild(newFormERelation);

                // Initial update of checkbox visibility
                updateCheckboxVisibility();

                // Attach click event to the remove button
                newFormERelation.querySelector('.remove-form-e-relation').addEventListener('click', function() {
                    newFormERelation.remove();
                    updateCheckboxVisibility();
                });
            }

            document.getElementById('add-more-e-detail').addEventListener('click', addNewEDetailForm);
            document.getElementById('add-more-e-relation').addEventListener('click', addNewERelationForm);

            // Attach input event to the existing nominal fields
            document.querySelectorAll('input[name="nominal_e_detail[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            document.querySelectorAll('input[name="nominal_e_detail_deklarasi[]"]').forEach(input => {
                input.addEventListener('input', function() {
                    formatInput(this);
                });
            });

            // Attach change event to existing select fields for checkbox visibility
            document.querySelectorAll('select[name="enter_type_e_detail[]"]').forEach(select => {
                select.addEventListener('change', updateCheckboxVisibility);
            });

            document.querySelectorAll('select[name="enter_type_e_detail_deklarasi[]"]').forEach(select => {
                select.addEventListener('change', updateCheckboxVisibilityDeklarasi);
            });

            calculateTotalNominalEDetail();
            calculateTotalNominalEDetailDeklarasi();
            updateCheckboxVisibility();
            updateCheckboxVisibilityDeklarasi();
        });

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta3/js/bootstrap.min.js"></script>
@endpush