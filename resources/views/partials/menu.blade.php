<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li class="nav-item" style="height: 50px;border-color: aquamarine;">
                <p style="margin: 15px 10px 20px 10px;font-size: 18px;">
                    HI,
                   {{ Auth::user()->name }}
                </p>
            </li>
            @can('dashboard_access')
                <li class="nav-item">
                    <a href="{{ route("admin.home") }}" class="nav-link">
                        <i class="nav-icon fas fa-fw fa-tachometer-alt">

                        </i>
                        {{ trans('global.dashboard') }}
                    </a>
                </li>
            @endcan
            @can('user_management_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-users nav-icon">

                        </i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                                    </i>
                                    {{ trans('cruds.permission.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-briefcase nav-icon">

                                    </i>
                                    {{ trans('cruds.role.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user nav-icon">

                                    </i>
                                    {{ trans('cruds.user.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('audit_log_access')
                            <li class="nav-item">
                                <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-file-alt nav-icon">

                                    </i>
                                    {{ trans('cruds.auditLog.title') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa fa-wrench nav-icon"></i>
                    Service
                </a>
                <ul class="nav-dropdown-items">
                    @can('status_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.statuses.index") }}" class="nav-link {{ request()->is('admin/statuses') || request()->is('admin/statuses/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-cogs nav-icon">

                                </i>
                                {{ trans('cruds.status.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('priority_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.priorities.index") }}" class="nav-link {{ request()->is('admin/priorities') || request()->is('admin/priorities/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-cogs nav-icon">

                                </i>
                                {{ trans('cruds.priority.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('category_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is('admin/categories') || request()->is('admin/categories/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-tags nav-icon">

                                </i>
                                {{ trans('cruds.category.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ticket_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.tickets.index") }}" class="nav-link {{ request()->is('admin/tickets') || request()->is('admin/tickets/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-question-circle nav-icon">

                                </i>
                                {{ trans('cruds.ticket.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('comment_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.comments.index") }}" class="nav-link {{ request()->is('admin/comments') || request()->is('admin/comments/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-comment nav-icon">

                                </i>
                                {{ trans('cruds.comment.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa fa-industry nav-icon"></i>
                    Dealer / Distributor
                </a>
                <ul class="nav-dropdown-items">
                    @can('product_model_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.product-model") }}" class="nav-link {{ request()->is('admin/product-model') || request()->is('admin/product-model/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list-alt nav-icon">
                                </i>
                                Product Model
                            </a>
                        </li>
                    @endcan
                    @can('transaction_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.transactions") }}" class="nav-link {{ request()->is('admin/transactions') || request()->is('admin/transactions/*') ? 'active' : '' }}">
                            <i class="fa-fw fa fa-money nav-icon">
                            </i>
                            Transactions
                        </a>
                    </li>
                    @endcan
                    @can('dealer_category_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.dealer-categories") }}" class="nav-link {{ request()->is('admin/dealer-categories') || request()->is('admin/dealer-categories/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list-alt nav-icon">
                                </i>
                                Dealer Category
                            </a>
                        </li>
                    @endcan
                    @can('dealer_profile_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.dealer-profile") }}" class="nav-link {{ request()->is('admin/dealer-profile') || request()->is('admin/dealer-profile/*') ? 'active' : '' }}">
                                <i class="fa-brands fa fa-user nav-icon">
                                </i>
                                Stores
                            </a>
                        </li>
                    @endcan
                    @can('product_attribute_access')
                        <li class="nav-item">
                            <a href="{{ route("admin.product-attributes") }}" class="nav-link {{ request()->is('admin/product-attributes') || request()->is('admin/product-attributes/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list-alt nav-icon">
                                </i>
                                Product Attributes
                            </a>
                        </li>
                    @endcan
                    @can('accounts_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.accounts') }}" class="nav-link {{ request()->is('admin/accounts') || request()->is('admin/accounts/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-list-alt nav-icon">
                            </i>
                            Accounts
                        </a>
                    </li>
                    @endcan
                    <!-- @can('orders_access') -->
                    <li class="nav-item">
                        <a href="{{ route('admin.getorders') }}" class="nav-link {{ request()->is('admin/getorders') || request()->is('admin/getorders/*') ? 'active' : '' }}">
                            <i class="fa-brands fa fa-user nav-icon">
                            </i>
                            Orders
                        </a>
                    </li>
                    <!-- @endcan -->
                    @can('transport_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.transport') }}" class="nav-link {{ request()->is('admin/transport') || request()->is('admin/transport/*') ? 'active' : '' }}">
                            <i class="fa-brands fas fa-truck nav-icon">
                            </i>
                            Transport
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-list-alt nav-icon">
                            </i>
                            Almirah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.officecollection') }}" class="nav-link {{ request()->is('admin/officecollection') || request()->is('admin/officecollection/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-list-alt nav-icon"></i>
                            Office Collection
                        </a>
                    </li>
                   <!--  <li class="nav-item">
                        <a href="{{ route('admin.accessories') }}" class="nav-link {{ request()->is('admin/accessories') || request()->is('admin/accessories/*') ? 'active' : '' }}">
                            <i class="fa-solid fa-gear nav-icon"></i>
                            Accessories 
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="{{ route("admin.cart") }}" class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                            <i class="fa-brands fas fa-shopping-cart nav-icon">
                            </i>
                            Cart ({{getCartQty()}})
                        </a>
                    </li>
                    @can('dd_orders_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.ddorders") }}" class="nav-link {{ request()->is('admin/ddorders') || request()->is('admin/ddorders/*') ? 'active' : '' }}">
                            <i class="fa-brands fas fa-shopping-cart nav-icon">
                            </i>
                            My Orders
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>

            @can('production_access')
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa fa-industry nav-icon"></i>
                    Production
                </a>
                <ul class="nav-dropdown-items">
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.production') }}" class="nav-link {{ request()->is('admin/production') || request()->is('admin/production/*') ? 'active' : '' }}">
                            <i class="fa-brands fa fa-user nav-icon">
                            </i>
                            Production
                        </a>
                    </li>
                </ul>
            </li>
             @endcan
            @can('logistic_access')
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa fa-industry nav-icon"></i>
                    Logistic
                </a>
                <ul class="nav-dropdown-items">
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.logistic') }}" class="nav-link {{ request()->is('admin/logistic') || request()->is('admin/logistic/*') ? 'active' : '' }}">
                            <i class="fa-brands fa fa-user nav-icon">
                            </i>
                            Logistic
                        </a>
                    </li>
                    
                </ul>
            </li>
            @endcan
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
