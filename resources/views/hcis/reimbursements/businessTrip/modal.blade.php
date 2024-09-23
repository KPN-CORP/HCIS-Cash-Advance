@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Warning!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonColor: "#9a2a27",
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', () => {
                const transactionId = button.getAttribute('data-id');
                const form = document.getElementById(`deleteForm_${transactionId}`);

                Swal.fire({
                    title: "Do you want to delete this request?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#AB2F2B", // Primary color
                    cancelButtonColor: "#CCCCC", // Darker shade for cancel button
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.approve-button').forEach(button => {
            button.addEventListener('click', () => {
                const transactionId = button.getAttribute('data-id');
                const form = document.getElementById(`approve-form-${transactionId}`);

                Swal.fire({
                    title: "Do you want to approve this request?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#4BB543", // Primary color
                    cancelButtonColor: "#CCCCC", // Darker shade for cancel button
                    confirmButtonText: "Yes, Approve it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
