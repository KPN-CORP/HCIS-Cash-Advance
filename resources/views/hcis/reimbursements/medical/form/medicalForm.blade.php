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
                            <li class="breadcrumb-item"><a href="{{ route('medical') }}">{{ $parentLink }}</a></li>
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
                        <h4 class="mb-0">Add Medical Usage</h4>
                        <a href="/medical" type="button" class="btn-close btn-close-white"></a>
                    </div>
                    <div class="card-body">
                        <form id="medicForm" action="/medical/form-add/post" method="POST">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-4 mb-2">
                                    <label for="bb_perusahaan" class="form-label">Patient Name</label>
                                    <select class="form-select form-select-sm select2" id="" name=""
                                        required>
                                        <option value="" disabled selected>--- Choose Patient ---</option>
                                        <option value="">Illumi Zoldyck</option>
                                        <option value="">Killua Zoldyck</option>
                                        <option value="">Alluka Zoldyck</option>
                                        <option value="">Milluki Zoldyck</option>
                                        {{-- @foreach ($companies as $company)
                                            <option value="{{ $company->contribution_level_code }}">
                                                {{ $company->contribution_level . ' (' . $company->contribution_level_code . ')' }}
                                            </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="nama" class="form-label">Hospital Name</label>
                                    <input type="text" class="form-control form-control-sm" id="" name=""
                                        style="cursor:not-allowed;" value="" placeholder="ex: RS. Murni Teguh" required>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="" class="form-label">Disease</label>
                                    <select class="form-select form-select-sm select2" id="" name=""
                                        required>
                                        <option value="" disabled selected>--- Choose Disease ---</option>
                                        @foreach ($diseases as $disease)
                                            <option value="{{ $disease->disease_name }}">
                                                {{ $disease->disease_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="keperluan" class="form-label">No. Invoice</label>
                                    <input type="text" class="form-control form-control-sm" id="" name=""
                                        rows="3" placeholder="Please add your invoice number ..." required></input>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="medical_date" class="form-label">Medical Date</label>
                                    <input type="date" class="form-control form-control-sm" id="" name=""
                                        style="cursor:not-allowed;" value="" required>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="rawatInap" class="form-label">Inpatient</label>
                                    <div class="input-group input-group-sm" id="rawatInap">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputRawatInap" class="form-control" placeholder="0"
                                            oninput="formatCurrency(this)"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="rawatJalan" class="form-label">Outpatient</label>
                                    <div class="input-group input-group-sm" id="rawatJalan">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputRawatJalan" class="form-control" placeholder="0"
                                            oninput="formatCurrency(this)" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="persalinan" class="form-label">Labor</label>
                                    <div class="input-group input-group-sm" id="persalinan">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputPersalinan" class="form-control" placeholder="0"
                                            oninput="formatCurrency(this)" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="kacamata" class="form-label">Glasses</label>
                                    <div class="input-group input-group-sm" id="kacamata">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputKacamata" class="form-control" placeholder="0"
                                            oninput="formatCurrency(this)" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12 mt-2">
                                    <label for="" class="form-label">Detail Information</label>
                                    <textarea class="form-control form-control-sm" id="" name="" rows="3"
                                        placeholder="Please add more detail of disease ..." required></textarea>
                                </div>
                            </div>
                            {{-- <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="rawatInap"
                                            onchange="toggleInput('rawatInap', 'rawatInapInputGroup')" />
                                        <label class="form-check-label" for="rawatInap">Rawat Inap</label>
                                    </div>
                                    <div class="input-group input-group-sm" id="rawatInapInputGroup" style="display: none;">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputRawatInap" class="form-control" placeholder="0" oninput="formatCurrency(this)"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="rawatJalan"
                                            onchange="toggleInput('rawatJalan', 'rawatJalanInputGroup')" />
                                        <label class="form-check-label" for="rawatJalan">Rawat Jalan</label>
                                    </div>
                                    <div class="input-group input-group-sm" id="rawatJalanInputGroup"
                                        style="display: none;">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputRawatJalan" class="form-control"
                                            placeholder="0" oninput="formatCurrency(this)"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="persalinan"
                                            onchange="toggleInput('persalinan', 'persalinanInputGroup')" />
                                        <label class="form-check-label" for="persalinan">Persalinan</label>
                                    </div>
                                    <div class="input-group input-group-sm" id="persalinanInputGroup"
                                        style="display: none;">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputPersalinan" class="form-control"
                                            placeholder="0" oninput="formatCurrency(this)"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="kacamata"
                                            onchange="toggleInput('kacamata', 'kacamataInputGroup')" />
                                        <label class="form-check-label" for="kacamata">Kacamata</label>
                                    </div>
                                    <div class="input-group input-group-sm" id="kacamataInputGroup"
                                        style="display: none;">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="inputKacamata" class="form-control" placeholder="0" oninput="formatCurrency(this)"/>
                                    </div>
                                </div>
                            </div> --}}


                            <input type="hidden" name="status" value="Pending L1" id="status">

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-outline-primary rounded-pill me-2 draft-button"
                                    name="action_draft" id="save-draft" value="Draft" id="save-draft">Save as
                                    Draft</button>
                                <button type="submit" class="btn btn-primary rounded-pill submit-button"
                                    name="action_submit" value="Pending L1" id="submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/medical/medical.js') }}"></script>
@endsection
