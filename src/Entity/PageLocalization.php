<?php

namespace Sokil\StaticPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sokil\StaticPageBundle\Entity\Page;

/**
 * @ORM\Entity
 * @ORM\Table(name="pages_local")
 */
class PageLocalization
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="localizations")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * @var TaskCategory
     */
    protected $page;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    protected $lang;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Page
     */
    public function setBody($body)
    {
        $this->body = strip_tags($body, '<h1><h2><h3><h4><h5><h6><a><div><p><blockquote><pre><span><sub><sup><br><strong><en><table><thead><tbody><tfoot><tr><th><td><ul><ol><li>');

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return PageLocalization
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set page
     *
     * @param Page $page
     * @return PageLocalization
     */
    public function setPage(Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}
