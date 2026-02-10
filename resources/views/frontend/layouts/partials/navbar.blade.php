<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ route('home') }}" class="brand">Home</a>
    </div>

    <div class="navbar-right">
        @auth
            <div class="user-menu">
                <button class="user-btn" id="userMenuBtn">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    @if(Auth::user()->hasRole('administrator'))
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @endif
                    <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Register</a>
            </div>
        @endauth
    </div>
</nav>
