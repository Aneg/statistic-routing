<?php

namespace v1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="unique_ip", columns={"ip"})}, indexes={@ORM\Index(name="index_browser", columns={"browser"}), @ORM\Index(name="index_operating_system", columns={"operating_system"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Идентификатор польлзователя"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="users_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false, options={"comment"="ip пользователя"})
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="browser", type="string", length=255, nullable=true, options={"comment"="Веб-браузер"})
     */
    private $browser;

    /**
     * @var string
     *
     * @ORM\Column(name="operating_system", type="string", length=2044, nullable=true, options={"comment"="Операционная система"})
     */
    private $operatingSystem;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set browser.
     *
     * @param string $browser
     *
     * @return User
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Get browser.
     *
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set operatingSystem.
     *
     * @param string $operatingSystem
     *
     * @return User
     */
    public function setOperatingSystem($operatingSystem)
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    /**
     * Get operatingSystem.
     *
     * @return string
     */
    public function getOperatingSystem()
    {
        return $this->operatingSystem;
    }

    public function setFields(array $fields) {
        foreach ($fields as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
