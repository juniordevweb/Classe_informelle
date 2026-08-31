<?php

function hasPermission($permissions, $menu_id, $sous_menu_id = 0, $perm_id = 1)
{
    foreach ($permissions as $p) {
        $dbSousMenu = ($p['sous_menu_id'] == 0)
            ? $p['menu_id']
            : $p['sous_menu_id'];

        if (
            $p['menu_id'] == $menu_id &&
            $dbSousMenu == $sous_menu_id &&
            $p['permission_id'] == $perm_id
        ) {
            return true;
        }
    }

    return false;
}


/**
 * Vérifie si l'utilisateur possède au moins une des permissions fournies
 * $perm_ids peut être un tableau d'IDs de permission (ex: [1,2,3])
 */
function hasAnyPermission($permissions, $menu_id, $sous_menu_id = 0, $perm_ids = [1])
{
    foreach ($permissions as $p) {
        $dbSousMenu = ($p['sous_menu_id'] == 0)
            ? $p['menu_id']
            : $p['sous_menu_id'];

        if (
            $p['menu_id'] == $menu_id &&
            $dbSousMenu == $sous_menu_id &&
            in_array($p['permission_id'], $perm_ids)
        ) {
            return true;
        }
    }

    return false;
}

$user_permissions = $user_permissions ?? [];
$fullName = trim(
    implode(' ', array_filter([
        session()->get('prenom'),
        session()->get('nom'),
    ]))
);

if ($fullName === '') {
    $fullName = 'Utilisateur';
}

$sidebarMenus = getSidebarMenus($user_permissions);

$normalizeLabel = static function (?string $value): string {
    $value = trim((string) $value);
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

    if ($transliterated !== false) {
        $value = $transliterated;
    }

    return strtolower($value);
};

$isParametersMenu = static function (array $menu) use ($normalizeLabel): bool {
    return ((int) ($menu['id'] ?? 0) === 6)
        || $normalizeLabel($menu['nom_menu'] ?? '') === 'parametres';
};

$directMenuRoutes = [
    1 => 'dashboard',
    2 => 'facilitateur',
    3 => 'superviseur',
    4 => 'operateur',
    5 => 'apprenant',
    7 => 'classes',
    8 => 'structures',
];
?>
<style>
    .sidebar-logout-wrap {
        position: sticky;
        bottom: 0;
        padding: 8px 0 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: #ffffff;
        z-index: 10;
    }

    .sidebar-logout-btn {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 15px;
        width: 100%;
        padding: 15px 25px;
        border-radius: 0;
        background: transparent !important;
        color: #444444 !important;
        font-weight: normal;
        letter-spacing: normal;
        box-shadow: none !important;
        transition: background 0.18s ease, color 0.18s ease;
    }

    .sidebar-logout-btn:hover,
    .sidebar-logout-btn:focus {
        color: #2b4049 !important;
        text-decoration: none;
        background: #f3f3f3 !important;
    }

    .sidebar-logout-btn i {
        font-size: 18px;
        line-height: 17px;
        margin-left: 3px;
        text-align: center;
        width: 20px;
    }

    .sidebar-inner {
        position: relative;
        height: 100vh !important;
        max-height: 100vh !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        padding-bottom: 58px;
        scrollbar-width: thin;
    }

    /* Les hauteurs/overflow inline du plugin slimScroll empêchaient le défilement. */
    .left.side-menu .slimScrollDiv {
        height: 100vh !important;
        max-height: 100vh !important;
        overflow: visible !important;
    }

    .left.side-menu .sidebar-inner {
        width: 100% !important;
    }

    .left.side-menu .slimScrollBar,
    .left.side-menu .slimScrollRail {
        display: none !important;
    }

    #wrapper.enlarged .sidebar-logout-btn {
        padding-left: 10px;
        padding-right: 10px;
    }

    #wrapper.enlarged .sidebar-logout-btn span {
        display: none;
    }

    .submenu-toggle {
        cursor: pointer;
    }
