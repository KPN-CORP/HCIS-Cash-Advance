@extends('layouts_.vertical', ['page_title' => 'Business Trip'])

@section('css')
    <style>
        .btn-action {
            margin-right: 10px;

        }

        .date-range {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .date-range label {
            margin-right: 10px;
        }

        .date-range input {
            margin-right: 10px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            align-content: center;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .table th.rowspan,
        .table th.colspan {
            border-bottom: 2px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .badge-med {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 10px;
            display: inline-block;
            width: 64px;
            text-align: center;
        }

        .badge-success {
            background-color: green;
            color: white;
            font-weight: bold;
        }

        .badge-danger {
            background-color: red;
            color: white;
            font-weight: bold;
        }

        .badge-pending {
            background-color: orange;
            color: black;
            font-weight: bold;
        }

        .pagination {
            margin-top: 10px;
            display: flex;
            gap: 5px;
        }

        .page-item {
            width: 100px;
            text-align: center;
        }

        .page-item {
            width: 100px;
            text-align: center;
        }

        .page-link {
            display: block;
            padding: 8px 20px;
            border: 0px;

            border-radius: 4px;
            text-decoration: none;
        }

        .page-item.disabled .page-link {
            background-color: #CCCCCC !important;
            /* Light grey background for disabled state */
            color: #888888 !important;
            /* Darker grey text color for disabled state */
            cursor: not-allowed;
            /* Change cursor to indicate disabled */
            border: 1px solid #CCCCCC !important;
            /* Match border with background color */
        }
        .btn-detail {
            font-size: 12px !important;
            padding: 3px 6px !important;
            border-radius: 3px !important;
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
                            <li class="breadcrumb-item">{{ $parentLink }}</li>
                            <li class="breadcrumb-item active">{{ $link }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $link }}</h4>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <a href="/reimbursements" class="btn btn-primary btn-action">
                    <i class="bi bi-caret-left-fill"></i> Back
                </a>
                {{-- <a href="/businessTrip" class="btn btn-info btn-action">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a> --}}
                <a href="/businessTrip/form/add" class="btn btn-outline-primary btn-action">
                    <i class="bi bi-plus-circle"></i> Add Data
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form class="date-range mb-3" method="GET" action="{{ route('businessTrip-filterDate.approval') }}">
                    <label for="start-date">Departure Date:</label>
                    <input type="date" id="start-date" name="start-date" class="form-control"
                        value="{{ request()->query('start-date') }}">
                    <label for="end-date">To:</label>
                    <input type="date" id="end-date" name="end-date" class="form-control"
                        value="{{ request()->query('end-date') }}">
                    <button type="submit" class="btn btn-primary">Find</button>
                    {{-- <a href="{{ route('filterDate') }}" class="btn btn-secondary" style="background-color: #e0e0e0; border:0px;">Reset Filter</a> --}}
                </form>
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title">Data SPPD</h3>
                            <form class="input-group" method="GET" id="searchForm" action="/businessTrip/search"
                                style="width: 300px;">
                                <input name="q" type="text" class="form-control" placeholder="Search...">
                                <button class="btn btn-outline-secondary" style="outline-color: #AB2F2B;" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th rowspan="3">No</th>
                                        <th rowspan="3">Nama</th>
                                        <th rowspan="3">Divisi</th>
                                        <th rowspan="3">No SPPD</th>
                                        <th colspan="2" class="text-center">Perjalanan Dinas</th>
                                        <th colspan="4" class="text-center">SPPD</th>
                                        <th rowspan="3">Status</th>
                                        <th rowspan="3">Export</th>
                                        <th rowspan="3">Confirm</th>
                                        <th rowspan="3">Action</th>
                                    </tr>
                                    <tr>
                                        <th>Mulai</th>
                                        <th>Kembali</th>
                                        <th rowspan="2" class="text-center">CA</th>
                                        <th rowspan="2" class="text-center">Ticket</th>
                                        <th rowspan="2" class="text-center">Hotel</th>
                                        <th rowspan="2" class="text-center">Taksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sppd as $idx => $n)
                                        <tr>
                                            <th scope="row">{{ $sppd->firstItem() + $idx }}
                                            </th>
                                            <td>{{ $n->nama }}</td>
                                            <td>{{ $n->divisi }}</td>
                                            <td>{{ $n->no_sppd }}</td>
                                            <td>{{ $n->mulai }}</td>
                                            <td>{{ $n->kembali }}</td>
                                            <td>
                                                @if ($n->ca == 'Ya' && isset($ca))
                                                    <button class="btn btn-secondary btn-detail" data-toggle="modal"
                                                        data-target="#detailModal"
                                                        data-ca="{{ json_encode([
                                                            'no_ca' => $ca->no_ca,
                                                            'no_sppd' => $ca->no_sppd,
                                                            'unit' => $ca->unit,
                                                            'destination' => $ca->destination,
                                                            'total_ca' => $ca->total_ca,
                                                            'total_real' => $ca->total_real,
                                                            'total_cost' => $ca->total_cost,
                                                            'start_date' => $ca->start_date,
                                                            'end_date' => $ca->end_date,
                                                        ]) }}"
                                                        data-tiket="{{ $n->tiket }}" data-hotel="{{ $n->hotel }}"
                                                        data-taksi="{{ $n->taksi }}">Detail</button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($n->tiket == 'Ya')
                                                    <button class="btn btn-secondary btn-detail" data-toggle="modal"
                                                        data-target="#detailModal" data-ca="{{ $n->ca }}"
                                                        data-tiket="{{ $n->tiket }}"
                                                        data-hotel="{{ $n->hotel }}"
                                                        data-taksi="{{ $n->taksi }}">Detail</button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($n->hotel == 'Ya')
                                                    <button class="btn btn-secondary btn-detail" data-toggle="modal"
                                                        data-target="#detailModal" data-ca="{{ $n->ca }}"
                                                        data-tiket="{{ $n->tiket }}" data-hotel="{{ $n->hotel }}"
                                                        data-taksi="{{ $n->taksi }}">Detail</button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($n->taksi == 'Ya')
                                                    <button class="btn btn-secondary btn-detail" data-toggle="modal"
                                                        data-target="#detailModal" data-ca="{{ $n->ca }}"
                                                        data-tiket="{{ $n->tiket }}"
                                                        data-hotel="{{ $n->hotel }}"
                                                        data-taksi="{{ $n->taksi }}">Detail</button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge-med
                                                    @if ($n->status === 'Diterima') badge-success
                                                    @elseif($n->status === 'Ditolak') badge-danger
                                                    @elseif($n->status === 'Pending') badge-pending @endif">
                                                    {{ $n->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('pdf', $n->id) }}" class="btn btn-outline-primary"
                                                    target="_blank">
                                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ url('businessTrip/status/change/' . $n->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Diterima">
                                                    <button type="submit" class="btn btn-primary mb-2"
                                                        onclick="return confirm('Are you sure you want to accept this?')">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ url('businessTrip/status/change/' . $n->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <button type="submit" class="btn btn-outline-danger mb-2"
                                                        onclick="return confirm('Are you sure you want to reject this?')">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET"
                                                action="/businessTrip/form/update/{{ $n->id }}"
                                                style="display: inline-block;">
                                                <button type="submit" class="btn btn-success mb-2"
                                                    {{ $n->status === 'Diterima' ? 'disabled' : '' }}
                                                    data-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </form>
                                                <form id="deleteForm_{{ $n->id }}" method="POST"
                                                    action="/businessTrip/delete/{{ $n->id }}"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button" class="btn btn-outline-danger mb-2"
                                                        onclick="confirmDelete('{{ $n->id }}')"
                                                        {{ $n->status === 'Diterima' ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">No data available in table</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="records-per-page">
                                <select class="form-select" id="recordsPerPage">
                                    <option value="10" {{ request()->query('per_page') == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="25" {{ request()->query('per_page') == 25 ? 'selected' : '' }}>25
                                    </option>
                                    <option value="35" {{ request()->query('per_page') == 35 ? 'selected' : '' }}>35
                                    </option>
                                    <option value="50" {{ request()->query('per_page') == 50 ? 'selected' : '' }}>50
                                    </option>
                                </select>
                                <span>records per page</span>
                            </div>
                            <div>
                                <span>{{ $sppd->count() }} of {{ $sppd->total() }} records</span>
                            </div>

                            <nav aria-label="Page navigation" class="mt-3">
                                <ul class="pagination justify-content-end">
                                    @if ($sppd->onFirstPage())
                                        <li class="page-item disabled">
                                            <a class="page-link text-primary" href="#" tabindex="-1"><i
                                                    class="bi bi-caret-left-fill"></i> Previous</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link text-primary"
                                                href="{{ $sppd->appends(['per_page' => request()->query('per_page')])->previousPageUrl() }}"
                                                tabindex="-1"><i class="bi bi-caret-left-fill"></i> Previous</a>
                                        </li>
                                    @endif

                                    @if ($sppd->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link text-primary"
                                                href="{{ $sppd->appends(['per_page' => request()->query('per_page')])->nextPageUrl() }}">Next
                                                <i class="bi bi-caret-right-fill"></i></a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <a class="page-link text-primary" href="#" tabindex="-1">Next <i
                                                    class="bi bi-caret-right-fill"></i> </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function getDate() {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1; // January is 0!
                var yyyy = today.getFullYear();

                if (dd < 10) {
                    dd = '0' + dd;
                }

                if (mm < 10) {
                    mm = '0' + mm;
                }

                // Correct date format for input fields
                var formattedToday = yyyy + '-' + mm + '-' + dd;
                console.log(formattedToday);

                var startDateElement = document.getElementById("start-date");
                var endDateElement = document.getElementById("end-date");

                // Only set the value if it's not already set
                if (!startDateElement.value) {
                    startDateElement.value = formattedToday;
                }
                if (!endDateElement.value) {
                    endDateElement.value = formattedToday;
                }
            }

            // Ensure the DOM is fully loaded before manipulating it
            document.addEventListener('DOMContentLoaded', function() {
                getDate();
            });


            document.getElementById('recordsPerPage').addEventListener('change', function() {
                const perPage = this.value;
                const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
                window.location.search = `?per_page=${perPage}&page=${currentPage}`;
            });

            function confirmDelete(id) {
                if (confirm("Are you sure you want to delete this item?")) {
                    document.getElementById('deleteForm_' + id).submit();
                }
            }
        </script>
    @endsection