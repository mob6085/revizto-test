<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class ChoiseQuestion extends Question
{
    /**
     * @ORM\Column(name="value_array", type="string", nullable=true)
     */
    private $value;

    public function getValue(): ?array
    {
        return json_decode($this->value);
    }

    public function setValue(?array $value): self
    {
        $this->value = json_encode($value);

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
