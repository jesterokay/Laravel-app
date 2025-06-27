<aside class="sidebar">
    <div class="sidebar-brand"><a href="/">{{ auth()->user()->username }}</a></div>
    <ul>
        <li class="{{ Request::routeIs('dashboard') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-tachometer-alt"></i> Dashboard
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('home') }}" class="{{ Request::routeIs('dashboard') ? 'active' : '' }}"><i
                            class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('products.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-shop"></i> Products
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('products.index') }}"
                        class="{{ Request::routeIs('products.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Product List</a></li>
                <li><a href="{{ route('products.create') }}"
                        class="{{ Request::routeIs('products.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Product</a></li>
                @can('view-promotions')
                    <li><a href="{{ route('promotions.index') }}"
                            class="{{ Request::routeIs('promotions.index') ? 'active' : '' }}"><i class="fas fa-tags"></i>
                            Promotions</a></li>
                @endcan
                @can('view-discounts')
                    <li><a href="{{ route('discounts.index') }}"
                            class="{{ Request::routeIs('discounts.index') ? 'active' : '' }}"><i
                                class="fas fa-percent"></i> Discounts</a></li>
                @endcan
            </ul>
        </li>
        <li class="{{ Request::routeIs('categories.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-box"></i> Categories
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('categories.index') }}"
                        class="{{ Request::routeIs('categories.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Category List</a></li>
                <li><a href="{{ route('categories.create') }}"
                        class="{{ Request::routeIs('categories.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Category</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('sales_summaries.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-chart-line"></i> Sales Summaries
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('sales_summaries.index') }}"
                        class="{{ Request::routeIs('sales_summaries.index') ? 'active' : '' }}"><i
                            class="fas fa-list"></i> Sales Summary List</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('inventory_summaries.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-warehouse"></i> Inventory Summaries
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('inventory_summaries.index') }}"
                        class="{{ Request::routeIs('inventory_summaries.index') ? 'active' : '' }}"><i
                            class="fas fa-list"></i> Inventory Summary List</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('currencies.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-money-bill"></i> Currencies
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('currencies.index') }}"
                        class="{{ Request::routeIs('currencies.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Currency List</a></li>
                <li><a href="{{ route('currencies.create') }}"
                        class="{{ Request::routeIs('currencies.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Currency</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('customers.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-user-friends"></i> Customers
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('customers.index') }}"
                        class="{{ Request::routeIs('customers.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Customer List</a></li>
                <li><a href="{{ route('customers.create') }}"
                        class="{{ Request::routeIs('customers.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Customer</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('suppliers.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-truck"></i> Suppliers
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('suppliers.index') }}"
                        class="{{ Request::routeIs('suppliers.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Supplier List</a></li>
                <li><a href="{{ route('suppliers.create') }}"
                        class="{{ Request::routeIs('suppliers.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Supplier</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('employees.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-users"></i> Employees
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('employees.index') }}"
                        class="{{ Request::routeIs('employees.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Employee List</a></li>
                <li><a href="{{ route('employees.create') }}"
                        class="{{ Request::routeIs('employees.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Employee</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('purchases.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-shopping-cart"></i> Purchases
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('purchases.index') }}"
                        class="{{ Request::routeIs('purchases.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Purchase List</a></li>
                <li><a href="{{ route('purchases.create') }}"
                        class="{{ Request::routeIs('purchases.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Purchase</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('expenses.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-money-check-alt"></i> Expenses
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('expenses.index') }}"
                        class="{{ Request::routeIs('expenses.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Expense List</a></li>
                <li><a href="{{ route('expenses.create') }}"
                        class="{{ Request::routeIs('expenses.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Expense</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('sales.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-cash-register"></i> Sales
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('sales.index') }}"
                        class="{{ Request::routeIs('sales.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Sale List</a></li>
                <li><a href="{{ route('sales.create') }}"
                        class="{{ Request::routeIs('sales.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Sale</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('tax_rates.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-percentage"></i> Tax Rates
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('tax_rates.index') }}"
                        class="{{ Request::routeIs('tax_rates.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Tax Rate List</a></li>
                <li><a href="{{ route('tax_rates.create') }}"
                        class="{{ Request::routeIs('tax_rates.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Tax Rate</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('units.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-ruler"></i> Units
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('units.index') }}"
                        class="{{ Request::routeIs('units.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Unit List</a></li>
                <li><a href="{{ route('units.create') }}"
                        class="{{ Request::routeIs('units.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Unit</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('payment_methods.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-credit-card"></i> Payment Methods
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('payment_methods.index') }}"
                        class="{{ Request::routeIs('payment_methods.index') ? 'active' : '' }}"><i
                            class="fas fa-list"></i> Payment Method List</a></li>
                <li><a href="{{ route('payment_methods.create') }}"
                        class="{{ Request::routeIs('payment_methods.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Payment Method</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('attendances.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-clock"></i> Attendances
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('attendances.index') }}"
                        class="{{ Request::routeIs('attendances.index') ? 'active' : '' }}"><i
                            class="fas fa-list"></i> Attendance List</a></li>
                <li><a href="{{ route('attendances.toggle') }}"
                        class="{{ Request::routeIs('attendances.toggle') ? 'active' : '' }}"><i
                            class="fas fa-clock"></i> Attendance Toggle</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('positions.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-briefcase"></i> Positions
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('positions.index') }}"
                        class="{{ Request::routeIs('positions.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Position List</a></li>
                <li><a href="{{ route('positions.create') }}"
                        class="{{ Request::routeIs('positions.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Position</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('departments.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-sitemap"></i> Departments
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('departments.index') }}"
                        class="{{ Request::routeIs('departments.index') ? 'active' : '' }}"><i
                            class="fas fa-list"></i> Department List</a></li>
                <li><a href="{{ route('departments.create') }}"
                        class="{{ Request::routeIs('departments.create') ? 'active' : '' }}"><i
                            class="fas fa-plus"></i> Add Department</a></li>
            </ul>
        </li>
        <li class="{{ Request::routeIs('roles.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-user-tag"></i> Roles
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('roles.index') }}"
                        class="{{ Request::routeIs('roles.index') ? 'active' : '' }}"><i class="fas fa-list"></i>
                        Role List</a></li>
                <li><a href="{{ route('roles.create') }}"
                        class="{{ Request::routeIs('roles.create') ? 'active' : '' }}"><i class="fas fa-plus"></i>
                        Add Role</a></li>
            </ul>
        </li>
        @can('manage-permissions')
            <li class="{{ Request::routeIs('permissions.*') ? 'main-active' : '' }}">
                <a href="#">
                    <i class="fas fa-lock"></i> Permissions
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('permissions.index') }}"
                            class="{{ Request::routeIs('permissions.index') ? 'active' : '' }}"><i
                                class="fas fa-list"></i> Permission List</a></li>
                    <li><a href="{{ route('permissions.assign') }}"
                            class="{{ Request::routeIs('permissions.assign') ? 'active' : '' }}"><i
                                class="fas fa-plus"></i> Assign Permission</a></li>
                </ul>
            </li>
        @endcan
        <li class="{{ Request::routeIs('profile.*') ? 'main-active' : '' }}">
            <a href="#">
                <i class="fas fa-user"></i> Profile
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('profile.edit') }}"
                        class="{{ Request::routeIs('profile.edit') ? 'active' : '' }}"><i class="fas fa-edit"></i>
                        Edit Profile</a></li>
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
        max-height: 200px;
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
