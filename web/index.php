<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__DIR__).'/vendor/autoload.php';

use fkooman\Http\Request;
use fkooman\Http\Response;
use fkooman\Json\Json;
use fkooman\Http\Exception\NotFoundException;
use fkooman\Http\Exception\BadRequestException;
use fkooman\Http\Exception\ForbiddenException;
use fkooman\Http\Exception\HttpException;
use fkooman\Http\Exception\InternalServerErrorException;

try {
    $configDir = dirname(__DIR__).'/config';

    $request = new Request($_SERVER);

    if ('https' !== $request->getUrl()->getScheme()) {
        throw new ForbiddenException('must use https');
    }
    $response = new Response(200, 'application/jrd+json');
    $response->setHeader('Access-Control-Allow-Origin', '*');
    $resource = $request->getUrl()->getQueryParameter('resource');

    if (null === $resource) {
        throw new BadRequestException('resource missing');
    }

    $eResource = explode(':', $resource, 2);
    if (2 !== count($eResource) || 'acct' !== $eResource[0]) {
        throw new BadRequestException('invalid resource');
    }
    $userAddress = $eResource[1];

    // FIXME: better user address validation
    if (false === strpos($userAddress, '@')) {
        throw new BadRequestException('invalid email address provided');
    }
    list($user, $host) = explode('@', $userAddress);

    // FIXME: should this really be Host, or SERVER_NAME?
    if ($host !== $request->getHeader('Host')) {
        throw new NotFoundException('host does not match');
    }

    $webFingerData = array(
        'subject' => sprintf('acct:%s@%s', $user, $host),
        'links' => array(
        ),
    );

    $linksDir = $configDir.'/conf.d';
    foreach (glob($linksDir.'/*.conf', GLOB_ERR) as $linkFile) {
        $webFingerData['links'][] = Json::decodeFile($linkFile);
    }

    $webFingerJson = Json::encode($webFingerData);
    $webFingerJson = str_replace(
        array(
            '__HOST__',
            '__USER__',
        ),
        array(
            $host,
            $user,
        ),
        $webFingerJson
    );

    $response->setBody($webFingerJson);
    $response->send();
} catch (Exception $e) {
    if ($e instanceof HttpException) {
        $response = $e->getJsonResponse();
    } else {
        // we catch all other (unexpected) exceptions and return a 500
        $e = new InternalServerErrorException($e->getMessage());
        $response = $e->getJsonResponse();
    }
    $response->setHeader('Access-Control-Allow-Origin', '*');
    $response->send();
}
