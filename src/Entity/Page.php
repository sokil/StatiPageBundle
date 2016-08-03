<?php

namespace Sokil\StaticPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sokil\StaticPageBundle\Entity\PageLocalization;
/**
 * @ORM\Table(name="pages")
 * @ORM\Entity
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @var Collection
     * @ORM\OneToMany(
     *  targetEntity="Sokil\StaticPageBundle\Entity\PageLocalization",
     *  mappedBy="page",
     *  indexBy="lang",
     *  cascade={"remove", "persist"}
     * )
     */
    protected $localizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->localizations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add localizations
     *
     * @param PageLocalization $localizations
     * @return Page
     */
    public function addLocalization(PageLocalization $localizations)
    {
        $this->localizations[$localizations->getLang()] = $localizations;

        return $this;
    }

    /**
     * Remove localizations
     *
     * @param PageLocalization $localizations
     */
    public function removeLocalization(PageLocalization $localizations)
    {
        $this->localizations->removeElement($localizations);
    }

    /**
     * Get localizations
     *
     * @return Collection
     */
    public function getLocalizations()
    {
        return $this->localizations;
    }
}
