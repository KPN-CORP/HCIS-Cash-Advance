document.addEventListener("DOMContentLoaded", function () {
    setupCheckboxListeners();
    // handleTicketForms();
    // handleHotelForms();
    handleTaksiForms();
    handleCaForms();
});

document.addEventListener("DOMContentLoaded", function () {
    var jnsDinasSelect = document.getElementById("jns_dinas");
    var additionalFields = document.getElementById("additional-fields");

    function showAdditionalFields() {
        if (jnsDinasSelect.value === "luar kota") {
            additionalFields.style.display = "block";
        } else {
            additionalFields.style.display = "none";
        }
    }

    // Show additional fields on page load if 'luar kota' is selected
    showAdditionalFields();

    jnsDinasSelect.addEventListener("change", function () {
        showAdditionalFields();
        if (this.value !== "luar kota") {
            // Reset all fields to "Tidak" if not 'luar kota'
            document.getElementById("ca").value = "Tidak";
            document.getElementById("tiket").value = "Tidak";
            document.getElementById("hotel").value = "Tidak";
            document.getElementById("taksi").value = "Tidak";
        }
    });
});

function setupCheckboxListeners() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const section = this.id.replace("Checkbox", "");
            const navItem = document.getElementById(`nav-${section}`);
            const tabContent = document.getElementById(`pills-${section}`);
            const tabButton = document.getElementById(`pills-${section}-tab`);
            if (this.checked) {
                navItem.style.display = "block";
                tabButton.click(); // Activate this tab
                // Ensure the tab content shows
                tabContent.classList.add("show", "active");
            } else {
                navItem.style.display = "none";
                tabContent.classList.remove("show", "active");

                // Find the next available tab to activate
                const nextTab = findNextAvailableTab();
                if (nextTab) {
                    nextTab.click();
                }
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Check if any checkbox is already checked and activate its tab
    if (document.getElementById("cashAdvancedCheckbox").checked) {
        activateTab("pills-cashAdvanced-tab");
    }
    if (document.getElementById("ticketCheckbox").checked) {
        activateTab("pills-ticket-tab");
    }
    if (document.getElementById("hotelCheckbox").checked) {
        activateTab("pills-hotel-tab");
    }
    if (document.getElementById("taksiCheckbox").checked) {
        activateTab("pills-taksi-tab");
    }

    function activateTab(tabId) {
        // Activate the tab by clicking on the button
        var tab = document.getElementById(tabId);
        if (tab) {
            tab.click();
        }
    }
});

function findNextAvailableTab() {
    const tabs = document.querySelectorAll(".nav-link");
    for (let tab of tabs) {
        const section = tab.id.replace("pills-", "").replace("-tab", "");
        const checkbox = document.getElementById(`${section}Checkbox`);
        if (checkbox && checkbox.checked) {
            return tab;
        }
    }
    return null;
}

//Format Taxi Input
function formatCurrency(input) {
    var cursorPos = input.selectionStart;
    var value = input.value.replace(/[^\d]/g, "");

    // Format the number with thousands separators
    var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Update the input value
    input.value = formattedValue;

    // Adjust cursor position
    cursorPos += formattedValue.length - value.length;

    // Set the cursor position
    input.setSelectionRange(cursorPos, cursorPos);
}

document.addEventListener("DOMContentLoaded", function () {
    var nominalVtInput = document.getElementById("nominal_vt");
    var keeperVtInput = document.getElementById("keeper_vt");

    // If the inputs have values, format them on page load
    if (nominalVtInput.value) {
        formatCurrency(nominalVtInput);
    }
    if (keeperVtInput.value) {
        formatCurrency(keeperVtInput);
    }

    // Add event listener to the form submission
    document
        .getElementById("btFrom")
        .addEventListener("submit", function (event) {
            // Unformat the voucher fields before submission by removing dots
            nominalVtInput.value = nominalVtInput.value.replace(/\./g, "");
            keeperVtInput.value = keeperVtInput.value.replace(/\./g, "");
        });
});

// Example formatCurrency function for formatting input value
function formatCurrency(input) {
    let value = input.value.replace(/[^,\d]/g, "").toString(); // Remove anything that's not a digit
    let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Add thousands separators
    input.value = formattedValue;
}

//save to draft
// document.addEventListener("DOMContentLoaded", function () {
//     document
//         .getElementById("save-draft")
//         .addEventListener("click", function (event) {
//             event.preventDefault();

//             // Remove the existing status input
//             const existingStatus = document.getElementById("status");
//             if (existingStatus) {
//                 existingStatus.remove();
//             }

//             // Create a new hidden input for "Draft"
//             const draftInput = document.createElement("input");
//             draftInput.type = "hidden";
//             draftInput.name = "status";
//             draftInput.value = "Draft";
//             draftInput.id = "status";

//             // Append the draft input to the form
//             this.closest("form").appendChild(draftInput);

//             // Submit the form
//             this.closest("form").submit();
//         });
// });

