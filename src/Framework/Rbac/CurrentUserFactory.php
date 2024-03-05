<?php declare(strict_type=1);

namespace SocialNews\Framework\Rbac;

interface CurrentUserFactory
{
    public function create(): User;
}

?>