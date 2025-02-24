<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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

        <!-- Menambahkan menu Maintenance di bawah Progress Project -->
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
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('surat.digital_marketing.list') }}" class="nav-link text-white">Digital Marketing</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Finance</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">Administrasi</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