// VALIDATION DATE JS SECTIONS
//BT Validation Date
function validateStartEndDates() {
    const startDateInput = document.getElementById("mulai");
    const endDateInput = document.getElementById("kembali");

    if (startDateInput && endDateInput) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate < startDate) {
            Swal.fire({
                title: "Warning!",
                text: "End Date cannot be earlier than Start Date",
                icon: "error",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: 'OK'
            });
            endDateInput.value = ""; // Reset the end date if it's invalid
        }
    }
}

//BT Toggle Other Locations
function BTtoggleOthers() {
    var locationFilter = document.getElementById("tujuan");
    var others_location = document.getElementById("others_location");

    if (locationFilter.value === "Others") {
        others_location.style.display = "block";
    } else {
        others_location.style.display = "none";
        others_location.value = "";
    }
}

//Ticket Validation Date
function validateDates(index) {
    const departureDateInput = document.getElementById(`tgl_brkt_tkt_${index}`);
    const returnDateInput = document.getElementById(`tgl_plg_tkt_${index}`);
    const departureTimeInput = document.getElementById(`jam_brkt_tkt_${index}`);
    const returnTimeInput = document.getElementById(`jam_plg_tkt_${index}`);

    if (
        departureDateInput &&
        returnDateInput &&
        departureTimeInput &&
        returnTimeInput
    ) {
        const departureDate = new Date(departureDateInput.value);
        const returnDate = new Date(returnDateInput.value);

        // Check if return date is earlier than departure date
        if (returnDate < departureDate) {
            Swal.fire({
                title: "Warning!",
                text: "Return date cannot be earlier than the departure date.",
                icon: "error",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: 'OK'
            });
            returnDateInput.value = ""; // Reset the return date if it's invalid
            return; // Stop further validation
        }

        // If the dates are the same, check the times
        if (returnDate.getTime() === departureDate.getTime()) {
            const departureTime = departureTimeInput.value;
            const returnTime = returnTimeInput.value;

            if (departureTime && returnTime && returnTime < departureTime) {
                Swal.fire({
                    title: "Warning!",
                    text: "Return time cannot be earlier than the departure time.",
                    icon: "error",
                    confirmButtonColor: "#9a2a27",
                    confirmButtonText: 'OK'
                });
                returnTimeInput.value = ""; // Reset the return time if it's invalid
            }
        }
    }
}

//Hotel Validation Date
function calculateTotalDays(index) {
    const checkInInput = document.getElementById(`check-in-${index}`);
    const checkOutInput = document.getElementById(`check-out-${index}`);
    const totalDaysInput = document.getElementById(`total-days-${index}`);

    // Get Start Date and End Date from the main form
    const mulaiInput = document.getElementById("mulai");
    const kembaliInput = document.getElementById("kembali");

    if (!checkInInput || !checkOutInput || !mulaiInput || !kembaliInput) {
        return; // Ensure elements are present before proceeding
    }

    // Parse the dates
    const checkInDate = new Date(checkInInput.value);
    const checkOutDate = new Date(checkOutInput.value);
    const mulaiDate = new Date(mulaiInput.value);
    const kembaliDate = new Date(kembaliInput.value);

    // Validate Check In Date
    if (checkInDate < mulaiDate) {
        Swal.fire({
            title: "Warning!",
            text: "Check In date cannot be earlier than Start date.",
            icon: "error",
            confirmButtonColor: "#9a2a27",
            confirmButtonText: 'OK'
        });
        checkInInput.value = ""; // Reset the Check In field
        totalDaysInput.value = ""; // Clear total days
        return;
    }
    if (checkInDate > kembaliDate) {
        Swal.fire({
            title: "Warning!",
            text: "Check In date cannot be more than End date.",
            icon: "error",
            confirmButtonColor: "#9a2a27",
            confirmButtonText: 'OK'
        });
        checkInInput.value = ""; // Reset the Check In field
        totalDaysInput.value = ""; // Clear total days
        return;
    }

    // Ensure Check Out Date is not earlier than Check In Date
    if (checkOutDate < checkInDate) {
        Swal.fire({
            title: "Warning!",
            text: "Check Out date cannot be earlier than Check In date.",
            icon: "error",
            confirmButtonColor: "#9a2a27",
            confirmButtonText: 'OK'
        });
        checkOutInput.value = ""; // Reset the Check Out field
        totalDaysInput.value = ""; // Clear total days
        return;
    }

    // Calculate the total days if all validations pass
    if (checkInDate && checkOutDate) {
        const diffTime = Math.abs(checkOutDate - checkInDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        totalDaysInput.value = diffDays;
    } else {
        totalDaysInput.value = "";
    }
}

// Attach event listeners to the hotel forms
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".hotel-form").forEach((form, index) => {
        const i = index + 1; // Adjust for 1-based index

        form.querySelector('input[name="tgl_masuk_htl[]"]').addEventListener(
            "change",
            () => calculateTotalDays(i)
        );
        form.querySelector('input[name="tgl_keluar_htl[]"]').addEventListener(
            "change",
            () => calculateTotalDays(i)
        );
    });
});

