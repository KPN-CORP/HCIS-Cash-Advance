@extends('layouts_.vertical', ['page_title' => 'Cash Advanced (Admin)'])

@section('css')
    @vite([
        'node_modules/select2/dist/css/select2.min.css',
        'node_modules/daterangepicker/daterangepicker.css',
        'node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
        'node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        'node_modules/flatpickr/dist/flatpickr.min.css'
    ])
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
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">{{ $parentLink }}</li>
                            <li class="breadcrumb-item active">{{ $link }}</li>
                        </ol>
                    </div>
                    <a href="{{ route('reimbursements') }}" class="page-title"><i class="ri-arrow-left-circle-line"></i></a>
                    {{-- <button type="button" class="page-title btn btn-warning rounded-pill">Back</button> --}}
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('cashadvanced.admin') }}" method="GET">
                            <div class="input-group">
                                <label class="col-form-label">Start Date : </label>
                                <input type="date" class="form-control mx-2" id="start_date" name="start_date" placeholder="Start Date" title="Start Date" value="{{ $startDate }}">
                                <label class="col-form-label"> - </label>
                                <input type="date" class="form-control mx-2" id="end_date" name="end_date" placeholder="End Date" title="End Date" value="{{ $endDate }}">
                                <select class="form-select mx-2" aria-label="Status" id="stat" name="stat">
                                    <option value="-" {{ request()->get('stat') == '-' ? 'selected' : '' }}>All Status</option>
                                    <option value="Refund" {{ request()->get('stat') == 'Refund' ? 'selected' : '' }}>Refund</option>
                                    <option value="Done" {{ request()->get('stat') == 'Done' ? 'selected' : '' }}>Done</option>
                                    <option value="On Progress" {{ request()->get('stat') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                </select>
                                <div class="input-group-append mx-2">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" onclick="redirectToExportExcel()">
                                        <i class="ri-file-excel-2-line"></i> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="tableFilter">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title">{{ $link }}</h3>
                            <div class="input-group" style="width: 30%;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white w-border-dark-subtle"><i class="ri-search-line"></i></span>
                                </div>
                                <input type="text" name="customsearch" id="customsearch" class="form-control w-border-dark-subtle border-left-0" placeholder="search.." aria-label="search" aria-describedby="search" >
                                {{-- &nbsp;&nbsp;&nbsp; --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm dt-responsive nowrap" id="scheduleTable" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th class="sticky-col-header" style="background-color: white">CA No</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total CA</th>
                                        <th>Total Declaration</th>
                                        <th>Balance</th>
                                        <th>Request</th>
                                        <th>Declaration</th>
                                        <th>Status CA</th>
                                        <th>Actions</th>
                                        <th>Export</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ca_transactions as $ca_transaction)
                                        <tr>
                                            <td class="text-center">{{ $loop->index + 1 }}</td>
                                            <td style="background-color: white;" class="sticky-col">{{ $ca_transaction->no_ca }}</td>
                                            @if ($ca_transaction->type_ca == 'dns')
                                                <td>Business Trip</td>
                                            @elseif($ca_transaction->type_ca == 'ndns')
                                                <td>Non Business Trip</td>
                                            @elseif($ca_transaction->type_ca == 'entr')
                                                <td>Entertainment</td>
                                            @endif

                                            <td>{{ $ca_transaction->employee->fullname }}</td>
                                            <td>{{ $ca_transaction->contribution_level_code }}</td>
                                            <td>{{ date('j M Y', strtotime($ca_transaction->formatted_start_date)) }}</td>
                                            <td>{{ date('j M Y', strtotime($ca_transaction->formatted_end_date)) }}</td>
                                            <td>Rp. {{ number_format($ca_transaction->total_ca) }}</td>
                                            <td>Rp. {{ number_format($ca_transaction->total_real) }}</td>
                                            <td>
                                                @if ($ca_transaction->total_cost < 0)
                                                    <span class="text-danger">Rp. -{{ number_format(abs($ca_transaction->total_cost)) }}</span>
                                                @else
                                                    <span class="text-success">Rp. {{ number_format($ca_transaction->total_cost) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="badge text-bg-{{ $ca_transaction->approval_status == 'Approved' ? 'success' : ($ca_transaction->approval_status == 'Declaration' ? 'info' : ($ca_transaction->approval_status == 'Pending' ? 'warning' : ($ca_transaction->approval_status == 'Rejected' ? 'danger' : ($ca_transaction->approval_status == 'Draft' ? 'secondary' : 'default')))) }}" style="pointer-events: auto; cursor: default;" title="{{$ca_transaction->approval_status." - ".$ca_transaction->ReqName}}">
                                                    {{ $ca_transaction->approval_status }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="badge text-bg-{{ $ca_transaction->approval_sett == 'Approved' ? 'success' : ($ca_transaction->approval_sett == 'Declaration' ? 'info' : ($ca_transaction->approval_sett == 'Pending' ? 'warning' : ($ca_transaction->approval_sett == 'Rejected' ? 'danger' : ($ca_transaction->approval_sett == 'Draft' ? 'secondary' : 'default')))) }}" style="pointer-events: auto; cursor: default;" title="{{$ca_transaction->approval_sett." - ".$ca_transaction->settName}}">
                                                    {{ $ca_transaction->approval_sett }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="badge text-bg-{{ $ca_transaction->ca_status == 'Done' ? 'success' :
                                                ($ca_transaction->ca_status == 'Refund' ? 'danger' :
                                                ($ca_transaction->ca_status == 'On Progress' ? 'secondary' : 'default')) }}" title="{{$ca_transaction->paid_date}}">
                                                    {{ $ca_transaction->ca_status }}
                                                </p>
                                            </td>
                                            <td class="text-left">
                                                @if(($ca_transaction->approval_status == 'Pending') ||
                                                    ($ca_transaction->approval_status == 'Approved' &&
                                                    ($ca_transaction->approval_sett == '' || $ca_transaction->approval_sett == 'Draft')))
                                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" title="Approval Update" data-bs-target="#approvalModal"
                                                        data-type="{{ $ca_transaction->type_ca }}"
                                                        data-total="{{ number_format($ca_transaction->total_ca, 0, ',', '.') }}"
                                                        data-id="{{ $ca_transaction->id }}"
                                                        data-no="{{ $ca_transaction->no_ca }}"
                                                        data-sppd="{{ $ca_transaction->no_sppd }}"
                                                        data-sett="{{ $ca_transaction->approval_sett }}"
                                                        data-status="{{ $ca_transaction->approval_status }}"
                                                        data-start-date="{{ $ca_transaction->start_date }}"
                                                        data-end-date="{{ $ca_transaction->end_date }}"
                                                        data-total-days="{{ $ca_transaction->total_days }}">
                                                        <i class="bi bi-list-check"></i>
                                                    </button>
                                                @elseif(($ca_transaction->approval_sett == '' || $ca_transaction->approval_sett == 'Pending' || $ca_transaction->approval_sett == 'Approved') && $ca_transaction->approval_status != 'Rejected' && $ca_transaction->approval_status != 'Draft')
                                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" title="Approval Declaration Update" data-bs-target="#approvalDecModal"
                                                        data-type="{{ $ca_transaction->type_ca }}"
                                                        data-total="{{ number_format($ca_transaction->total_ca, 0, ',', '.') }}"
                                                        data-id="{{ $ca_transaction->id }}"
                                                        data-no="{{ $ca_transaction->no_ca }}"
                                                        data-sppd="{{ $ca_transaction->no_sppd }}"
                                                        data-sett="{{ $ca_transaction->approval_sett }}"
                                                        data-status="{{ $ca_transaction->approval_status }}"
                                                        data-start-date="{{ $ca_transaction->start_date }}"
                                                        data-end-date="{{ $ca_transaction->end_date }}"
                                                        data-total-days="{{ $ca_transaction->total_days }}">
                                                        <i class="bi bi-list-check"></i>
                                                    </button>
                                                @endif
                                                @if($ca_transaction->approval_sett=='Approved' && $ca_transaction->ca_status<>'Done')
                                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" title="Status Update" data-bs-target="#statusModal"
                                                        data-id="{{ $ca_transaction->id }}"
                                                        data-status="{{ $ca_transaction->ca_status }}"
                                                        data-no="{{ $ca_transaction->no_ca }}">
                                                        <i class="ri-file-edit-line"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($ca_transaction->approval_status != 'Draft' && $ca_transaction->approval_status != 'Rejected')
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"  data-bs-target="#exportModal"
                                                        data-id="{{ $ca_transaction->id }}"
                                                        data-status="{{ $ca_transaction->approval_sett }}"
                                                        data-no="{{ $ca_transaction->no_ca }}"
                                                        title="Print">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cashadvanced.delete', $ca_transaction->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button onclick="return confirm('Are you sure you want to delete this transaction?')" class="btn btn-outline-danger" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
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

    @include('hcis.reimbursements.cashadv.navigation.modalCashadv')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
@endsection
@section('script')
    {{-- @vite(['resources/js/pages/demo.form-advanced.js']) --}}

    <!-- Include jQuery -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <!-- Include Bootstrap Date Range Picker -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('/js/cashAdvanced/adminPage.js') }}"></script>
@endsection
@push('scripts')
    <script>
        // Periksa apakah ada pesan sukses
        var successMessage = "{{ session('success') }}";

        // Jika ada pesan sukses, tampilkan sebagai alert
        if (successMessage) {
            Swal.fire({
                title: "Success!",
                text: successMessage,
                icon: "success",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: "Ok",
            });
        }
        function redirectToExportExcel() {
            const route = "{{ route('exportca.excel') }}";

            const startDate = document.getElementById("start_date").value;
            const endDate = document.getElementById("end_date").value;

            // Create a form element
            const form = document.createElement("form");
            form.method = "GET";
            form.action = route;

            const startDateInput = document.createElement("input");
            startDateInput.type = "hidden";
            startDateInput.name = "start_date";
            startDateInput.value = startDate;

            const endDateInput = document.createElement("input");
            endDateInput.type = "hidden";
            endDateInput.name = "end_date";
            endDateInput.value = endDate;

            form.appendChild(startDateInput);
            form.appendChild(endDateInput);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        }

        // Event listener untuk menangkap date range yang dipilih
        $("#singledaterange").on("apply.daterangepicker", function (ev, picker) {
            var startDate = picker.startDate.format("YYYY-MM-DD");
            var endDate = picker.endDate.format("YYYY-MM-DD");

            // Panggil fungsi untuk mendapatkan data yang difilter
            filterTableByDateRange(startDate, endDate);
        });

        // Fungsi untuk mengirimkan tanggal yang dipilih ke server dan memperbarui tabel
        function filterTableByDateRange(startDate, endDate) {
            $.ajax({
                url: '{{ route("cashadvanced.admin") }}', // Route yang sudah Anda buat
                type: "GET",
                data: {
                    start_date: startDate,
                    end_date: endDate,
                },
                success: function (response) {
                    // Perbarui tabel dengan data yang difilter
                    // $('#scheduleTable tbody').html(response);
                    // $('#tableFilter').html(response);
                },
                error: function (xhr) {
                    // console.error(xhr);
                },
            });
        }
        //script modal

        // Modal Export
        document.addEventListener("DOMContentLoaded", function () {
            var exportModal = document.getElementById("exportModal");
            var declareSection = document.querySelector(".declare-section");
            exportModal.addEventListener("show.bs.modal", function (event) {
                var button = event.relatedTarget;

                var transactionId = button.getAttribute("data-id");
                var status = button.getAttribute("data-status");
                console.log(status);

                var downloadLink = document.getElementById("downloadLink");
                downloadLink.href =
                    '{{ route("cashadvanced.download", ":id") }}'.replace(
                        ":id",
                        transactionId
                    );

                var declareLink = document.getElementById("declareLink");
                declareLink.href =
                    '{{ route("cashadvanced.downloadDeclare", ":id") }}'.replace(
                        ":id",
                        transactionId
                    );

                var transactionInput = document.getElementById("transaction_id");
                transactionInput.value = transactionId;

                if (
                    status === "Pending" ||
                    status === "Approved"
                ) {
                    declareSection.style.display = "flex"; // Tampilkan
                } else {
                    declareSection.style.display = "none"; // Sembunyikan
                }
            });
        });

        // Modal Mengubah Status
        document.addEventListener("DOMContentLoaded", function () {
            var statusModal = document.getElementById("statusModal");
            statusModal.addEventListener("show.bs.modal", function (event) {
                // Dapatkan tombol yang men-trigger modal
                var button = event.relatedTarget;

                // Ambil data-id dan data-status dari tombol tersebut
                var transactionId = button.getAttribute("data-id");
                var transactionStatus = button.getAttribute("data-status");

                // Temukan form di dalam modal dan update action-nya
                var form = statusModal.querySelector("form");
                var action = form.getAttribute("action");
                form.setAttribute("action", action.replace(":id", transactionId));

                // Set nilai transaction_id di input hidden
                var transactionInput = form.querySelector("#transaction_id");
                transactionInput.value = transactionId;

                // Pilih status yang sesuai di dropdown
                var statusSelect = form.querySelector("#ca_status");
                statusSelect.value = transactionStatus;

                // Update opsi dropdown berdasarkan status yang dipilih
                updateStatusOptions(transactionStatus, statusSelect);
            });

            function updateStatusOptions(selectedStatus, statusSelect) {
                // Reset opsi yang ada
                var options = [
                    { value: 'On Progress', text: 'On Progress' },
                    { value: 'Refund', text: 'Refund' },
                    { value: 'Done', text: 'Done' }
                ];

                // Filter opsi berdasarkan status yang dipilih
                var filteredOptions;
                if (selectedStatus === 'On Progress') {
                    filteredOptions = options.filter(function(option) {
                        return option.value === 'On Progress' || option.value === 'Done';
                    });
                } else if (selectedStatus === 'Refund') {
                    filteredOptions = options.filter(function(option) {
                        return option.value === 'Refund' || option.value === 'Done';
                    });
                } else {
                    filteredOptions = options; // Default: tampilkan semua opsi
                }

                // Hapus opsi yang ada
                while (statusSelect.options.length > 0) {
                    statusSelect.remove(0);
                }

                // Tambahkan opsi baru yang sudah difilter
                filteredOptions.forEach(function(option) {
                    var newOption = new Option(option.text, option.value);
                    statusSelect.add(newOption);
                });

                // Set nilai dropdown ke status yang dipilih
                statusSelect.value = selectedStatus;
            }
        });

        // Approval Request Modal
        document.addEventListener('DOMContentLoaded', function () {
            var approvalModal = document.getElementById('approvalModal');
            approvalModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                var transactionType = button.getAttribute('data-type');
                var transactionTotal = button.getAttribute('data-total');
                var transactionId = button.getAttribute('data-id');
                var transactionNo = button.getAttribute('data-no');
                var transactionSPPD = button.getAttribute('data-sppd');

                var form = approvalModal.querySelector('form');
                var action = form.getAttribute('action');
                form.setAttribute('action', action.replace(':id', transactionId));

                form.querySelector('#ca_type').value = transactionType;
                form.querySelector('#totalca').value = transactionTotal;
                form.querySelector('#no_id').value = transactionId;
                form.querySelector('#no_ca').value = transactionNo;
                form.querySelector('#bisnis_numb').value = transactionSPPD;

                var buttonList = document.getElementById('buttonList');
                buttonList.innerHTML = '';

                var previousLayerApproved = true; // Untuk mengecek status layer sebelumnya

                @foreach ($ca_approvals as $approval)
                    if (transactionId === "{{ $approval->ca_id }}") {
                        var rowContainer = document.createElement('div');
                        rowContainer.className = 'row mb-3 text-center';

                        var nameCol = document.createElement('div');
                        nameCol.className = 'col-md-6';
                        var nameText = document.createElement('div');
                        nameText.innerHTML = `
                                {{ $approval->ReqName }}
                            `;
                        nameCol.appendChild(nameText);

                        var buttonCol = document.createElement('div');
                        buttonCol.className = 'col-md-6';

                        var dateText = document.createElement('p');

                        if ("{{ $approval->approval_status }}" === "Approved") {
                            dateText.textContent = `{{ $approval->approval_status }} ({{ \Carbon\Carbon::parse($approval->approved_at)->format('d-M-y') }})`;
                            buttonCol.appendChild(dateText);
                        } else if (previousLayerApproved) {
                            // Form Data
                            var dataNoIdInput = document.createElement('input');
                            dataNoIdInput.type = 'hidden';
                            dataNoIdInput.name = 'data_no_id';
                            dataNoIdInput.value = "{{ $approval->id }}";

                            // Tombol Approve
                            var rejectButton = document.createElement('button');
                            rejectButton.type = 'button';
                            rejectButton.className = 'btn btn-sm btn-primary btn-pill px-1 me-1';
                            rejectButton.setAttribute('data-bs-toggle', 'modal'); // Menambahkan atribut data-bs-toggle
                            rejectButton.setAttribute('data-bs-target', '#modalReject'); // Menambahkan atribut data-bs-target
                            rejectButton.setAttribute('data-no-id', transactionId); // Menambahkan atribut data-no-id
                            rejectButton.setAttribute('data-no-ca', transactionNo); // Menambahkan atribut data-no-ca
                            rejectButton.setAttribute('data-no-idCA', '{{ $approval->id }}');
                            rejectButton.textContent = 'Reject';

                            // Tombol Approve
                            var approveButton = document.createElement('button');
                            approveButton.type = 'submit';
                            approveButton.name = 'action_ca_approve';
                            approveButton.value = 'Approve';
                            approveButton.className = 'btn btn-sm btn-success btn-pill px-1';
                            approveButton.textContent = 'Approve';
                            approveButton.setAttribute('data-no-ca', transactionNo); // Tambahkan data-no-ca agar SweetAlert bisa menggunakan nilai ini

                            // Tambahkan event listener SweetAlert pada tombol Approve
                            addSweetAlert(approveButton);

                            form.querySelector('#data_no_id').value = "{{ $approval->id }}";

                            buttonCol.appendChild(rejectButton);
                            buttonCol.appendChild(approveButton);
                        } else {
                            // Jika layer sebelumnya tidak disetujui, layer ini tidak menampilkan tombol
                            dateText.textContent = 'Waiting for previous approval';
                            buttonCol.appendChild(dateText);
                        }

                        // Jika approval_status tidak "Approved", previousLayerApproved menjadi false
                        if ("{{ $approval->approval_status }}" !== "Approved") {
                            previousLayerApproved = false;
                        }

                        rowContainer.appendChild(nameCol);
                        rowContainer.appendChild(buttonCol);

                        buttonList.appendChild(rowContainer);
                    }
                @endforeach
            });
        });

        // Approval Declaration Modal
        document.addEventListener('DOMContentLoaded', function () {
            var approvalDecModal = document.getElementById('approvalDecModal');

            approvalDecModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                var transactionType = button.getAttribute('data-type');
                var transactionTotal = button.getAttribute('data-total');
                var transactionId = button.getAttribute('data-id');
                var transactionNo = button.getAttribute('data-no');
                var transactionSPPD = button.getAttribute('data-sppd');
                var transactionStart = button.getAttribute('data-start-date');
                var transactionEnd = button.getAttribute('data-end-date');
                var transactionTotal = button.getAttribute('data-total-days');

                document.getElementById('approval_no_ca').textContent = transactionNo;

                var form = approvalDecModal.querySelector('form');
                var action = form.getAttribute('action');
                form.setAttribute('action', action.replace(':id', transactionId));

                form.querySelector('#ca_type').value = transactionType;
                form.querySelector('#totalca').value = transactionTotal;
                form.querySelector('#no_id').value = transactionId;
                form.querySelector('#no_ca').value = transactionNo;
                form.querySelector('#bisnis_numb').value = transactionSPPD;

                // Clear existing content to prevent duplicates
                document.getElementById('requestList').innerHTML = '';
                document.getElementById('declarationList').innerHTML = '';

                var previousLayerApproved = true; // To check previous layer status
                var previousLayerApprovedDec = true; // To check previous declaration status

                var requestLabel = document.createElement('label');
                requestLabel.className = 'col-form-label mb-3';
                requestLabel.textContent = 'Approval Request';
                document.getElementById('requestList').appendChild(requestLabel);

                var declarationLabel = document.createElement('label');
                declarationLabel.className = 'col-form-label mb-3';
                declarationLabel.textContent = 'Approval Declaration';
                document.getElementById('declarationList').appendChild(declarationLabel);

                @foreach ($ca_approvals as $approval)
                    if (transactionId === "{{ $approval->ca_id }}") {
                        var rowContainer = document.createElement('div');
                        rowContainer.className = 'row mb-3 text-center';

                        var nameCol = document.createElement('div');
                        nameCol.className = 'col-md-6';
                        var nameText = document.createElement('p');
                        nameText.innerHTML = "{{ $approval->ReqName }} </br> Layer {{ $approval->layer}}";
                        nameCol.appendChild(nameText);

                        var buttonCol = document.createElement('div');
                        buttonCol.className = 'col-md-6';

                        var dateText = document.createElement('p');

                        if ("{{ $approval->approval_status }}" === "Approved") {
                            dateText.textContent = "{{ $approval->approval_status }} ({{ \Carbon\Carbon::parse($approval->approved_at)->format('d-M-y') }})";
                            buttonCol.appendChild(dateText);
                        } else if (previousLayerApproved) {
                            dateText.textContent = 'Something Wrong, This form just for Approve Declaration';
                            buttonCol.appendChild(dateText);
                        } else {
                            dateText.textContent = 'Waiting for previous approval';
                            buttonCol.appendChild(dateText);
                        }

                        if ("{{ $approval->approval_status }}" !== "Approved") {
                            previousLayerApproved = false;
                        }

                        rowContainer.appendChild(nameCol);
                        rowContainer.appendChild(buttonCol);

                        document.getElementById('requestList').appendChild(rowContainer);
                    }
                @endforeach

                @foreach ($ca_sett as $approval_sett)
                    if (transactionId === "{{ $approval_sett->ca_id }}") {
                        var rowContainerDec = document.createElement('div');
                        rowContainerDec.className = 'row mb-3 text-center';

                        var nameColDec = document.createElement('div');
                        nameColDec.className = 'col-md-6';
                        var nameTextDec = document.createElement('p');
                        nameTextDec.innerHTML = "{{ $approval_sett->ReqName }} </br> Layer {{ $approval_sett->layer }}";
                        nameColDec.appendChild(nameTextDec);

                        var buttonColDec = document.createElement('div');
                        buttonColDec.className = 'col-md-6';

                        var dateTextDec = document.createElement('p');

                        if ("{{ $approval_sett->approval_status }}" === "Approved") {
                            dateTextDec.textContent = "{{ $approval_sett->approval_status }} ({{ \Carbon\Carbon::parse($approval_sett->approved_at)->format('d-M-y') }})";
                            buttonColDec.appendChild(dateTextDec);
                        } else if (previousLayerApprovedDec) {
                            var rejectButtonDec = document.createElement('button');
                            rejectButtonDec.type = 'button'; // Mengubah type menjadi 'button'
                            rejectButtonDec.className = 'btn btn-sm btn-primary btn-pill px-1 me-1'; // Mengatur class sesuai yang diinginkan
                            rejectButtonDec.setAttribute('data-bs-toggle', 'modal'); // Menambahkan atribut data-bs-toggle
                            rejectButtonDec.setAttribute('data-bs-target', '#modalRejectDec'); // Menambahkan atribut data-bs-target
                            rejectButtonDec.setAttribute('data-no-id', transactionId); // Menambahkan atribut data-no-id
                            rejectButtonDec.setAttribute('data-no-ca', transactionNo); // Menambahkan atribut data-no-ca
                            rejectButtonDec.setAttribute('data-start-date', transactionStart); // Menambahkan atribut data-start-date
                            rejectButtonDec.setAttribute('data-end-date', transactionEnd); // Menambahkan atribut data-end-date
                            rejectButtonDec.setAttribute('data-total-days', transactionTotal); // Menambahkan atribut data-total-days
                            rejectButtonDec.setAttribute('data-no-idCA', '{{ $approval_sett->id }}');
                            rejectButtonDec.textContent = 'Reject'; // Mengubah text button

                            var approveButtonDec = document.createElement('button');
                            approveButtonDec.type = 'submit';
                            approveButtonDec.name = 'action_ca_approve';
                            approveButtonDec.value = 'Approve';
                            approveButtonDec.className = 'btn btn-sm btn-success btn-pill px-1';
                            approveButtonDec.textContent = 'Approve';
                            approveButtonDec.setAttribute('data-no-ca', transactionNo); // Tambahkan data-no-ca agar SweetAlert bisa menggunakan nilai ini

                            // Tambahkan event listener SweetAlert pada tombol Approve
                            addSweetAlertDec(approveButtonDec);

                            form.querySelector('#data_no_id').value = "{{ $approval_sett->id }}";

                            buttonColDec.appendChild(rejectButtonDec);
                            buttonColDec.appendChild(approveButtonDec);
                        } else {
                            dateTextDec.textContent = 'Waiting for previous approval';
                            buttonColDec.appendChild(dateTextDec);
                        }

                        if ("{{ $approval_sett->approval_status }}" !== "Approved") {
                            previousLayerApprovedDec = false;
                        }
                        rowContainerDec.appendChild(nameColDec);
                        rowContainerDec.appendChild(buttonColDec);

                        document.getElementById('declarationList').appendChild(rowContainerDec);
                    }
                @endforeach
            });
        });

        function addSweetAlert(approveButton) {
            approveButton.addEventListener("click", function (event) {
                event.preventDefault(); // Mencegah submit form secara langsung
                const transactionCA = approveButton.getAttribute("data-no-ca");
                const form = document.getElementById("approveForm");

                Swal.fire({
                    title: `Do you want to approve transaction "${transactionCA}"?`,
                    text: "You won't be able to revert this!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#0c63e4",
                    cancelButtonColor: "#9a2a27",
                    confirmButtonText: "Yes, approve it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat input baru untuk action_ca_approve
                        const input = document.createElement("input");
                        input.type = "hidden"; // Set input sebagai hidden
                        input.name = "action_ca_approve"; // Set nama input
                        input.value = "Approve"; // Set nilai input

                        // Tambahkan input ke form
                        form.appendChild(input);

                        form.submit(); // Kirim form
                    }
                });
            });
        }

        function addSweetAlertDec(approveButtonDec) {
            approveButtonDec.addEventListener("click", function (event) {
                event.preventDefault(); // Mencegah submit form secara langsung
                const transactionCA = approveButtonDec.getAttribute("data-no-ca");
                const form = document.getElementById("approveFormDec");

                Swal.fire({
                    title: `Do you want to approve transaction "${transactionCA}"?`,
                    text: "You won't be able to revert this!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#0c63e4",
                    cancelButtonColor: "#9a2a27",
                    confirmButtonText: "Yes, approve it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buat input baru untuk action_ca_approve
                        const input = document.createElement("input");
                        input.type = "hidden"; // Set input sebagai hidden
                        input.name = "action_ca_approve"; // Set nama input
                        input.value = "Approve"; // Set nilai input

                        // Tambahkan input ke form
                        form.appendChild(input);

                        form.submit(); // Kirim form
                    }
                });
            });
        }

        // Reject Request Modal
        document.addEventListener("DOMContentLoaded", function () {
            var modalRejectDec = document.getElementById("modalReject");
            modalReject.addEventListener("show.bs.modal", function (event) {
                var button = event.relatedTarget;

                var transactionId = button.getAttribute("data-no-id");
                var transactionNo = button.getAttribute("data-no-ca");
                var transactionIdCA = button.getAttribute("data-no-idCA");
                console.log(transactionIdCA);

                // Mendefinisikan form terlebih dahulu
                var form = modalReject.querySelector("form");

                form.querySelector("#data_no_id").value = transactionIdCA;

                document.getElementById("reject_no_ca_2").textContent = transactionNo;

                var form = modalReject.querySelector("form");
                var action = form.getAttribute("action");
                form.setAttribute("action", action.replace(":id", transactionId));
            });
        });

        // Reject Declaration Modal
        document.addEventListener("DOMContentLoaded", function () {
            var modalRejectDec = document.getElementById("modalRejectDec");
            modalRejectDec.addEventListener("show.bs.modal", function (event) {
                var button = event.relatedTarget;

                var transactionId = button.getAttribute("data-no-id");
                var transactionNo = button.getAttribute("data-no-ca");
                var transactionIdCA = button.getAttribute("data-no-idCA");
                console.log(transactionIdCA);

                // Mendefinisikan form terlebih dahulu
                var form = modalRejectDec.querySelector("form");

                form.querySelector("#data_no_id").value = transactionIdCA;

                document.getElementById("rejectDec_no_ca_2").textContent =
                    transactionNo;

                var form = modalRejectDec.querySelector("form");
                var action = form.getAttribute("action");
                form.setAttribute("action", action.replace(":id", transactionId));
            });
        });

    </script>

    @if (session('refresh'))
        <script>
            // Refresh the page after 1 seconds
            setTimeout(function(){
                window.location.reload();
            }, 1000);
        </script>
    @endif
@endpush
