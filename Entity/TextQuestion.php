<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TextQuestion extends Question
{
    /**
     * @ORM\Column(name="value_text", type="string", length=255, nullable=true)
     */
    private $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
    
    /**
     * Return array of params
     */    
    public function toResponse(): ?array
    {
        $arr = [];
        $arr['id'] = $this->getId();
        $arr['value'] = $this->getValue();
        $arr['quiz_id'] = $this->getQuiz()->getId();
        
        return $arr;
    }
}
