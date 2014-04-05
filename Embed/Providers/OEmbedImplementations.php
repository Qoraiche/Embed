<?php
/**
 * Generic oembed provider.
 * Load the oembed data of an url and store it
 */
namespace Embed\Providers;

use Embed\Url;
use Embed\Request;

class OEmbedImplementations extends Provider
{
    public static function create(Url $url)
    {
        //Search the oembed provider using the domain
        $class = 'Embed\\Providers\\OEmbed\\'.str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', $url->getDomain()))));

        if (class_exists($class)) {
            $settings = array(
                'patterns' => $class::getPatterns(),
                'endPoint' => $class::getEndpoint(),
                'params' => $class::getParams()
            );

            if ($url->match($settings['patterns'])) {
                $endPoint = new Request($settings['endPoint']);

                if (empty($settings['params']) === false) {
                    $endPoint->setParameter($settings['params']);
                }

                $endPoint->setParameter('url', $url->getUrl());

                return new OEmbed($endPoint);
            }
        }
    }
}
