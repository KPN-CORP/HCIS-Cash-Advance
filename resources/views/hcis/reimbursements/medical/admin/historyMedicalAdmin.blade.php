  {{-- Detail Penggunaan Plafond --}}
  <h4>Health Coverage Usage History</h4>
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
                              style="font-size: 12px; padding: 0.5rem 1rem; cursor: pointer;"
                              @if ($item->status == 'Rejected' && isset($rejectMedic[$item->no_medic])) onclick="showRejectInfo('{{ $item->no_medic }}')"
                                title="Click to see rejection reason" @endif>
                              {{ $item->status }}
                          </span>
                      </td>
                      <td class="text-center">
                          <form method="GET" action="/medical/admin/form-update/{{ $item->usage_id }}"
                              style="display: inline-block;">
                              <button type="submit" class="btn btn-outline-warning rounded-pill my-1"
                                  data-toggle="tooltip" title="Edit">
                                  <i class="bi bi-pencil-square"></i>
                              </button>
                          </form>
                          <form id="deleteForm_{{ $item->no_medic }}" method="POST"
                              action="/medical/admin/delete/{{ $item->usage_id }}" style="display: inline-block;">
                              @csrf
                              @method('DELETE')
                              <input type="hidden" id="no_sppd_{{ $item->no_medic }}" value="{{ $item->no_medic }}">
                              <button type="button" class="btn btn-outline-danger rounded-pill delete-button"
                                  data-id="{{ $item->no_medic }}">
                                  <i class="bi bi-trash-fill"></i>
                              </button>
                          </form>
                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  </div>