//RESET CHECKBOX FIELDS
document.addEventListener("DOMContentLoaded", function () {
    var jnsDinasSelect = document.getElementById("jns_dinas");
    var additionalFields = document.getElementById("additional-fields");

    var checkboxes = [
        "cashAdvancedCheckbox",
        "ticketCheckbox",
        "hotelCheckbox",
        "taksiCheckbox",
    ];

    // Corresponding section divs to hide/reset
    var sections = [
        "nav-cash-advanced",
        "nav-ticket",
        "nav-hotel",
        "nav-taksi",
    ];

    jnsDinasSelect.addEventListener("change", function () {
        if (this.value === "luar kota") {
            additionalFields.style.display = "block";
        } else {
            additionalFields.style.display = "none";

            // Uncheck all the checkboxes and hide/reset related fields
            checkboxes.forEach(function (checkboxId) {
                var checkbox = document.getElementById(checkboxId);
                if (checkbox.checked) {
                    checkbox.checked = false; // Uncheck the checkbox
                    // Trigger the change event to ensure corresponding sections are hidden
                    checkbox.dispatchEvent(new Event("change"));
                }
            });
        }
    });
});

// Function to toggle the visibility of sections based on checkboxes
function toggleSection(checkboxId, navId, tabId) {
    const checkbox = document.getElementById(checkboxId);
    const nav = document.getElementById(navId);
    const tab = document.getElementById(tabId); // The tab button (anchor) for navigation

    // console.log(checkbox);

    checkbox.addEventListener("change", function () {
        if (this.checked) {
            nav.style.display = "block";
            tab.click(); // Programmatically activate the tab
        } else {
            nav.style.display = "none";
        }
    });
}

// Initialize toggling for each checkbox and tab
toggleSection(
    "cashAdvancedCheckbox",
    "nav-cash-advanced",
    "pills-cash-advanced-tab"
);
toggleSection("ticketCheckbox", "nav-ticket", "pills-ticket-tab");
toggleSection("hotelCheckbox", "nav-hotel", "pills-hotel-tab");
toggleSection("taksiCheckbox", "nav-taksi", "pills-taksi-tab");

document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const caSelect = document.getElementById("ca");
    const caNbtDiv = document.getElementById("ca_div");

    const hotelSelect = document.getElementById("hotel");
    const hotelDiv = document.getElementById("hotel_div");

    const taksiSelect = document.getElementById("taksi");
    const taksiDiv = document.getElementById("taksi_div");

    const tiketSelect = document.getElementById("tiket");
    const tiketDiv = document.getElementById("tiket_div");

    // Function to reset fields in the target div
    function resetFields(container) {
        const inputs = container.querySelectorAll(
            'input[type="text"], input[type="number"], textarea'
        );
        inputs.forEach((input) => {
            input.value = "";
        });
        const selects = container.querySelectorAll("select");
        selects.forEach((select) => {
            select.selectedIndex = 0;
        });
    }

    // Function to toggle display and reset fields
    function toggleDisplay(selectElement, targetDiv) {
        if (selectElement.value === "Ya") {
            targetDiv.style.display = "block";
        } else {
            targetDiv.style.display = "none";
            resetFields(targetDiv); // Reset fields when hiding the target div
        }
    }

    // Event listeners for select elements
    caSelect.addEventListener("change", function () {
        toggleDisplay(caSelect, caNbtDiv);
    });

    hotelSelect.addEventListener("change", function () {
        toggleDisplay(hotelSelect, hotelDiv);
    });

    taksiSelect.addEventListener("change", function () {
        toggleDisplay(taksiSelect, taksiDiv);
    });

    tiketSelect.addEventListener("change", function () {
        toggleDisplay(tiketSelect, tiketDiv);
    });
});

