<script>
    var formCount = 1;

    function addMoreFormNBT(event) {
        event.preventDefault();
        formCount++;
        // Create a new form div
        const newForm = document.createElement('div');
        newForm.id = `form-container-nbt-${formCount}`;
        newForm.className = "card-body bg-light p-2 mb-3";
        newForm.innerHTML = `
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Date</label>
                    <input type="date" name="tanggal_nbt[]" class="form-control" placeholder="mm/dd/yyyy">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Amount</label>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input class="form-control" name="nominal_nbt[]" id="nominal_nbt_${formCount}" type="text" min="0" value="0"
                            onfocus="this.value = this.value === '0' ? '' : this.value;"
                            oninput="formatInput(this)"
                            onblur="formatOnBlur(this)">
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="mb-2">
                        <label class="form-label">Information</label>
                        <textarea name="keterangan_nbt[]" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="d-flex justify-start w-100">
                    <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormNBT(${formCount}, event)">Clear</button>
                    <button class="btn btn-warning mr-2" onclick="removeFormNBT(${formCount}, event)">Remove</button>
                </div>
            </div>
        `;
        document.getElementById('form-container-nonb').appendChild(newForm);

        // Hanya nominal field yang menggunakan event listener
        document.querySelector(`#nominal_nbt_${formCount}`).addEventListener('input', function() {
            formatInput(this);
            calculateTotalNominal();
        });

        calculateTotalNominal();
    }

    function removeFormNBT(index, event) {
        event.preventDefault();
        const formContainer = document.getElementById(`form-container-nbt-${index}`);
        if (formContainer) {
            formContainer.remove();
            formCount--;
            calculateTotalNominal(); // Recalculate total after removing form
        }
    }

    function clearFormNBT(index, event) {
        event.preventDefault();
        const formContainer = document.getElementById(`form-container-nbt-${index}`);
        if (formContainer) {
            formContainer.querySelectorAll('input[type="text"], input[type="date"]').forEach(input => {
                input.value = '';
            });

            formContainer.querySelectorAll('textarea').forEach(textarea => {
                textarea.value = '';
            });

            // Reset nominal value to 0
            formContainer.querySelector(`#nominal_nbt_${index}`).value = 0;
            calculateTotalNominal(); // Recalculate total after clearing the form
        }
    }

    function calculateTotalNominal() {
        let total = 0;
        document.querySelectorAll('input[name="nominal_nbt[]"]').forEach(input => {
            total += cleanNumber(input.value);  // Pastikan hanya menghitung angka
        });
        document.querySelector('input[name="totalca"]').value = formatNumber(total);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Attach input event to the existing nominal fields
        document.querySelectorAll('input[name="nominal_nbt[]"]').forEach(input => {
            input.addEventListener('input', function() {
                formatInput(this);  // Hanya memformat dan menghitung input nominal
                calculateTotalNominal();
            });
        });

        calculateTotalNominal();  // Kalkulasi total saat halaman pertama kali dimuat
    });

</script>

<div id="form-container-nonb">
    <div id="form-container-nbt-1" class="card-body bg-light p-2 mb-3">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">Date</label>
                <input type="date" name="tanggal_nbt[]" class="form-control" placeholder="mm/dd/yyyy">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label">Amount</label>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input class="form-control" name="nominal_nbt[]" id="nominal_nbt_1" type="text" min="0" value="0" onfocus="this.value = this.value === '0' ? '' : this.value;" oninput="formatInput(this)" onblur="formatOnBlur(this)">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <div class="mb-2">
                    <label class="form-label">Information</label>
                    <textarea name="keterangan_nbt[]" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="d-flex justify-start w-100">
                <button class="btn btn-danger mr-2" style="margin-right: 10px" onclick="clearFormNBT(1, event)">Clear</button>
                <button class="btn btn-warning mr-2" onclick="removeFormNBT(1, event)">Remove</button>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <button class="btn btn-primary" id="addMoreButtonNBT" onclick="addMoreFormNBT(event)">Add More</button>
</div>

<div class="mt-2">
    <label class="form-label">Total Perdiem</label>
    <div class="input-group">
        <div class="input-group-append">
            <span class="input-group-text">Rp</span>
        </div>
        <input class="form-control bg-light" name="total_bt_perdiem" id="total_bt_perdiem" type="text" value="0" readonly>
    </div>
</div>