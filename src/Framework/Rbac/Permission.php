<?php

namespace SocialNews\Framework\Rbac;

abstract class Permission
{
    public function equals(Permission $permission): bool
    {
        return (get_class() === get_class($permission));
    }
}

?>