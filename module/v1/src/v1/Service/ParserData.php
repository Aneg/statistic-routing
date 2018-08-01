<?php
namespace v1\Service;

use Doctrine\ORM\EntityManager;
use v1\Entity\Displacement;
use v1\Entity\User;

class ParserData
{
    /** @var int Количество нераспознанных строк */
    protected $notParsingCount = 0;

    /** @var int Количество обновленных моделий */
    protected $updateCount = 0;

    /** @var int Количество добавленных моделий */
    protected $insertCount = 0;

    /** @var int Количество Добавленных связанных моделей */
    protected $relationCount = 0;


    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Загрузка из данных о пользователях
     * Формат данных в файле:
     * <ip>|<браузер>|<оперейионная система>
     * <ip> :                   /\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/
     * <браузер> :              /[\w_\-\$#%\/&\?:\.=]+/
     * <оперейионная система> : /[\w_\-\$#%\/&\?:\.=]+/
     *
     * @param $absFilePath string абсалютный путь к файлу для загрузки
     *
     * @return array
     * [
     * 'notParsingCount' => <количество нераспознанных строк>,
     * 'updateCount'     => <количество обновленнх моделей пользователей>,
     * 'insertCount'     => <количество добавленных моделей пользователей>,
     * ];
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function parsingUsers($absFilePath)
    {
        if (!file_exists($absFilePath)) {
            throw new \RuntimeException("Файл {$absFilePath} не существует.");
        }

        $userRepository = $this->em->getRepository('v1\Entity\User');

        $this->notParsingCount = 0;
        $this->updateCount     = 0;
        $this->insertCount     = 0;

        $file = fopen($absFilePath, "r");
        while ($string = fgets($file)) {
            $stringData = $this->validateAndNormalize($string, 3, [
                0 => '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/',
                1 => '/[\w _-]+/',
                2 => '/[\w _-]+/',
            ]);
            if ($stringData === false) {
                continue;
            }

            /** @var User $user */
            $user = $userRepository->findOneBy(['ip' => $stringData[0]]) ?: new User();
            if ($user->getId()) {
                $this->updateCount++;
            } else {
                $this->insertCount++;
            }

            $user->setFields(['ip' => $stringData[0], 'browser' => $stringData[1], 'operatingSystem' => $stringData[2]]);
            $this->em->persist($user);
        }

        fclose($file);
        $this->em->flush();

        return [
            'notParsingCount' => $this->notParsingCount,
            'updateCount'     => $this->updateCount,
            'insertCount'     => $this->insertCount
        ];
    }

    /**
     * Валидация и нормолизация данных в строке
     *
     * @param $string string строка  для нормализации
     * @param $countColumn int количество колонок
     * @param $conditions array правила нормализации для колонак где ключ - номер колонки (начиная с 0),
     * а значение - регулярное выражение
     *
     * @return array|false
     * false - если данные не прошли валидацию
     * array - данные нормализованные согластно $conditions
     */
    protected function validateAndNormalize($string, $countColumn, array $conditions)
    {
        $stringData = explode('|', strip_tags(strtolower(trim($string, " \t\n\r\0\x0B"))));
        array_walk($stringData, 'trim');

        if (count($stringData) !== $countColumn) {
            $this->notParsingCount++;
            return false;
        }

        foreach ($conditions as $index => $condition) {
            if (!preg_match($condition, $stringData[$index], $val)) {
                $this->notParsingCount++;
                return false;
            }
            $stringData[$index] = $val[0];
        }

        return $stringData;
    }

    /**
     * Загрузка из данных о перемещениях пользователей
     * Формат данных в файле:
     * <дата>|<время>|<ip>|<URL с которого зашел>|<URL куда зашел>
     * <дата> :                 /\d{1,4}(\.|\-)\d{1,2}(\.|\-)\d{1,4}/
     * <время> :                /\d{1,2}:\d{1,2}:?\d{1,4}/
     * <ip> :                   /\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/
     * <URL с которого зашел> : /[\w_\-\$#%\/&\?:\.=]+/
     * <URL куда зашел> :       /[\w_\-\$#%\/&\?:\.=]+/
     *
     * @param $absFilePath string абсалютный путь к файлу для загрузки
     *
     * @return array
     * [
     * 'notParsingCount' => <количество нераспознанных строк>,
     * 'updateCount'     => <количество обновленнх моделей перемещения>,
     * 'insertCount'     => <количество добавленных моделей перемещения>,
     * 'relationCount'   => <количество добавленных пользорвателей>,
     * ];
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function parsingDisplacements($absFilePath)
    {
        $this->notParsingCount = 0;
        $this->updateCount     = 0;
        $this->insertCount     = 0;
        $this->relationCount   = 0;

        $userRepository = $this->em->getRepository('v1\Entity\User');
        $displacementRepository = $this->em->getRepository('v1\Entity\Displacement');

        $file = fopen($absFilePath, "r");

        while ($string = fgets($file)) {
            $stringData = $this->validateAndNormalize($string, 5, [
                0 => '/\d{1,4}(\.|\-)\d{1,2}(\.|\-)\d{1,4}/',
                1 => '/\d{1,2}:\d{1,2}:?\d{1,4}/',
                2 => '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/',
                3 => '/[\w_\-\$#%\/&\?:\.=]+/',
                4 => '/[\w_\-\$#%\/&\?:\.=]+/',
            ]);
            if ($stringData === false) {
                continue;
            }

            $datetime = new \DateTime();
            $datetime->setTimestamp(strtotime("{$stringData[0]} {$stringData[1]}"));

            $user = $userRepository->findOneBy(['ip' => $stringData[2]]) ?: new User();

            /** @var User $user */
            if (!$user->getId()) {
                $user->setFields(['ip' => $stringData[2]]);
                $this->em->persist($user);
                $this->relationCount++;
            } else {
                /** @var Displacement $displacement */
                if ($displacement = $displacementRepository->findOneBy(['user' => $user, 'visitAt' => $datetime])) {
                    $displacement->setFields([
                        'urlCame' => $stringData[3],
                        'urlWent' => $stringData[4],
                    ]);
                    $this->em->persist($displacement);
                    $this->updateCount++;
                    continue;
                }
            }

            $displacement = new Displacement();
            $displacement->setFields([
                'user' => $user,
                'visitAt' => $datetime,
                'urlCame' => $stringData[3],
                'urlWent' => $stringData[4],
            ]);

            $this->em->persist($displacement);
            $this->insertCount++;
        }

        fclose($file);
        $this->em->flush();

        return [
            'notParsingCount' => $this->notParsingCount,
            'updateCount'     => $this->updateCount,
            'insertCount'     => $this->insertCount,
            'relationCount'   => $this->relationCount,
        ];
    }
}