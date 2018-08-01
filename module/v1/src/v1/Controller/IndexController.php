<?php


namespace v1\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController,
    Zend\View\Model\JsonModel;

class IndexController extends AbstractRestfulController
{
    public function indexAction()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        // IP-адрес, браузер, ос, URL с которого зашел первый раз,
        // URL на который зашел последний раз, кол-во просмотренных уникальных URL-адресов.
        $sql = 'SELECT 
    u.ip, -- ip 
    u.browser, -- браузер
    u.operating_system, -- операционная система
    dc.url_came, -- URL с которого зашел первый раз 
    dw.url_went, -- URL на который зашел последний раз
    count(du.url) AS count_uniqe_url -- количество просмотренных уникальных URL-адресов
FROM users AS u
LEFT JOIN (
    -- URL с которого зашел первый раз
    SELECT DISTINCT ON(user_id) user_id, url_came FROM displacements ORDER BY user_id,visit_at ASC
) dc ON u.id = dc.user_id
LEFT JOIN (
    -- URL на который зашел последний раз
    SELECT DISTINCT ON(user_id) user_id, url_went FROM displacements ORDER BY user_id, visit_at DESC
) dw ON u.id = dw.user_id
RIGHT JOIN (
    -- просмотренные уникальные URL-адреса
    SELECT t.user_id, t.url FROM (
        (SELECT DISTINCT ON(user_id, url_came) user_id, url_came AS url FROM displacements) 
        UNION 
        (SELECT DISTINCT ON(user_id, url_went) user_id, url_went AS url FROM displacements)
    ) t 
    GROUP BY t.user_id, t.url
) du ON u.id = du.user_id
GROUP by u.ip, u.browser, u.operating_system, dc.url_came, dw.url_went';

        $query = $entityManager->getConnection()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();

        return new JsonModel(array('response' => $result));
    }
}
