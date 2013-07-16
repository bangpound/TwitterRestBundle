<?php

namespace Bangpound\Twitter\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Bangpound\Twitter\DataBundle\Entity\DataRepository;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\Get;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\ORM\EntityManager;

/*
 * @FOSRest\RouteResource("Tweet")
 */
class TweetController extends FOSRestController
{
    /**
     * @FOSRest\View()
     * @FOSRest\Get("/statuses/show/{id}")
     */
    public function showStatusAction($id = null)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('Bangpound\Twitter\DataBundle\Entity\Tweet');
        $data = $repository->find($id);
        $view = $this->view($data, 200);

        return $view;
    }

    /**
     * @FOSRest\View()
     * @FOSRest\Get("/search/tweets")
     */
    public function searchStatusAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository('Bangpound\Twitter\DataBundle\Entity\Tweet');
        $data = $repository->createQueryBuilder('t')
            ->where('t.text LIKE :q')
            ->setParameter('q', '%'. $request->get('q') .'%')
            ->getQuery()
            ->getResult();
        $view = $this->view($data, 200);
        return $view;
    }
}
