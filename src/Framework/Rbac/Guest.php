<?php declare(strict_type=1);

namespace SocialNews\Framework\Rbac;

final class Guest implements User
{
    public function hasPermission(Permission $permission): bool
    {
        return false;
    }
}

?>