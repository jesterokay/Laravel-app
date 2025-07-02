@extends('media::components.layouts.master')

@section('content')
<div class="container">
    <h1>Test Telegram Bot Configuration</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Verify Bot Token and Chat ID</h5>
            <p class="card-text">Click the button below to test if the Telegram bot token and chat ID are correctly configured.</p>
            <button id="testTelegram" class="btn btn-primary">Test Telegram Connection</button>
            <div id="testResult" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('testTelegram').addEventListener('click', async function() {
    const resultDiv = document.getElementById('testResult');
    resultDiv.innerHTML = '<div class="alert alert-info">Testing Telegram connection...</div>';

    try {
        const response = await fetch('{{ route("media.test-telegram") }}', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    ${data.message}
                    <p>Check your Telegram channel for a test message.</p>
                </div>`;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    ${data.message}<br>
                    Error: ${data.error}
                </div>`;
        }
    } catch (error) {
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                Failed to test Telegram connection: ${error.message}
            </div>`;
    }
});
</script>
@endsection