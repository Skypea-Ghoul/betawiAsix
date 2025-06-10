<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-1">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('image/Logo.png')}}" alt="Logo BetawiAsix" class="h-12 w-12 ml-10">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
           <a href="#" class="nav-item hover:text-amber-600 mr-0" data-target="home">Beranda</a>
<a href="#" class="nav-item hover:text-amber-600"    data-target="menu">Menu</a>
<a href="#" class="nav-item hover:text-amber-600"    data-target="about">Tentang Kami</a>

                
                <!-- Login/Logout Links -->
                <a id="navbar-login-link" href="#" class="nav-item hover:text-amber-600">Login</a>
                <a id="navbar-logout-link" href="#" class="nav-item hover:text-red-600" style="display: none;">Logout</a>
                
                <!-- Cart Link -->
                <a id="cart-nav-link" href="#" class="nav-item flex items-center hover:text-amber-600" style="display: none;">
                    <i class="fas fa-shopping-cart mr-1"></i>
                    <span id="cart-count">0</span>
                </a>
            </div>

            <!-- Mobile Menu Button --> 
            <div class="md:hidden flex items-center">
                <!-- Cart Link for Mobile -->
                <a id="mobile-cart-link" href="#" class="mr-4 flex items-center text-amber-600" style="display: none;">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="mobile-cart-count" class="ml-1">0</span>
                </a>
                <button id="mobile-menu-button" class="outline-none">
                    <svg class="w-6 h-6 text-gray-500 hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <div class="container mx-auto px-4 py-3">
                <a href="#home" class="mobile-nav-item block py-2 hover:text-amber-600">Beranda</a>
                <a href="#menu" class="mobile-nav-item block py-2 hover:text-amber-600">Menu</a>
                <a href="#about" class="mobile-nav-item block py-2 hover:text-amber-600">Tentang Kami</a>
                
                <!-- Login/Logout Links -->
                <a id="mobile-navbar-login-link" href="#" class="mobile-nav-item block py-2 hover:text-amber-600">Login</a>
                <a id="mobile-navbar-logout-link" href="#" class="mobile-nav-item block py-2 hover:text-red-600" style="display: none;">Logout</a>

                <!-- Cart Link for Mobile Menu -->
                <a id="mobile-cart-nav-link" href="#" class="mobile-nav-item block py-2 hover:text-amber-600 flex items-center" style="display: none;">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <span id="mobile-menu-cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>