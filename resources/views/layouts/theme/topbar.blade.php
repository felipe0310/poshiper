<div class="topbar-nav header navbar" role="banner">
    <nav id="topbar">
        <ul class="list-unstyled menu-categories" id="topAccordion">

            <li class="menu single-menu active">
                <a href="#starter-kit" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle autodroprown">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Ventas</span>
                    </div>
                </a>
            </li>

            <li class="menu single-menu">
                <a href="#menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle autodroprown">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Clientes</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <ul class="collapse submenu list-unstyled" id="menu1" data-parent="#topAccordion">
                    <li>
                        <a href="javascript:void(0);"> Submenu 1 </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"> Submenu 2 </a>
                    </li>
                </ul>
            </li>


            <li class="menu single-menu">
                <a href="#menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle autodroprown">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-box">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span>Bodega</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <ul class="collapse submenu list-unstyled" id="menu2" data-parent="#topAccordion">
                    <li>
                        <a href="{{url('/productos')}}"> Productos </a>
                    </li>
                    <li>
                        <a href="{{url('/inventarios')}}"> Inventarios </a>
                    </li>                   
                    <li>
                        <a href="{{url('/categorias')}}"> Categorias </a>
                    </li>
                    <li>
                        <a href="{{url('/proveedores')}}"> Proveedores </a>
                    </li>
                    <li>
                        <a href="{{url('/bodega')}}"> Bodega </a>
                    </li>
                    <li>
                        <a href="{{url('/compras')}}"> Compras </a>
                    </li>
                    
                </ul>
            </li>
            <li class="menu single-menu">
                <a href="#menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle autodroprown">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Administración</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <ul class="collapse submenu list-unstyled" id="menu1" data-parent="#topAccordion">
                    <li>
                        <a href="{{url('/empresas')}}"> Empresas </a>
                    </li>
                    <li>
                        <a href="{{url('/almacenes')}}"> Almacenes </a>
                    </li>
                    <li>
                        <a href="{{url('/clientes')}}"> Clientes </a>
                    </li>
                    <li>
                        <a href="{{url('/docAlmacenes')}}"> Doc. Almacenes </a>
                    </li>
                    <li>
                        <a href="{{url('/empleados')}}"> Empleados </a>
                    </li>                     
                </ul>
            </li>
        </ul>
    </nav>
</div>
