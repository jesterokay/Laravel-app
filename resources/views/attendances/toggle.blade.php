<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for the fingerprint icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            color: white;
            margin: 5px;
            width: 50px;
            height: 50px;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-primary {
            background-color: #007bff;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<h2>Attendance</h2>

<input type="hidden" id="employee_id" value="{{ $employee_id }}">
<button id="attendance-button" class="btn" onclick="toggleAttendance()">
    <i class="fas fa-fingerprint"></i>
</button>

<script>
    let isCheckedIn = {{ $isChecked ? 'true' : 'false' }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateButton() {
        const button = document.getElementById('attendance-button');
        
        if (isCheckedIn) {
            button.className = 'btn btn-danger';
        } else {
            button.className = 'btn btn-success';
        }
    }

    function toggleAttendance() {
        const employeeId = document.getElementById('employee_id').value;

        fetch("{{ route('attendances.toggle.submit') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                employee_id: employeeId,
                is_checked: !isCheckedIn
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                isCheckedIn = !isCheckedIn;
                updateButton();
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // Set initial button state
    updateButton();
</script>

</body>
</html>
