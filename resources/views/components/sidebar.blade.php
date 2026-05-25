<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">⚡</div>
        <div class="sidebar-logo-text">
            <h2>ERP System</h2>
            <span>Digital Services</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="sidebar-section-label">Main Menu</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            Dashboard
        </a>

        <div class="sidebar-section-label">Manajemen</div>

        <a href="{{ route('services.index') }}"
           class="nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span>
            Services
        </a>

        <a href="{{ route('customers.index') }}"
           class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span>
            Customers
        </a>

        <a href="{{ route('subscriptions.index') }}"
           class="nav-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
            <span class="nav-icon">📋</span>
            Subscriptions
        </a>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="api-status-pill">
            <div class="api-status-dot"></div>
            <div>
                <div class="api-status-text">API Connected</div>
                <div class="api-status-url">127.0.0.1:8000/api</div>
            </div>
        </div>
    </div>

</aside>

{{-- Mobile hamburger --}}
<button id="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')"
    style="display:none; position:fixed; top:14px; left:14px; z-index:300;
           background:var(--primary-dark); border:none; color:white;
           width:40px; height:40px; border-radius:10px; cursor:pointer;
           font-size:18px; align-items:center; justify-content:center;">
    ☰
</button>

<style>
@media (max-width: 1024px) {
    #sidebar-toggle { display: flex !important; }
}
</style>