<script>
    var formCount = 0;

    window.addEventListener('DOMContentLoaded', function() {
        formCount = document.querySelectorAll('#form-container-detail > div').length;
        console.log("Form ada",formCount);
        updateCheckboxVisibility();
    });

    function addMoreFormDetail(event) {
        event.preventDefault();
        formCount++;

        const newForm = document.createElement("div");
        newForm.id = `form-container-e-detail-${formCount}`;
        newForm.className = "card-body bg-light p-2 mb-3";
        newForm.innerHTML = `
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Entertainment Type</label>
                    <select name="enter_type_e_detail[]" id="enter_type_e_detail_${formCount}" class="form-select">
                        <option value="">-</option>
                        <option value="food">Food/Beverages/Souvenir</option>
                        <option value="transport">Transport</option>
                        <option value="accommodation">Accommodation</option>
                        <option value="gift">Gift</option>
                        <option value="fund">Fund</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Amount</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_e_detail[]"
                            id="nominal_e_detail_${formCount}"
                            type="text" min="0" value="0"
                            onfocus="this.value = this.value === '0' ? '' : this.value;"
                            oninput="formatInputENT(this)">
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="form-label">Entertainment Fee Detail</label>
                    <textarea name="enter_fee_e_detail[]" class="form-control"></textarea>
                </div>
            </div>
            <br>
            <div class="row mt-3">
                <div class="d-flex justify-start w-100">
                    <button class="btn btn-danger mr-2" style="margin-right: 10px"
                        onclick="clearFormDetail(${formCount}, event)">Clear</button>
                    <button class="btn btn-warning mr-2"
                        onclick="removeFormDetail(${formCount}, event)">Remove</button>
                </div>
            </div>
        `;

        document.getElementById("form-container-detail").appendChild(newForm);

        // Menambahkan listener untuk select dan input baru
        newForm.querySelector('select[name="enter_type_e_detail[]"]').addEventListener('change', updateCheckboxVisibility);

        newForm.querySelector('input[name="nominal_e_detail[]"]').addEventListener('input', function() {
            formatInputENT(this);
            calculateTotalNominalEDetail();
        });

        calculateTotalNominalEDetail(); // Hitung total secara otomatis.
        updateCheckboxVisibility(); // Memperbarui visibilitas checkbox.
    }

    $('.btn-warning').click(function(event) {
        event.preventDefault();
        var index = $(this).closest('.card-body').index() + 1;
        removeFormDetail(index, event);
    });

    function removeFormDetail(index, event) {
        event.preventDefault();
        if (formCount > 0) {
            let nominalValue = cleanNumber(document.querySelector(`#nominal_e_detail_${index}`).value);
            let totalCA = cleanNumber(document.querySelector('input[name="totalca"]').value) || 0;
            totalCA -= nominalValue;
            document.querySelector('input[name="total_e_detail"]').value = formatNumber(totalCA);
            document.querySelector('input[name="totalca"]').value = formatNumber(totalCA);

            // Hide the form to be removed
            let formContainer = document.getElementById(`form-container-e-detail-${index}`);
            formContainer.remove();
            formCount--;

            updateCheckboxVisibility();
        }
    }

    function clearFormDetail(index, event) {
        event.preventDefault();
        if (formCount > 0) {
            let nominalValue = cleanNumber(document.querySelector(`#nominal_e_detail_${index}`).value);
            let totalCA = cleanNumber(document.querySelector('input[name="totalca"]').value) || 0;
            totalCA -= nominalValue;
            document.querySelector('input[name="total_e_detail"]').value = formatNumber(totalCA);
            document.querySelector('input[name="totalca"]').value = formatNumber(totalCA);

            let formContainer = document.getElementById(`form-container-e-detail-${index}`);

            formContainer.querySelectorAll('input[type="text"], input[type="date"]').forEach(input => {
                input.value = '';
            });

            formContainer.querySelectorAll('input[type="number"]').forEach(input => {
                input.value = 0;
            });

            formContainer.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });

            formContainer.querySelectorAll('textarea').forEach(textarea => {
                textarea.value = '';
            });

            document.querySelector(`#nominal_e_detail_${index}`).value = 0;

            // Call this function to update visibility of relation fields
            updateCheckboxVisibility();
        }
    }

    function calculateTotalNominalEDetail() {
        let total = 0;
        document.querySelectorAll('input[name="nominal_e_detail[]"]').forEach(input => {
            total += parseNumber(input.value);
        });
        document.querySelector('input[name="total_e_detail"]').value = formatNumber(total);
        document.getElementById('totalca').value = formatNumber(total);
    }

    function updateCheckboxVisibility() {
        const selectedOptions = Array.from(document.querySelectorAll('select[name="enter_type_e_detail[]"]'))
            .map(select => select.value)
            .filter(value => value !== "");
        for (let i = 1; i <= formCount; i++) {
            const formContainerERelation = document.getElementById(`form-container-e-relation-${i}`);
            if (!formContainerERelation) continue; // Skip if the form does not exist

            formContainerERelation.querySelectorAll('.form-check').forEach(checkDiv => {
                const checkbox = checkDiv.querySelector('input.form-check-input');
                const checkboxValue = checkbox.value.toLowerCase().replace(/\s/g, "_");
                checkDiv.style.display = selectedOptions.includes(checkboxValue) ? 'block' : 'none';
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="nominal_e_detail[]"]').forEach(input => {
            input.addEventListener('input', function() {
                formatInputENT(this);
                calculateTotalNominalEDetail(); // Ensure we calculate total here
            });
        });

        // Call the function after the existing select elements are processed
        document.querySelectorAll('select[name="enter_type_e_detail[]"]').forEach(select => {
            select.addEventListener('change', updateCheckboxVisibility);
        });

        calculateTotalNominalEDetail();
        updateCheckboxVisibility();
    });