//Ticket JS
document.addEventListener("DOMContentLoaded", function () {
    let formTicketCount =
        document.querySelectorAll('[id^="ticket-form-"]').length || 1; // Count existing forms on page load
    const maxTicketForms = 5;
    const ticketFormsContainer = document.getElementById(
        "ticket_forms_container"
    );
    const ticketCheckbox = document.getElementById("ticketCheckbox");
    const addTicketButton = document.getElementById("add-ticket-btn");

    function toggleRequiredAttributes(form, isRequired) {
        const fields = [
            'select[name="noktp_tkt[]"]',
            'input[name="dari_tkt[]"]',
            'input[name="ke_tkt[]"]',
            'input[name="tgl_brkt_tkt[]"]',
            'input[name="jam_brkt_tkt[]"]',
            'select[name="jenis_tkt[]"]',
            'select[name="type_tkt[]"]',
        ];

        fields.forEach((selector) => {
            const field = form.querySelector(selector);
            if (field) {
                field.required = isRequired;
            }
        });
        const typeSelect = form.querySelector('select[name="type_tkt[]"]');
        const returnDateField = form.querySelector(
            'input[name="tgl_plg_tkt[]"]'
        );
        const returnTimeField = form.querySelector(
            'input[name="jam_plg_tkt[]"]'
        );

        function updateReturnFields() {
            if (isRequired && typeSelect.value === "Round Trip") {
                returnDateField.required = true;
                returnTimeField.required = true;
            } else {
                returnDateField.required = false;
                returnTimeField.required = false;
            }
        }

        typeSelect.addEventListener("change", updateReturnFields);
        updateReturnFields(); // Initial call to set the correct state
    }

    function updateAllFormsRequiredState(isRequired) {
        document.querySelectorAll('[id^="ticket-form-"]').forEach((form) => {
            toggleRequiredAttributes(form, isRequired);
        });
    }

    function updateFormNumbers() {
        const forms = ticketFormsContainer.querySelectorAll(
            '[id^="ticket-form-"]'
        );
        forms.forEach((form, index) => {
            const formNumber = index + 1;
            form.querySelector(
                ".h5.text-uppercase b"
            ).textContent = `TICKET ${formNumber}`;
            form.id = `ticket-form-${formNumber}`;
            form.querySelector(".remove-ticket-btn").dataset.formId =
                formNumber;

            updateFormElementIds(form, formNumber);
        });
        formTicketCount = forms.length;
        updateRemoveButtons();
        updateAddButtonVisibility();
        // initializeRoundTripOptions();
    }

    function updateAddButtonVisibility() {
        addTicketButton.style.display =
            formTicketCount < maxTicketForms ? "inline-block" : "none";
    }

    function updateFormElementIds(form, formNumber) {
        const elements = form.querySelectorAll("[id],[name],[onchange]");
        elements.forEach((element) => {
            // Update IDs
            if (element.id) {
                element.id = element.id.replace(/\d+$/, formNumber);
            }
            // Update names
            if (element.name) {
                element.name = element.name.replace(
                    /\[\d*\]/,
                    `[${formNumber}]`
                );
            }
            // Update onchange attributes
            if (element.hasAttribute("onchange")) {
                const onchangeValue = element.getAttribute("onchange");
                const updatedOnchangeValue = onchangeValue.replace(
                    /\d+/,
                    formNumber
                );
                element.setAttribute("onchange", updatedOnchangeValue);
            }
        });
    }

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll(".remove-ticket-btn");
        removeButtons.forEach((button) => {
            button.style.display =
                formTicketCount > 1 ? "inline-block" : "none";
        });
    }

    function resetTicketFields(container) {
        const inputs = container.querySelectorAll(
            'input[type="text"], input[type="number"], input[type="date"], input[type="time"], textarea'
        );
        inputs.forEach((input) => (input.value = ""));

        const selects = container.querySelectorAll("select");
        selects.forEach((select) => {
            if ($(select).data("select2")) {
                $(select).val(null).trigger("change");
            } else {
                select.value = select.querySelector("option[selected]")
                    ? select.querySelector("option[selected]").value
                    : select.querySelector("option").value;
            }
        });

        const roundTripOptions = container.querySelector(".round-trip-options");
        if (roundTripOptions) {
            roundTripOptions.style.display = "none";
        }
    }

    function resetAllTicketForms() {
        const forms = ticketFormsContainer.querySelectorAll(
            '[id^="ticket-form-"]'
        );
        forms.forEach((form, index) => {
            resetTicketFields(form);
            if (index === 0) {
                form.style.display = "block";
            } else {
                form.remove();
            }
        });
        formTicketCount = 1;
        updateFormNumbers();
    }

    function addNewTicketForm() {
        if (formTicketCount < maxTicketForms) {
            formTicketCount++;
            const newTicketForm = createNewTicketForm(formTicketCount);
            ticketFormsContainer.insertAdjacentHTML("beforeend", newTicketForm);
            const addedForm = ticketFormsContainer.lastElementChild;
            toggleRequiredAttributes(addedForm, ticketCheckbox.checked);
            updateFormNumbers();
            initializeAllSelect2(); // Initialize Select2 for new dropdowns
        } else {
            Swal.fire({
                title: "Warning!",
                text: "You have reached the maximum number of tickets (5).",
                icon: "error",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: "OK",
            });
        }
    }

    document
        .getElementById("add-ticket-btn")
        .addEventListener("click", addNewTicketForm);

    ticketFormsContainer.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-ticket-btn")) {
            const formId = e.target.dataset.formId;
            document.getElementById(`ticket-form-${formId}`).remove();
            updateFormNumbers();
        }
    });

    ticketFormsContainer.addEventListener("change", function (e) {
        if (e.target.name && e.target.name.startsWith("type_tkt")) {
            const roundTripOptions = e.target
                .closest(".card-body")
                .querySelector(".round-trip-options");
            if (roundTripOptions) {
                roundTripOptions.style.display =
                    e.target.value === "Round Trip" ? "block" : "none";
            }
        }
    });

    if (ticketCheckbox) {
        ticketCheckbox.addEventListener("change", function () {
            ticketFormsContainer.style.display = this.checked
                ? "block"
                : "none";
            updateAllFormsRequiredState(this.checked);
            if (!this.checked) {
                resetAllTicketForms();
            }
        });
    }

    function createNewTicketForm(formNumber) {
        return `
            <div class="card bg-light shadow-none" id="ticket-form-${formNumber}" style="display: block;">
                <div class="card-body">
                    <div class="h5 text-uppercase">
                        <b>TICKET ${formNumber}</b>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Employee Name</label>
                            <select class="form-select form-select-sm selection2" id="noktp_tkt_${formNumber}" name="noktp_tkt[]">
                               <option value="">Please Select</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">From</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" name="dari_tkt[]" type="text" placeholder="ex. Yogyakarta (YIA)">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">To</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" name="ke_tkt[]" type="text" placeholder="ex. Jakarta (CGK)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Date</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" id="tgl_brkt_tkt_${formNumber}" name="tgl_brkt_tkt[]" type="date" onchange="validateDates(${formNumber})">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Time</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" id="jam_brkt_tkt_${formNumber}" name="jam_brkt_tkt[]" type="time" onchange="validateDates(${formNumber})">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Transportation Type</label>
                            <div class="input-group">
                                <select class="form-select form-select-sm" name="jenis_tkt[]" id="jenis_tkt_${formNumber}">
                                    <option value="">Select Transportation Type</option>
                                    <option value="Train">Train</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Airplane">Airplane</option>
                                    <option value="Car">Car</option>
                                    <option value="Ferry">Ferry</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Ticket Type</label>
                            <select class="form-select form-select-sm" name="type_tkt[]">
                                <option value="One Way" selected>One Way</option>
                                <option value="Round Trip">Round Trip</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Information</label>
                            <textarea class="form-control" id="ket_tkt_${formNumber}" name="ket_tkt[]" rows="3" placeholder="This field is for adding ticket details, e.g., Citilink, Garuda Indonesia, etc."></textarea>
                        </div>
                    </div>
                    <div class="round-trip-options" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Return Date</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm" name="tgl_plg_tkt[]" type="date" id="tgl_plg_tkt_${formNumber}" onchange="validateDates(${formNumber})">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Return Time</label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm" id="jam_plg_tkt_${formNumber}" name="jam_plg_tkt[]" type="time" onchange="validateDates(${formNumber})">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-ticket-btn" data-form-id="${formNumber}">Remove Ticket</button>
                    </div>
                </div>
            </div>`;
    }

    function initializeAllSelect2() {
        $(".selection2").each(function () {
            const $select = $(this);
            if (!$select.data("select2")) {
                const config = {
                    theme: "bootstrap-5",
                    width: "100%",
                };

                if ($select.find("option").length > 1) {
                    config.minimumResultsForSearch = 10;
                } else {
                    config.minimumInputLength = 1;
                    config.ajax = {
                        url: "/search/name",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term,
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        id: item.ktp,
                                        text: item.fullname + " - " + item.ktp,
                                    };
                                }),
                            };
                        },
                        cache: true,
                    };
                }

                $select.select2(config);
            }
        });
    }
    // Initial setup
    updateRemoveButtons();
    initializeAllSelect2();
    updateAllFormsRequiredState(ticketCheckbox.checked);
});

