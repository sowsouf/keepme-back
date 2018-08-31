<?php

namespace KeepMe\Entities;

use Doctrine\ORM\EntityManager;
use KeepMe\Utils\Doctrine\AutoIncrementId;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="KeepMe\Repositories\PostRepository")
 * @Table(name="post")
 */
class Post implements \JsonSerializable
{
    use AutoIncrementID;

    /**
     * @Column(type="string", name="description", nullable=false)
     */
    protected $description;

    /**
     * @OneToOne(targetEntity="user")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @Column(type="float", name="longitude", length=45, nullable=false)
     */
    protected $longitude;

    /**
     * @Column(type="float", name="latitude", length=45, nullable=false)
     */
    protected $latitude;

    /**
     * @Column(type="string", name="title", nullable=false)
     */
    protected $title;

    /**
     * @Column(type="datetime", name="date_start", nullable=false)
     */
    protected $start;

    /**
     * @Column(type="datetime", name="date_end", nullable=false)
     */
    protected $end;

    /**
     * @Column(type="integer", name="nb_children", nullable=false)
     */
    protected $nb_children;

    /**
     * @Column(type="float", name="hourly_rate", nullable=false)
     */
    protected $hourly_rate;

    /**
     * @Column(type="integer", name="note", nullable=true)
     */
    protected $note;

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
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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

    /**
     * Gets the value of longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Gets the value of latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Gets the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the value of start date
     *
     * @return datetime
     */
    public function getStart($format = null)
    {
        if (null !== $format) {
            return $this->start->format($format);
        }
        return $this->start;
    }

    /**
     * Gets the value of end date
     *
     * @return datetime
     */
    public function getEnd($format = null)
    {
        if (null !== $format) {
            return $this->end->format($format);
        }
        return $this->end;
    }

    /**
     * Gets the value of number children.
     *
     * @return integer
     */
    public function getNbChildren()
    {
        return $this->nb_children;
    }

    /**
     * Gets the value of hourly_rate
     *
     * @return float
     */
    public function getHourlyRate()
    {
        return $this->hourly_rate;
    }


    /* ------------ SETTERS ------------ */

    /**
     * Sets the value of description.
     *
     * @param string $description the descript
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * Sets the value of longitude.
     *
     * @param float $longitude the longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

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

    /**
     * Sets the value of title.
     *
     * @param string $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the value of start.
     *
     * @param datetime $start the start
     *
     * @return self
     */
    public function setStart($start)
    {
        $this->start = new \Datetime($start);
    }

    /**
     * Sets the value of end.
     *
     * @param datetime $end the end
     *
     * @return self
     */
    public function setEnd($end)
    {
        $this->end = new \Datetime($end);
    }

    /**
     * Sets the value of nb_children.
     *
     * @param integer $nb_children the nb_children
     *
     * @return self
     */
    public function setNbChildren($nb_children)
    {
        $this->nb_children = $nb_children;
    }

    /**
     * Sets the value of hourly_rate.
     *
     * @param integer $hourly_rate the hourly_rate
     *
     * @return self
     */
    public function setHourlyRate($hourly_rate)
    {
        $this->hourly_rate = $hourly_rate;
    }

    /* ------------ UTILS ------------ */

    public function toArray()
    {
        return [
            "id"          => $this->getId(),
            "title"       => $this->getTitle(),
            "description" => $this->getDescription(),
            "user"        => $this->getUser(),
            "longitude"   => $this->getLongitude(),
            "latitude"    => $this->getLatitude(),
            "start"       => $this->getStart("Y-m-d HH:mm:ss"),
            "end"         => $this->getend("Y-m-d HH:mm:ss"),
            "children"    => $this->getNbChildren(),
            "hourly_rate" => $this->getHourlyRate()
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
            "title",
            "description",
            "longitude",
            "latitude",
            "start",
            "end",
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
