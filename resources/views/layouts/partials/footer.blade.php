<footer class="footer">
    <p>© {{ date('Y') }} {{ auth()->user()->username }}. All rights reserved.</p>
</footer>

<style>
    /* Footer Styles */
    .footer {
        background: #333;
        color: white;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
