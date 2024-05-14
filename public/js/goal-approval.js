function checkEmptyFields() {
    const alertField = $(".mandatory-field");
    var requiredInputs = document.querySelectorAll(
        "input[required], select[required]"
    );
    for (var i = 0; i < requiredInputs.length; i++) {
        if (requiredInputs[i].value.trim() === "") {
            Swal.fire({
                title: "Please fill out all empty fields!",
                confirmButtonColor: "#3085d6",
                icon: "error",
                // If confirmed, proceed with form submission
            });
            alertField.removeAttr("hidden");
            return false; // Prevent form submission
        }
    }
    return true; // All required fields are filled
}

function validate() {
    var weight = document.querySelectorAll('input[name="weightage[]"]');
    var sum = 0;
    for (var i = 0; i < weight.length; i++) {
        sum += parseInt(weight[i].value) || 0; // Parse input value to integer, default to 0 if NaN
    }

    if (sum != 100) {
        Swal.fire({
            title: "Submit failed",
            html: `Your current weightage is ${sum}%, <br>Please adjust to reach the total weightage of 100%`,
            confirmButtonColor: "#3085d6",
            icon: "error",
            // If confirmed, proceed with form submission
        });
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

function validateWeightage() {
    // Get all input elements with name="weightage[]"
    var weightageInputs = document.getElementsByName("weightage[]");

    // Iterate through each input element
    for (var i = 0; i < weightageInputs.length; i++) {
        var input = weightageInputs[i];

        // Get the value of the input (convert to number)
        var value = parseFloat(input.value);

        // Check if value is below 5%
        if (value < 5) {
            // Display alert message
            Swal.fire({
                title: "The weightage cannot lower than 5%",
                confirmButtonColor: "#3085d6",
                icon: "error",
                // If confirmed, proceed with form submission
            });
            weightageInputs.focus();
            return false; // Prevent form submission
        }
    }

    return true; // All weightages are valid
}

function confirmAprroval() {
    if (!checkEmptyFields()) {
        return false; // Stop submission if required fields are empty
    }
    if (!validateWeightage()) {
        return false; // Stop submission if required fields are empty
    }
    if (!validate()) {
        return false; // Stop submission if required fields are empty
    }

    let title1;
    let title2;
    let text;
    let confirmText;

    title1 = "Do you want to submit?";
    title2 = "KPI submitted successfuly!";
    text = "You won't be able to revert this!";
    confirmText = "Submit";

    Swal.fire({
        title: title1,
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#4e73df",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("goalApprovalForm").submit();
            Swal.fire({
                title: title2,
                icon: "success",
                showConfirmButton: false,
                // If confirmed, proceed with form submission
            });
        }
    });

    return false; // Prevent default form submission
}

function confirmAprrovalAdmin() {
    let title1;
    let title2;
    let text;
    let confirmText;

    title1 = "Do you want to submit?";
    title2 = "KPI submitted successfuly!";
    text = "You won't be able to revert this!";
    confirmText = "Submit";

    Swal.fire({
        title: title1,
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#4e73df",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("goalApprovalAdminForm").submit();
            Swal.fire({
                title: title2,
                icon: "success",
                showConfirmButton: false,
                // If confirmed, proceed with form submission
            });
        }
    });

    return false; // Prevent default form submission
}

function sendBack(id, nik, name) {
    let msg = $(`#messages${id}`);

    $("#request_id").val(id);
    $("#sendto").val(nik);

    const approver = $("#approver").val();

    title1 = "Do you want to sendback?";
    title2 = "KPI sendback successfuly!";
    text = `This form will sendback to ${name}`;
    confirmText = "Submit";

    Swal.fire({
        title: title1,
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#4e73df",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmText,
        input: "textarea",
        nputLabel: "Message",
        inputPlaceholder: "Type your message here...",
        inputAttributes: {
            "aria-label": "Type your message here",
        },
        inputValidator: (value) => {
            if (!value) {
                return "Message cannot be empty"; // Display error message if input is empty
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const message = result.value; // Get the input message value
            if (message.trim() !== "") {
                document.getElementById(
                    "sendback_message"
                ).value = `${approver} : ${message}`;
                // Menggunakan Ajax untuk mengirim data ke Laravel
                document.getElementById("goalSendbackForm").submit();
                Swal.fire({
                    title: title2,
                    icon: "success",
                    showConfirmButton: false,
                    // If confirmed, proceed with form submission
                });
            }
        }
    });

    return false;
}
