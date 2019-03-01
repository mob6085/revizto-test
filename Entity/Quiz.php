<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\TestQuestion as TestQuestion;

/**
 * @ORM\Table(name="quiz") 
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
    * @var Question[]|ArrayCollection
    *
    * @ORM\OneToMany(
    * targetEntity="App\Entity\Question",
    * mappedBy="quiz",
    * cascade={"REMOVE"}
    * )
    */
    private $questions;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getQuestions()
    {

//         $this->questions = new ArrayCollection($this->questions);
        return $this->questions;
    }

    public function setQuestions($questions): self
    {
        $this->questions = $questions;

        return $this;
    }
}