//Hotel JS
//Hotel JS
document.addEventListener("DOMContentLoaded", function () {
    let formHotelCount = 1;
    const maxHotelForms = 5;
    const hotelFormsContainer = document.getElementById(
        "hotel_forms_container"
    );
    const hotelCheckbox = document.getElementById("hotelCheckbox");
    const addHotelButton = document.querySelector(".add-hotel-btn");

    function updateFormNumbers() {
        const forms = hotelFormsContainer.querySelectorAll(
            '[id^="hotel-form-"]'
        );
        forms.forEach((form, index) => {
            const formNumber = index + 1;
            form.querySelector(
                ".h5.text-uppercase b"
            ).textContent = `Hotel ${formNumber}`;
            form.id = `hotel-form-${formNumber}`;
            form.querySelector(".remove-hotel-btn").dataset.formId = formNumber;

            updateFormElementIds(form, formNumber);
        });
        formHotelCount = forms.length;
        updateButtonVisibility();
    }

    function updateFormElementIds(form, formNumber) {
        const elements = form.querySelectorAll("[id],[name],[onchange]");
        elements.forEach((element) => {
            // Update IDs
            if (element.id) {
                element.id = element.id.replace(/\d+$/, formNumber);
            }
            // Update names
            if (element.name) {
                element.name = element.name.replace(
                    /\[\d*\]/,
                    `[${formNumber}]`
                );
            }
            // Update onchange attributes
            if (element.hasAttribute("onchange")) {
                const onchangeValue = element.getAttribute("onchange");
                const updatedOnchangeValue = onchangeValue.replace(
                    /\d+/,
                    formNumber
                );
                element.setAttribute("onchange", updatedOnchangeValue);
            }
        });
    }

    function updateButtonVisibility() {
        addHotelButton.style.display =
            formHotelCount < maxHotelForms ? "inline-block" : "none";
        const removeButtons =
            hotelFormsContainer.querySelectorAll(".remove-hotel-btn");
        removeButtons.forEach((button) => {
            button.style.display = formHotelCount > 1 ? "inline-block" : "none";
        });
    }

    function resetHotelFields(container) {
        const inputs = container.querySelectorAll(
            'input[type="text"], input[type="number"], input[type="date"], input[type="time"], textarea'
        );
        inputs.forEach((input) => (input.value = ""));

        const selects = container.querySelectorAll("select");
        selects.forEach((select) => {
            select.value = select.querySelector("option[selected]")
                ? select.querySelector("option[selected]").value
                : select.querySelector("option").value;
        });
    }

    function resetAllHotelForms() {
        const forms = hotelFormsContainer.querySelectorAll(
            '[id^="hotel-form-"]'
        );
        forms.forEach((form, index) => {
            resetHotelFields(form);
            if (index === 0) {
                form.style.display = "block";
            } else {
                form.remove();
            }
        });
        formHotelCount = 1;
        updateButtonVisibility();
    }

    function toggleRequiredAttributes(form, isRequired) {
        const fields = [
            'input[name="nama_htl[]"]',
            'input[name="lokasi_htl[]"]',
            'select[name="bed_htl[]"]',
            'input[name="jmlkmr_htl[]"]',
            'input[name="tgl_masuk_htl[]"]',
            'input[name="tgl_keluar_htl[]"]',
        ];

        fields.forEach((selector) => {
            const field = form.querySelector(selector);
            if (field) {
                field.required = isRequired;
            }
        });
    }

    function updateAllFormsRequiredState(isRequired) {
        document.querySelectorAll('[id^="hotel-form-"]').forEach((form) => {
            toggleRequiredAttributes(form, isRequired);
        });
    }

    function addNewHotelForm() {
        if (formHotelCount < maxHotelForms) {
            formHotelCount++;
            const newHotelForm = createNewHotelForm(formHotelCount);
            hotelFormsContainer.insertAdjacentHTML("beforeend", newHotelForm);
            const addedForm = hotelFormsContainer.lastElementChild;
            toggleRequiredAttributes(addedForm, hotelCheckbox.checked);
            updateFormNumbers();
        } else {
            Swal.fire({
                title: "Warning!",
                text: "You have reached the maximum number of hotels (5).",
                icon: "error",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: 'OK'
            });
        }
    }

    addHotelButton.addEventListener("click", addNewHotelForm);

    hotelFormsContainer.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-hotel-btn")) {
            const formId = e.target.dataset.formId;
            document.getElementById(`hotel-form-${formId}`).remove();
            updateFormNumbers();
        }
    });

    if (hotelCheckbox) {
        hotelCheckbox.addEventListener("change", function () {
            hotelFormsContainer.style.display = this.checked ? "block" : "none";
            updateAllFormsRequiredState(this.checked);
            if (!this.checked) {
                resetAllHotelForms();
            }
        });
    }

    function createNewHotelForm(formNumber) {
        return `
            <div class="card bg-light shadow-none" id="hotel-form-${formNumber}" style="display: block;">
                <div class="card-body">
                    <div class="h5 text-uppercase">
                        <b>Hotel ${formNumber}</b>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Hotel Name</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" name="nama_htl[]" type="text" placeholder="ex: Hyatt">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Hotel Location</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" name="lokasi_htl[]" type="text" placeholder="ex: Jakarta">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Bed Size</label>
                            <select class="form-select form-select-sm" name="bed_htl[]">
                                <option value="Single Bed">Single Bed</option>
                                <option value="Twin Bed">Twin Bed</option>
                                <option value="King Bed">King Bed</option>
                                <option value="Super King Bed">Super King Bed</option>
                                <option value="Extra Bed">Extra Bed</option>
                                <option value="Baby Cot">Baby Cot</option>
                                <option value="Sofa Bed">Sofa Bed</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Total Room</label>
                            <div class="input-group">
                                <input class="form-control form-control-sm" name="jmlkmr_htl[]" type="number" min="1" placeholder="ex: 1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                        <label class="form-label">Check In Date</label>
                        <input type="date" class="form-control form-control-sm" id="check-in-${formNumber}" name="tgl_masuk_htl[]"
                            onchange="calculateTotalDays(${formNumber})">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Check Out Date</label>
                        <input type="date" class="form-control form-control-sm" id="check-out-${formNumber}" name="tgl_keluar_htl[]"
                            onchange="calculateTotalDays(${formNumber})">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Total Days</label>
                        <input type="number" class="form-control form-control-sm bg-light" id="total-days-${formNumber}" name="total_hari[]"
                            readonly>
                    </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-hotel-btn" data-form-id="${formNumber}">Remove Data</button>
                    </div>
                </div>
            </div>`;
    }

    // Initial setup
    updateButtonVisibility();
    updateAllFormsRequiredState(hotelCheckbox.checked);
});

