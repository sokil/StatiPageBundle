<?php

namespace Sokil\StaticPageBundle\Controller;

use Sokil\StaticPageBundle\Entity\Page;
use Sokil\StaticPageBundle\Entity\PageLocalization;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\Expr\Join;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        return $this->render('StaticPageBundle:Page:index.html.twig', [
            'page'      => $page,
            'locale'    => $request->getLocale(),
        ]);
    }

    /**
     * @Route("/pages", name="page_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request)
    {
        // check access
        if (!$this->isGranted('ROLE_PAGE_MANAGER')) {
            throw $this->createAccessDeniedException();
        }

        // get list
        $query = $this->getDoctrine()->getManager()
            ->createQuery('
                SELECT p.id, p.slug, pl.title
                FROM StaticPageBundle:Page p
                LEFT JOIN StaticPageBundle:PageLocalization pl WITH pl.page = p.id
                WHERE pl.lang = :lang
                ORDER BY pl.title DESC
            ')
            ->setParameter(':lang', $request->getLocale());

        // pager
        $limit = (int) $request->get('limit', 20);
        if($limit > 100) {
            $limit = 100;
        }
        $query->setMaxResults($limit);

        $offset = (int) $request->get('offset', 0);
        if($offset > 1000) {
            $offset = 1000;
        }
        $query->setFirstResult($offset);

        // get list of pages
        $pages = $query->getResult();

        // return response
        return new JsonResponse([
            'pages' => array_map(
                function(array $page) {
                    return [
                        'id'    => $page['id'],
                        'slug'  => $page['slug'],
                        'title' => $page['title'],
                    ];
                },
                $pages
            ),
        ]);
    }

    /**
     * @Route("/pages/{id}", name="page")
     * @Method({"GET"})
     */
    public function getAction(Request $request, $id)
    {
        /* @var $page Page */

        // check access
        if (!$this->isGranted('ROLE_PAGE_MANAGER')) {
            throw $this->createAccessDeniedException();
        }

        // get page
        $page = $this
            ->getDoctrine()
            ->getRepository('StaticPageBundle:Page')
            ->find($id);

        if (!$page) {
            throw new NotFoundHttpException;
        }

        $response = [
            'id'    => $page->getId(),
            'slug'  => $page->getSlug(),
        ];

        $localizations = $page->getLocalizations();

        foreach($this->container->getParameter('locales') as $locale) {
            if (isset($localizations[$locale])) {
                $response['title'][$locale] = $localizations[$locale]->getTitle();
                $response['body'][$locale] = $localizations[$locale]->getBody();
            } else {
                $response['title'][$locale] = '';
                $response['body'][$locale] = '';
            }
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/pages", name="insert_page")
     * @Route("/pages/{id}", name="update_page")
     * @Method({"POST", "PUT"})
     */
    public function saveAction(Request $request, $id = null)
    {
        // check access
        if (!$this->isGranted('ROLE_PAGE_MANAGER')) {
            throw $this->createAccessDeniedException();
        }

        if ($id) {
            $page = $this
                ->getDoctrine()
                ->getRepository('StaticPageBundle:Page')
                ->find($id);

            if (!$page) {
                throw new NotFoundHttpException;
            }
        } else {
            $page = new Page();
        }

        // persist
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        try {
            // common fields
            $page->setSlug($request->get('slug'));
            $em->persist($page);
            $em->flush();

            // translated fields
            $localizations = $page->getLocalizations();
            $title = $request->get('title');
            $body = $request->get('body');
            foreach ($this->container->getParameter('locales') as $locale) {
                // create instance
                if (!isset($localizations[$locale])) {
                    $localizations[$locale] = new PageLocalization;
                    $localizations[$locale]
                        ->setPage($page)
                        ->setLang($locale);
                }
                // set values
                $localizations[$locale]
                    ->setTitle(isset($title[$locale]) ? $title[$locale] : null)
                    ->setBody(isset($body[$locale]) ? $body[$locale] : null);
                // persist
                $em->persist($localizations[$locale]);
            }
            // flush
            $em->flush();
            $em->getConnection()->commit();
            return new JsonResponse([
                'error' => 0,
                'id'    => $page->getId(),
            ]);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            return new JsonResponse([
                'error'     => 1,
                'message'   => $e->getMessage(),
            ]);
        }

    }

    /**
     * @Route("/pages/{id}", name="delete_page")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        // check access
        if (!$this->isGranted('ROLE_PAGE_MANAGER')) {
            throw $this->createAccessDeniedException();
        }

        // get page
        $page = $this
            ->getDoctrine()
            ->getRepository('StaticPageBundle:Page')
            ->find($id);

        if (!$page) {
            throw new NotFoundHttpException;
        }

        // remove
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
            return new JsonResponse(['error' => 0]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 1, 'message' => $e->getMessage()]);
        }

    }
}
