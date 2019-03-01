<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class QuizController
 */
class QuizController extends FOSRestController
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;
    
    /**
     * QuizRepository constructor.
     * @param QuizRepository $quizRepository
     */
    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }
    
    /**
     * Get all Quizes
     * @Rest\Get("/api/quiz", name="get_all_quizes")
     * @return View
     */
    public function getQuizes(): View
    {
        $quizes = $this->quizRepository->findAll();
        
        $quizes = (new ArrayCollection($quizes))->map(        
            function($value) {        
                return $value->setQuestions($value->getQuestions()->map(
                    function($value) {
                        return $value->toResponse();
                    })
                );
            }
        );
        
        return View::create($quizes, Response::HTTP_OK);
    }
    
    /**
     * Get the Quiz by ID
     * @Rest\Get("/api/quiz/{quizId}", name="get_quiz")
     * @param int $quizId
     * @return View
     */
    public function getQuiz(int $quizId): View
    {
        $quiz = $this->quizRepository->find($quizId);

        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }

        $quiz->setQuestions($quiz->getQuestions()->map(
            function($value) {
                return $value->toResponse();
            })
        ); 

        return View::create($quiz, Response::HTTP_OK);
    }
    
    /**
     * Create a Quiz
     * @Rest\Post("/api/quiz", name="post_quiz")
     * @param Request $request
     * @return View
     */
    public function postQuiz(Request $request): View
    {
        $quiz = new Quiz();
        $quiz->setName($request->get('name'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($quiz);
        $em->flush();
        
        return View::create($quiz, Response::HTTP_CREATED);
    }
    
    /**
     * Replace the Quiz
     * @Rest\Put("/api/quiz/{quizId}", name="put_quiz")
     * @param int $quizId
     * @param Request $request
     * @return View
     */
    public function putQuiz(int $quizId, Request $request): View
    {
        $quiz = $this->quizRepository->find($quizId);
        
        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $quiz->setName($request->get('name'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($quiz);
        $em->flush();
        
        if (null != $quiz->getQuestions()) {
            $quiz->setQuestions($quiz->getQuestions()->map(
                function($value) {
                    return $value->toResponse();
                })
            );
        } 
        
        return View::create($quiz, Response::HTTP_OK);
    }
    
    /**
     * Remove the Quiz
     * @Rest\Delete("/api/quiz/{quizId}", name="delete_quiz")
     * @param int $quizId
     * @return View
     */
    public function deleteQuiz(int $quizId): View
    {
        $quiz = $this->quizRepository->find($quizId);
        
        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($quiz);
        $em->flush();       
        
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}