//Taksi JS
function handleTaksiForms() {
    const taksiCheckbox = document.getElementById("taksiCheckbox");
    const taksiDiv = document.getElementById("taksi_div");
    const formFields = taksiDiv.querySelectorAll("input");

    // Function to toggle 'required' attribute
    function toggleRequired() {
        if (taksiDiv.style.display === "block") {
            // If form is visible
            formFields.forEach(function (field) {
                field.setAttribute("required", "required"); // Add 'required' attribute
            });
        } else {
            formFields.forEach(function (field) {
                field.removeAttribute("required"); // Remove 'required' attribute
            });
        }
    }

    // Handle checkbox change event
    taksiCheckbox.addEventListener("change", function () {
        if (this.checked) {
            taksiDiv.style.display = "block";
        } else {
            taksiDiv.style.display = "none";
        }
        toggleRequired(); // Toggle required based on visibility
    });
    toggleRequired();
}

//CA JS
function handleCaForms() {
    const caCheckbox = document.getElementById("cashAdvancedCheckbox");
    const caDiv = document.getElementById("ca_div");

    caCheckbox.addEventListener("change", function () {
        if (this.checked) {
            caDiv.style.display = "block";
        } else {
            caDiv.style.display = "none";
        }
    });
}

function handleCaForms() {
    const caCheckbox = document.getElementById("caCheckbox");
    const caDiv = document.getElementById("ca_div");

    caCheckbox.addEventListener("change", function () {
        if (this.checked) {
            caDiv.style.display = "block";
        } else {
            caDiv.style.display = "none";
        }
    });
}

