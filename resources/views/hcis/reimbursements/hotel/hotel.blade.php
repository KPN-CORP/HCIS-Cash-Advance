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
                  <input type="text" name="customsearch" id="customsearch" class="form-control  border-dark-subtle border-left-0" placeholder="search.." aria-label="search" aria-describedby="search">
                </div>
              </div>
            </div>
            <div class="col">
                <div class="mb-2 text-end">
                    <a href="{{ route('hotel.form') }}" class="btn btn-primary rounded-pill shadow">Create CA</a>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card shadow mb-4">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover dt-responsive nowrap" id="scheduleTable" width="100%" cellspacing="0">
                          <thead class="thead-light">
                              <tr class="text-center">
                                  <th>No</th>
                                  <th>No Hotel</th>
                                  {{-- <th>No SPPD</th> --}}
                                  <th>Requestor</th>
                                  <th>Hotel Name</th>
                                  <th>Location</th>
                                  <th>Rooms</th>
                                  <th>Bed Type</th>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $transaction->no_htl }}</td>
                                {{-- <td>{{ $transaction->no_sppd }}</td> --}}
                                <td>{{ $transaction->employee->fullname }}</td>
                                <td>{{ $transaction->nama_htl }}</td>
                                <td>{{ $transaction->lokasi_htl }}</td>
                                <td>{{ $transaction->jmlkmr_htl }}</td>
                                <td>{{ $transaction->bed_htl }}</td>
                                <td>{{ $transaction->tgl_masuk_htl }}</td>
                                <td>{{ $transaction->tgl_keluar_htl }}</td>
                                <td>
                                    @if ($transaction->hotel == 'Ya' && isset($hotel[$transaction->no_sppd]))
                                        <button class="btn btn-secondary btn-detail" data-toggle="modal"
                                            data-target="#detailModal"
                                            data-hotel="{{ json_encode([
                                                'no_htl' => $hotel[$transaction->no_sppd]->no_htl,
                                                'no_sppd' => $hotel[$transaction->no_sppd]->no_sppd,
                                                'unit' => $hotel[$transaction->no_sppd]->unit,
                                                'nama_htl' => $hotel[$transaction->no_sppd]->nama_htl,
                                                'lokasi_htl' => $hotel[$transaction->no_sppd]->lokasi_htl,
                                                'jmlkmr_htl' => $hotel[$transaction->no_sppd]->jmlkmr_htl,
                                                'bed_htl' => $hotel[$transaction->no_sppd]->bed_htl,
                                                'tgl_masuk_htl' => $hotel[$transaction->no_sppd]->tgl_masuk_htl,
                                                'tgl_keluar_htl' => $hotel[$transaction->no_sppd]->tgl_keluar_htl,
                                                'total_hari' => $hotel[$transaction->no_sppd]->total_hari,
                                            ]) }}">Detail</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
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
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <td class="text-center">
                                {{-- @if($transaction->created_by == $userId) --}}
                                    <a href="{{ route('hotel.edit', encrypt($transaction->id)) }}" class="btn btn-sm rounded-pill btn-primary" title="Edit" ><i class="ri-edit-box-line"></i></a>
                                    <form action="{{ route('hotel.delete', encrypt($transaction->id)) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button onclick="return confirm('Apakah ingin Menghapus?')" class="btn btn-sm rounded-pill btn-danger" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                {{-- @endif --}}
                                </td>
                            </tr>
                            @endforeach
                          </tbody>
                            @if(session('message'))
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
