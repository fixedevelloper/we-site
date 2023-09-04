<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{url('dashboard')}}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
            @if(auth()->user()->user_type==1)
         <li class="nav-item nav-category">TRANSACTIONS</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#payment" aria-expanded="false" aria-controls="payment">
              <i class="menu-icon mdi mdi-currency-usd"></i>
              <span class="menu-title">Payments</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="payment">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('payements')}}">Payments</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('paymentlinks')}}">Payement Links</a></li>
              </ul>
            </div>
          </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="menu-icon mdi mdi-transfer-down"></i>
                    <span class="menu-title">Transfers</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{route('transferts')}}">Transfers</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{route('addbanktransfert')}}">Create a bank transfer</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{route('addtransfert')}}">Create a mobile transfer</a></li>
                  </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('balances')}}">
                    <i class="mdi mdi-white-balance-auto menu-icon"></i>
                    <span class="menu-title">Balances</span>
                </a>
            </li>
            <li class="nav-item nav-category">SETTINGS</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#setting" aria-expanded="false" aria-controls="ui-basic">
                    <i class="menu-icon mdi mdi-console-network"></i>
                    <span class="menu-title">Merchants</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="setting">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{route('merchants')}}">List</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{route('addmerchant')}}">New</a></li>
                    </ul>
                </div>
            </li>
                <li class="nav-item nav-category">API DOCUMENTATION</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('documentation')}}" target="_blank">
                        <i class="mdi mdi-file menu-icon"></i>
                        <span class="menu-title">Documentation</span>
                    </a>
                </li>

            @endif
            @if(auth()->user()->user_type==0)
                <li class="nav-item">
                    <a class="nav-link" href="{{route('alltransferts')}}">
                        <i class="mdi mdi-bank-transfer menu-icon"></i>
                        <span class="menu-title">Transactions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('customers')}}">
                        <i class="mdi mdi-account-multiple menu-icon"></i>
                        <span class="menu-title">Customers</span>
                    </a>
                </li>
            <li class="nav-item nav-category">ADMINISTRATION</li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('currencies')}}">
                        <i class="mdi mdi-gauge-low menu-icon"></i>
                        <span class="menu-title">Currencies</span>
                    </a>
                </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('zones')}}">
                    <i class="mdi mdi-flag-checkered menu-icon"></i>
                    <span class="menu-title">Zones</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('countries')}}">
                    <i class="mdi mdi-globe-model menu-icon"></i>
                    <span class="menu-title">Countries</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('operators')}}">
                    <i class="mdi mdi-book-open menu-icon"></i>
                    <span class="menu-title">Operators</span>
                </a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('partenaires')}}">
                        <i class="mdi mdi-access-point-network menu-icon"></i>
                        <span class="menu-title">Partenaires</span>
                    </a>
                </li>
            @endif
        </ul>
      </nav>
