<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Master')</title>
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        body.sidebar-open {
            overflow: hidden; /* Lock body scroll when sidebar is open */
        }

        .layout-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
        }

        /* Content Container */
        .content-container {
            position: relative;
            min-height: 100vh;
            transition: margin-left 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            background-color: #f5f5f5;
            margin-left: 0;
        }

        .sidebar.expanded ~ .content-container {
            margin-left: 250px;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            min-height: calc(100vh - 90px); /* Header (50px) + Footer (40px) */
        }
        
        /* Mobile-specific adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 70%;
                z-index: 2000;
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
            }

            .sidebar.expanded {
                transform: translateX(0);
            }

            .content-container {
                margin-left: 0;
                transition: none;
            }

            .sidebar.expanded ~ .content-container {
                margin-left: 0;
                position: relative;
            }

            .sidebar.expanded ~ .content-container::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1999;
                pointer-events: auto; /* Allow clicks to pass through to lower layers */
            }

            .sidebar, .sidebar * {
                z-index: 2000; /* Ensure all sidebar content is above overlay */
                pointer-events: auto; /* Ensure clicks register on sidebar */
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100">

    <div class="layout-wrapper">
        @include('layouts.partials.sidebar')
        <div class="content-container">
            @include('layouts.partials.header')
            <div class="main-content">
                @yield('content')
            </div>
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-JobWAqYk5CSjWuVV3mxgS+MmccJqkrBaDhk8SKS1BW+71dJ9gzascwzW85UwGhxiSyR7Pxhu50k+Nl3+o5I49A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/tailwind.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script type="module">
        $(document).ready(function() {
            const $sidebar = $('.sidebar');
            const $toggleBtn = $('#toggleSidebar');
            const $menuItems = $('.sidebar > ul > li > a');
            const $submenuItems = $('.sidebar .submenu a');

            // Toggle sidebar and body scroll lock
            $toggleBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $sidebar.toggleClass('expanded');
                if (window.innerWidth <= 768) {
                    $('body').toggleClass('sidebar-open');
                }
                console.log('Sidebar toggled:', $sidebar.hasClass('expanded'));
            });

            // Handle dropdowns
            $menuItems.on('click', function(e) {
                const $parentLi = $(this).parent();
                if ($parentLi.find('.submenu').length) {
                    e.preventDefault();
                    e.stopPropagation();
                    $parentLi.toggleClass('open');
                    $('.sidebar > ul > li').not($parentLi).removeClass('open');
                    console.log('Dropdown toggled:', $parentLi.hasClass('open'));
                }
            });

            // Ensure submenu links are clickable
            $submenuItems.on('click', function(e) {
                e.stopPropagation();
                console.log('Submenu link clicked:', $(this).attr('href'));
            });

            // Close sidebar on outside click (mobile)
            $(document).on('click', function(event) {
                if (window.innerWidth <= 768 && $sidebar.hasClass('expanded')) {
                    const isSidebarClick = $sidebar.is(event.target) || $sidebar.has(event.target).length > 0;
                    const isToggleBtnClick = $toggleBtn.is(event.target) || $toggleBtn.has(event.target).length > 0;
                    if (!isSidebarClick && !isToggleBtnClick) {
                        $sidebar.removeClass('expanded');
                        $('body').removeClass('sidebar-open'); // Unlock scroll
                        $('.sidebar > ul > li').removeClass('open');
                        console.log('Sidebar closed on outside click');
                    }
                }
            });

            // Prevent sidebar clicks from bubbling to document
            $sidebar.on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>