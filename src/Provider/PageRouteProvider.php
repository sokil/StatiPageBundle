<?php

namespace Sokil\StaticPageBundle\Provider;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Doctrine\Bundle\DoctrineBundle\Registry;

class PageRouteProvider implements RouteProviderInterface
{
    /**
     *
     * @var Registry
     */
    private $registry;

    /**
     *
     * @var RouteCollection
     */
    private $collection;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->collection = new RouteCollection();
    }
    
    public function getRouteCollectionForRequest(Request $request)
    {
        $uri = ltrim($request->getPathInfo(), '/');
        
        // find page by slug
        $page = $this->registry->getRepository('Sokil\StaticPageBundle\Entity\Page')
            ->findOneBy([
                'slug' => $uri,
            ]);

        if(!$page) {
            throw new RouteNotFoundException;
        }

        // add route to route collection
        $route = new Route($uri, [
            '_controller' => 'StaticPageBundle:Page:index',
            'page' => $page,
        ]);

        $this->collection->add(
            'page_' . $page->getId(),
            $route
        );

        return $this->collection;
    }

    public function getRouteByName($name)
    {
        return $this->collection->get($name);
    }

    public function getRoutesByNames($names)
    {
        return array_intersect_keys(
            $this->collection->all(),
            array_flip($names)
        );
    }
}