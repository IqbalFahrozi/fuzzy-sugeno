<div class="container">
    <div class="topbar">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="{{ asset('assets/imgs/customer01.jpg') }}" alt=""
                    style="width: 40px; height: 40px;">
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-white border-0 rounded-0 rounded-bottom m-0">
                <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="bi bi-person-fill-gear"></i>
                    {{ __('My Profile') }}
                </a>
                <hr class="dropdown-divider">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault();
                    this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        {{ __('Log Out') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
