<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(session('division') == 'percetakan')
                        <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                            {{ __('Invoices') }}
                        </x-nav-link>
                        <x-nav-link :href="route('purchases.index', ['division' => 'percetakan'])" :active="request()->routeIs('purchases.*') && request('division') == 'percetakan'">
                            {{ __('Purchases') }}
                        </x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                            {{ __('Products') }}
                        </x-nav-link>
                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                            {{ __('Customers') }}
                        </x-nav-link>
                    @endif

                    @if(session('division') == 'konfeksi')
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                            {{ __('Employees') }}
                        </x-nav-link>
                        <x-nav-link :href="route('payrolls.index')" :active="request()->routeIs('payrolls.*')">
                            {{ __('Payrolls') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kasbons.index')" :active="request()->routeIs('kasbons.*')">
                            {{ __('Kasbons') }}
                        </x-nav-link>
                        <x-nav-link :href="route('purchases.index', ['division' => 'konfeksi'])" :active="request()->routeIs('purchases.*') && request('division') == 'konfeksi'">
                            {{ __('Purchases') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">
                        {{ __('Finance') }}
                    </x-nav-link>
                    
                    @if(session('division'))
                        <div class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <form action="{{ route('division.set') }}" method="POST" class="ml-4">
                                @csrf
                                <!-- Hacky way to unset or change division? Or just a link back to selection? -->
                                <!-- Let's add a button to switch division -->
                            </form>
                           <span class="px-2 py-1 rounded bg-gray-100 uppercase text-xs font-bold">{{ session('division') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Clear Session / Switch Division Button -->
                <a href="{{ route('dashboard') }}?switch=1" 
                   onclick="event.preventDefault(); document.getElementById('switch-division-form').submit();"
                   class="text-sm text-gray-500 underline mr-4 hover:text-gray-700">
                   Ganti Divisi
                </a>
                <form id="switch-division-form" action="{{ route('division.set') }}" method="POST" class="hidden">
                    @csrf
                    <!-- If we post without division, maybe we can clear it? 
                         Actually DashboardController@setDivision requires 'division'. 
                         Let's just use a dedicated logout or just clearing session logic.
                         For now, let's keep it simple: Logout to switch, or custom route. 
                         I'll add a 'clear-division' route later if needed, but for now 
                         let's just modify the session in a simple closure route or similar if user wants to switch.
                         Actually, standard approach: Just Logout. 
                         Or... add a specific route to clear session.
                    -->
                </form>

                 <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Add responsive links here similarly if needed -->
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
