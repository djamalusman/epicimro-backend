<aside class="main-sidebar sidebar-dark-danger bg-dark elevation-1 shadow">
    <a href="#" class="brand-link bg-dark" style="border: none;">
        <img src="{{ asset('/') }}dist/img/logo-red.png" alt="logo" class="brand-image">
        <br />
    </a>
    <div class="sidebar">
        <nav class="">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="{{ route('dashboard') }}" class="nav-link p-3 <?= (str_contains($title_page, 'Dashboard'))  ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <label> Pages </label>
                @foreach($menu as $menuItem)
                @if($menuItem->is_parent == 'Y' and $menuItem->is_cms != 'N')
                <li class="nav-item  <?= ((explode(' | ', $title_page)[0] == $menuItem->menu_name))  ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link p-3 <?= ((explode(' | ', $title_page)[0] == $menuItem->menu_name))  ? 'active' : ''; ?>">
                       <img src="{{asset('/')}}images/menu-icon.svg" alt="" srcset="">
                        <p>
                           <?= $menuItem->menu_name ?>

                            <i class="right fas fa-angle-left p-2"></i>
                        </p>
                    </a>
                    @foreach($menu as $menuItemChild)
                    @if(($menuItemChild->is_parent == 'N' and $menuItemChild->is_cms != 'N') and ($menuItemChild->parent_id == $menuItem->id))
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route($menuItemChild->menu_link, base64_encode($menuItemChild->id))}}" class="nav-link text-sm 
                  <?= (explode(' | ', $title_page)[1] == $menuItemChild->menu_name)  ? 'active' : ''; ?>
              ">
                                <i class="far fa-circle nav-icon"></i>

                                <?= $menuItemChild->menu_name ?>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @endforeach
                </li>
                @elseif($menuItem->is_parent == '-' and $menuItem->is_cms != 'N')
                <li class="nav-item  <?= (str_contains($title_page, $menuItem->menu_name))  ? 'menu-open' : ''; ?>">
                    <a href="{{ route($menuItem->menu_link, ['id' => base64_encode($menuItem->id)]) }}" class="nav-link p-3 <?= (str_contains($title_page, $menuItem->menu_name))  ? 'active' : ''; ?>">
                       <img src="{{asset('/')}}images/menu-icon.svg" alt="" srcset="">
                        <p>
                            <?= $menuItem->menu_name ?>
                        </p>
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>