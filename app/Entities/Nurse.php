<?php

namespace KeepMe\Entities;

use Doctrine\ORM\EntityManager;
use KeepMe\Utils\Doctrine\AutoIncrementId;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="KeepMe\Repositories\NurseRepository")
 * @Table(name="nurse")
 */
class Nurse implements \JsonSerializable
{
    use AutoIncrementID;

    /**
     * @Column(type="datetime", name="birthdate", nullable=false)
     */
    protected $birthdate;

    /**
     * @OneToOne(targetEntity="user")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @Column(type="integer", name="is_valid", nullable=false)
     */
    protected $validate;

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
     * Gets the value of user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getBirthdate($format = null)
    {
        if (null !== $format) {
            return $this->birthdate->format($format);
        }
        return $this->birthdate;
    }

    /**
     * Gets the value of validation.
     *
     * @return integer
     */
    public function getValidation()
    {
        return $this->validate;
    }

    /* ------------ SETTERS ------------ */

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
     * Sets the value of user.
     *
     * @param string $user the user
     *
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the value of validate.
     *
     * @param string $validate the validate
     *
     * @return self
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /* ------------ UTILS ------------ */

    public function toArray()
    {
        return [
            "id"        => $this->getId(),
            "birthdate" => $this->getBirthdate('Y-m-d'),
            "validate"  => 1 === $this->getValidation() ? true : false
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
            "id"       ,
            "birthdate",
            "validate"
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