</script>

@if (!empty($detailCA['detail_e']) && $detailCA['detail_e'][0]['type'] !== null)
    <div id="form-container-detail">
        @foreach ($detailCA['detail_e'] as $detail)
            <div id="form-container-e-detail-{{ $loop->index + 1 }}" class="card-body bg-light p-2 mb-3" style="border-radius: 1%;">
                <div class="row">
                    <div class="col-md-6 mb-2">
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
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input class="form-control" name="nominal_e_detail[]"
                                id="nominal_e_detail_{{ $loop->index + 1 }}"
                                type="text" min="0" value="{{ number_format($detail['nominal'], 0, ',', '.') }}"
                                onfocus="this.value = this.value === '0' ? '' : this.value;"
                                oninput="formatInputENT(this)">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Entertainment Fee Detail</label>
                        <textarea name="enter_fee_e_detail[]" class="form-control">{{ $detail['fee_detail'] }}</textarea>
                    </div>
                </div>
                <br>
                <div class="row mt-3">
                    <div class="d-flex justify-start w-100">
                        <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormDetail({{ $loop->index + 1 }}, event)">Clear</button>
                        <button class="btn btn-warning mr-2" onclick="removeFormDetail({{ $loop->index + 1 }}, event)">Remove</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="addMoreButtonDetail" onclick="addMoreFormDetail(event)">Add More</button>
    </div>

    <div class="mt-2">
        <label class="form-label">Total Entertain</label>
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Rp</span>
            </div>
            <input class="form-control bg-light"
                name="total_e_detail" id="total_e_detail"
                type="text" min="0" value="0"
                readonly>
        </div>
    </div>
@else
    <div id="form-container-detail">
        <div id="form-container-e-detail-1" class="card-body bg-light p-2 mb-3" style="border-radius: 1%;">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Entertainment Type</label>
                    <select name="enter_type_e_detail[]" id="enter_type_e_detail_1" class="form-select">
                        <option value="">-</option>
                        <option value="food">Food/Beverages/Souvenir</option>
                        <option value="transport">Transport</option>
                        <option value="accommodation">Accommodation</option>
                        <option value="gift">Gift</option>
                        <option value="fund">Fund</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Amount</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_e_detail[]"
                            id="nominal_e_detail_1"
                            type="text" min="0" value="0"
                            onfocus="this.value = this.value === '0' ? '' : this.value;"
                            oninput="formatInputENT(this)">
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Entertainment Fee Detail</label>
                    <textarea name="enter_fee_e_detail[]" class="form-control"></textarea>
                </div>
            </div>
            <br>
            <div class="row mt-3">
                <div class="d-flex justify-start w-100">
                    <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormDetail(1, event)">Clear</button>
                    <button class="btn btn-warning mr-2" onclick="removeFormDetail(1, event)">Remove</button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary" id="addMoreButtonDetail" onclick="addMoreFormDetail(event)">Add More</button>
    </div>

    <div class="mt-2">
        <label class="form-label">Total Entertain</label>
        <div class="input-group">
            <div class="input-group-append">
                <span class="input-group-text">Rp</span>
            </div>
            <input class="form-control bg-light"
                name="total_e_detail" id="total_e_detail"
                type="text" min="0" value="0"
                readonly>
        </div>
    </div>
@endif
