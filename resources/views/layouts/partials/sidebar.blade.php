<aside class="sidebar">
    <div class="sidebar-brand"><a href="/">{{ auth()->user()->username }}</a></div>
    <ul>
        <!-- Dashboard -->
        <li class="{{ Request::routeIs('home') ? 'main-active' : '' }}">
            <a href="{{ route('home') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <!-- Sales & Finances -->
        <li class="{{ Request::routeIs('sales.*', 'purchases.*', 'expenses.*', 'sales_summaries.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-dollar-sign"></i> Sales & Finances
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('sales.index') }}" class="{{ Request::routeIs('sales.index') ? 'active' : '' }}"><i class="fas fa-cash-register"></i> Sales</a></li>
                <li><a href="{{ route('purchases.index') }}" class="{{ Request::routeIs('purchases.index') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Purchases</a></li>
                <li><a href="{{ route('expenses.index') }}" class="{{ Request::routeIs('expenses.index') ? 'active' : '' }}"><i class="fas fa-money-check-alt"></i> Expenses</a></li>
                <li><a href="{{ route('sales_summaries.index') }}" class="{{ Request::routeIs('sales_summaries.index') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Sales Summaries</a></li>
            </ul>
        </li>

        <!-- Inventory -->
        <li class="{{ Request::routeIs('products.*', 'categories.*', 'inventory_summaries.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-warehouse"></i> Inventory
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('products.index') }}" class="{{ Request::routeIs('products.index') ? 'active' : '' }}"><i class="fas fa-list"></i> Products</a></li>
                <li><a href="{{ route('categories.index') }}" class="{{ Request::routeIs('categories.index') ? 'active' : '' }}"><i class="fas fa-box"></i> Categories</a></li>
                <li><a href="{{ route('inventory_summaries.index') }}" class="{{ Request::routeIs('inventory_summaries.index') ? 'active' : '' }}"><i class="fas fa-boxes"></i> Inventory Summaries</a></li>
            </ul>
        </li>

        <!-- Human Resources -->
        <li class="{{ Request::routeIs('employees.*', 'departments.*', 'positions.*', 'roles.*', 'attendances.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-users-cog"></i> Human Resources
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('employees.index') }}" class="{{ Request::routeIs('employees.index') ? 'active' : '' }}"><i class="fas fa-users"></i> Employees</a></li>
                <li><a href="{{ route('departments.index') }}" class="{{ Request::routeIs('departments.index') ? 'active' : '' }}"><i class="fas fa-sitemap"></i> Departments</a></li>
                <li><a href="{{ route('positions.index') }}" class="{{ Request::routeIs('positions.index') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> Positions</a></li>
                <li><a href="{{ route('roles.index') }}" class="{{ Request::routeIs('roles.index') ? 'active' : '' }}"><i class="fas fa-user-tag"></i> Roles</a></li>
                <li><a href="{{ route('attendances.index') }}" class="{{ Request::routeIs('attendances.index') ? 'active' : '' }}"><i class="fas fa-clock"></i> Attendances</a></li>
            </ul>
        </li>

        <!-- People -->
        <li class="{{ Request::routeIs('customers.*', 'suppliers.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-users"></i> People
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('customers.index') }}" class="{{ Request::routeIs('customers.index') ? 'active' : '' }}"><i class="fas fa-user-friends"></i> Customers</a></li>
                <li><a href="{{ route('suppliers.index') }}" class="{{ Request::routeIs('suppliers.index') ? 'active' : '' }}"><i class="fas fa-truck"></i> Suppliers</a></li>
            </ul>
        </li>

        <!-- Settings -->
        <li class="{{ Request::routeIs('currencies.*', 'tax_rates.*', 'units.*', 'payment_methods.*', 'promotions.*', 'discounts.*', 'permissions.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-cogs"></i> Settings
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('currencies.index') }}" class="{{ Request::routeIs('currencies.index') ? 'active' : '' }}"><i class="fas fa-money-bill"></i> Currencies</a></li>
                <li><a href="{{ route('tax_rates.index') }}" class="{{ Request::routeIs('tax_rates.index') ? 'active' : '' }}"><i class="fas fa-percentage"></i> Tax Rates</a></li>
                <li><a href="{{ route('units.index') }}" class="{{ Request::routeIs('units.index') ? 'active' : '' }}"><i class="fas fa-ruler"></i> Units</a></li>
                <li><a href="{{ route('payment_methods.index') }}" class="{{ Request::routeIs('payment_methods.index') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
                @can('view-promotions')
                    <li><a href="{{ route('promotions.index') }}" class="{{ Request::routeIs('promotions.index') ? 'active' : '' }}"><i class="fas fa-tags"></i> Promotions</a></li>
                @endcan
                @can('view-discounts')
                    <li><a href="{{ route('discounts.index') }}" class="{{ Request::routeIs('discounts.index') ? 'active' : '' }}"><i class="fas fa-percent"></i> Discounts</a></li>
                @endcan
                @can('manage-permissions')
                    <li><a href="{{ route('permissions.index') }}" class="{{ Request::routeIs('permissions.index') ? 'active' : '' }}"><i class="fas fa-lock"></i> Permissions</a></li>
                @endcan
            </ul>
        </li>

        <!-- Profile -->
        <li class="{{ Request::routeIs('profile.*') ? 'main-active' : '' }}">
            <a href="{{ route('profile.edit') }}">
                <i class="fas fa-user"></i> Profile
            </a>
        </li>

        <!-- Modules -->
        <li class="{{ Request::routeIs(['jester.*', 'media.*', 'modulemanagement.*', 'superadmin.*']) ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-puzzle-piece"></i> Modules
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ url('/jester/chat') }}" class="{{ Request::is('jester/chat*') ? 'active' : '' }}"><i class="fas fa-robot"></i> Jester</a></li>
                @if (Route::has('media.index'))
                    <li><a href="{{ route('media.index') }}" class="{{ Request::routeIs('media.*') ? 'active' : '' }}"><i class="fas fa-photo-video"></i> Media</a></li>
                @endif
                @if (Route::has('modulemanagement.index'))
                    <li><a href="{{ route('modulemanagement.index') }}" class="{{ Request::routeIs('modulemanagement.*') ? 'active' : '' }}"><i class="fas fa-cubes"></i> Module Management</a></li>
                @endif

                @php
                    $modules = [];
                    if (class_exists('Modules\ModuleManagement\Models\ModuleManagement')) {
                        $modules = \Modules\ModuleManagement\Models\ModuleManagement::where('enabled', true)
                                    ->whereNotIn('name', ['Jester', 'Media', 'ModuleManagement'])
                                    ->get();
                    }
                @endphp
                @foreach ($modules as $module)
                    @php
                        $moduleName = $module->name;
                        $lowerName = strtolower($moduleName);
                        $routeName = $lowerName . '.index';
                        $displayName = preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $moduleName);
                        $icon = 'fas fa-cube'; // Default icon
                        if ($moduleName === 'Superadmin') {
                            $icon = 'fas fa-user-shield';
                        }
                    @endphp

                    @if (Route::has($routeName))
                        <li><a href="{{ route($routeName) }}" class="{{ Request::routeIs($lowerName . '.*') ? 'active' : '' }}"><i class="{{ $icon }}"></i> {{ $displayName }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>
    </ul>
</aside>

<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background: linear-gradient(135deg, #333 0%, #222 100%);
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding-bottom: 5%;
        transition: transform 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
        z-index: 2000;
        transform: translateX(-100%);
        overflow-y: auto;
        scrollbar-width: none; /* Firefox */
    }

    .sidebar::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .sidebar.expanded {
        transform: translateX(0);
    }

    .sidebar-brand {
        padding: 20px;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        z-index: 2001;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        transition: background 0.3s ease;
        position: relative;
        z-index: 2001;
        pointer-events: auto;
    }

    .sidebar ul li.main-active {
        background: rgba(255, 255, 255, 0.05);
        border-left: 3px solid #4caf50;
    }

    .sidebar ul li a.active {
        background: rgb(36, 1, 1);
    }

    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar ul li a i {
        margin-right: 10px;
    }

    .dropdown-icon {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    .sidebar ul li.open .dropdown-icon {
        transform: rotate(180deg);
    }

    .submenu {
        max-height: 0;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.2);
        padding-left: 20px;
        opacity: 0;
        transform: translateY(-10px);
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
            opacity 0.3s ease,
            transform 0.3s ease;
        z-index: 2001;
        /* Hide scrollbar on submenu if content grows */
        scrollbar-width: none; /* Firefox */
    }

    .submenu::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .sidebar ul li.open .submenu {
        max-height: 5000px;
        opacity: 1;
        transform: translateY(0);
    }

    .submenu li a {
        padding: 10px 20px;
        font-size: 0.9rem;
        z-index: 2001;
        position: relative;
        pointer-events: auto;
    }
</style>
