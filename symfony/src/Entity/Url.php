<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="urls")
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 */
class Url
{
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    /**
     * @Groups("main")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("main")
     * @ORM\Column(type="string", length=255)
     */
    private $namelong;

    /**
     * @Groups("main")
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nameshort;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="url", orphanRemoval=true)
     */
    private $activities;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamelong(): ?string
    {
        return $this->namelong;
    }

    public function setNamelong(string $namelong): self
    {
        $this->namelong = $namelong;

        return $this;
    }

    public function getNameshort(): ?string
    {
        return $this->nameshort;
    }

    public function setNameshort(string $nameshort): self
    {
        $this->nameshort = $nameshort;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setUrl($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getUrl() === $this) {
                $activity->setUrl(null);
            }
        }

        return $this;
    }
}
