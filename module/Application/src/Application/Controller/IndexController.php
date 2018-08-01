<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use v1\Service\ParserData;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Загрузка из данных о пользователях
     *
     * @return string
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * TODO: Добавить возможность использовать абсолютные пути к файлу для парсинга
     */
    public function parsingUsersAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof Request){
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $absFilePath = getcwd() . '/public/data' . '/' . $request->getParam('fileName');

        /** @var ParserData $parserData */
        $parserData = $this->getServiceLocator()->get('ParserData');
        // TODO: добавить логирование ошибки.
        $result = $parserData->parsingUsers($absFilePath);

        return "Не прочиитанно: {$result['notParsingCount']}\nОбновленно : {$result['updateCount']}\nДобавлено: {$result['insertCount']}\n";
    }

    /**
     * Загрузка из данных о перемещениях пользователей
     *
     * @return string
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * TODO: Добавить возможность использовать абсолютные пути к файлу для парсинга
     */
    public function parsingDisplacementsAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof Request){
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $absFilePath = getcwd() . '/public/data' . '/' . $request->getParam('fileName');

        /** @var ParserData $parserData */
        $parserData = $this->getServiceLocator()->get('ParserData');
        // TODO: добавить логирование ошибки.
        $result = $parserData->parsingDisplacements($absFilePath);

        return "Не прочиитанно: {$result['notParsingCount']}\n".
        "Обновленно : {$result['updateCount']}\n".
        "Добавлено: {$result['insertCount']}\n".
        "Добавлено пользователей: {$result['relationCount']}\n";
    }
}