</style>
<div class="left side-menu">
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 706px;">
        <div class="sidebar-inner slimscrollleft" style="overflow: hidden; width: auto; height: 706px;">
            <div class="user-details">
                <div class="pull-left">
                    <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt=""
                        class="thumb-md rounded-circle">
                </div>
                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" _msttexthash="88855" _msthash="17">
                            <?= esc($fullName) ?>
                        </a>
                        <ul class="dropdown-menu" _msthidden="4">
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i
                                        class="md md-face-unlock mr-2"></i>
                                    <font _mstmutation="1" _msttexthash="94315" _msthidden="1" _msthash="18"> Profile
                                    </font>
                                    <div class="ripple-wrapper"></div>
                                </a></li>
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i
                                        class="md md-settings mr-2"></i>
                                    <font _mstmutation="1" _msttexthash="117221" _msthidden="1" _msthash="19"> Settings
                                    </font>
                                </a></li>
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i
                                        class="md md-lock mr-2"></i>
                                    <font _mstmutation="1" _msttexthash="156065" _msthidden="1" _msthash="20"> Lock
                                        screen</font>
                                </a></li>
                            <li>
                                <form method="post" action="<?= base_url('logout') ?>" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="md md-settings-power mr-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <p class="text-muted m-0"><?= session()->get('role') ?? 'InvitÃ©' ?></p>
                </div>
            </div>

            <div id="sidebar-menu">
                <ul>
                    <?php
                    $currentRoute = trim(uri_string(), '/');
                    foreach ($sidebarMenus as $menu):
                        $menuId = (int) ($menu['id'] ?? 0);
                        $menuRoute = trim((string) ($menu['url'] ?? ''), '/');
                        $children = $menu['children'] ?? [];
                        $resolvedRoute = $directMenuRoutes[$menuId] ?? $menuRoute;
                        $menuHref = $resolvedRoute !== '' ? base_url($resolvedRoute) : '';
                        $menuActive = $currentRoute !== '' && $resolvedRoute !== '' && $currentRoute === $resolvedRoute;
                        foreach ($children as $child) {
                            $childRoute = trim((string) ($child['url'] ?? ''), '/');
                            if ($childRoute !== '' && $childRoute === $currentRoute) {
                                $menuActive = true;
                                break;
                            }
                        }

                        $isDropdownMenu = $isParametersMenu($menu) && ! empty($children);
                    ?>
                        <?php if ($isDropdownMenu): ?>
                            <li class="has_sub <?= $menuActive ? 'active' : '' ?>">
                                <a href="javascript:void(0);" class="waves-effect sidebar-toggle-link <?= $menuActive ? 'active subdrop' : '' ?>">
                                    <i class="<?= esc(sidebarIconClass($menu['icone'] ?? null)) ?>"></i>
                                    <span><?= esc($menu['nom_menu'] ?? '') ?></span>
                                    <span class="pull-right submenu-toggle" role="button" tabindex="0" aria-label="Afficher ou masquer les sous-menus">
                                        <i class="md md-add"></i>
                                    </span>
                                </a>

                                <ul class="list-unstyled">
                                    <?php foreach ($children as $child): ?>
                                        <?php
                                        $childRoute = trim((string) ($child['url'] ?? ''), '/');
                                        $childActive = $currentRoute !== '' && $childRoute !== '' && $childRoute === $currentRoute;
                                        ?>
                                        <li class="<?= $childActive ? 'active' : '' ?>">
                                            <a href="<?= base_url($childRoute) ?>">
                                                <i class="<?= esc(sidebarIconClass($child['icon'] ?? null, 'md md-circle')) ?>"></i>
                                                <?= esc($child['nom_sous_menu'] ?? '') ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php elseif ($menuHref !== ''): ?>
                            <li class="<?= $menuActive ? 'active' : '' ?>">
                                <a href="<?= esc($menuHref) ?>" class="waves-effect">
                                    <i class="<?= esc(sidebarIconClass($menu['icone'] ?? null)) ?>"></i>
                                    <span><?= esc($menu['nom_menu'] ?? '') ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <div class="clearfix"></div>
                </ul>
            </div>

            <div class="sidebar-logout-wrap">
                <form method="post" action="<?= base_url('logout') ?>" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="sidebar-logout-btn border-0">
                        <i class="md md-settings-power"></i>
                        <span>Deconnexion</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="slimScrollBar"
            style="background: rgb(122, 134, 143); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 680.923px; visibility: visible;">
        </div>
        <div class="slimScrollRail"
            style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">
        </div>
    </div>
</div>
