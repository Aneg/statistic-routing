<?php

namespace v1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Displacements
 *
 * @ORM\Table(name="displacements", indexes={@ORM\Index(name="fk_user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Displacement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Идентификатор перемещения"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="displacements_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_at", type="datetime", nullable=false, options={"comment"="Дата и время перемещения"})
     */
    private $visitAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_came", type="string", length=2044, nullable=true, options={"comment"="URL с которого зашел"})
     */
    private $urlCame;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_went", type="string", length=2044, nullable=true, options={"comment"="URL куда зашел"})
     */
    private $urlWent;

    /**
     * @var \v1\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="v1\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



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
     * Set visitAt.
     *
     * @param \DateTime $visitAt
     *
     * @return Displacement
     */
    public function setVisitAt($visitAt)
    {
        $this->visitAt = $visitAt;

        return $this;
    }

    /**
     * Get visitAt.
     *
     * @return \DateTime
     */
    public function getVisitAt()
    {
        return $this->visitAt;
    }

    /**
     * Set urlCame.
     *
     * @param string|null $urlCame
     *
     * @return Displacement
     */
    public function setUrlCame($urlCame = null)
    {
        $this->urlCame = $urlCame;

        return $this;
    }

    /**
     * Get urlCame.
     *
     * @return string|null
     */
    public function getUrlCame()
    {
        return $this->urlCame;
    }

    /**
     * Set urlWent.
     *
     * @param string|null $urlWent
     *
     * @return Displacement
     */
    public function setUrlWent($urlWent = null)
    {
        $this->urlWent = $urlWent;

        return $this;
    }

    /**
     * Get urlWent.
     *
     * @return string|null
     */
    public function getUrlWent()
    {
        return $this->urlWent;
    }

    /**
     * Set user.
     *
     * @param \v1\Entity\User|null $user
     *
     * @return Displacement
     */
    public function setUser(\v1\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \v1\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setFields(array $fields) {
        foreach ($fields as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