function cleanNumber(value) {
    return parseFloat(value.replace(/\./g, "").replace(/,/g, "")) || 0;
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function formatNumberPerdiem(num) {
    return num.toLocaleString("id-ID");
}

function parseNumberPerdiem(value) {
    return parseFloat(value.replace(/\./g, "").replace(/,/g, "")) || 0;
}

function parseNumber(value) {
    return parseFloat(value.replace(/\./g, "")) || 0;
}

function formatInput(input) {
    let value = input.value.replace(/\./g, "");
    value = parseFloat(value);
    if (!isNaN(value)) {
        input.value = formatNumber(Math.floor(value));
    } else {
        input.value = formatNumber(0);
    }
    calculateTotalNominalBTPerdiem();
    calculateTotalNominalBTTransport();
    calculateTotalNominalBTPenginapan();
    calculateTotalNominalBTLainnya();
    calculateTotalNominalBTTotal();
}

function calculateTotalNominalBTTotal() {
    let total = 0;
    document
        .querySelectorAll('input[name="total_bt_perdiem"]')
        .forEach((input) => {
            total += parseNumber(input.value);
        });
    document
        .querySelectorAll('input[name="total_bt_transport"]')
        .forEach((input) => {
            total += parseNumber(input.value);
        });
    document
        .querySelectorAll('input[name="total_bt_penginapan"]')
        .forEach((input) => {
            total += parseNumber(input.value);
        });
    document
        .querySelectorAll('input[name="total_bt_lainnya"]')
        .forEach((input) => {
            total += parseNumber(input.value);
        });
    document.querySelector('input[name="totalca"]').value = formatNumber(total);
}

function toggleDivs() {
    // ca_type ca_nbt ca_e
    var ca_type = document.getElementById("ca_type");
    var ca_nbt = document.getElementById("ca_nbt");
    var ca_e = document.getElementById("ca_e");
    var div_bisnis_numb_dns = document.getElementById("div_bisnis_numb_dns");
    var div_bisnis_numb_ent = document.getElementById("div_bisnis_numb_ent");
    var bisnis_numb = document.getElementById("bisnis_numb");
    var div_allowance = document.getElementById("div_allowance");

    if (ca_type.value === "dns") {
        ca_bt.style.display = "block";
        ca_nbt.style.display = "none";
        ca_e.style.display = "none";
        div_bisnis_numb_dns.style.display = "block";
        div_bisnis_numb_ent.style.display = "none";
        div_allowance.style.display = "block";
    } else if (ca_type.value === "ndns") {
        ca_bt.style.display = "none";
        ca_nbt.style.display = "block";
        ca_e.style.display = "none";
        div_bisnis_numb_dns.style.display = "none";
        div_bisnis_numb_ent.style.display = "none";
        bisnis_numb.style.value = "";
        div_allowance.style.display = "none";
    } else if (ca_type.value === "entr") {
        ca_bt.style.display = "none";
        ca_nbt.style.display = "none";
        ca_e.style.display = "block";
        div_bisnis_numb_dns.style.display = "none";
        div_bisnis_numb_ent.style.display = "block";
    } else {
        ca_bt.style.display = "none";
        ca_nbt.style.display = "none";
        ca_e.style.display = "none";
        div_bisnis_numb_dns.style.display = "none";
        div_bisnis_numb_ent.style.display = "none";
        bisnis_numb.style.value = "";
    }
}

function toggleOthers() {
    // ca_type ca_nbt ca_e
    var locationFilter = document.getElementById("locationFilter");
    var others_location = document.getElementById("others_location");

    if (locationFilter.value === "Others") {
        others_location.style.display = "block";
    } else {
        others_location.style.display = "none";
        others_location.value = "";
    }
}

function validateInput(input) {
    //input.value = input.value.replace(/[^0-9,]/g, '');
    input.value = input.value.replace(/[^0-9]/g, "");
}

document.addEventListener("DOMContentLoaded", function () {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const totalDaysInput = document.getElementById("totaldays");
    const perdiemInput = document.getElementById("perdiem");
    const allowanceInput = document.getElementById("allowance");
    const othersLocationInput = document.getElementById("others_location");
    const transportInput = document.getElementById("transport");
    const accommodationInput = document.getElementById("accommodation");
    const otherInput = document.getElementById("other");
    const totalcaInput = document.getElementById("totalca");
    const nominal_1Input = document.getElementById("nominal_1");
    const nominal_2Input = document.getElementById("nominal_2");
    const nominal_3Input = document.getElementById("nominal_3");
    const nominal_4Input = document.getElementById("nominal_4");
    const nominal_5Input = document.getElementById("nominal_5");
    const caTypeInput = document.getElementById("ca_type");

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseNumber(value) {
        return parseFloat(value.replace(/\./g, "")) || 0;
    }

    function formatInput(input) {
        let value = input.value.replace(/\./g, "");
        value = parseFloat(value);
        if (!isNaN(value)) {
            // input.value = formatNumber(value);
            input.value = formatNumber(Math.floor(value));
        } else {
            input.value = formatNumber(0);
        }

        calculateTotalCA();
    }

    function calculateTotalDays() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        if (startDate && endDate && !isNaN(startDate) && !isNaN(endDate)) {
            const timeDiff = endDate - startDate;
            const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            const totalDays = daysDiff > 0 ? daysDiff + 1 : 0 + 1;
            totalDaysInput.value = totalDays;

            const perdiem = parseFloat(perdiemInput.value) || 0;
            let allowance = totalDays * perdiem;

            if (othersLocationInput.value.trim() !== "") {
                allowance *= 1; // allowance * 50%
            } else {
                allowance *= 0.5;
            }

            allowanceInput.value = formatNumber(Math.floor(allowance));
        } else {
            totalDaysInput.value = 0;
            allowanceInput.value = 0;
        }
        calculateTotalCA();
    }

    function calculateTotalCA() {
        const allowance = parseNumber(allowanceInput.value);
        const transport = parseNumber(transportInput.value);
        const accommodation = parseNumber(accommodationInput.value);
        const other = parseNumber(otherInput.value);
        const nominal_1 = parseNumber(nominal_1Input.value);
        const nominal_2 = parseNumber(nominal_2Input.value);
        const nominal_3 = parseNumber(nominal_3Input.value);
        const nominal_4 = parseNumber(nominal_4Input.value);
        const nominal_5 = parseNumber(nominal_5Input.value);

        // Perbaiki penulisan caTypeInput.value
        const ca_type = caTypeInput.value;

        let totalca = 0;
        if (ca_type === "dns") {
            totalca = allowance + transport + accommodation + other;
        } else if (ca_type === "ndns") {
            totalca = transport + accommodation + other;
            allowanceInput.value = 0;
        } else if (ca_type === "entr") {
            totalca = nominal_1 + nominal_2 + nominal_3 + nominal_4 + nominal_5;
            allowanceInput.value = 0;
        }

        // totalcaInput.value = formatNumber(totalca.toFixed(2));
        totalcaInput.value = formatNumber(Math.floor(totalca));
    }

    startDateInput.addEventListener("change", calculateTotalDays);
    endDateInput.addEventListener("change", calculateTotalDays);
    othersLocationInput.addEventListener("input", calculateTotalDays);
    caTypeInput.addEventListener("change", calculateTotalDays);
    [
        transportInput,
        accommodationInput,
        otherInput,
        allowanceInput,
        nominal_1,
        nominal_2,
        nominal_3,
        nominal_4,
        nominal_5,
    ].forEach((input) => {
        input.addEventListener("input", () => formatInput(input));
    });
});

