@extends('layouts_.vertical', ['page_title' => 'Medical Approvals'])

@section('css')
    <style>
        th {
            color: white !important;
            text-align: center;
            margin-top: 10px;
        }

        table {
            white-space: nowrap;
            width: 100%;
        }

        tr.sticky {
            position: sticky;
            top: 0;
            z-index: 1;
            background: var(--stickyBackground);
        }

        th.sticky,
        td.sticky {
            position: sticky;
            left: 0;
            background: var(--stickyBackground);
        }

        table.dataTable>tbody>tr.child ul.dtr-details {
            width: 100%;
            vertical-align: middle !important;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li {
            display: flex;
            align-items: center !important;
        }

        table.dataTable>tbody>tr.child span.dtr-title {
            min-width: 120px !important;
            max-width: 120px !important;
            text-wrap: wrap !important;
        }
    </style>
@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-6 d-flex align-items-center">
                <ol class="breadcrumb mb-0" style="align-items: center; padding-left: 0;">
                    <li class="breadcrumb-item" style="font-size: 18px;">
                        <a href="/reimbursements">
                            {{ $parentLink }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        {{ $link }}
                    </li>
                </ol>
            </div>
        </div>
        @include('hcis.reimbursements.businessTrip.modal')
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display nowrap responsive" id="example" width="100%">
                                <thead class="bg-primary text-center align-middle">
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Period</th>
                                        <th data-priority="0">No. Medical</th>
                                        <th>Hospital Name</th>
                                        <th>Patient Name</th>
                                        <th>Disease</th>
                                        <th>Child Birth</th>
                                        <th>Inpatient</th>
                                        <th>Outpatient</th>
                                        <th>Glasses</th>
                                        <th data-priority="1">Status</th>
                                        <th data-priority="2">Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($medical as $item)
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d F Y') }}</td>
                                            <td class="text-center">{{ $item->period }}</td>
                                            <td class="text-center">{{ $item->no_medic }}</td>
                                            <td>{{ $item->hospital_name }}</td>
                                            <td>{{ $item->patient_name }}</td>
                                            <td>{{ $item->disease }}</td>
                                            <td class="text-center">
                                                {{ 'Rp. ' . number_format($item->child_birth_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{ 'Rp. ' . number_format($item->inpatient_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{ 'Rp. ' . number_format($item->outpatient_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{ 'Rp. ' . number_format($item->glasses_total, 0, ',', '.') }}
                                            </td>
                                            <td style="align-content: center; text-align: center">
                                                @php
                                                    $badgeClass = match ($item->status) {
                                                        'Pending' => 'bg-warning',
                                                        'Done' => 'bg-success',
                                                        'Rejected' => 'bg-danger',
                                                        'Draft' => 'bg-secondary',
                                                        default => 'bg-light',
                                                    };
                                                @endphp
                                                <span class="badge rounded-pill {{ $badgeClass }} text-center"
                                                    style="font-size: 12px; padding: 0.5rem 1rem;">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                <a class="btn btn-primary rounded-pill"
                                                    href="{{ route('medical-approval-form.edit', ['id' => $item->usage_id]) }}"
                                                    style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                    Act
                                                </a>
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="detailModalLabel">Detail Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"
                        style="border: 0px; border-radius:4px;">
                    </button>
                </div>
                <div class="modal-body">
                    <h6 id="detailTypeHeader" class="mb-3"></h6>
                    <div id="detailContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary rounded-pill" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/medical/medical.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#yourTableId').DataTable({
                "pageLength": 10 // Set default page length
            });
            // Set to 10 entries per page
            $('#dt-length-0').val(10);

            // Trigger the change event to apply the selected value
            $('#dt-length-0').trigger('change');
        });
    </script>
@endsection
