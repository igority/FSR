<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if (Auth::user()->profile_image_id == null)
          <img src="{{url('img/avatar5.png')}}" class="img-rounded" alt="User Image">
        @elseif (FSR\File::find(Auth::user()->profile_image_id)->filename == null)
          <img src="{{url('img/avatar5.png')}}" class="img-rounded" alt="User Image">
        @else
          <img src="{{FSR\Custom\Methods::getFileUrl(FSR\File::find(Auth::user()->profile_image_id)->filename)}}" class="img-rounded" alt="User Image">
        @endif
      </div>
      <div class="pull-left info">
        <p>{{Auth::user()->email}}</p>
        <p><small>{{Auth::user()->organization->name}}</small></p>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">


      {{-- <li>
        <a href="/{{Auth::user()->type()}}/home">
          <i class="fa fa-dashboard"></i> <span>Почетна</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li> --}}

      <li class="header">ДОНАЦИИ</li>

      @if (Auth::user()->type() == 'donor')
        <li>
          <a href="/{{Auth::user()->type()}}/my_active_listings">
            <i class="fa fa-bookmark"></i> <span>Мои донации</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-blue">2</small>
            </span>
          </a>
        </li>


        <li>
          <a href="/{{Auth::user()->type()}}/new_listing">
            <i class="fa fa-plus-circle"></i> <span>Додади нова донација</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
      @elseif (Auth::user()->type() == 'cso')
        <li>
          <a href="/{{Auth::user()->type()}}/accepted_listings">
            <i class="fa fa-bookmark"></i> <span>Прифатени донации</span>
              @yield('cso_accepted_listings_no')
          </a>
        </li>

        <li>
          <a href="/{{Auth::user()->type()}}/active_listings">
            <i class="fa fa-cutlery"></i> <span>Активни донации</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
      @endif


      <li class="header">ИЗВЕСТУВАЊА</li>

      <li>
        <a href="#">
          <i class="fa fa-commenting"></i> <span>Пораки</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-green">4</small>
          </span>
        </a>
      </li>

      <li>
        <a href="#">
          <i class="fa fa-bell"></i> <span>Нови известувања</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-orange">10</small>
          </span>
        </a>
      </li>

      @if (Auth::user()->type() == 'cso')
        <li class="treeview">
            <a href="#">
              <i class="fa fa-universal-access"></i> <span>Волонтери</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="/{{Auth::user()->type()}}/volunteers">
                  <i class="fa fa-users"></i> <span>Преглед</span>
                  <span class="pull-right-container">
                  </span>
                </a>
              </li>

              <li>
                <a href="/{{Auth::user()->type()}}/volunteers/new">
                  <i class="fa fa-user-plus"></i> <span>Додади нов</span>
                  <span class="pull-right-container">
                  </span>
                </a>
              </li>

            </ul>
        </li>
      @endif

      <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Подесувања</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="/{{Auth::user()->type()}}/profile">
                <i class="fa fa-user-circle"></i> <span>Мој профил</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="#">
                <i class="fa fa-shield"></i> <span>Безбедност</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="#">
                <i class="fa fa-exchange"></i> <span>Подеси известувања</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>
          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Извештаи</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="#">
                <i class="fa fa-star-half-full"></i> <span>Рејтинг</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green">4.74</small>
                </span>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-th"></i> <span>Статистики</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>


      <li>
        <a href="#">
          <i class="fa fa-sign-out"></i> <span>Одјави се</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
