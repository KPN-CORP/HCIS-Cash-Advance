@extends('layouts_.vertical', ['page_title' => 'Ticket'])

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
                    <a href="{{ route('ticket.form') }}" class="btn btn-primary rounded-pill shadow">Add Ticket</a>
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
                                        <th>No. SPPD</th>
                                        <th>No. Ticket</th>
                                        <th>Total Tickets</th>
                                        <th>Purposes</th>
                                        {{-- <th>Ticket Type</th> --}}
                                        {{-- <th>Requestor</th> --}}
                                        {{-- <th>Transportation Type</th> --}}
                                        {{-- <th>Passengers Name</th> --}}
                                        <th>From/To</th>
                                        {{-- <th>To</th> --}}
                                        {{-- <th>Departure</th> --}}
                                        {{-- <th>Departure Time</th> --}}
                                        {{-- <th>Homecoming</th> --}}
                                        {{-- <th>Homecoming Time</th> --}}
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td style="text-align: center">{{ $loop->index + 1 }}</td>
                                            <td>{{ $transaction->no_sppd }}</td>
                                            <td>{{ $transaction->no_tkt }}</td>
                                            <td style="text-align: left">{{ $ticketCounts[$transaction->no_tkt]['total'] ?? 1 }} Tickets</td>
                                            <td>{{ $transaction->jns_dinas_tkt }}</td>
                                            {{-- <td>{{ $transaction->np_tkt }}</td> --}}
                                            <td>{{ $transaction->dari_tkt . '/' . $transaction->ke_tkt }}</td>
                                            <td class="text-info">
                                                <a class="text-info btn-detail" data-toggle="modal"
                                                    data-target="#detailModal" style="cursor: pointer"
                                                    data-tiket="{{ json_encode(
                                                        $ticket[$transaction->no_tkt]->map(function ($ticket) {
                                                            return [
                                                                // 'No. Ticket' => $ticket->no_tkt ?? 'No Data',
                                                                'No. SPPD' => $ticket->no_sppd,
                                                                'No. Ticket' => $ticket->no_tkt,
                                                                'Passengers Name' => $ticket->np_tkt,
                                                                'Unit' => $ticket->unit,
                                                                'Gender' => $ticket->jk_tkt,
                                                                'NIK' => $ticket->noktp_tkt,
                                                                'Phone No.' => $ticket->tlp_tkt,
                                                                'Transport Type.' => $ticket->jenis_tkt,
                                                                'From' => $ticket->dari_tkt,
                                                                'To' => $ticket->ke_tkt,
                                                                'Information' => $ticket->ket_tkt ?? 'No Data',
                                                                'Ticket Type' => $ticket->type_tkt,
                                                                'Departure Date' => date('d-M-Y', strtotime($ticket->tgl_brkt_tkt)),
                                                                'Time' => !empty($ticket->jam_brkt_tkt) ? date('H:i', strtotime($ticket->jam_brkt_tkt)) : 'No Data',
                                                                'Return Date' => isset($ticket->tgl_plg_tkt) ? date('d-M-Y', strtotime($ticket->tgl_plg_tkt)) : 'No Data',
                                                                'Return Time' => !empty($ticket->jam_plg_tkt) ? date('H:i', strtotime($ticket->jam_plg_tkt)) : 'No Data',
                                                            ];
                                                        }),
                                                    ) }}">
                                                    <u>Details</u>
                                                </a>
                                            </td>

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
                                            <td class="text-center">
                                                @if ($transaction->approval_status == 'Draft')
                                                    <a href="{{ route('ticket.edit', encrypt($transaction->id)) }}"
                                                        class="btn btn-sm rounded-pill btn-outline-warning" title="Edit">
                                                        <i class="ri-edit-box-line"></i>
                                                    </a>
                                                    <form action="{{ route('ticket.delete', encrypt($transaction->id)) }}"
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
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="border: 0px; border-radius:4px;">
                        <span aria-hidden="true">&times;</span>
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


    {{-- @push('scripts') --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-detail').click(function() {
                var tiket = $(this).data('tiket');

                function createTableHtml(data, title) {
                    var tableHtml = '<h5>' + title + '</h5>';
                    tableHtml += '<div class="table-responsive"><table class="table table-sm"><thead><tr>';
                    var isArray = Array.isArray(data) && data.length > 0;

                    // Assuming all objects in the data array have the same keys, use the first object to create headers
                    if (isArray) {
                        for (var key in data[0]) {
                            if (data[0].hasOwnProperty(key)) {
                                tableHtml += '<th>' + key + '</th>';
                            }
                        }
                    } else if (typeof data === 'object') {
                        // If data is a single object, create headers from its keys
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                tableHtml += '<th>' + key + '</th>';
                            }
                        }
                    }

                    tableHtml += '</tr></thead><tbody>';

                    // Loop through each item in the array and create a row for each
                    if (isArray) {
                        data.forEach(function(row) {
                            tableHtml += '<tr>';
                            for (var key in row) {
                                if (row.hasOwnProperty(key)) {
                                    tableHtml += '<td>' + row[key] + '</td>';
                                }
                            }
                            tableHtml += '</tr>';
                        });
                    } else if (typeof data === 'object') {
                        // If data is a single object, create a single row
                        tableHtml += '<tr>';
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                tableHtml += '<td>' + data[key] + '</td>';
                            }
                        }
                        tableHtml += '</tr>';
                    }

                    tableHtml += '</tbody></table>';
                    return tableHtml;
                }

                // $('#detailTypeHeader').text('Detail Information');
                $('#detailContent').empty();

                try {
                    var content = '';

                    if (tiket && tiket !== 'undefined') {
                        var tiketData = typeof tiket === 'string' ? JSON.parse(tiket) : tiket;
                        content += createTableHtml(tiketData, 'Ticket Detail');
                    }

                    if (content !== '') {
                        $('#detailContent').html(content);
                    } else {
                        $('#detailContent').html('<p>No detail information available.</p>');
                    }

                    $('#detailModal').modal('show');
                } catch (e) {
                    $('#detailContent').html('<p>Error loading data</p>');
                }
            });

            $('#detailModal').on('hidden.bs.modal', function() {
                $('body').removeClass('modal-open').css({
                    overflow: '',
                    padding: ''
                });
                $('.modal-backdrop').remove();
            });
        });

        $(document).ready(function() {
            var table = $('#yourTableId').DataTable({
                "pageLength": 10 // Set default page length
            });
            // Set to 10 entries per page
            $('#dt-length-0').val(10);

            // Trigger the change event to apply the selected value
            $('#dt-length-0').trigger('change');
        });

        // Periksa apakah ada pesan sukses
        var successMessage = "{{ session('success') }}";

        // Jika ada pesan sukses, tampilkan sebagai alert
        if (successMessage) {
            alert(successMessage);
        }
    </script>
    {{-- @endpush --}}
@endsection