document.getElementById("end_date").addEventListener("change", function () {
    const endDate = new Date(this.value);
    const declarationEstimateDate = new Date(endDate);
    declarationEstimateDate.setDate(declarationEstimateDate.getDate() + 3);

    const year = declarationEstimateDate.getFullYear();
    const month = String(declarationEstimateDate.getMonth() + 1).padStart(
        2,
        "0"
    );
    const day = String(declarationEstimateDate.getDate()).padStart(2, "0");

    document.getElementById("ca_decla").value = `${year}-${month}-${day}`;
});

document
    .getElementById("start_date")
    .addEventListener("change", handleDateChange);
document
    .getElementById("end_date")
    .addEventListener("change", handleDateChange);

function handleDateChange() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    // Set the min attribute of the end_date input to the selected start_date
    endDateInput.min = startDateInput.value;

    // Validate dates
    if (endDate < startDate) {
        Swal.fire({
            title: "Warning!",
            text: "End Date cannot be earlier than Start Date",
            icon: "error",
            confirmButtonColor: "#9a2a27",
            confirmButtonText: 'OK'
        });
        endDateInput.value = "";
    }

    // Update min and max values for all dynamic perdiem date fields
    document
        .querySelectorAll('input[name="start_bt_perdiem[]"]')
        .forEach(function (input) {
            input.min = startDateInput.value;
            input.max = endDateInput.value;
        });

    document
        .querySelectorAll('input[name="end_bt_perdiem[]"]')
        .forEach(function (input) {
            input.min = startDateInput.value;
            input.max = endDateInput.value;
        });

    document
        .querySelectorAll('input[name="total_days_bt_perdiem[]"]')
        .forEach(function (input) {
            calculateTotalDaysPerdiem(input);
        });
}
