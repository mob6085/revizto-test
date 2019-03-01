<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @ORM\Table(name="question", indexes={@ORM\Index(name="type_idx", columns={"type"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=6)
 * @ORM\DiscriminatorMap({
 *     "Text"="TextQuestion",
 *     "Date"="DateQuestion",
 *     "Choise"="ChoiseQuestion"
 * })
 */
abstract class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="quiz_id", type="integer")
     */
    protected $quizId;

    /**
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="question")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    protected $quiz;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuizId(): ?int
    {
        return $this->quizId;
    }

    public function setQuizId(int $quizId): self
    {
        $this->quizId = $quizId;

        return $this;
    }
    
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
    
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    
    /**
     * Return array of params
     */ 
    abstract function toResponse(): ?array;
}








