<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{route('users.index')}}" class="nav-link text-white {{(Request::routeIs('users.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-users"></i>
                <p>User</p>
            </a>
        </li>
                       
        <li class="nav-item">
            <a href="{{route('omsets.index')}}" class="nav-link text-white {{(Request::routeIs('omsets.index') ? 'active':'')}}">
                <i class="nav-icon fas fa-dollar-sign"></i> 
                <p>Omset</p>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{route('progress_projects.index')}}" class="nav-link text-white">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Progress</p>
            </a>
        </li>
                                               
    </ul>
</nav>