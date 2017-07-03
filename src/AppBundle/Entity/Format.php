<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Format
 *
 * @ORM\Table(name="format")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormatRepository")
 */
class Format
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255, unique=true)
     */
    private $format;

    /**
     * @var Book[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Book", mappedBy="format")
     */
    private $book;


    public function __toString()
    {
        return $this->format;
    }

    /**
     * @return Book[]|ArrayCollection
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param ArrayCollection|Book[] $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return Format
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}

