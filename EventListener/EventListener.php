<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Quiz;
use App\Entity\Question;

use Psr\Log\LoggerInterface;

class EventListener
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Quiz) {
            $this->logger->info('Added new Quiz with id '.$entity->getId());
        }
        
        if ($entity instanceof Question) {
            $this->logger->info('Added new Question with id '.$entity->getId().
                '. Value: '.(get_class($entity) == "App\Entity\ChoiseQuestion" ?
                    '<'.implode(">,<", $entity->getValue()).'>' :
                    $entity->getValue()).
                '. Quiz id: '.$entity->getQuiz()->getId()
            );
        }
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Quiz) {
            $this->logger->info('Edited the Quiz with id '.$entity->getId());
        }
        
        if ($entity instanceof Question) {            
            $oldValue = $args->getOldValue('value');
            $newValue = $args->getNewValue('value');
            
            if (get_class($entity) == "App\Entity\ChoiseQuestion") {                            
                $oldValue = json_decode($oldValue);
                $newValue = json_decode($newValue);
                
                $deleted = array_diff($oldValue, $newValue);
                $added = array_diff($newValue, $oldValue);
                
                $logText = 'Change class ChoiseQuestion. '.
                    'Delete '.(null != $deleted ?
                        '<'.implode(">,<", $deleted).'>' :
                        'nothing').
                    ', Add '.(null != $added ?
                        '<'.implode(">,<", $added).'>' :
                        'nothing')
                ;
            }
            else {
                $logText = 'Change class DateQuestion. '.
                        'Delete <'.$oldValue.'>, Add <'.$newValue.'>'
                ;
            }
            $this->logger->info($logText);            
        }
    }
    
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if ($entity instanceof Quiz) {
            $this->logger->info('Deleted the Quiz with id '.$entity->getId());
        }
        
        if ($entity instanceof Question) {
            $this->logger->info('Deleted the Question with id '.$entity->getId().
                '. Quiz id: '.$entity->getQuiz()->getId());
        }
    }
}