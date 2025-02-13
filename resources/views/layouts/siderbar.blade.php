<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-item {{ $menu == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                {{-- <li class="nav-item {{ $menu == 'interface' ? 'active' : ''  }}">
                    <a href="{{ route('interface.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Interface</p>
                    </a>
                </li> --}}
                <li class="nav-item {{ $menu == 'keloladata' ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#keloladata">
                        <i class="fas fa-server"></i>
                        <p>Kelola Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="keloladata">
                        <ul class="nav nav-collapse">
                            <li class="{{ $submenu == 'pelanggan' ? 'active' : '' }}">
                                <a href="{{ route('pelanggan.index') }}">
                                    <span class="sub-item ">Data Pelanggan</span>
                                </a>
                            </li>
                            <li class="{{ $submenu == 'router' ? 'active' : '' }}">
                                <a href="{{ route('router.index') }}">
                                    <span class="sub-item ">Data Router</span>
                                </a>
                            </li>
                            <li class="{{ $submenu == 'paket' ? 'active' : '' }}">
                                <a href="{{ route('package.index') }}">
                                    <span class="sub-item ">Data Paket</span>
                                </a>
                            </li>
                            <li class="{{ $submenu == 'notifikasi' ? 'active' : '' }}">
                                <a href="{{ route('notifikasi.index') }}">
                                    <span class="sub-item ">Data Notifikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </li>
                <li class="nav-item {{ $menu == 'transaksi' ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#transaksi">
                        <i class="fas fa-money-bill"></i>
                        <p>Transaksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="transaksi">
                        <ul class="nav nav-collapse">
                            <li class="{{ $submenu == 'pembayaran' ? 'active' : '' }}">
                                <a href="{{ route('pembayaran.index') }}">
                                    <span class="sub-item ">Pembayaran Pelanggan</span>
                                </a>
                            </li>


                        </ul>
                    </div>

                </li>

                <li class="nav-item {{ $menu == 'laporan' ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#laporan">
                        <i class="fas fa-clipboard"></i>
                        <p>Laporan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="laporan">
                        <ul class="nav nav-collapse">
                          
                            <li class="{{ $submenu == 'lunas' ? 'active' : '' }}">
                                <a href="{{ route('pelanggan.lunas') }}">
                                    <span class="sub-item">Pelanggan Lunas</span>
                                </a>
                            </li>
                            <li class="{{ $submenu == 'belumlunas' ? 'active' : '' }}">
                                <a href="{{ route('pelanggan.belum_lunas') }}">
                                    <span class="sub-item ">Pelanggan Belum Lunas</span>
                                </a>
                            </li>
                </li>



            </ul>
        </div>

        </li>
        {{-- <li class="nav-item {{ $menu == 'pppoe' ? 'active' : ''  }}">
                    <a data-toggle="collapse" href="#base">
                        <i class="fas fa-rocket"></i>
                        <p>PPPoE</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('pppoe.secret') }}">
                                    <span class="sub-item {{ $submenu == 'secret' ? 'active' : ''  }}">PPPoE Secret</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pppoe.active') }}">
                                    <span class="sub-item {{ $submenu == 's-active' ? 'active' : ''  }}">PPPoE Active</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </li> --}}
        {{-- <li class="nav-item {{ $menu == 'hotspot' ? 'active' : ''  }}">
                    <a data-toggle="collapse" href="#base1">
                        <i class="fas fa-wifi"></i>
                        <p>Hotspot</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base1">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('hotspot.users') }}">
                                    <span class="sub-item {{ $submenu == 'user' ? 'active' : ''  }}">Hotspot Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hotspot.active') }}">
                                    <span class="sub-item {{ $submenu == 'u-active' ? 'active' : ''  }}">Hotspot Users Active</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
        {{-- <li class="nav-item {{ $menu == 'report' ? 'active' : ''  }}">
                    <a data-toggle="collapse" href="#base3">
                        <i class="fas fa-paste"></i>
                        <p>Report</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base3">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('report-up.index') }}">
                                    <span class="sub-item {{ $submenu == 'traffic-up' ? 'active' : ''  }}">Report Traffic UP</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('search.report') }}">
                                    <span class="sub-item {{ $submenu == 'search-traffic' ? 'active' : ''  }}">Search Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
        {{-- <li class="nav-item {{ $menu == 'useractive' ? 'active' : ''  }}">
                    <a href="{{ route('user.index') }}">
                        <i class="fas fa-user-alt"></i>
                        <p>User Mikrotik Active</p>
                    </a>
                </li> --}}
        </ul>
    </div>
</div>
</div>
