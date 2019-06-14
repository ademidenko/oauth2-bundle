<?php

namespace Trikoder\Bundle\OAuth2Bundle\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Авторизация по штрих-коду
 */
class BarcodeGrant extends PasswordGrant
{
    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface $client
     *
     * @return UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $barcode = $this->getRequestParameter('barcode', $request);
        if (!is_string($barcode) || mb_strlen($barcode) <= 3) {
            throw OAuthServerException::invalidRequest('barcode');
        }

        $barcode = substr($barcode, 0, -1);
        $barcode = substr($barcode, 2);

        $user = $this->userRepository->getUserEntityByUserCredentials(
            $barcode,
            '',
            $this->getIdentifier(),
            $client
        );

        if (!$user instanceof UserEntityInterface) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidCredentials();
        }
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'barcode';
    }
}
