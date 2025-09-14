<aside :class="sidebarToggle ? 'translate-x-0 xl:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed top-0 left-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-auto border-r border-gray-200 bg-white px-5 transition-all duration-300 xl:static xl:translate-x-0 dark:border-gray-800 dark:bg-black"
    @click.outside="sidebarToggle = false">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="sidebar-header flex items-center gap-2 pt-8 pb-7">
        <a href="{{ route('dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="{{ asset('images/logo/logo.svg') }}" alt="Lovely Boutique" />
                <img class="hidden dark:block" src="{{ asset('images/logo/logo-dark.svg') }}" alt="Lovely Boutique" />
            </span>

            <img class="logo-icon" :class="sidebarToggle ? 'xl:block' : 'hidden'" src="{{ asset('images/logo/logo-icon.svg') }}"
                alt="Logo" />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->
    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs leading-[20px] text-gray-400 uppercase">
                    <span class="menu-group-title" :class="sidebarToggle ? 'xl:hidden' : ''">
                        LOVELY BOUTIQUE
                    </span>

                    <svg :class="sidebarToggle ? 'xl:block hidden' : 'hidden'"
                        class="menu-group-icon mx-auto fill-current" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="currentColor" />
                    </svg>
                </h3>

                <ul class="mb-6 flex flex-col gap-1">
                    <!-- Menu Item Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                            class="menu-item group"
                            :class="(selected === 'Dashboard') && (page === 'dashboard') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Dashboard') && (page === 'dashboard') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" fill="currentColor"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                🏠 Tableau de bord
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Articles -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Articles' ? '':'Articles')"
                            class="menu-item group"
                            :class="(selected === 'Articles') || (page === 'articles' || page === 'addArticle' ||
                                page === 'categories' || page === 'addCategory') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Articles') || (page === 'articles' || page === 'addArticle') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 6H4c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 10H4V8h16v8zM6 10h2v2H6zm0 3h8v1H6zm10-3h2v2h-2z" fill="currentColor" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                📦 Gestion des Articles
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Articles') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Articles') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('articles.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'articles' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Liste des articles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('articles.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addArticle' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Ajouter un article
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('categories.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'categories' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Liste des catégories
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('categories.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addCategory' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Ajouter une catégorie
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Articles -->

                    <!-- Menu Item Stock -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Stock' ? '':'Stock')"
                            class="menu-item group"
                            :class="(selected === 'Stock') || (page === 'approvisionnement' || page === 'newLot' ||
                                page === 'historiqueLots' || page === 'stockActuel' || page === 'alertesStock'
                            ) ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Stock') || (page === 'approvisionnement' || page === 'newLot') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                🏬 Stock & Approvisionnement
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Stock') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Stock') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('lots.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'newLot' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Nouveau lot (entrée en stock)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('lots.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'historiqueLots' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Historique des lots
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('stock.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'stockActuel' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Vue stock actuel
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('stock.alerts') }}" class="menu-dropdown-item group"
                                        :class="page === 'alertesStock' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Alertes stock faible
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Stock -->

                    <!-- Menu Item Ventes -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Ventes' ? '':'Ventes')"
                            class="menu-item group"
                            :class="(selected === 'Ventes') || (page === 'newSale' || page === 'invoices' || page === 'searchInvoice' || page === 'payments') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Ventes') || (page === 'newSale' || page === 'invoices') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.31641 4H3.49696C4.24468 4 4.87822 4.55068 4.98234 5.29112L5.13429 6.37161M5.13429 6.37161L6.23641 14.2089C6.34053 14.9493 6.97407 15.5 7.72179 15.5L17.0833 15.5C17.6803 15.5 18.2205 15.146 18.4587 14.5986L21.126 8.47023C21.5572 7.4795 20.8312 6.37161 19.7507 6.37161H5.13429Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7.7832 19.5H7.7932M16.3203 19.5H16.3303" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                🛒 Ventes
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Ventes') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Ventes') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('sales.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'newSale' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Nouvelle vente
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invoices.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'invoices' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Liste des factures
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invoices.search') }}" class="menu-dropdown-item group"
                                        :class="page === 'searchInvoice' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Rechercher une facture
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('payments.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'payments' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Règlements clients
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Ventes -->

                    <!-- Menu Item Clients -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Clients' ? '':'Clients')"
                            class="menu-item group"
                            :class="(selected === 'Clients') || (page === 'clients' || page === 'addClient' || page === 'clientHistory') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Clients') || (page === 'clients' || page === 'addClient') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="m22 21-3-3m0 0a2 2 0 1 1-2.83-2.83A2 2 0 0 1 19 18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                👥 Clients
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Clients') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Clients') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('clients.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'clients' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Liste des clients
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('clients.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addClient' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Ajouter un client
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('clients.history') }}" class="menu-dropdown-item group"
                                        :class="page === 'clientHistory' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Historique des achats
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Clients -->

                    <!-- Menu Item Rapports -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Rapports' ? '':'Rapports')"
                            class="menu-item group"
                            :class="(selected === 'Rapports') || (page === 'salesReports' || page === 'financialReports' || page === 'stockReports' || page === 'exports') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Rapports') || (page === 'salesReports' || page === 'financialReports') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 3v18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="m19 9-5 5-4-4-3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                📊 Rapports
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Rapports') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Rapports') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('reports.sales') }}" class="menu-dropdown-item group"
                                        :class="page === 'salesReports' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Rapports des ventes
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.financial') }}" class="menu-dropdown-item group"
                                        :class="page === 'financialReports' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Rapports financiers
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.stock') }}" class="menu-dropdown-item group"
                                        :class="page === 'stockReports' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Rapports de stock
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.exports') }}" class="menu-dropdown-item group"
                                        :class="page === 'exports' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Exportation (Excel/PDF)
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Rapports -->

                    <!-- Menu Item Utilisateurs -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Utilisateurs' ? '':'Utilisateurs')"
                            class="menu-item group"
                            :class="(selected === 'Utilisateurs') || (page === 'users' || page === 'addUser' ||
                                page === 'roles' || page === 'permissions') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Utilisateurs') || (page === 'users' || page === 'addUser' ||
                                page === 'roles' || page === 'permissions') ? 'menu-item-icon-active' :
                            'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="m22 21-3-3m0 0a2 2 0 1 1-2.83-2.83A2 2 0 0 1 19 18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                🔐 Utilisateurs & Sécurité
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Utilisateurs') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Utilisateurs') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('users.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'users' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Liste des utilisateurs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('users.create') }}" class="menu-dropdown-item group"
                                        :class="page === 'addUser' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Ajouter un utilisateur
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('roles.index') }}" class="menu-dropdown-item group"
                                        :class="page === 'roles' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Rôles & Permissions
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Utilisateurs -->

                    <!-- Menu Item Paramètres -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Parametres' ? '':'Parametres')"
                            class="menu-item group"
                            :class="(selected === 'Parametres') || (page === 'generalSettings' || page === 'billingSettings' ||
                                page === 'citiesSettings' || page === 'taxSettings') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Parametres') || (page === 'generalSettings' || page === 'billingSettings') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'xl:hidden' : ''">
                                ⚙️ Paramètres
                            </span>

                            <svg class="menu-item-arrow"
                                :class="[(selected === 'Parametres') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'xl:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="translate transform overflow-hidden"
                            :class="(selected === 'Parametres') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'xl:hidden' : 'flex'"
                                class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                                <li>
                                    <a href="{{ route('settings.general') }}" class="menu-dropdown-item group"
                                        :class="page === 'generalSettings' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Paramètres généraux
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('settings.billing') }}" class="menu-dropdown-item group"
                                        :class="page === 'billingSettings' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Paramètres de facturation
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('settings.cities') }}" class="menu-dropdown-item group"
                                        :class="page === 'citiesSettings' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Villes & Taxes
                                    </a>
                                </li>
                </ul>
            </div>
            <!-- Menu Group -->
        </nav>
        <!-- Sidebar Menu -->
    </div>
</aside>
