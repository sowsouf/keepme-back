<?php

namespace KeepMe\Entities;

use Doctrine\ORM\EntityManager;
use KeepMe\Utils\Doctrine\AutoIncrementId;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="KeepMe\Repositories\ChildrenRepository")
 * @Table(name="children")
 */
class Children implements \JsonSerializable
{
    use AutoIncrementID;

    /**
     * @Column(type="string", name="firstname", nullable=false)
     */
     protected $firstname;

    /**
     * @Column(type="datetime", name="birthdate", nullable=false)
     */
    protected $birthdate;

    /**
     * @Column(type="string", name="description", nullable=true)
     */
    protected $description;

    /**
     * @OneToOne(targetEntity="user")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /* ------------ GETTERS ------------ */

    /**
     * Gets the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Gets the value of birthdate.
     *
     * @return Datetime
     */
    public function getBirthdate($format = null)
    {
        if (null !== $format) {
            return $this->birthdate->format($format);
        }
        return $this->birthdate;
    }

        /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Gets the value of User.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /* ------------ SETTERS ------------ */

    /**
     * Sets the value of firstname.
     *
     * @param string $firstname the firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Sets the value of user.
     *
     * @param User $user the user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the value of birthdate.
     *
     * @param string $birthdate the birthdate
     *
     * @return self
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = new \Datetime($birthdate);
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    /**
     * Sets the value of latitude.
     *
     * @param float $latitude the latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }



    /* ------------ UTILS ------------ */

    public function toArray()
    {
        return [
            "id"          => $this->getId(),
            "firstname"   => $this->getFirstname(),
            "birthdate"   => $this->getBirthdate('Y-m-d'),
            "description" => $this->getDescription(),
            "user"        => $this->getUser()
        ];
    }

    public function toIndex()
    {
        return $this->toArray();
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function setProperties(array $data)
    {
        $mandatory_fields = [
            "id",
            "firstname",
            "user",
            "description",
            "birthdate"
        ];

        $fields = array_intersect(
            $mandatory_fields,
            array_keys($data)
        );

        foreach ($fields as $field) {
            $setterName = 'set' . ucfirst($field);
            if (method_exists($this, $setterName)) {
                $this->{$setterName}($data[$field]);
            }
        }
    }
}
