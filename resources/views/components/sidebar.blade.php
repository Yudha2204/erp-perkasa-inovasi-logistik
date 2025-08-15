<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar bg-dark">
        <div class="side-header bg-dark" style="border-bottom: 0px;">
            <a class="header-brand1" href="index.html">
                <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ url('assets/images/logo/logopilwp.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
            <ul class="side-menu">
                <li class="sub-category text-white">
                    <h3>Menu</h3>
                </li>
                <li class="slide {{ request()->is('dashboard') || request()->is('dashboard/*') ?'bg-primary' : '' }}" style="border-radius: 5px;">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{ route('dashboard.index') }}"><i class="side-menu__icon fe fe-home text-white"></i><span class="side-menu__label text-white">Dashboard</span></a>
                </li>
                {{-- <li class="slide {{ request()->is('report/report-master') || request()->is('report/report-master/*') ?'bg-primary' : '' }}"  style="border-radius: 5px;">
                    <a href="{{ route('report.report-master.index') }}" class="side-menu__item has-link" data-bs-toggle="slide" href=""><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="side-menu__icon text-white">
                        <path d="M19.4643 11.6214C19.8515 11.6214 20.1654 11.3074 20.1654 10.92V9.98483C20.1654 6.27624 18.5133 4.47032 15.1287 4.47032H12.6306C12.2239 4.47225 11.8395 4.28446 11.591 3.96241L10.7851 2.89014C10.2645 2.22184 9.46429 1.83195 8.61738 1.83399H6.8606C3.52434 1.83399 1.83203 3.76085 1.83203 7.56619V14.4432C1.83203 17.9744 4.02397 20.1673 7.5617 20.1673H14.4276C17.9573 20.1673 20.1573 17.9744 20.1573 14.4432C20.1187 14.0827 19.8146 13.8093 19.4522 13.8093C19.0897 13.8093 18.7856 14.0827 18.747 14.4432C18.747 17.2246 17.2159 18.7645 14.4276 18.7645H7.55364C4.76536 18.7645 3.23423 17.2327 3.23423 14.4432V13.774H14.5163C14.9035 13.774 15.2174 13.46 15.2174 13.0726C15.2174 12.6853 14.9035 12.3712 14.5163 12.3712H3.23423V7.53394C3.23423 4.50257 4.31408 3.20457 6.85254 3.20457H8.62544C9.03209 3.21058 9.41421 3.40024 9.665 3.72054L10.4709 4.78475C10.9824 5.46284 11.7814 5.86256 12.6306 5.86507H15.1207C17.7397 5.86507 18.7551 7.03409 18.7551 9.98483V10.92C18.7551 11.1075 18.8301 11.2871 18.9633 11.4189C19.0965 11.5507 19.2769 11.6236 19.4643 11.6214Z" fill="#F7FAFB"/></svg><span class="side-menu__label text-white">Report</span></a>
                </li> --}}
                <li class="slide">
                    @canany(['view-export@marketing','view-import@marketing'])
                    <a class="side-menu__item {{ request()->is('marketing/overview') || request()->is('marketing/export') || request()->is('marketing/export/create') || request()->is('marketing/export/*') || request()->is('marketing/import') || request()->is('marketing/import/create') || request()->is('marketing/import/*') || request()->is('marketing/report') ?'bg-primary' : '' }}" style="border-radius: 5px;" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-bar-chart-2 text-white"></i><span class="side-menu__label text-white">Marketing</span><i class="angle fe fe-chevron-right text-white"></i></a>
                    <ul class="slide-menu"  style="display: {{ request()->is('marketing/overview') || request()->is('marketing/export') || request()->is('marketing/export/create') || request()->is('marketing/export/*') || request()->is('marketing/import') || request()->is('marketing/import/create') || request()->is('marketing/import/*') || request()->is('marketing/report') ?'block' : 'none' }}">
                        <li class="side-menu-label1 text-white"><a href="javascript:void(0)">Marketing</a></li>
                        @canany(['view-export@marketing','view-import@marketing'])
                        <li><a href="{{ route('marketing.overview.index') }}" class="slide-item {{ request()->is('marketing/overview') ?'text-primary' : 'text-white' }}"> Overview</a></li>
                        @endcanany
                        @canany(['view-export@marketing'])
                        <li><a href="{{ route('marketing.export.index') }}" class="slide-item {{ request()->is('marketing/export') || request()->is('marketing/export/create') || request()->is('marketing/export/*') ?'text-primary' : 'text-white' }}"> Export</a></li>
                        @endcanany
                        @canany(['view-import@marketing'])
                        <li><a href="{{ route('marketing.import.index') }}" class="slide-item {{ request()->is('marketing/import') || request()->is('marketing/import/create') || request()->is('marketing/import/*') ?'text-primary' : 'text-white' }}"> Import</a></li>
                        @endcanany
                        @canany(['view-import@marketing','view-export@marketing'])
                        <li><a href="{{ route('marketing.report.index') }}" class="slide-item {{ request()->is('marketing/report') ?'text-primary' : 'text-white' }}"> Report</a></li>
                        @endcanany
                    </ul>
                    @endcanany
                </li>
                <li class="slide">
                    @canany(['view-export@operation','view-import@operation'])
                    <a class="side-menu__item {{ request()->is('operation/overview') || request()->is('operation/export') || request()->is('operation/export/create') || request()->is('operation/export/*') || request()->is('operation/import') || request()->is('operation/import/create') || request()->is('operation/import/*') || request()->is('operation/report') || request()->is('operation/report/*') ?'bg-primary' : '' }}" style="border-radius: 5px;" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-maximize-2 text-white"></i><span class="side-menu__label text-white">Operation</span><i class="angle fe fe-chevron-right text-white"></i></a>
                    <ul class="slide-menu" style="display: {{ request()->is('operation/overview') || request()->is('operation/export') || request()->is('operation/export/create') || request()->is('operation/export/*') || request()->is('operation/import') || request()->is('operation/import/create') || request()->is('operation/import/*') || request()->is('operation/report') || request()->is('operation/report/*') ?'block' : 'none' }}">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Operation</a></li>
                        @canany(['view-export@operation'])
                        <li><a href="{{ route('operation.export.index') }}" class="slide-item {{ request()->is('operation/export') || request()->is('operation/export/create') || request()->is('operation/export/*') ?'text-primary' : 'text-white' }}"> Export</a></li>
                        @endcanany
                        @canany(['view-import@operation'])
                        <li><a href="{{ route('operation.import.index') }}" class="slide-item {{ request()->is('operation/import') || request()->is('operation/import/create') || request()->is('operation/import/*') ?'text-primary' : 'text-white' }}"> Import</a></li>
                        @endcanany
                        @canany(['view-import@operation','view-export@operation'])
                        <li><a href="{{ route('operation.report.index') }}" class="slide-item {{ request()->is('operation/report') || request()->is('operation/report/*') ?'text-primary' : 'text-white' }}"> Report</a></li>
                        @endcanany
                    </ul>
                    @endcanany
                </li>
                <li class="slide {{ request()->is('realtime-tracking') || request()->is('realtime-tracking/*') ?'bg-primary' : '' }}"  style="border-radius: 5px;">
                    <a href="{{ route('realtime-tracking.index') }}" class="side-menu__item has-link" data-bs-toggle="slide" href=""><i class="side-menu__icon fe fe-send text-white"></i><span class="side-menu__label text-white">Realtime Tracking</span></a>
                </li>
                <li class="slide">
                    @canany([
                        'view-contact@finance','view-account@finance','view-currency@finance','view-tax@finance','view-term@finance',
                        'view-sales_order@finance','view-invoice@finance','view-receive_payment@finance',
                        'view-kas_in@finance','view-kas_out@finance',
                        'view-account_payable@finance','view-payment@finance',
                        'view-exchange_rate@finance',
                        'view-buku_besar@finance','view-jurnal_umum@finance','view-neraca_saldo@finance','view-arus_kas@finance','view-laba_rugi@finance',
                    ])
                    <a class="side-menu__item {{ request()->is('finance/master-data') || request()->is('finance/master-data/*') || request()->is('finance/piutang') || request()->is('finance/piutang/*') || request()->is('finance/kas') || request()->is('finance/kas/*') || request()->is('finance/payments') || request()->is('finance/payments/*') || request()->is('finance/exchange-rate') || request()->is('finance/exchange-rate/*') || request()->is('finance/report-finance') || request()->is('finance/report-finance/*') ?'bg-primary' : '' }}" data-bs-toggle="slide" href="javascript:void(0)" style="border-radius: 5px;"><i class="side-menu__icon fe fe-pie-chart text-white"></i><span class="side-menu__label text-white">Finance</span><i class="angle fe fe-chevron-right text-white"></i></a>
                    <ul class="slide-menu"  style="display: {{ request()->is('finance/master-data') || request()->is('finance/master-data/*') || request()->is('finance/piutang') || request()->is('finance/piutang/*')  || request()->is('finance/kas') || request()->is('finance/kas/*') || request()->is('finance/payments') || request()->is('finance/payments/*') || request()->is('finance/exchange-rate') || request()->is('finance/exchange-rate/*') || request()->is('finance/report-finance') || request()->is('finance/report-finance/*') ? 'block' : 'none' }}">
                        <li class="side-menu-label1 text-white"><a href="javascript:void(0)">Finance</a></li>
                        @canany(['view-contact@finance','view-account@finance','view-currency@finance','view-tax@finance','view-term@finance'])
                        <li><a href="{{ route('finance.master-data.index') }}" class="slide-item {{ request()->is('finance/master-data') || request()->is('finance/master-data/*') ?'text-primary' : 'text-white' }}"> Master Data</a></li>
                        @endcanany
                        @canany(['view-sales_order@finance','view-invoice@finance','view-receive_payment@finance'])
                        <li><a href="{{ route('finance.piutang.index') }}" class="slide-item {{ request()->is('finance/piutang') || request()->is('finance/piutang/*') ?'text-primary' : 'text-white' }}"> Sales</a></li>
                        @endcanany
                        @canany(['view-kas_in@finance','view-kas_out@finance'])
                        <li><a href="{{ route('finance.kas.index') }}" class="slide-item {{ request()->is('finance/kas') || request()->is('finance/kas/*') ?'text-primary' : 'text-white' }}"> Kas</a></li>
                        @endcanany
                        @canany(['view-account_payable@finance','view-payment@finance'])
                        <li><a href="{{ route('finance.payments.index') }}" class="slide-item {{ request()->is('finance/payments') || request()->is('finance/payments/*') ?'text-primary' : 'text-white' }}"> Payments</a></li>
                        @endcanany
                        @canany(['view-exchange_rate@finance'])
                        <li><a href="{{ route('finance.exchange-rate.index') }}" class="slide-item {{ request()->is('finance/exchange-rate') || request()->is('finance/exchange-rate/*') ?'text-primary' : 'text-white' }}"> Exchange Rate</a></li>
                        @endcanany
                        @canany(['view-buku_besar@finance','view-jurnal_umum@finance','view-neraca_saldo@finance','view-arus_kas@finance','view-laba_rugi@finance'])
                        <li><a href="{{ route('finance.report-finance.index') }}" class="slide-item {{ request()->is('finance/report-finance') || request()->is('finance/report-finance/*') ?'text-primary' : 'text-white' }}"> Report</a></li>
                        @endcanany
                    </ul>
                    @endcanany
                </li>

                @canany([
                    'create-role@role','edit-role@role','delete-role@role',
                    'create-user@user','edit-user@user','delete-user@user'
                ])
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('utility/user-role') || request()->is('utility/user-role/create' ) || request()->is('utility/user-role/*' ) || request()->is('utility/user-list') || request()->is('utility/user-list/create') || request()->is('utility/user-list/*') ?'bg-primary' : '' }}" style="border-radius: 5px;" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-cpu text-white"></i><span class="side-menu__label text-white">Utility</span><i class="angle fe fe-chevron-right text-white"></i></a>
                    <ul class="slide-menu"  style="display: {{ request()->is('utility/user-role') || request()->is('utility/user-role/create' ) || request()->is('utility/user-role/*' ) ||  request()->is('utility/user-list') || request()->is('utility/user-list/create') || request()->is('utility/user-list/*') ?'block' : 'none' }}">
                        <li class="side-menu-label1 text-white"><a href="javascript:void(0)">Utility</a></li>
                        @canany([
                            'create-role@role','edit-role@role','delete-role@role'
                        ])
                        <li><a href="{{ route('utility.user-role.index') }}" class="slide-item {{ request()->is('utility/user-role' ) || request()->is('utility/user-role/create' ) || request()->is('utility/user-role/*' ) ?'text-primary' : 'text-white' }}"> User Role</a></li>
                        @endcanany
                        @canany([
                            'create-user@user','edit-user@user','delete-user@user'
                        ])
                        <li><a href="{{ route('utility.user-list.index') }}" class="slide-item {{ request()->is('utility/user-list') || request()->is('utility/user-list/create') || request()->is('utility/user-list/*') ?'text-primary' : 'text-white' }}"> User List</a></li>
                        @endcanany
                    </ul>
                </li>
                @endcanany
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="side-menu__icon fe fe-log-out text-white"></i><span class="side-menu__label text-white">Logout</span></a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
        </div>
    </div>
</div>