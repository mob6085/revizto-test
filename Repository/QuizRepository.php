<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }
    
//     public function find($id, $lockMode = null, $lockVersion = null)
//     {
//         $quiz = parent::find($id, $lockMode, $lockVersion);
//         
//         foreach ($quiz->questions as $key => $val) {
//             if (get_class($quiz->questions[$key]) == "App\Entity\ChoiseQuestion") {
//                 dump(json_decode($quiz->questions[$key]->getValue()));
// //                 $quiz->questions[$key]->setValue(json_decode($quiz->questions[$key]->getValue()));
//             }
// //             print $question->getValue();
// //             print "<br />";
//         }
//         
//         return $quiz;
//     }


    // /**
    //  * @return Quiz[] Returns an array of Quiz objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quiz
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
