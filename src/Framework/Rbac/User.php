<?php declare(strict_type=1);

namespace SocialNews\Framework\Rbac;

interface User
{
    public function hasPermission(Permission $permission): bool;
}

?>