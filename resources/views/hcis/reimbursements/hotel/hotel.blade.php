@extends('layouts_.vertical', ['page_title' => 'Hotel'])

@section('css')
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
                    <h4 class="page-title">{{ $link }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-auto">
                <div class="mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-dark-subtle"><i class="ri-search-line"></i></span>
                        </div>
                        <input type="text" name="customsearch" id="customsearch"
                            class="form-control  border-dark-subtle border-left-0" placeholder="Search.."
                            aria-label="search" aria-describedby="search">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="mb-2 text-end">
                    <a href="{{ route('hotel.form') }}" class="btn btn-primary rounded-pill shadow">Add Hotel</a>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover dt-responsive nowrap" id="scheduleTable" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th style="text-align: left">No Hotel</th>
                                        {{-- <th>No SPPD</th> --}}
                                        {{-- <th>Requestor</th> --}}
                                        <th>Hotel Name</th>
                                        <th>Location</th>
                                        <th style="text-align: left">Rooms</th>
                                        <th>Bed Type</th>
                                        <th>Status</th>
                                        {{-- <th>Start Date</th>
                                  <th>End Date</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td style="text-align: center">{{ $loop->index + 1 }}</td>
                                            <td style="text-align: left">{{ $transaction->no_htl }}</td>
                                            {{-- <td>{{ $transaction->employee->fullname }}</td> --}}
                                            <td>{{ $transaction->nama_htl }}</td>
                                            <td>{{ $transaction->lokasi_htl }}</td>
                                            <td style="text-align: left">{{ $transaction->jmlkmr_htl }}</td>
                                            <td>{{ $transaction->bed_htl }}</td>
                                            <td style="align-content: center">
                                                <span
                                                    class="badge rounded-pill bg-{{ $transaction->approval_status == 'Approved' ||
                                                    $transaction->approval_status == 'Declaration Approved' ||
                                                    $transaction->approval_status == 'Verified'
                                                        ? 'success'
                                                        : ($transaction->approval_status == 'Rejected' ||
                                                        $transaction->approval_status == 'Return/Refund' ||
                                                        $transaction->approval_status == 'Declaration Rejected'
                                                            ? 'danger'
                                                            : (in_array($transaction->approval_status, [
                                                                'Pending L1',
                                                                'Pending L2',
                                                                'Declaration L1',
                                                                'Declaration L2',
                                                                'Waiting Submitted',
                                                            ])
                                                                ? 'warning'
                                                                : ($transaction->approval_status == 'Draft'
                                                                    ? 'secondary'
                                                                    : (in_array($transaction->approval_status, ['Doc Accepted'])
                                                                        ? 'info'
                                                                        : 'secondary')))) }}"
                                                    style="font-size: 12px; padding: 0.5rem 1rem;"
                                                    @if ($transaction->approval_status == 'Pending L1') title="L1 Manager: {{ $managerL1Names ?? 'Unknown' }}"
                                        @elseif ($transaction->approval_status == 'Pending L2')
                                        title="L2 Manager: {{ $managerL2Names ?? 'Unknown' }}"
                                        @elseif($transaction->approval_status == 'Declaration L1') title="L1 Manager: {{ $managerL1Names ?? 'Unknown' }}"
                                        @elseif($transaction->approval_status == 'Declaration L2') title="L2 Manager: {{ $managerL2Names ?? 'Unknown' }}" @endif>
                                                    {{ $transaction->approval_status == 'Approved' ? 'Request Approved' : $transaction->approval_status }}
                                                </span>
                                            </td>
                                            {{-- <td>{{ \Carbon\Carbon::parse($transaction->tgl_masuk_htl)->format('d/m/Y') }}
                                <td>{{ \Carbon\Carbon::parse($transaction->tgl_keluar_htl)->format('d/m/Y') }} --}}
                                            <td class="text-center">
                                                @if ($transaction->approval_status == 'Draft')
                                                    <a href="{{ route('hotel.edit', encrypt($transaction->id)) }}"
                                                        class="btn btn-sm rounded-pill btn-outline-warning"
                                                        title="Edit"><i class="ri-edit-box-line"></i></a>
                                                    <form action="{{ route('hotel.delete', encrypt($transaction->id)) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <button onclick="return confirm('Do you want to delete this data?')"
                                                            class="btn btn-sm rounded-pill btn-outline-danger"
                                                            title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('ticket.export', ['id' => $transaction->id]) }}"
                                                        class="btn btn-sm btn-outline-info rounded-pill" target="_blank">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                            </td>
                                    @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if (session('message'))
                                    <script>
                                        alert('{{ session('message') }}');
                                    </script>
                                @endif
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
