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

$user_permissions = $user_permissions ?? [];

$simpleMenuPermissions = [
    'dashboard' => ['menu_id' => 1, 'sous_menu_id' => 1],
    'facilitateur' => ['menu_id' => 2, 'sous_menu_id' => 2],
    'superviseur' => ['menu_id' => 3, 'sous_menu_id' => 3],
    'operateur' => ['menu_id' => 4, 'sous_menu_id' => 4],
];

$showParam = hasPermission($user_permissions, 6, 6, 1);
?>
<style>
    .sidebar-logout-wrap {
        padding: 18px 16px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: 18px;
    }

    .sidebar-logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 12px 14px;
        border-radius: 14px;
        background: linear-gradient(135deg, #d94b64 0%, #b8324c 100%);
        color: #fff !important;
        font-weight: 600;
        letter-spacing: 0.2px;
        box-shadow: 0 12px 24px rgba(185, 50, 76, 0.28);
        transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
    }

    .sidebar-logout-btn:hover,
    .sidebar-logout-btn:focus {
        color: #fff !important;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(185, 50, 76, 0.34);
        opacity: 0.98;
    }

    .sidebar-logout-btn i {
        font-size: 18px;
    }
</style>
<div class="left side-menu">
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 706px;">
        <div class="sidebar-inner slimscrollleft" style="overflow: hidden; width: auto; height: 706px;">
            <div class="user-details">
                <div class="pull-left">
                    <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="" class="thumb-md rounded-circle">
                </div>
                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" _msttexthash="88855" _msthash="17">
                            John Doe
                        </a>
                        <ul class="dropdown-menu" _msthidden="4">
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-face-unlock mr-2"></i><font _mstmutation="1" _msttexthash="94315" _msthidden="1" _msthash="18"> Profile</font><div class="ripple-wrapper"></div></a></li>
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-settings mr-2"></i><font _mstmutation="1" _msttexthash="117221" _msthidden="1" _msthash="19"> Settings</font></a></li>
                            <li _msthidden="1"><a href="javascript:void(0)" class="dropdown-item" _msthidden="1"><i class="md md-lock mr-2"></i><font _mstmutation="1" _msttexthash="156065" _msthidden="1" _msthash="20"> Lock screen</font></a></li>
                            <li><a href="<?= base_url('logout') ?>" class="dropdown-item"><i class="md md-settings-power mr-2"></i> Logout</a></li>
                        </ul>
                    </div>

                    <p class="text-muted m-0"><?= session()->get('role') ?? 'InvitÃ©' ?></p>
                </div>
            </div>

            <div id="sidebar-menu">
                <ul>
                    <?php if (hasPermission($user_permissions, $simpleMenuPermissions['dashboard']['menu_id'], $simpleMenuPermissions['dashboard']['sous_menu_id'], 1)): ?>
                    <li class="<?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= base_url('/dashboard') ?>" class="waves-effect">
                            <i class="md md-home"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (hasPermission($user_permissions, $simpleMenuPermissions['facilitateur']['menu_id'], $simpleMenuPermissions['facilitateur']['sous_menu_id'], 1)): ?>
                    <li class="<?= (uri_string() == 'facilitateur') ? 'active' : '' ?>">
                        <a href="<?= base_url('facilitateur') ?>" class="waves-effect">
                            <i class="md md-person"></i>
                            <span>Facilitateur</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (hasPermission($user_permissions, $simpleMenuPermissions['superviseur']['menu_id'], $simpleMenuPermissions['superviseur']['sous_menu_id'], 1)): ?>
                    <li class="<?= (uri_string() == 'superviseur') ? 'active' : '' ?>">
                        <a href="<?= base_url('superviseur') ?>" class="waves-effect">
                            <i class="md md-people"></i>
                            <span>Superviseur</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (hasPermission($user_permissions, $simpleMenuPermissions['operateur']['menu_id'], $simpleMenuPermissions['operateur']['sous_menu_id'], 1)): ?>
                    <li class="<?= (uri_string() == 'operateur') ? 'active' : '' ?>">
                        <a href="<?= base_url('operateur') ?>" class="waves-effect">
                            <i class="md md-laptop"></i>
                            <span>Operateur</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ($showParam): ?>
                    <li class="has_sub <?= (in_array(uri_string(), ['users', 'profils', 'tables-editable'])) ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="waves-effect">
                            <i class="md md-settings"></i>
                            <span>Parametres</span>
                            <span class="pull-right"><i class="md md-add"></i></span>
                        </a>

                        <ul class="list-unstyled">
                            <?php if ($showParam): ?>
                            <li>
                                <a href="<?= base_url('users') ?>">Gestion users</a>
                            </li>
                            <?php endif; ?>

                            <?php if ($showParam): ?>
                            <li>
                                <a href="<?= base_url('profils') ?>">Gestion profil</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <div class="clearfix"></div>
                </ul>
            </div>

            <div class="sidebar-logout-wrap">
                <a href="<?= base_url('logout') ?>" class="sidebar-logout-btn">
                    <i class="md md-settings-power"></i>
                    <span>Deconnexion</span>
                </a>
            </div>
        </div>
        <div class="slimScrollBar" style="background: rgb(122, 134, 143); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 680.923px; visibility: visible;"></div>
        <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
    </div>
</div>
