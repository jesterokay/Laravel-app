<header class="bg-dark text-white sticky-top d-flex align-items-center justify-content-between px-3 shadow"
    style="height: 50px; z-index: 2001;">
    <div class="d-flex align-items-center">
        <button class="bg-transparent border-0 text-white me-2" id="toggleSidebar" aria-expanded="false"
            aria-controls="sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="text-white text-decoration-none" title="home" href="{{ url('/') }}">
            {{ auth()->user()->username }} Syst</a>
    </div>
    <div class="d-flex align-items-center gap-3 position-relative">
        <a class="text-white" title="home" href="{{ url('/') }}"><i class="fas fa-home d-block"></i></a>
        <a href="{{ route('customers.index') }}" class="text-white" title="customers"><i class="fas fa-user-friends d-block"></i></a>
        <a class="text-white" title="employees" href="{{ route('employees.index') }}"><i class="fas fa-users d-block"></i></a>
        <a class="text-white" title="roles" href="{{ route('roles.index') }}"><i class="fas fa-user-shield"></i></a>
        <a class="text-white" title="attendances" href="{{ route('attendances.index') }}"><i class="fas fa-calendar-alt"></i></a>
        @if (session('impersonating') && session('original_user_id'))
            <a class="text-white" title="Back to Superadmin" href="{{ route('stop-impersonating') }}">
                <i class="fa-solid fa-door-open"></i>
            </a>
        @endif    
        <i class="fas fa-user text-white cursor-pointer" id="profilePopup" title="profile"></i>

        <!-- Modal Profile -->
        <div class="modalProfile position-absolute bg-white rounded shadow-lg"
            style="width: 240px; top: 40px; right: 0; display: none; z-index: 2010;">
            <!-- Profile Header -->
            <div class="bg-primary bg-gradient d-flex justify-content-center py-2">
                @php
                    $user = Auth::user();
                    $imageUrl = null;
                    if ($user->image) {
                        try {
                            $client = new \GuzzleHttp\Client();
                            $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
                            $response = $client->get("https://api.telegram.org/bot{$botToken}/getFile", [
                                'query' => ['file_id' => $user->image],
                                'timeout' => 10,
                            ]);
                            $data = json_decode($response->getBody(), true);
                            if ($data['ok']) {
                                $filePath = $data['result']['file_path'];
                                $imageUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to fetch Telegram image for user', [
                                'file_id' => $user->image,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                @endphp
                @if ($imageUrl)
                    <a href="{{ route('employees.show', $user) }}" target="_blank">
                        <img src="{{ $imageUrl }}" alt="Profile Image"
                            class="w-16 h-16 rounded-full object-cover border border-white">
                    </a>
                @else
                    <i class="fas fa-user-circle text-black text-6xl"></i>
                @endif
            </div>

            <!-- Profile Content -->
            <div class="p-1">
                <h2 class="d-block text-center mb-1 text-success font-extrabold text-2xl">{{ auth()->user()->username }} <br />
                    <small class="text-[14px]">{{ auth()->user()->email }}</small>
                </h2>

                <!-- Profile Actions -->
                <div class="border-top border-bottom py-2 mb-3">
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-id-card text-primary me-2"></i> My Profile
                    </a>
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-cog text-primary me-2"></i> Settings
                    </a>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit"
                        class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>

        {{-- <a class="text-white" title="sign out" href="/logout"><i class="fas fa-sign-out-alt d-block"></i></a> --}}
        <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="text-white">
                <i class="fas fa-sign-out-alt me-2"></i>
            </button>
        </form>
    </div>
</header>

<script type="module">
    $(function() {
        $("#profilePopup").click(function(e) {
            $(".modalProfile").slideToggle("fast");
            e.stopPropagation();
        });

        // Close modal when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.modalProfile').length && !$(e.target).is('#profilePopup')) {
                $(".modalProfile").slideUp("fast");
            }
        });
    });
</script>

<style>
    /* Minimal custom styles that can't be achieved with Bootstrap/Tailwind */
    .cursor-pointer {
        cursor: pointer;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
</style>
