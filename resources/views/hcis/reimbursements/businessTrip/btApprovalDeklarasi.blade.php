@extends('layouts_.vertical', ['page_title' => 'Business Trip'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Declaration Data</h4>
                        <a href="{{ route('businessTrip.approval') }}" class="btn-close btn-close-white"></a>
                    </div>
                    <div class="card-body">
                        <form action="/businessTrip/deklarasi/update/{{ $n->id }}" method="POST" id="btEditForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="no_sppd" class="form-label">No SPPD</label>
                                    <input type="text" class="form-control bg-light" id="no_sppd" name="no_sppd"
                                        value="{{ $n->no_sppd }}" readonly>
                                </div>

                                {{-- <div class="row mb-4"> --}}
                                <div class="col-md-4">
                                    <label for="mulai" class="form-label">Start Date</label>
                                    <input type="date" class="form-control bg-light" id="mulai" name="mulai"
                                        value="{{ $n->mulai }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="kembali" class="form-label">End Date</label>
                                    <input type="date" class="form-control bg-light" id="kembali" name="kembali"
                                        value="{{ $n->kembali }}" readonly>
                                    {{-- </div> --}}
                                </div>
                            </div>
                            <!-- 1st Form -->
                            <div class="row mt-2" id="ca_div">
                                <div class="col-md-6">
                                    <div class="table-responsive-sm">
                                        <div class="d-flex flex-column gap-2">
                                            <div class="text-bg-primary p-2" style="text-align:center; border-radius:4px;">
                                                Cash Advanced</div>
                                            <div class="row" id="ca_bt" style="">
                                                <div class="col-md-12">
                                                    <div class="table-responsive-sm">
                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-perdiem-deklarasi"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Perdiem</button>
                                                                </div>
                                                                <div id="perdiem-card-deklarasi" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionPerdiem">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="enter-headingOne">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button" data-bs-toggle="collapse"
                                                                                    data-bs-target="#enter-collapseOne"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="enter-collapseOne">
                                                                                    Perdiem Plan
                                                                                </button>
                                                                            </h2>
                                                                            <div id="enter-collapseOne"
                                                                                class="accordion-collapse show"
                                                                                aria-labelledby="enter-headingOne">
                                                                                <div class="accordion-body">
                                                                                    <div
                                                                                        id="form-container-bt-perdiem-deklarasi">
                                                                                        @php
                                                                                            // Provide default empty arrays if caDetail or sections are not set
                                                                                            $detailPerdiem =
                                                                                                $caDetail[
                                                                                                    'detail_perdiem'
                                                                                                ] ?? [];
                                                                                            $detailTransport =
                                                                                                $caDetail[
                                                                                                    'detail_transport'
                                                                                                ] ?? [];
                                                                                            $detailPenginapan =
                                                                                                $caDetail[
                                                                                                    'detail_penginapan'
                                                                                                ] ?? [];
                                                                                            $detailLainnya =
                                                                                                $caDetail[
                                                                                                    'detail_lainnya'
                                                                                                ] ?? [];

                                                                                            // Calculate totals with default values
                                                                                            $totalPerdiem = array_reduce(
                                                                                                $detailPerdiem,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalTransport = array_reduce(
                                                                                                $detailTransport,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalPenginapan = array_reduce(
                                                                                                $detailPenginapan,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalLainnya = array_reduce(
                                                                                                $detailLainnya,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            // Total Cash Advanced
                                                                                            $totalCashAdvanced =
                                                                                                $totalPerdiem +
                                                                                                $totalTransport +
                                                                                                $totalPenginapan +
                                                                                                $totalLainnya;
                                                                                        @endphp
                                                                                        @if (!empty($detailPerdiem))
                                                                                            @foreach ($detailPerdiem as $index => $perdiem)
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Start
                                                                                                        Perdiem</label>
                                                                                                    <input type="date"
                                                                                                        name="start_bt_perdiem[]"
                                                                                                        class="form-control start-perdiem"
                                                                                                        value="{{ old('start_bt_perdiem.' . $index, $perdiem['start_date'] ?? '') }}"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">End
                                                                                                        Perdiem</label>
                                                                                                    <input type="date"
                                                                                                        name="end_bt_perdiem[]"
                                                                                                        class="form-control end-perdiem"
                                                                                                        value="{{ old('end_bt_perdiem.' . $index, $perdiem['end_date'] ?? '') }}"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="start">Total
                                                                                                        Days</label>
                                                                                                    <div
                                                                                                        class="input-group">
                                                                                                        <input
                                                                                                            class="form-control bg-light total-days-perdiem"
                                                                                                            id="total_days_bt_perdiem_{{ $index }}"
                                                                                                            name="total_days_bt_perdiem[]"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            value="{{ old('total_days_bt_perdiem.' . $index, $perdiem['total_days'] ?? '') }}"
                                                                                                            readonly>
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">days</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Location
                                                                                                        Agency</label>
                                                                                                    <select
                                                                                                        class="form-control select2 location-select"
                                                                                                        name="location_bt_perdiem[]"
                                                                                                        disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            location...
                                                                                                        </option>
                                                                                                        @foreach ($locations as $location)
                                                                                                            <option
                                                                                                                value="{{ $location->area }}"
                                                                                                                {{ ($perdiem['location'] ?? '') == $location->area ? 'selected' : '' }}>
                                                                                                                {{ $location->area . ' (' . $location->company_name . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                        <option
                                                                                                            value="Others"
                                                                                                            {{ ($perdiem['location'] ?? '') == 'Others' ? 'selected' : '' }}
                                                                                                            disabled>
                                                                                                            Others
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    <br>
                                                                                                    <input type="text"
                                                                                                        name="other_location_bt_perdiem[]"
                                                                                                        class="form-control other-location"
                                                                                                        placeholder="Other Location"
                                                                                                        value="{{ old('other_location_bt_perdiem.' . $index, $perdiem['other_location'] ?? '') }}"
                                                                                                        style="{{ ($perdiem['location'] ?? '') == 'Others' ? 'display:block;' : 'display:none;' }}"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_{{ $index }}"
                                                                                                        name="company_bt_perdiem[]"
                                                                                                        disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            ---
                                                                                                            Select
                                                                                                            Company
                                                                                                            ---
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}"
                                                                                                                {{ ($perdiem['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        $index =
                                                                                                            $index ?? 0;
                                                                                                        $nominalPerdiem =
                                                                                                            $nominalPerdiem ??
                                                                                                            0; // Default to 0 if $totalPerdiem is not set
                                                                                                        $formattedNominalPerdiem = number_format(
                                                                                                            $nominalPerdiem,
                                                                                                            0,
                                                                                                            ',',
                                                                                                            '.',
                                                                                                        );
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_perdiem_deklarasi[]"
                                                                                                        id="nominal_bt_perdiem_{{ $index }}"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('nominal_bt_perdiem.' . $index, $formattedNominalPerdiem) }}"
                                                                                                        disabled>

                                                                                                </div>
                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endforeach
                                                                                        @else
                                                                                            <!-- Default empty fields if no data is available -->
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Start
                                                                                                    Perdiem</label>
                                                                                                <input type="date"
                                                                                                    name="start_bt_perdiem[]"
                                                                                                    class="form-control start-perdiem"
                                                                                                    disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">End
                                                                                                    Perdiem</label>
                                                                                                <input type="date"
                                                                                                    name="end_bt_perdiem[]"
                                                                                                    class="form-control end-perdiem"
                                                                                                    disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="start">Total
                                                                                                    Days</label>
                                                                                                <div class="input-group">
                                                                                                    <input
                                                                                                        class="form-control bg-light total-days-perdiem"
                                                                                                        id="total_days_bt_perdiem_0"
                                                                                                        name="total_days_bt_perdiem[]"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        readonly>
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">days</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Location
                                                                                                    Agency</label>
                                                                                                <select
                                                                                                    class="form-control select2 location-select"
                                                                                                    name="location_bt_perdiem[]"
                                                                                                    disabled>
                                                                                                    <option value="">
                                                                                                        Select
                                                                                                        location...
                                                                                                    </option>
                                                                                                    @foreach ($locations as $location)
                                                                                                        <option
                                                                                                            value="{{ $location->area }}">
                                                                                                            {{ $location->area . ' (' . $location->company_name . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                    <option value="Others">
                                                                                                        Others
                                                                                                    </option>
                                                                                                </select>
                                                                                                <br>
                                                                                                <input type="text"
                                                                                                    name="other_location_bt_perdiem[]"
                                                                                                    class="form-control other-location"
                                                                                                    placeholder="Other Location"
                                                                                                    style="display:none;"
                                                                                                    disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Company
                                                                                                    Code</label>
                                                                                                <select
                                                                                                    class="form-control select2"
                                                                                                    id="companyFilter_0"
                                                                                                    name="company_bt_perdiem[]"
                                                                                                    disabled>
                                                                                                    <option value="">
                                                                                                        --- Select
                                                                                                        Company ---
                                                                                                    </option>
                                                                                                    @foreach ($companies as $company)
                                                                                                        <option
                                                                                                            value="{{ $company->contribution_level_code }}">
                                                                                                            {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Amount</label>
                                                                                            </div>
                                                                                            <div class="input-group mb-3">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                <input class="form-control"
                                                                                                    name="nominal_bt_perdiem[]"
                                                                                                    id="nominal_bt_perdiem_0"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    disabled>
                                                                                            </div>
                                                                                            <hr
                                                                                                class="border border-primary border-1 opacity-50">
                                                                                        @endif

                                                                                        <div class="mb-2">
                                                                                            <label class="form-label">Total
                                                                                                Perdiem</label>
                                                                                            <div class="input-group">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>

                                                                                                @php
                                                                                                    $index =
                                                                                                        $index ?? 0;
                                                                                                    $totalPerdiem =
                                                                                                        $totalPerdiem ??
                                                                                                        0; // Default to 0 if $totalPerdiem is not set
                                                                                                    $formattedTotalPerdiem = number_format(
                                                                                                        $totalPerdiem,
                                                                                                        0,
                                                                                                        ',',
                                                                                                        '.',
                                                                                                    );
                                                                                                @endphp
                                                                                                <input class="form-control"
                                                                                                    name="nominal_bt_perdiem_deklarasi[]"
                                                                                                    id="nominal_bt_perdiem_{{ $index }}"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    value="{{ old('nominal_bt_perdiem.' . $index, $formattedTotalPerdiem) }}"
                                                                                                    disabled>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Button and Card for Transport -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-transport-deklarasi"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Transport</button>
                                                                </div>
                                                                <div id="transport-card-deklarasi" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionTransport">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingTransport">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseTransport"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapseTransport">
                                                                                    Rencana Transport
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseTransport"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingTransport">
                                                                                <div class="accordion-body">
                                                                                    <div
                                                                                        id="form-container-bt-transport-deklarasi">
                                                                                        @php
                                                                                            // Provide default empty array if detail_transport is not set
                                                                                            $detailTransport =
                                                                                                $caDetail[
                                                                                                    'detail_transport'
                                                                                                ] ?? [];

                                                                                            // Calculate total transport cost with default values
                                                                                            $totalTransport = array_reduce(
                                                                                                $detailTransport,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp
                                                                                        @if (!empty($detailTransport))
                                                                                            @foreach ($detailTransport as $index => $transport)
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Tanggal
                                                                                                        Transport</label>
                                                                                                    <input type="date"
                                                                                                        name="tanggal_bt_transport[]"
                                                                                                        class="form-control"
                                                                                                        placeholder="mm/dd/yyyy"
                                                                                                        value="{{ old('tanggal_bt_transport.' . $index, $transport['tanggal'] ?? '') }}"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_{{ $index }}"
                                                                                                        name="company_bt_transport[]"
                                                                                                        disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            Company...
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}"
                                                                                                                {{ ($transport['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Keterangan</label>
                                                                                                    <textarea name="keterangan_bt_transport[]" class="form-control" placeholder="Write your information here..."
                                                                                                        disabled>{{ old('keterangan_bt_transport.' . $index, $transport['keterangan'] ?? '') }}</textarea>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>

                                                                                                    @php
                                                                                                        $totalTransport =
                                                                                                            $transport[
                                                                                                                'nominal'
                                                                                                            ] ?? 0; // Default to 0 if $transport['nominal'] is not set
                                                                                                        $formattedTotalTransport = number_format(
                                                                                                            $totalTransport,
                                                                                                            0,
                                                                                                            ',',
                                                                                                            '.',
                                                                                                        );
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_transport_deklarasi[]"
                                                                                                        id="nominal_bt_transport_{{ $index }}"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('nominal_bt_transport.' . $index, $formattedTotalTransport) }}"
                                                                                                        disabled>

                                                                                                </div>

                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endforeach
                                                                                        @else
                                                                                            <!-- Default empty fields if no data is available -->
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Tanggal
                                                                                                    Transport</label>
                                                                                                <input type="date"
                                                                                                    name="tanggal_bt_transport[]"
                                                                                                    class="form-control"
                                                                                                    placeholder="mm/dd/yyyy"
                                                                                                    disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Company
                                                                                                    Code</label>
                                                                                                <select
                                                                                                    class="form-control select2"
                                                                                                    id="companyFilter_0"
                                                                                                    name="company_bt_transport[]"
                                                                                                    disabled>
                                                                                                    <option value="">
                                                                                                        Select
                                                                                                        Company...
                                                                                                    </option>
                                                                                                    @foreach ($companies as $company)
                                                                                                        <option
                                                                                                            value="{{ $company->contribution_level_code }}">
                                                                                                            {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Keterangan</label>
                                                                                                <textarea name="keterangan_bt_transport[]" class="form-control" placeholder="Write your information here..."
                                                                                                    disabled></textarea>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Amount</label>
                                                                                            </div>
                                                                                            <div class="input-group mb-3">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                <input class="form-control"
                                                                                                    name="nominal_bt_transport[]"
                                                                                                    id="nominal_bt_transport_0"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    disabled>
                                                                                            </div>

                                                                                            <hr
                                                                                                class="border border-primary border-1 opacity-50">
                                                                                        @endif

                                                                                        <div class="mb-2">
                                                                                            <label class="form-label">Total
                                                                                                Transport</label>
                                                                                            <div class="input-group">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                @php
                                                                                                    $totalTransport =
                                                                                                        $totalTransport ??
                                                                                                        0; // Default to 0 if $totalTransport is not set
                                                                                                    $formattedTotalTransport = number_format(
                                                                                                        $totalTransport,
                                                                                                        0,
                                                                                                        ',',
                                                                                                        '.',
                                                                                                    );
                                                                                                @endphp
                                                                                                <input
                                                                                                    class="form-control bg-light"
                                                                                                    name="total_bt_transport_deklarasi[]"
                                                                                                    id="total_bt_transport_deklarasi[]"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    value="{{ $formattedTotalTransport }}"
                                                                                                    readonly>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Button and Card for Penginapan -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-penginapan-deklarasi"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Accommodation</button>
                                                                </div>
                                                                <div id="penginapan-card-deklarasi" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionPenginapan">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingPenginapan">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapsePenginapan"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapsePenginapan">
                                                                                    Rencana Penginapan
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapsePenginapan"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingPenginapan">
                                                                                <div class="accordion-body">
                                                                                    <div
                                                                                        id="form-container-bt-penginapan-deklarasi">
                                                                                        @php
                                                                                            // Default empty array if 'detail_penginapan' is not set
                                                                                            $penginapan =
                                                                                                $caDetail[
                                                                                                    'detail_penginapan'
                                                                                                ] ?? [];

                                                                                            // Calculate total penginapan cost
                                                                                            $totalPenginapanCost = array_reduce(
                                                                                                $penginapan,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp

                                                                                        <!-- Form for Penginapan Details -->
                                                                                        <div
                                                                                            id="form-container-bt-penginapan-deklarasi">
                                                                                            @if (!empty($penginapan))
                                                                                                @foreach ($penginapan as $index => $item)
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Start
                                                                                                            Penginapan</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="start_bt_penginapan[]"
                                                                                                            class="form-control start-penginapan"
                                                                                                            placeholder="mm/dd/yyyy"
                                                                                                            value="{{ old('start_bt_penginapan.' . $index, $item['start_date'] ?? '') }}"
                                                                                                            disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">End
                                                                                                            Penginapan</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="end_bt_penginapan[]"
                                                                                                            class="form-control end-penginapan"
                                                                                                            placeholder="mm/dd/yyyy"
                                                                                                            value="{{ old('end_bt_penginapan.' . $index, $item['end_date'] ?? '') }}"
                                                                                                            disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="start">Total
                                                                                                            Days</label>
                                                                                                        <div
                                                                                                            class="input-group">
                                                                                                            <input
                                                                                                                class="form-control bg-light total-days-penginapan"
                                                                                                                id="total_days_bt_penginapan_{{ $index }}"
                                                                                                                name="total_days_bt_penginapan[]"
                                                                                                                type="text"
                                                                                                                min="0"
                                                                                                                value="{{ old('total_days_bt_penginapan.' . $index, $item['total_days'] ?? '0') }}"
                                                                                                                readonly>
                                                                                                            <div
                                                                                                                class="input-group-append">
                                                                                                                <span
                                                                                                                    class="input-group-text">days</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="name">Hotel
                                                                                                            Name</label>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="hotel_name_bt_penginapan[]"
                                                                                                            class="form-control"
                                                                                                            placeholder="ex: Westin"
                                                                                                            value="{{ old('hotel_name_bt_penginapan.' . $index, $item['hotel_name'] ?? '') }}"
                                                                                                            disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="name">Company
                                                                                                            Code</label>
                                                                                                        <select
                                                                                                            class="form-control select2"
                                                                                                            id="companyFilter_{{ $index }}"
                                                                                                            name="company_bt_penginapan[]"
                                                                                                            disabled>
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                Company...
                                                                                                            </option>
                                                                                                            @foreach ($companies as $company)
                                                                                                                <option
                                                                                                                    value="{{ $company->contribution_level_code }}"
                                                                                                                    {{ ($item['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                    {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Amount</label>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="input-group mb-3">
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">Rp</span>
                                                                                                        </div>
                                                                                                        @php
                                                                                                            $nominalPenginapan =
                                                                                                                $item[
                                                                                                                    'nominal'
                                                                                                                ] ?? 0; // Default to 0 if $item['nominal'] is not set
                                                                                                            $formattedNominalPenginapan = number_format(
                                                                                                                $nominalPenginapan,
                                                                                                                0,
                                                                                                                ',',
                                                                                                                '.',
                                                                                                            );
                                                                                                        @endphp
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            name="nominal_bt_penginapan_deklarasi[]"
                                                                                                            id="nominal_bt_penginapan_{{ $index }}"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            value="{{ old('nominal_bt_penginapan.' . $index, $formattedNominalPenginapan) }}"
                                                                                                            disabled>
                                                                                                    </div>

                                                                                                    <hr
                                                                                                        class="border border-primary border-1 opacity-50">
                                                                                                @endforeach
                                                                                            @else
                                                                                                <!-- Default empty fields if no data is available -->
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Start
                                                                                                        Penginapan</label>
                                                                                                    <input type="date"
                                                                                                        name="start_bt_penginapan[]"
                                                                                                        class="form-control start-penginapan"
                                                                                                        placeholder="mm/dd/yyyy"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">End
                                                                                                        Penginapan</label>
                                                                                                    <input type="date"
                                                                                                        name="end_bt_penginapan[]"
                                                                                                        class="form-control end-penginapan"
                                                                                                        placeholder="mm/dd/yyyy"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="start">Total
                                                                                                        Days</label>
                                                                                                    <div
                                                                                                        class="input-group">
                                                                                                        <input
                                                                                                            class="form-control bg-light total-days-penginapan"
                                                                                                            id="total_days_bt_penginapan_0"
                                                                                                            name="total_days_bt_penginapan[]"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            readonly>
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">days</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Hotel
                                                                                                        Name</label>
                                                                                                    <input type="text"
                                                                                                        name="hotel_name_bt_penginapan[]"
                                                                                                        class="form-control"
                                                                                                        placeholder="ex: Westin"
                                                                                                        disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_0"
                                                                                                        name="company_bt_penginapan[]"
                                                                                                        disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            Company...
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}">
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_penginapan[]"
                                                                                                        id="nominal_bt_penginapan_0"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        disabled>
                                                                                                </div>

                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endif

                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Total
                                                                                                    Penginapan</label>
                                                                                                <div class="input-group">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        $totalPenginapanCost =
                                                                                                            $totalPenginapanCost ??
                                                                                                            0; // Default to 0 if $totalPenginapanCost is not set
                                                                                                        $formattedTotalPenginapanCost = number_format(
                                                                                                            $totalPenginapanCost,
                                                                                                            0,
                                                                                                            ',',
                                                                                                            '.',
                                                                                                        );
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control bg-light"
                                                                                                        name="total_bt_penginapan_deklarasi[]"
                                                                                                        id="total_bt_penginapan_deklarasi"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ $formattedTotalPenginapanCost }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <!-- Button and Card for Lainnya -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-lainnya-deklarasi"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Others</button>
                                                                </div>
                                                                <div id="lainnya-card-deklarasi" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionLainnya">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingLainnya">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseLainnya"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapseLainnya">
                                                                                    Rencana Lainnya
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseLainnya"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingLainnya">
                                                                                <div class="accordion-body">
                                                                                    <div
                                                                                        id="form-container-bt-lainnya-deklarasi">
                                                                                        @php
                                                                                            // Default empty array if 'detail_lainnya' is not set
                                                                                            $lainnya =
                                                                                                $caDetail[
                                                                                                    'detail_lainnya'
                                                                                                ] ?? [];

                                                                                            // Calculate total lainnya cost
                                                                                            $totalLainnyaCost = array_reduce(
                                                                                                $lainnya,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp

                                                                                        <div
                                                                                            id="form-container-bt-lainnya-deklarasi">
                                                                                            @if (!empty($lainnya))
                                                                                                @foreach ($lainnya as $index => $lainnyaItem)
                                                                                                    <div
                                                                                                        class="lainnya-item">
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Tanggal</label>
                                                                                                            <input
                                                                                                                type="date"
                                                                                                                name="tanggal_bt_lainnya[]"
                                                                                                                class="form-control"
                                                                                                                value="{{ old('tanggal_bt_lainnya.' . $index, $lainnyaItem['tanggal'] ?? '') }}"
                                                                                                                placeholder="mm/dd/yyyy"
                                                                                                                disabled>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Keterangan</label>
                                                                                                            <textarea name="keterangan_bt_lainnya[]" class="form-control" placeholder="Write your other purposes ..." disabled>{{ old('keterangan_bt_lainnya.' . $index, $lainnyaItem['keterangan'] ?? '') }}</textarea>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Amount</label>
                                                                                                            <div
                                                                                                                class="input-group mb-3">
                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <span
                                                                                                                        class="input-group-text">Rp</span>
                                                                                                                </div>
                                                                                                                @php
                                                                                                                    $nominalLainnya =
                                                                                                                        $lainnyaItem[
                                                                                                                            'nominal'
                                                                                                                        ] ??
                                                                                                                        0; // Default to 0 if not set
                                                                                                                    $formattedNominalLainnya = number_format(
                                                                                                                        $nominalLainnya,
                                                                                                                        0,
                                                                                                                        ',',
                                                                                                                        '.',
                                                                                                                    );
                                                                                                                @endphp
                                                                                                                <input
                                                                                                                    class="form-control nominal-lainnya"
                                                                                                                    name="nominal_bt_lainnya_deklarasi[]"
                                                                                                                    type="text"
                                                                                                                    min="0"
                                                                                                                    value="{{ old('nominal_bt_lainnya.' . $index, $formattedNominalLainnya) }}"
                                                                                                                    disabled>

                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <hr
                                                                                                            class="border border-primary border-1 opacity-50">
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <div class="lainnya-item">
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Tanggal</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="tanggal_bt_lainnya[]"
                                                                                                            class="form-control"
                                                                                                            placeholder="mm/dd/yyyy"
                                                                                                            disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Keterangan</label>
                                                                                                        <textarea name="keterangan_bt_lainnya[]" class="form-control" placeholder="Write your other purposes ..." disabled></textarea>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Amount</label>
                                                                                                        <div
                                                                                                            class="input-group mb-3">
                                                                                                            <div
                                                                                                                class="input-group-append">
                                                                                                                <span
                                                                                                                    class="input-group-text">Rp</span>
                                                                                                            </div>
                                                                                                            <input
                                                                                                                class="form-control nominal-lainnya"
                                                                                                                name="nominal_bt_lainnya[]"
                                                                                                                type="text"
                                                                                                                min="0"
                                                                                                                value="0"
                                                                                                                disabled>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <hr
                                                                                                        class="border border-primary border-1 opacity-50">
                                                                                                </div>
                                                                                            @endif

                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Total
                                                                                                    Lainnya</label>
                                                                                                <div class="input-group">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        $index =
                                                                                                            $index ?? 0;
                                                                                                        $totalLainnya =
                                                                                                            $totalLainnya ??
                                                                                                            0; // Default to 0 if $totalLainnya is not set
                                                                                                        $formattedTotalLainnya = number_format(
                                                                                                            $totalLainnya,
                                                                                                            0,
                                                                                                            ',',
                                                                                                            '.',
                                                                                                        );
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control bg-light"
                                                                                                        name="total_bt_lainnya_deklarasi[]"
                                                                                                        id="total_bt_lainnya_deklarasi[]"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('total_bt_lainnya.' . $index, $formattedTotalLainnya) }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- </div> --}}
                                                                {{-- </div> --}}
                                                            </div>
                                                        </div>
                                                        <br>
                                                        @php
                                                            // Provide default empty arrays if any section is not set
                                                            $detailPerdiem = $caDetail['detail_perdiem'] ?? [];
                                                            $detailTransport = $caDetail['detail_transport'] ?? [];
                                                            $detailPenginapan = $caDetail['detail_penginapan'] ?? [];
                                                            $detailLainnya = $caDetail['detail_lainnya'] ?? [];

                                                            // Calculate total costs for each section
                                                            $totalPerdiem = array_reduce(
                                                                $detailPerdiem,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalTransport = array_reduce(
                                                                $detailTransport,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalPenginapan = array_reduce(
                                                                $detailPenginapan,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalLainnya = array_reduce(
                                                                $detailLainnya,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            // Total Cash Advanced
                                                            $totalCashAdvanced =
                                                                $totalPerdiem +
                                                                $totalTransport +
                                                                $totalPenginapan +
                                                                $totalLainnya;
                                                            $formattedTotalCashAdvanced = number_format(
                                                                $totalCashAdvanced,
                                                                0,
                                                                ',',
                                                                '.',
                                                            );
                                                        @endphp
                                                        <div class="col-md-12 mb-2">
                                                            <label class="form-label">Total Cash Advanced</label>
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input class="form-control bg-light"
                                                                    name="totalca_deklarasi" id="totalca_deklarasi"
                                                                    type="text" min="0"
                                                                    value="{{ $formattedTotalCashAdvanced }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- 2ND FORM --}}
                                <div class="col-md-6">
                                    <div class="table-responsive-sm">
                                        <div class="d-flex flex-column gap-2">
                                            <div class="text-bg-primary p-2"
                                                style="text-align:center; border-radius:4px;">Cash Advanced Declaration
                                            </div>
                                            <div class="row" id="ca_bt" style="">
                                                <div class="col-md-12">
                                                    <div class="table-responsive-sm">
                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-perdiem"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Perdiem</button>
                                                                </div>
                                                                <div id="perdiem-card" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionPerdiem">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="enter-headingOne">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#enter-collapseOne"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="enter-collapseOne">
                                                                                    Perdiem Plan
                                                                                </button>
                                                                            </h2>
                                                                            <div id="enter-collapseOne"
                                                                                class="accordion-collapse show"
                                                                                aria-labelledby="enter-headingOne">
                                                                                <div class="accordion-body">
                                                                                    <div id="form-container-bt-perdiem">
                                                                                        @php
                                                                                            $detailPerdiem2 =
                                                                                                $declareCa[
                                                                                                    'detail_perdiem'
                                                                                                ] ?? [];
                                                                                            $detailTransport2 =
                                                                                                $declareCa[
                                                                                                    'detail_transport'
                                                                                                ] ?? [];
                                                                                            $detailPenginapan2 =
                                                                                                $declareCa[
                                                                                                    'detail_penginapan'
                                                                                                ] ?? [];
                                                                                            $detailLainnya2 =
                                                                                                $declareCa[
                                                                                                    'detail_lainnya'
                                                                                                ] ?? [];
                                                                                            $totalPerdiem2 = array_reduce(
                                                                                                $detailPerdiem2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalTransport2 = array_reduce(
                                                                                                $detailTransport2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalPenginapan2 = array_reduce(
                                                                                                $detailPenginapan2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );

                                                                                            $totalLainnya2 = array_reduce(
                                                                                                $detailLainnya2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp
                                                                                        @if (!empty($detailPerdiem2))
                                                                                            @foreach ($detailPerdiem2 as $index => $perdiem2)
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Start
                                                                                                        Perdiem</label>
                                                                                                    <input type="date"
                                                                                                        name="start_bt_perdiem[]"
                                                                                                        class="form-control start-perdiem"
                                                                                                        value="{{ old('start_bt_perdiem.' . $index, $perdiem2['start_date'] ?? '') }}" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">End
                                                                                                        Perdiem</label>
                                                                                                    <input type="date"
                                                                                                        name="end_bt_perdiem[]"
                                                                                                        class="form-control end-perdiem"
                                                                                                        value="{{ old('end_bt_perdiem.' . $index, $perdiem2['end_date'] ?? '') }}" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="start">Total
                                                                                                        Days</label>
                                                                                                    <div
                                                                                                        class="input-group">
                                                                                                        <input
                                                                                                            class="form-control bg-light total-days-perdiem"
                                                                                                            id="total_days_bt_perdiem_{{ $index }}"
                                                                                                            name="total_days_bt_perdiem[]"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            value="{{ old('total_days_bt_perdiem.' . $index, $perdiem2['total_days'] ?? '') }}"
                                                                                                            readonly>
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">days</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Location
                                                                                                        Agency</label>
                                                                                                    <select
                                                                                                        class="form-control select2 location-select"
                                                                                                        name="location_bt_perdiem[]" disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            location...
                                                                                                        </option>
                                                                                                        @foreach ($locations as $location)
                                                                                                            <option
                                                                                                                value="{{ $location->area }}"
                                                                                                                {{ ($perdiem['location'] ?? '') == $location->area ? 'selected' : '' }}>
                                                                                                                {{ $location->area . ' (' . $location->company_name . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                        <option
                                                                                                            value="Others"
                                                                                                            {{ ($perdiem2['location'] ?? '') == 'Others' ? 'selected' : '' }}>
                                                                                                            Others
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    <br>
                                                                                                    <input type="text"
                                                                                                        name="other_location_bt_perdiem[]"
                                                                                                        class="form-control other-location"
                                                                                                        placeholder="Other Location"
                                                                                                        value="{{ old('other_location_bt_perdiem.' . $index, $perdiem2['other_location'] ?? '') }}"
                                                                                                        style="{{ ($perdiem2['location'] ?? '') == 'Others' ? 'display:block;' : 'display:none;' }}" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_{{ $index }}"
                                                                                                        name="company_bt_perdiem[]" disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            ---
                                                                                                            Select
                                                                                                            Company
                                                                                                            ---
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}"
                                                                                                                {{ ($perdiem2['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        // Format nominal value if available
                                                                                                        $formattedNominal2 = isset(
                                                                                                            $perdiem2[
                                                                                                                'nominal'
                                                                                                            ],
                                                                                                        )
                                                                                                            ? number_format(
                                                                                                                $perdiem2[
                                                                                                                    'nominal'
                                                                                                                ],
                                                                                                                0,
                                                                                                                ',',
                                                                                                                '.',
                                                                                                            )
                                                                                                            : '';
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_perdiem[]"
                                                                                                        id="nominal_bt_perdiem_{{ $index }}"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('nominal_bt_perdiem.' . $index, $formattedNominal2) }}" disabled>
                                                                                                </div>
                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endforeach
                                                                                        @else
                                                                                            <!-- Default empty fields if no data is available -->
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Start
                                                                                                    Perdiem</label>
                                                                                                <input type="date"
                                                                                                    name="start_bt_perdiem[]"
                                                                                                    class="form-control start-perdiem" disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">End
                                                                                                    Perdiem</label>
                                                                                                <input type="date"
                                                                                                    name="end_bt_perdiem[]"
                                                                                                    class="form-control end-perdiem" disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="start">Total
                                                                                                    Days</label>
                                                                                                <div class="input-group">
                                                                                                    <input
                                                                                                        class="form-control bg-light total-days-perdiem"
                                                                                                        id="total_days_bt_perdiem_0"
                                                                                                        name="total_days_bt_perdiem[]"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        readonly>
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">days</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Location
                                                                                                    Agency</label>
                                                                                                <select
                                                                                                    class="form-control select2 location-select"
                                                                                                    name="location_bt_perdiem[]" disabled>
                                                                                                    <option value="">
                                                                                                        Select
                                                                                                        location...
                                                                                                    </option>
                                                                                                    @foreach ($locations as $location)
                                                                                                        <option
                                                                                                            value="{{ $location->area }}">
                                                                                                            {{ $location->area . ' (' . $location->company_name . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                    <option value="Others">
                                                                                                        Others
                                                                                                    </option>
                                                                                                </select>
                                                                                                <br>
                                                                                                <input type="text"
                                                                                                    name="other_location_bt_perdiem[]"
                                                                                                    class="form-control other-location"
                                                                                                    placeholder="Other Location"
                                                                                                    style="display:none;" disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Company
                                                                                                    Code</label>
                                                                                                <select
                                                                                                    class="form-control select2"
                                                                                                    id="companyFilter_0"
                                                                                                    name="company_bt_perdiem[]">
                                                                                                    <option value="">
                                                                                                        --- Select
                                                                                                        Company ---
                                                                                                    </option>
                                                                                                    @foreach ($companies as $company)
                                                                                                        <option
                                                                                                            value="{{ $company->contribution_level_code }}">
                                                                                                            {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Amount</label>
                                                                                            </div>
                                                                                            <div class="input-group mb-3">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                <input class="form-control"
                                                                                                    name="nominal_bt_perdiem[]"
                                                                                                    id="nominal_bt_perdiem_0"
                                                                                                    type="text"
                                                                                                    min="0" disabled>
                                                                                            </div>
                                                                                            <hr
                                                                                                class="border border-primary border-1 opacity-50">
                                                                                        @endif

                                                                                        <div class="mb-2">
                                                                                            <label class="form-label">Total
                                                                                                Perdiem</label>
                                                                                            <div class="input-group">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                @php
                                                                                                    // Format totalPerdiem value if available
                                                                                                    $formattedTotalPerdiem2 = number_format(
                                                                                                        $totalPerdiem2 ??
                                                                                                            0,
                                                                                                        0,
                                                                                                        ',',
                                                                                                        '.',
                                                                                                    );
                                                                                                @endphp
                                                                                                <input
                                                                                                    class="form-control bg-light"
                                                                                                    name="total_bt_perdiem[]"
                                                                                                    id="total_bt_perdiem[]"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    value="{{ $formattedTotalPerdiem2 ?? 0 }}"
                                                                                                    readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Button and Card for Transport -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-transport"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Transport</button>
                                                                </div>
                                                                <div id="transport-card" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionTransport">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingTransport">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseTransport"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapseTransport">
                                                                                    Rencana Transport
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseTransport"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingTransport">
                                                                                <div class="accordion-body">
                                                                                    <div id="form-container-bt-transport">
                                                                                        @php
                                                                                            // Provide default empty array if detail_transport is not set
                                                                                            $detailTransport2 =
                                                                                                $declareCa[
                                                                                                    'detail_transport'
                                                                                                ] ?? [];

                                                                                            // Calculate total transport cost with default values
                                                                                            $totalTransport2 = array_reduce(
                                                                                                $detailTransport2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp
                                                                                        @if (!empty($detailTransport2))
                                                                                            @foreach ($detailTransport2 as $index => $transport2)
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Tanggal
                                                                                                        Transport</label>
                                                                                                    <input type="date"
                                                                                                        name="tanggal_bt_transport[]"
                                                                                                        class="form-control"
                                                                                                        placeholder="mm/dd/yyyy"
                                                                                                        value="{{ old('tanggal_bt_transport.' . $index, $transport2['tanggal'] ?? '') }}" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_{{ $index }}"
                                                                                                        name="company_bt_transport[]" disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            Company...
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}"
                                                                                                                {{ ($transport['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Keterangan</label>
                                                                                                    <textarea name="keterangan_bt_transport[]" class="form-control" placeholder="Write your information here..." disabled>{{ old('keterangan_bt_transport.' . $index, $transport2['keterangan'] ?? '') }}</textarea>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_transport[]"
                                                                                                        id="nominal_bt_transport_{{ $index }}"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('nominal_bt_transport.' . $index, $transport2['nominal'] ?? '0') }}" disabled>
                                                                                                </div>

                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endforeach
                                                                                        @else
                                                                                            <!-- Default empty fields if no data is available -->
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Tanggal
                                                                                                    Transport</label>
                                                                                                <input type="date"
                                                                                                    name="tanggal_bt_transport[]"
                                                                                                    class="form-control"
                                                                                                    placeholder="mm/dd/yyyy" disabled>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label class="form-label"
                                                                                                    for="name">Company
                                                                                                    Code</label>
                                                                                                <select
                                                                                                    class="form-control select2"
                                                                                                    id="companyFilter_0"
                                                                                                    name="company_bt_transport[]" disabled>
                                                                                                    <option value="">
                                                                                                        Select
                                                                                                        Company...
                                                                                                    </option>
                                                                                                    @foreach ($companies as $company)
                                                                                                        <option
                                                                                                            value="{{ $company->contribution_level_code }}">
                                                                                                            {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Keterangan</label>
                                                                                                <textarea name="keterangan_bt_transport[]" class="form-control" placeholder="Write your information here..." disabled></textarea>
                                                                                            </div>
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Amount</label>
                                                                                            </div>
                                                                                            <div class="input-group mb-3">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                <input class="form-control"
                                                                                                    name="nominal_bt_transport[]"
                                                                                                    id="nominal_bt_transport_0"
                                                                                                    type="text"
                                                                                                    min="0" disabled>
                                                                                            </div>

                                                                                            <hr
                                                                                                class="border border-primary border-1 opacity-50">
                                                                                        @endif

                                                                                        <div class="mb-2">
                                                                                            <label class="form-label">Total
                                                                                                Transport</label>
                                                                                            <div class="input-group">
                                                                                                <div
                                                                                                    class="input-group-append">
                                                                                                    <span
                                                                                                        class="input-group-text">Rp</span>
                                                                                                </div>
                                                                                                @php
                                                                                                    // Format the total transport value if available
                                                                                                    $formattedTotalTransport2 = number_format(
                                                                                                        $totalTransport2 ??
                                                                                                            0,
                                                                                                        0,
                                                                                                        ',',
                                                                                                        '.',
                                                                                                    );
                                                                                                @endphp
                                                                                                <input
                                                                                                    class="form-control bg-light"
                                                                                                    name="total_bt_transport[]"
                                                                                                    id="total_bt_transport[]"
                                                                                                    type="text"
                                                                                                    min="0"
                                                                                                    value="{{ $formattedTotalTransport2 ?? 0 }}"
                                                                                                    readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- <button type="button"
                                                                                        id="add-more-bt-transport"
                                                                                        class="btn btn-primary mt-3">Add
                                                                                        More</button> --}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Button and Card for Penginapan -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-penginapan"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Accommodation</button>
                                                                </div>
                                                                <div id="penginapan-card" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionPenginapan">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingPenginapan">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapsePenginapan"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapsePenginapan">
                                                                                    Rencana Penginapan
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapsePenginapan"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingPenginapan">
                                                                                <div class="accordion-body">
                                                                                    <div id="form-container-bt-penginapan">
                                                                                        @php
                                                                                            // Default empty array if 'detail_penginapan' is not set
                                                                                            $penginapan2 =
                                                                                                $declareCa[
                                                                                                    'detail_penginapan'
                                                                                                ] ?? [];

                                                                                            // Calculate total penginapan cost
                                                                                            $totalPenginapanCost2 = array_reduce(
                                                                                                $penginapan2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp

                                                                                        <!-- Form for Penginapan Details -->
                                                                                        <div
                                                                                            id="form-container-bt-penginapan">
                                                                                            @if (!empty($penginapan2))
                                                                                                @foreach ($penginapan2 as $index => $item2)
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Start
                                                                                                            Penginapan</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="start_bt_penginapan[]"
                                                                                                            class="form-control start-penginapan"
                                                                                                            placeholder="mm/dd/yyyy"
                                                                                                            value="{{ old('start_bt_penginapan.' . $index, $item2['start_date'] ?? '') }}" disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">End
                                                                                                            Penginapan</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="end_bt_penginapan[]"
                                                                                                            class="form-control end-penginapan"
                                                                                                            placeholder="mm/dd/yyyy"
                                                                                                            value="{{ old('end_bt_penginapan.' . $index, $item2['end_date'] ?? '') }}" disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="start">Total
                                                                                                            Days</label>
                                                                                                        <div
                                                                                                            class="input-group">
                                                                                                            <input
                                                                                                                class="form-control bg-light total-days-penginapan"
                                                                                                                id="total_days_bt_penginapan_{{ $index }}"
                                                                                                                name="total_days_bt_penginapan[]"
                                                                                                                type="text"
                                                                                                                min="0"
                                                                                                                value="{{ old('total_days_bt_penginapan.' . $index, $item2['total_days'] ?? '0') }}"
                                                                                                                readonly>
                                                                                                            <div
                                                                                                                class="input-group-append">
                                                                                                                <span
                                                                                                                    class="input-group-text">days</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="name">Hotel
                                                                                                            Name</label>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="hotel_name_bt_penginapan[]"
                                                                                                            class="form-control"
                                                                                                            placeholder="ex: Westin"
                                                                                                            value="{{ old('hotel_name_bt_penginapan.' . $index, $item2['hotel_name'] ?? '') }}" disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label"
                                                                                                            for="name">Company
                                                                                                            Code</label>
                                                                                                        <select
                                                                                                            class="form-control select2"
                                                                                                            id="companyFilter_{{ $index }}"
                                                                                                            name="company_bt_penginapan[]" disabled>
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                Company...
                                                                                                            </option>
                                                                                                            @foreach ($companies as $company)
                                                                                                                <option
                                                                                                                    value="{{ $company->contribution_level_code }}"
                                                                                                                    {{ ($item['company_code'] ?? '') == $company->contribution_level_code ? 'selected' : '' }}>
                                                                                                                    {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Amount</label>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="input-group mb-3">
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">Rp</span>
                                                                                                        </div>
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            name="nominal_bt_penginapan[]"
                                                                                                            id="nominal_bt_penginapan_{{ $index }}"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            value="{{ old('nominal_bt_penginapan.' . $index, $item2['nominal'] ?? '0') }}" disabled>
                                                                                                    </div>

                                                                                                    <hr
                                                                                                        class="border border-primary border-1 opacity-50">
                                                                                                @endforeach
                                                                                            @else
                                                                                                <!-- Default empty fields if no data is available -->
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Start
                                                                                                        Penginapan</label>
                                                                                                    <input type="date"
                                                                                                        name="start_bt_penginapan[]"
                                                                                                        class="form-control start-penginapan"
                                                                                                        placeholder="mm/dd/yyyy" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">End
                                                                                                        Penginapan</label>
                                                                                                    <input type="date"
                                                                                                        name="end_bt_penginapan[]"
                                                                                                        class="form-control end-penginapan"
                                                                                                        placeholder="mm/dd/yyyy" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="start">Total
                                                                                                        Days</label>
                                                                                                    <div
                                                                                                        class="input-group">
                                                                                                        <input
                                                                                                            class="form-control bg-light total-days-penginapan"
                                                                                                            id="total_days_bt_penginapan_0"
                                                                                                            name="total_days_bt_penginapan[]"
                                                                                                            type="text"
                                                                                                            min="0"
                                                                                                            readonly>
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text">days</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Hotel
                                                                                                        Name</label>
                                                                                                    <input type="text"
                                                                                                        name="hotel_name_bt_penginapan[]"
                                                                                                        class="form-control"
                                                                                                        placeholder="ex: Westin" disabled>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="name">Company
                                                                                                        Code</label>
                                                                                                    <select
                                                                                                        class="form-control select2"
                                                                                                        id="companyFilter_0"
                                                                                                        name="company_bt_penginapan[]" disabled>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            Company...
                                                                                                        </option>
                                                                                                        @foreach ($companies as $company)
                                                                                                            <option
                                                                                                                value="{{ $company->contribution_level_code }}">
                                                                                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="mb-2">
                                                                                                    <label
                                                                                                        class="form-label">Amount</label>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="input-group mb-3">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        name="nominal_bt_penginapan[]"
                                                                                                        id="nominal_bt_penginapan_0"
                                                                                                        type="text"
                                                                                                        min="0" disabled>
                                                                                                </div>

                                                                                                <hr
                                                                                                    class="border border-primary border-1 opacity-50">
                                                                                            @endif

                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Total
                                                                                                    Penginapan</label>
                                                                                                <div class="input-group">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        // Format totalPerdiem value if available
                                                                                                        $formattedtotalPenginapanCost2 = number_format(
                                                                                                            $totalPenginapanCost2 ??
                                                                                                                0,
                                                                                                            0,
                                                                                                            ',',
                                                                                                            '.',
                                                                                                        );
                                                                                                    @endphp
                                                                                                    <input
                                                                                                        class="form-control bg-light"
                                                                                                        name="total_bt_penginapan[]"
                                                                                                        id="total_bt_penginapan[]"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ $formattedtotalPenginapanCost2 }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        {{-- <button type="button"
                                                                                            id="add-more-bt-penginapan"
                                                                                            class="btn btn-primary mt-3">Add
                                                                                            More</button> --}}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <!-- Button and Card for Lainnya -->
                                                                <div class="card-body text-center">
                                                                    <button type="button" style="width: 60%" disabled
                                                                        id="toggle-bt-lainnya"
                                                                        class="btn btn-primary mt-3" data-state="false"><i
                                                                            class="bi bi-plus-circle"></i>
                                                                        Others</button>
                                                                </div>
                                                                <div id="lainnya-card" class="card-body"
                                                                    style="display: none;">
                                                                    <div class="accordion" id="accordionLainnya">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header"
                                                                                id="headingLainnya">
                                                                                <button class="accordion-button fw-medium"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseLainnya"
                                                                                    aria-expanded="true"
                                                                                    aria-controls="collapseLainnya">
                                                                                    Rencana Lainnya
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseLainnya"
                                                                                class="accordion-collapse collapse show"
                                                                                aria-labelledby="headingLainnya">
                                                                                <div class="accordion-body">
                                                                                    <div id="form-container-bt-lainnya">
                                                                                        @php
                                                                                            // Default empty array if 'detail_lainnya' is not set
                                                                                            $lainnya2 =
                                                                                                $declareCa[
                                                                                                    'detail_lainnya'
                                                                                                ] ?? [];

                                                                                            // Calculate total lainnya cost
                                                                                            $totalLainnyaCost2 = array_reduce(
                                                                                                $lainnya2,
                                                                                                function (
                                                                                                    $carry,
                                                                                                    $item,
                                                                                                ) {
                                                                                                    return $carry +
                                                                                                        (int) ($item[
                                                                                                            'nominal'
                                                                                                        ] ?? 0);
                                                                                                },
                                                                                                0,
                                                                                            );
                                                                                        @endphp

                                                                                        <div
                                                                                            id="form-container-bt-lainnya">
                                                                                            @if (!empty($lainnya2))
                                                                                                @foreach ($lainnya2 as $index => $lainnyaItem2)
                                                                                                    <div
                                                                                                        class="lainnya-item">
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Tanggal</label>
                                                                                                            <input
                                                                                                                type="date"
                                                                                                                name="tanggal_bt_lainnya[]"
                                                                                                                class="form-control"
                                                                                                                value="{{ old('tanggal_bt_lainnya.' . $index, $lainnyaItem2['tanggal'] ?? '') }}"
                                                                                                                placeholder="mm/dd/yyyy" disabled>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Keterangan</label>
                                                                                                            <textarea name="keterangan_bt_lainnya[]" class="form-control" placeholder="Write your other purposes ..." disabled>{{ old('keterangan_bt_lainnya.' . $index, $lainnyaItem2['keterangan'] ?? '') }}</textarea>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                class="form-label">Amount</label>
                                                                                                            <div
                                                                                                                class="input-group mb-3">
                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <span
                                                                                                                        class="input-group-text">Rp</span>
                                                                                                                </div>
                                                                                                                <input
                                                                                                                    class="form-control nominal-lainnya"
                                                                                                                    name="nominal_bt_lainnya[]"
                                                                                                                    type="text"
                                                                                                                    min="0"
                                                                                                                    value="{{ old('nominal_bt_lainnya.' . $index, $lainnyaItem2['nominal'] ?? '0') }}" disabled>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <hr
                                                                                                            class="border border-primary border-1 opacity-50">
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            @else
                                                                                                <div class="lainnya-item">
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Tanggal</label>
                                                                                                        <input
                                                                                                            type="date"
                                                                                                            name="tanggal_bt_lainnya[]"
                                                                                                            class="form-control"
                                                                                                            placeholder="mm/dd/yyyy" disabled>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Keterangan</label>
                                                                                                        <textarea name="keterangan_bt_lainnya[]" class="form-control" placeholder="Write your other purposes ..." disabled></textarea>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label
                                                                                                            class="form-label">Amount</label>
                                                                                                        <div
                                                                                                            class="input-group mb-3">
                                                                                                            <div
                                                                                                                class="input-group-append">
                                                                                                                <span
                                                                                                                    class="input-group-text">Rp</span>
                                                                                                            </div>
                                                                                                            <input
                                                                                                                class="form-control nominal-lainnya"
                                                                                                                name="nominal_bt_lainnya[]"
                                                                                                                type="text"
                                                                                                                min="0"
                                                                                                                value="0" disabled>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <hr
                                                                                                        class="border border-primary border-1 opacity-50">
                                                                                                </div>
                                                                                            @endif
                                                                                            @php
                                                                                                $formattedTotalLainnya2 = number_format(
                                                                                                    $totalLainnya2,
                                                                                                    0,
                                                                                                    ',',
                                                                                                    '.',
                                                                                                );
                                                                                            @endphp
                                                                                            <div class="mb-2">
                                                                                                <label
                                                                                                    class="form-label">Total
                                                                                                    Lainnya</label>
                                                                                                <div class="input-group">
                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text">Rp</span>
                                                                                                    </div>
                                                                                                    <input
                                                                                                        class="form-control bg-light"
                                                                                                        name="total_bt_lainnya[]"
                                                                                                        id="total_bt_lainnya"
                                                                                                        type="text"
                                                                                                        min="0"
                                                                                                        value="{{ old('total_bt_lainnya.' . $index, $formattedTotalLainnya2) }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>

                                                                                            {{-- <button type="button"
                                                                                                id="add-more-bt-lainnya"
                                                                                                class="btn btn-primary mt-3">Add
                                                                                                More</button> --}}
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- </div> --}}
                                                                {{-- </div> --}}
                                                            </div>
                                                        </div>
                                                        <br>
                                                        @php
                                                            // Provide default empty arrays if any section is not set
                                                            $detailPerdiem = $caDetail['detail_perdiem'] ?? [];
                                                            $detailTransport = $caDetail['detail_transport'] ?? [];
                                                            $detailPenginapan = $caDetail['detail_penginapan'] ?? [];
                                                            $detailLainnya = $caDetail['detail_lainnya'] ?? [];

                                                            // Calculate total costs for each section
                                                            $totalPerdiem = array_reduce(
                                                                $detailPerdiem,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalTransport = array_reduce(
                                                                $detailTransport,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalPenginapan = array_reduce(
                                                                $detailPenginapan,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            $totalLainnya = array_reduce(
                                                                $detailLainnya,
                                                                function ($carry, $item) {
                                                                    return $carry + (int) ($item['nominal'] ?? 0);
                                                                },
                                                                0,
                                                            );

                                                            // Total Cash Advanced
                                                            $totalCashAdvanced2 =
                                                                $totalPerdiem2 +
                                                                $totalTransport2 +
                                                                $totalPenginapan2 +
                                                                $totalLainnya2;
                                                            $formattedTotalCashAdvanced2 = number_format(
                                                                $totalCashAdvanced2,
                                                                0,
                                                                ',',
                                                                '.',
                                                            );
                                                        @endphp
                                                        <div class="col-md-12 mb-2">
                                                            <label class="form-label">Total Cash Advanced</label>
                                                            <div class="input-group">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input class="form-control bg-light" name="totalca"
                                                                    id="totalca" type="text" min="0"
                                                                    value="{{ $formattedTotalCashAdvanced2 }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>




                        </form>
                        @php
                            use Illuminate\Support\Facades\Storage;
                        @endphp
                        <div class="d-flex justify-content-end mt-3">
                            @if (isset($ca->prove_declare) && $ca->prove_declare)
                                <a href="{{ Storage::url($ca->prove_declare) }}" target="_blank"
                                    class="btn btn-outline-primary rounded-pill" style="margin-right: 4px;">View</a>
                            @endif

                            <form method="POST" action="{{ route('confirm.deklarasi', ['id' => $n->id]) }}"
                                style="display: inline-block;" class="status-form">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_approval" value="Declaration Rejected">
                                <button type="submit" class="btn btn-primary rounded-pill"
                                    style="padding: 0.5rem 1rem; margin-right: 5px">
                                    Decline
                                </button>
                            </form>

                            <form method="POST" action="{{ route('confirm.deklarasi', ['id' => $n->id]) }}"
                                style="display: inline-block; margin-right: 5px;" class="status-form">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_approval"
                                    value="{{ Auth::user()->id == $n->manager_l1_id ? 'Pending L2' : 'Declaration Approved' }}">
                                <button type="submit" class="btn btn-success rounded-pill"
                                    style="padding: 0.5rem 1rem;">
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-light rounded-4 border-0 shadow" style="border-radius: 1rem;">
                    <div class="modal-body text-center p-5" style="padding: 2rem;">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill" style="font-size: 100px; color: #AB2F2B !important;"></i>
                        </div>
                        <h4 class="mb-3 fw-bold" style="font-size: 32px; color: #AB2F2B !important;">Success!</h4>
                        <p class="mb-4" id="successModalBody" style="font-size: 20px;">
                            <!-- The success message will be inserted here -->
                        </p>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-4"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.status-form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const action = this.querySelector('input[name="status_approval"]').value;
                        const confirmMessage = action === 'Rejected' ?
                            'Are you sure you want to reject this?' :
                            'Are you sure you want to confirm this?';

                        if (confirm(confirmMessage)) {
                            const formData = new FormData(this);
                            fetch(this.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update the success modal content
                                        document.getElementById('successModalBody').textContent =
                                            data.message;

                                        // Show the success modal
                                        var successModal = new bootstrap.Modal(document
                                            .getElementById('successModal'));
                                        successModal.show();

                                        // Reload the page after modal is closed
                                        document.getElementById('successModal').addEventListener(
                                            'hidden.bs.modal',
                                            function() {
                                                window.location.href = '/businessTrip/approval';
                                            });
                                    } else {
                                        alert('An error occurred. Please try again.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred. Please try again.');
                                });
                        }
                    });
                });
            });

            function formatCurrency(input) {
                var cursorPos = input.selectionStart;
                var value = input.value.replace(/[^\d]/g, ''); // Remove everything that is not a digit
                var formattedValue = '';

                // Format the value with dots
                if (value.length > 3) {
                    formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                } else {
                    formattedValue = value;
                }

                input.value = formattedValue;

                // Adjust the cursor position after formatting
                cursorPos += (formattedValue.length - value.length);
                input.setSelectionRange(cursorPos, cursorPos);
            }

            document.getElementById('btEditForm').addEventListener('submit', function(event) {
                // Unformat the voucher fields before submission
                var nominalField = document.getElementById('nominal_vt');
                var keeperField = document.getElementById('keeper_vt');

                // Remove dots from the formatted value to keep the number intact
                nominalField.value = nominalField.value.replace(/\./g, '');
                keeperField.value = keeperField.value.replace(/\./g, '');
            });


            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('save-draft').addEventListener('click', function(event) {
                    event.preventDefault();

                    // Remove the existing status input
                    const existingStatus = document.getElementById('status');
                    if (existingStatus) {
                        existingStatus.remove();
                    }

                    // Create a new hidden input for "Draft"
                    const draftInput = document.createElement('input');
                    draftInput.type = 'hidden';
                    draftInput.name = 'status';
                    draftInput.value = 'Declaration Draft';
                    draftInput.id = 'status';

                    // Append the draft input to the form
                    this.closest('form').appendChild(draftInput);

                    // Submit the form
                    this.closest('form').submit();
                });
            });


            function calculateTotalDays(index) {
                const checkInInput = document.querySelector(`#hotel-form-${index} input[name="tgl_masuk_htl[]"]`);
                const checkOutInput = document.querySelector(`#hotel-form-${index} input[name="tgl_keluar_htl[]"]`);
                const totalDaysInput = document.querySelector(`#hotel-form-${index} input[name="total_hari[]"]`);

                // Get Start Date and End Date from the main form
                const mulaiInput = document.getElementById('mulai');
                const kembaliInput = document.getElementById('kembali');

                if (!checkInInput || !checkOutInput || !mulaiInput || !kembaliInput) {
                    return; // Ensure elements are present before proceeding
                }

                // Parse the dates
                const checkInDate = new Date(checkInInput.value);
                const checkOutDate = new Date(checkOutInput.value);
                const mulaiDate = new Date(mulaiInput.value);
                const kembaliDate = new Date(kembaliInput.value);

                // Validate Check In Date
                if (checkInDate < mulaiDate) {
                    alert('Check In date cannot be earlier than Start date.');
                    checkInInput.value = ''; // Reset the Check In field
                    totalDaysInput.value = ''; // Clear total days
                    return;
                }

                // Ensure Check Out Date is not earlier than Check In Date
                if (checkOutDate < checkInDate) {
                    alert('Check Out date cannot be earlier than Check In date.');
                    checkOutInput.value = ''; // Reset the Check Out field
                    totalDaysInput.value = ''; // Clear total days
                    return;
                }

                // Calculate the total days if all validations pass
                if (checkInDate && checkOutDate) {
                    const diffTime = Math.abs(checkOutDate - checkInDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDaysInput.value = diffDays;
                } else {
                    totalDaysInput.value = '';
                }
            }

            // Attach event listeners to the hotel forms
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.hotel-form').forEach((form, index) => {
                    const i = index + 1; // Adjust for 1-based index

                    form.querySelector('input[name="tgl_masuk_htl[]"]').addEventListener('change', () =>
                        calculateTotalDays(i));
                    form.querySelector('input[name="tgl_keluar_htl[]"]').addEventListener('change', () =>
                        calculateTotalDays(i));
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                var jnsDinasSelect = document.getElementById('jns_dinas');
                var additionalFields = document.getElementById('additional-fields');

                function showAdditionalFields() {
                    if (jnsDinasSelect.value === 'luar kota') {
                        additionalFields.style.display = 'block';
                    } else {
                        additionalFields.style.display = 'none';
                    }
                }

                // Show additional fields on page load if 'luar kota' is selected
                showAdditionalFields();

                jnsDinasSelect.addEventListener('change', function() {
                    showAdditionalFields();
                    if (this.value !== 'luar kota') {
                        // Reset all fields to "Tidak" if not 'luar kota'
                        document.getElementById('ca').value = 'Tidak';
                        document.getElementById('tiket').value = 'Tidak';
                        document.getElementById('hotel').value = 'Tidak';
                        document.getElementById('taksi').value = 'Tidak';
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const caSelect = document.getElementById('ca');
                const caNbtDiv = document.getElementById('ca_div');

                const hotelSelect = document.getElementById('hotel');
                const hotelDiv = document.getElementById('hotel_div');

                const taksiSelect = document.getElementById('taksi');
                const taksiDiv = document.getElementById('taksi_div');

                const tiketSelect = document.getElementById('tiket');
                const tiketDiv = document.getElementById('tiket_div');


                function toggleDisplay(selectElement, targetDiv) {
                    if (selectElement.value === 'Ya') {
                        targetDiv.style.display = 'block';
                    } else {
                        targetDiv.style.display = 'none';
                    }
                }

                caSelect.addEventListener('change', function() {
                    toggleDisplay(caSelect, caNbtDiv);
                });

                hotelSelect.addEventListener('change', function() {
                    toggleDisplay(hotelSelect, hotelDiv);
                });

                taksiSelect.addEventListener('change', function() {
                    toggleDisplay(taksiSelect, taksiDiv);
                });

                tiketSelect.addEventListener('change', function() {
                    toggleDisplay(tiketSelect, tiketDiv);
                });

            });
            document.addEventListener('DOMContentLoaded', function() {
                // Get references to the caSelect and caDiv elements
                const caSelect = document.getElementById('ca'); // Make sure this matches your HTML ID
                const caDiv = document.getElementById('ca_div');

                // Check if elements exist
                if (!caSelect || !caDiv) {
                    console.error('caSelect or caDiv element not found.');
                    return;
                }

                // Function to handle display of ca_div based on caSelect value
                function handleCaDisplay() {
                    // Ensure caSelect has a value
                    if (caSelect.value === 'Ya') {
                        caDiv.style.display = 'block';
                    } else {
                        caDiv.style.display = 'none';
                    }
                }

                // Initial check on page load
                handleCaDisplay();

                // Add event listener to handle changes in caSelect
                caSelect.addEventListener('change', function() {
                    handleCaDisplay();
                });

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
            });




            document.addEventListener('DOMContentLoaded', function() {
                // Ticket form handling
                const ticketSelect = document.getElementById('tiket');
                const ticketDiv = document.getElementById('tiket_div');

                ticketSelect.addEventListener('change', function() {
                    if (this.value === 'Ya') {
                        ticketDiv.style.display = 'block';
                    } else {
                        ticketDiv.style.display = 'none';
                        // Reset all input fields within the ticketDiv when 'Tidak' is selected
                        resetTicketFields(ticketDiv);
                    }
                });

                function resetTicketFields(container) {
                    const inputs = container.querySelectorAll('input[type="text"], input[type="number"], textarea');
                    inputs.forEach(input => {
                        input.value = '';
                    });
                    const selects = container.querySelectorAll('select');
                    selects.forEach(select => {
                        select.selectedIndex = 0;
                    });
                }

                for (let i = 1; i <= 4; i++) {
                    const yesRadio = document.getElementById(`more_tkt_yes_${i}`);
                    const noRadio = document.getElementById(`more_tkt_no_${i}`);
                    const nextForm = document.getElementById(`ticket-form-${i + 1}`);

                    yesRadio.addEventListener('change', function() {
                        if (this.checked) {
                            nextForm.style.display = 'block';
                        }
                    });

                    noRadio.addEventListener('change', function() {
                        if (this.checked) {
                            nextForm.style.display = 'none';
                            // Hide all subsequent forms
                            for (let j = i + 1; j <= 5; j++) {
                                const form = document.getElementById(`ticket-form-${j}`);
                                if (form) {
                                    form.style.display = 'none';
                                    // Reset the form when it is hidden
                                    resetTicketFields(form);
                                }
                            }
                            // Reset radio buttons for subsequent forms
                            for (let j = i + 1; j <= 4; j++) {
                                const noRadioButton = document.getElementById(`more_tkt_no_${j}`);
                                if (noRadioButton) {
                                    noRadioButton.checked = true;
                                }
                            }
                        }
                    });
                }

                // Handle Round Trip options
                const ticketTypes = document.querySelectorAll('select[name="type_tkt[]"]');
                ticketTypes.forEach((select, index) => {
                    select.addEventListener('change', function() {
                        const roundTripOptions = this.closest('.card-body').querySelector(
                            '.round-trip-options');
                        if (this.value === 'Round Trip') {
                            roundTripOptions.style.display = 'block';
                        } else {
                            roundTripOptions.style.display = 'none';
                        }
                    });
                });
                // Handle hotel forms
                for (let i = 1; i <= 4; i++) {
                    const yesRadio = document.getElementById(`more_htl_yes_${i}`);
                    const noRadio = document.getElementById(`more_htl_no_${i}`);
                    const nextForm = document.getElementById(`hotel-form-${i + 1}`);

                    if (yesRadio) {
                        yesRadio.addEventListener('change', function() {
                            if (this.checked) {
                                nextForm.style.display = 'block';
                            }
                        });
                    }

                    if (noRadio) {
                        noRadio.addEventListener('change', function() {
                            if (this.checked) {
                                nextForm.style.display = 'none';
                                // Hide all subsequent forms
                                for (let j = i + 1; j <= 5; j++) {
                                    const form = document.getElementById(`hotel-form-${j}`);
                                    if (form) {
                                        form.style.display = 'none';
                                        // Reset the form when it is hidden
                                        resetHotelFields(form);
                                    }
                                }
                                // Reset radio buttons for subsequent forms
                                for (let j = i + 1; j <= 4; j++) {
                                    const noRadioButton = document.getElementById(`more_htl_no_${j}`);
                                    if (noRadioButton) {
                                        noRadioButton.checked = true;
                                    }
                                }
                            }
                        });
                    }
                }

                // Function to reset hotel fields
                function resetHotelFields(container) {
                    const inputs = container.querySelectorAll('input[type="text"], input[type="number"], textarea');
                    inputs.forEach(input => {
                        input.value = '';
                    });
                    const selects = container.querySelectorAll('select');
                    selects.forEach(select => {
                        select.selectedIndex = 0;
                    });
                }

                // Calculate total days for each hotel form
                function calculateTotalDays(index) {
                    const checkIn = document.querySelector(`#hotel-form-${index} input[name="tgl_masuk_htl[]"]`);
                    const checkOut = document.querySelector(`#hotel-form-${index} input[name="tgl_keluar_htl[]"]`);
                    const totalDays = document.querySelector(`#hotel-form-${index} input[name="total_hari[]"]`);

                    if (checkIn && checkOut && totalDays) {
                        const start = new Date(checkIn.value);
                        const end = new Date(checkOut.value);

                        if (checkIn.value && checkOut.value) {
                            // Calculate difference in milliseconds and convert to days, excluding the same day
                            const difference = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                            if (difference < 0) {
                                alert("Check out date cannot be earlier than check in date.");
                                checkOut.value = ''; // Clear the check-out date if invalid
                                totalDays.value = ''; // Clear the total days if check-out date is reset
                            } else {
                                totalDays.value = difference >= 0 ? difference : 0;
                            }
                        } else {
                            totalDays.value = ''; // Clear total days if dates are not set
                        }
                    } else {
                        console.error("Elements not found. Check selectors.");
                    }
                }

                // Add event listeners for date inputs in hotel forms
                for (let i = 1; i <= 5; i++) {
                    const checkIn = document.querySelector(`#hotel-form-${i} input[name="tgl_masuk_htl[]"]`);
                    const checkOut = document.querySelector(`#hotel-form-${i} input[name="tgl_keluar_htl[]"]`);

                    if (checkIn && checkOut) {
                        checkIn.addEventListener('change', () => calculateTotalDays(i));
                        checkOut.addEventListener('change', () => calculateTotalDays(i));
                    }
                }

                // Handle date validation for the return date
                document.getElementById('kembali').addEventListener('change', function() {
                    const mulaiDate = document.getElementById('mulai').value;
                    const kembaliDate = this.value;

                    if (kembaliDate < mulaiDate) {
                        alert('Return date cannot be earlier than Start date.');
                        this.value = ''; // Reset the kembali field
                    }
                });
            });



            document.getElementById('tgl_keluar_htl').addEventListener('change', function() {
                var masukHtl = document.getElementById('tgl_masuk_htl').value;
                var keluarDate = this.value;

                if (masukHtl && keluarDate) {
                    var checkInDate = new Date(masukHtl);
                    var checkOutDate = new Date(keluarDate);

                    if (checkOutDate < checkInDate) {
                        alert("Check out date cannot be earlier than check in date.");
                        this.value = ''; // Reset the check out date field
                    }
                }
            });

            document.getElementById('type_tkt').addEventListener('change', function() {
                var roundTripOptions = document.getElementById('roundTripOptions');
                if (this.value === 'Round Trip') {
                    roundTripOptions.style.display = 'block';
                } else {
                    roundTripOptions.style.display = 'none';
                }
            });


            function BTtoggleOthers() {
                var locationFilter = document.getElementById("tujuan");
                var others_location = document.getElementById("others_location");
                var selectedValue = locationFilter.value;
                var options = Array.from(locationFilter.options).map(option => option.value);

                // Check if the selected value is "Others" or not in the list
                if (selectedValue === "Others" || !options.includes(selectedValue)) {
                    others_location.style.display = "block";

                    if (!options.includes(selectedValue)) {
                        locationFilter.value = "Others"; // Select "Others"
                        others_location.value = selectedValue; // Show the unlisted value in the text field
                    }
                } else {
                    others_location.style.display = "none";
                    others_location.value = ""; // Clear the input field
                }
            }

            // Call the function on page load to handle any pre-filled values
            window.onload = toggleOthers;

            function validateDates(index) {
                // Get the departure and return date inputs for the given form index
                const departureDate = document.querySelector(`#tgl_brkt_tkt_${index}`);
                const returnDate = document.querySelector(`#tgl_plg_tkt_${index}`);

                // Get the departure and return time inputs for the given form index
                const departureTime = document.querySelector(`#jam_brkt_tkt_${index}`);
                const returnTime = document.querySelector(`#jam_plg_tkt_${index}`);

                if (departureDate && returnDate) {
                    const depDate = new Date(departureDate.value);
                    const retDate = new Date(returnDate.value);

                    // Check if both dates are valid
                    if (depDate && retDate) {
                        // Validate if return date is earlier than departure date
                        if (retDate < depDate) {
                            alert("Return date cannot be earlier than departure date.");
                            returnDate.value = ''; // Reset the return date field
                        } else if (retDate.getTime() === depDate.getTime() && departureTime && returnTime) {
                            // If dates are the same, validate time
                            const depTime = departureTime.value;
                            const retTime = returnTime.value;

                            // Check if both times are set and validate
                            if (depTime && retTime) {
                                const depDateTime = new Date(`1970-01-01T${depTime}:00`);
                                const retDateTime = new Date(`1970-01-01T${retTime}:00`);

                                if (retDateTime < depDateTime) {
                                    alert("Return time cannot be earlier than departure time on the same day.");
                                    returnTime.value = ''; // Reset the return time field
                                }
                            }
                        }
                    }
                }
            }




            document.getElementById('nik').addEventListener('change', function() {
                var nik = this.value;

                fetch('/get-employee-data?nik=' + nik)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('jk_tkt').value = data.jk_tkt;
                            document.getElementById('tlp_tkt').value = data.tlp_tkt;
                        } else {
                            alert('Employee data not found!');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            //CA JS
            function toggleDivs() {
                // ca_type ca_nbt ca_e
                var ca_type = document.getElementById("ca_type");
                var ca_nbt = document.getElementById("ca_nbt");
                var ca_e = document.getElementById("ca_e");
                var div_bisnis_numb = document.getElementById("div_bisnis_numb");
                var bisnis_numb = document.getElementById("bisnis_numb");
                var div_allowance = document.getElementById("div_allowance");

                if (ca_type.value === "dns") {
                    ca_bt.style.display = "block";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "block";
                    div_allowance.style.display = "block";
                } else if (ca_type.value === "ndns") {
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "block";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "none";
                    bisnis_numb.style.value = "";
                    div_allowance.style.display = "none";
                } else if (ca_type.value === "entr") {
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "block";
                    div_bisnis_numb.style.display = "block";
                } else {
                    ca_bt.style.display = "none";
                    ca_nbt.style.display = "none";
                    ca_e.style.display = "none";
                    div_bisnis_numb.style.display = "none";
                    bisnis_numb.style.value = "";
                }
            }

            function toggleOthers() {
                // ca_type ca_nbt ca_e
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
                //input.value = input.value.replace(/[^0-9,]/g, '');
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

                function formatInput(input) {
                    let value = input.value.replace(/\./g, '');
                    value = parseFloat(value);
                    if (!isNaN(value)) {
                        // input.value = formatNumber(value);
                        input.value = formatNumber(Math.floor(value));
                    } else {
                        input.value = formatNumber(0);
                    }

                    calculateTotalCA();
                }

                function calculateTotalCA() {
                    const allowance = parseNumber(allowanceInput.value);
                    const transport = parseNumber(transportInput.value);
                    const accommodation = parseNumber(accommodationInput.value);
                    const other = parseNumber(otherInput.value);
                    const nominal_1 = parseNumber(nominal_1Input.value);
                    const nominal_2 = parseNumber(nominal_2Input.value);
                    const nominal_3 = parseNumber(nominal_3Input.value);
                    const nominal_4 = parseNumber(nominal_4Input.value);
                    const nominal_5 = parseNumber(nominal_5Input.value);

                    // Perbaiki penulisan caTypeInput.value
                    const ca_type = caTypeInput.value;

                    let totalca = 0;
                    if (ca_type === 'dns') {
                        totalca = allowance + transport + accommodation + other;
                    } else if (ca_type === 'ndns') {
                        totalca = transport + accommodation + other;
                        allowanceInput.value = 0;
                    } else if (ca_type === 'entr') {
                        totalca = nominal_1 + nominal_2 + nominal_3 + nominal_4 + nominal_5;
                        allowanceInput.value = 0;
                    }

                    // totalcaInput.value = formatNumber(totalca.toFixed(2));
                    totalcaInput.value = formatNumber(Math.floor(totalca));
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
                            // Clear form inputs (optional, uncomment if needed)
                            // $card.find('input[type="text"], input[type="date"], textarea').val('');
                            // $card.find('select').prop('selectedIndex', 0);
                            // $card.find('input[readonly]').val(0);
                            // $card.find('input[type="number"]').val(0);

                            // Set button text and icon
                            var buttonText = $button.data('text') || $button.text();
                            $button.html('<i class="bi bi-plus-circle"></i> ' + buttonText);
                            $button.data('state', 'false');
                        } else {
                            // Set button text and icon
                            var buttonText = $button.data('text') || $button.text();
                            $button.html('<i class="bi bi-dash-circle"></i> ' + buttonText);
                            $button.data('state', 'true');
                        }
                    });
                }

                // Store the original button text
                $('#toggle-bt-perdiem-deklarasi, #toggle-bt-transport-deklarasi, #toggle-bt-penginapan-deklarasi, #toggle-bt-lainnya-deklarasi')
                    .each(function() {
                        $(this).data('text', $(this).text().trim());
                    });

                // Click events for toggle buttons
                $('#toggle-bt-perdiem-deklarasi').click(function() {
                    toggleCard('#toggle-bt-perdiem-deklarasi', '#perdiem-card-deklarasi');
                });

                $('#toggle-bt-transport-deklarasi').click(function() {
                    toggleCard('#toggle-bt-transport-deklarasi', '#transport-card-deklarasi');
                });

                $('#toggle-bt-penginapan-deklarasi').click(function() {
                    toggleCard('#toggle-bt-penginapan-deklarasi', '#penginapan-card-deklarasi');
                });

                $('#toggle-bt-lainnya-deklarasi').click(function() {
                    toggleCard('#toggle-bt-lainnya-deklarasi', '#lainnya-card-deklarasi');
                });

                // Automatically close cards if totalca_deklarasi is 0
                var totalcaDeklarasi = parseInt($('#totalca_deklarasi').val().replace('.', '') || 0, 10);

                if (totalcaDeklarasi >= 0) {
                    $('#perdiem-card-deklarasi').hide();
                    $('#transport-card-deklarasi').hide();
                    $('#penginapan-card-deklarasi').hide();
                    $('#lainnya-card-deklarasi').hide();
                    $('#toggle-bt-perdiem-deklarasi').html('<i class="bi bi-plus-circle"></i> ' + $(
                        '#toggle-bt-perdiem-deklarasi').data('text')).data('state', 'false');
                    $('#toggle-bt-transport-deklarasi').html('<i class="bi bi-plus-circle"></i> ' + $(
                        '#toggle-bt-transport-deklarasi').data('text')).data('state', 'false');
                    $('#toggle-bt-penginapan-deklarasi').html('<i class="bi bi-plus-circle"></i> ' + $(
                        '#toggle-bt-penginapan-deklarasi').data('text')).data('state', 'false');
                    $('#toggle-bt-lainnya-deklarasi').html('<i class="bi bi-plus-circle"></i> ' + $(
                        '#toggle-bt-lainnya-deklarasi').data('text')).data('state', 'false');
                }

                // Trigger clicks for the toggle buttons on page load
                $('#toggle-bt-perdiem-deklarasi').click();
                $('#toggle-bt-transport-deklarasi').click();
                $('#toggle-bt-penginapan-deklarasi').click();
                $('#toggle-bt-lainnya-deklarasi').click();
            });


            $(document).ready(function() {
                function toggleCard(buttonId, cardId) {
                    var $button = $(buttonId);
                    var $card = $(cardId);
                    var isVisible = $card.is(':visible');

                    $card.slideToggle('fast', function() {
                        if (isVisible) {
                            // Clear form inputs
                            // $card.find('input[type="text"], input[type="date"], textarea').val('');
                            // $card.find('select').prop('selectedIndex', 0);
                            // $card.find('input[readonly]').val(0);
                            // $card.find('input[type="number"]').val(0);

                            // Set button text and icon
                            var buttonText = $button.data('text') || $button.text();
                            $button.html('<i class="bi bi-plus-circle"></i> ' + buttonText);
                            $button.data('state', 'false');
                        } else {
                            // Set button text and icon
                            var buttonText = $button.data('text') || $button.text();
                            $button.html('<i class="bi bi-dash-circle"></i> ' + buttonText);
                            $button.data('state', 'true');
                        }
                    });
                }

                // Store the original button text
                $('#toggle-bt-perdiem, #toggle-bt-transport, #toggle-bt-penginapan, #toggle-bt-lainnya, #toggle-e-detail, #toggle-e-relation')
                    .each(function() {
                        $(this).data('text', $(this).text().trim());
                    });

                $('#toggle-bt-perdiem').click(function() {
                    toggleCard('#toggle-bt-perdiem', '#perdiem-card');
                });

                $('#toggle-bt-transport').click(function() {
                    toggleCard('#toggle-bt-transport', '#transport-card');
                });

                $('#toggle-bt-penginapan').click(function() {
                    toggleCard('#toggle-bt-penginapan', '#penginapan-card');
                });

                $('#toggle-bt-lainnya').click(function() {
                    toggleCard('#toggle-bt-lainnya', '#lainnya-card');
                });
                $('#toggle-bt-perdiem').click();
                $('#toggle-bt-transport').click();
                $('#toggle-bt-penginapan').click();
                $('#toggle-bt-lainnya').click();
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
                    calculateTotalNominalBTPerdiem();
                    calculateTotalNominalBTTransport();
                    calculateTotalNominalBTPenginapan();
                    calculateTotalNominalBTLainnya();
                    calculateTotalNominalBTTotal();
                }

                function calculateTotalNominalBTPerdiem() {
                    let total = 0;
                    document.querySelectorAll('input[name="nominal_bt_perdiem[]"]').forEach(input => {
                        total += parseNumber(input.value);
                    });
                    document.querySelector('input[name="total_bt_perdiem[]"]').value = formatNumber(total);
                }

                function calculateTotalNominalBTTransport() {
                    let total = 0;
                    document.querySelectorAll('input[name="nominal_bt_transport[]"]').forEach(input => {
                        total += parseNumber(input.value);
                    });
                    document.querySelector('input[name="total_bt_transport[]"]').value = formatNumber(total);
                }

                function calculateTotalNominalBTPenginapan() {
                    let total = 0;
                    document.querySelectorAll('input[name="nominal_bt_penginapan[]"]').forEach(input => {
                        total += parseNumber(input.value);
                    });
                    document.querySelector('input[name="total_bt_penginapan[]"]').value = formatNumber(total);
                }

                function calculateTotalNominalBTLainnya() {
                    let total = 0;
                    document.querySelectorAll('input[name="nominal_bt_lainnya[]"]').forEach(input => {
                        total += parseNumber(input.value);
                    });
                    document.querySelector('input[name="total_bt_lainnya[]"]').value = formatNumber(total);
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
                    document.querySelector('input[name="totalca"]').value = formatNumber(total);
                }

                function calculateTotalDaysPerdiem(input) {
                    const formGroup = input.closest('.mb-2').parentElement;
                    const startDateInput = formGroup.querySelector('input[name="start_bt_perdiem[]"]');
                    const endDateInput = formGroup.querySelector('input[name="end_bt_perdiem[]"]');

                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (!isNaN(startDate) && !isNaN(endDate)) {
                        if (startDate > endDate) {
                            alert('End date cannot be earlier than start date.');
                            endDateInput.value = ''; // Clear the end date field
                            formGroup.querySelector('input[name="total_days_bt_perdiem[]"]').value = 0;
                            return; // Exit the function to prevent further calculation
                        }

                        const diffTime = Math.abs(endDate - startDate);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        formGroup.querySelector('input[name="total_days_bt_perdiem[]"]').value = diffDays;
                    } else {
                        formGroup.querySelector('input[name="total_days_bt_perdiem[]"]').value = 0;
                    }
                }


                function calculateTotalDaysPenginapan(input) {
                    const formGroup = input.closest('.mb-2').parentElement;
                    const startDateInput = formGroup.querySelector('input[name="start_bt_penginapan[]"]');
                    const endDateInput = formGroup.querySelector('input[name="end_bt_penginapan[]"]');

                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (!isNaN(startDate) && !isNaN(endDate)) {
                        if (startDate > endDate) {
                            alert('End date cannot be earlier than start date.');
                            endDateInput.value = ''; // Clear the end date field
                            formGroup.querySelector('input[name="total_days_bt_penginapan[]"]').value = 0;
                            return; // Exit the function to prevent further calculation
                        }

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
                    <select class="form-control select2 location-select" name="location_bt_perdiem[]">
                        <option value="">Select location...</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->area }}">{{ $location->area . ' (' . $location->company_name . ')' }}</option>
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
                        @foreach ($companies as $company)
                            <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}</option>
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
                    newFormBTPerdiem.querySelector('input[name="nominal_bt_perdiem[]"]').addEventListener('input',
                        function() {
                            formatInput(this);
                        });

                    // Attach change event to the date fields to calculate total days
                    newFormBTPerdiem.querySelector('input[name="start_bt_perdiem[]"]').addEventListener('change',
                        function() {
                            calculateTotalDaysPerdiem(this);
                        });

                    newFormBTPerdiem.querySelector('input[name="end_bt_perdiem[]"]').addEventListener('change',
                        function() {
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
                        @foreach ($companies as $company)
                            <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}</option>
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
                    newFormBTTransport.querySelector('input[name="nominal_bt_transport[]"]').addEventListener('input',
                        function() {
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
                        @foreach ($companies as $company)
                            <option value="{{ $company->contribution_level_code }}">{{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}</option>
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
                    newFormBTPenginapan.querySelector('input[name="nominal_bt_penginapan[]"]').addEventListener('input',
                        function() {
                            formatInput(this);
                        });

                    // Attach change event to the date fields to calculate total days
                    newFormBTPenginapan.querySelector('input[name="start_bt_penginapan[]"]').addEventListener('change',
                        function() {
                            calculateTotalDaysPenginapan(this);
                        });

                    newFormBTPenginapan.querySelector('input[name="end_bt_penginapan[]"]').addEventListener('change',
                        function() {
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
                    newFormBTLainnya.querySelector('input[name="nominal_bt_lainnya[]"]').addEventListener('input',
                        function() {
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
                document.querySelectorAll('input[name="start_bt_perdiem[]"], input[name="end_bt_perdiem[]"]').forEach(
                    input => {
                        input.addEventListener('change', function() {
                            calculateTotalDaysPerdiem(this);
                        });
                    });

                document.querySelectorAll('input[name="start_bt_penginapan[]"], input[name="end_bt_penginapan[]"]')
                    .forEach(input => {
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
                calculateTotalNominalBTTransport();
                calculateTotalNominalBTPenginapan();
                calculateTotalNominalBTLainnya();
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
                const formContainer = document.getElementById('form-container');

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

                document.getElementById('add-more').addEventListener('click', function() {
                    const newForm = document.createElement('div');
                    newForm.classList.add('mb-2', 'form-group');

                    newForm.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal_nbt[]" class="form-control" placeholder="mm/dd/yyyy">
                </div>
                <div class="mb-2">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan_nbt[]" class="form-control"></textarea>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input class="form-control" name="nominal_nbt[]" type="text" min="0" value="0">
                </div>
                <button type="button" class="btn btn-danger remove-form">Remove</button>
                <hr class="border border-primary border-1 opacity-50">
            `;

                    formContainer.appendChild(newForm);

                    // Attach input event to the newly added nominal field
                    newForm.querySelector('input[name="nominal_nbt[]"]').addEventListener('input', function() {
                        formatInput(this);
                    });

                    // Attach click event to the remove button
                    newForm.querySelector('.remove-form').addEventListener('click', function() {
                        newForm.remove();
                        calculateTotalNominal();
                    });

                    // Update the date constraints for the new 'tanggal_nbt[]' input fields
                    const startDateInput = document.getElementById('start_date').value;
                    const endDateInput = document.getElementById('end_date').value;

                    newForm.querySelectorAll('input[name="tanggal_nbt[]"]').forEach(function(input) {
                        input.min = startDateInput;
                        input.max = endDateInput;
                    });
                });

                // Attach input event to the existing nominal fields
                document.querySelectorAll('input[name="nominal_nbt[]"]').forEach(input => {
                    input.addEventListener('input', function() {
                        formatInput(this);
                    });
                });

                // Initial calculation for the total nominal
                calculateTotalNominal();

            });

            document.addEventListener('DOMContentLoaded', function() {
                const formContainerEDetail = document.getElementById('form-container-e-detail');
                const formContainerERelation = document.getElementById('form-container-e-relation');

                // Function to update checkboxes visibility based on selected options
                function updateCheckboxVisibility() {
                    // Gather all selected options from enter_type_e_detail
                    const selectedOptions = Array.from(document.querySelectorAll(
                            'select[name="enter_type_e_detail[]"]'))
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

                // Function to add new EDetail form
                function addNewEDetailForm() {
                    const newFormEDetail = document.createElement('div');
                    newFormEDetail.classList.add('mb-2');

                    newFormEDetail.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Entertainment Type</label>
                    <select name="enter_type_e_detail[]" class="form-select">
                        <option value="">-</option>
                        <option value="food_cost">Food/Beverages/Souvenir</option>
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
                    newFormEDetail.querySelector('input[name="nominal_e_detail[]"]').addEventListener('input',
                        function() {
                            formatInput(this);
                        });

                    // Attach change event to update checkbox visibility
                    newFormEDetail.querySelector('select[name="enter_type_e_detail[]"]').addEventListener('change',
                        updateCheckboxVisibility);

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
                        <input class="form-check-input" type="checkbox" id="food_cost_e_relation[]" value="food_cost">
                        <label class="form-check-label" for="food_cost_e_relation[]">Food/Beverages/Souvenir</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="transport_e_relation[]" value="transport">
                        <label class="form-check-label" for="transport_e_relation[]">Transport</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="accommodation_e_relation[]" value="accommodation">
                        <label class="form-check-label" for="accommodation_e_relation[]">Accommodation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gift_e_relation[]" value="gift">
                        <label class="form-check-label" for="gift_e_relation[]">Gift</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="fund_e_relation[]" value="fund">
                        <label class="form-check-label" for="fund_e_relation[]">Fund</label>
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

                // Attach change event to existing select fields for checkbox visibility
                document.querySelectorAll('select[name="enter_type_e_detail[]"]').forEach(select => {
                    select.addEventListener('change', updateCheckboxVisibility);
                });

                calculateTotalNominalEDetail();
                updateCheckboxVisibility();
            });
        </script>
    @endsection