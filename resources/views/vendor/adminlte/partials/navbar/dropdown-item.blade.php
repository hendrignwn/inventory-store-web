@inject('menuItemHelper', \JeroenNoten\LaravelAdminLte\Helpers\MenuItemHelper)

@if ($menuItemHelper->isSubmenu($item))

    {{-- Dropdown submenu --}}
    @include('adminlte::partials.navbar.dropdown-item-type-submenu')

@elseif ($menuItemHelper->isLink($item))

    {{-- Dropdown link --}}
    @include('adminlte::partials.navbar.dropdown-item-type-link')

@endif
