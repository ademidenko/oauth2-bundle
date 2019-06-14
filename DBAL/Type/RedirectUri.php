<?php

declare(strict_types=1);

namespace Trikoder\Bundle\OAuth2Bundle\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri as RedirectUriModel;

final class RedirectUri extends ImplodedArray
{
    /**
     * @var string
     */
    private const NAME = 'oauth2_redirect_uri';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function convertDatabaseValues(array $values): array
    {
        foreach ($values as &$value) {
            $value = new RedirectUriModel($value);
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
