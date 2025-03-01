<nav class="mt-2 d-flex flex-column" style="height: 100vh; position: relative;">
    <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link text-white {{ (Request::routeIs('users.index') ? 'active' : '') }}">
                <i class="nav-icon fas fa-users"></i>
                <p>User</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('omsets.index') }}" class="nav-link text-white {{ (Request::routeIs('omsets.index') ? 'active' : '') }}">
                <i class="nav-icon fas fa-dollar-sign"></i>
                <p>Omset</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('progress_projects.index') }}" class="nav-link text-white">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Progress Project</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('maintenances.index') }}" class="nav-link text-white {{ (Request::routeIs('maintenances.index') ? 'active' : '') }}">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Maintenance</p>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menuSurat" role="button" aria-expanded="false" aria-controls="menuSurat">
                <i class="nav-icon fas fa-envelope"></i>
                <p>Surat</p>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse" id="menuSurat">
                <ul class="ps-4 list-unstyled">
                    <li><a href="{{ route('dashboard.marketing') }}" class="nav-link text-white">Dashboard</a></li>
                    <li><a href="{{ route('surat.digital_marketing.list') }}" class="nav-link text-white">Digital Marketing</a></li>
                    <li><a href="{{ route('surat.finance.index') }}" class="nav-link text-white">Finance</a></li>
                    <li><a href="{{ route('surat.admin.index') }}" class="nav-link text-white">Administrasi</a></li>
                    <li><a href="{{ route('surat.warehouse.index') }}" class="nav-link text-white">Warehouse</a></li>
                    <li><a href="{{ route('surat.purchasing.index') }}" class="nav-link text-white">Purchasing</a></li>
                </ul>
            </div>
        </li>
    </ul>

    <!-- Tombol Logout di bawah tapi tidak terlalu mepet -->
    <form method="POST" action="{{ route('logout') }}" class="text-center mt-auto mb-3" style="position: absolute; bottom: 200px; width: 100%;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</nav>
