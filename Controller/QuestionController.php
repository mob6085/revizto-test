<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;

use App\Entity\TextQuestion;
use App\Entity\DateQuestion;
use App\Entity\ChoiseQuestion;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class QuestionController
 */
class QuestionController extends FOSRestController
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;
    
    /**
     * @var QuestionRepository
     */
    private $questionRepository;
    
    /**
     * QuestionRepository constructor.
     * @param QuizRepository $quizRepository
     * @param QuestionRepository $questionRepository
     */
    public function __construct(
        QuizRepository $quizRepository,
        QuestionRepository $questionRepository
    )
    {
        $this->quizRepository = $quizRepository;
        $this->questionRepository = $questionRepository;
    }
    
    /**
     * Get Question list of the Quiz by Quiz ID
     * @Rest\Get("/api/quiz/{quizId}/question", name="get_questions_of_quiz")
     * @param int $quizId
     * @return View
     */
    public function getQuestionsOfQuiz(int $quizId): View
    {
        $quiz = $this->quizRepository->find($quizId);

        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $questions = $this->questionRepository->findBy(['quizId' => $quizId]);

        if (null == $questions) {
            throw new EntityNotFoundException('No any question for this Quiz ID '.$quizId);
        }
        
        $questions = (new ArrayCollection($questions))->map(function($value) {
            return $value->toResponse();
        });
        
        return View::create($questions, Response::HTTP_OK);
    }
    
    /**
     * Get the Question of the Quiz by Quiz ID and Question ID
     * @Rest\Get("/api/quiz/{quizId}/question/{questionId}", name="get_question_of_quiz")
     * @param int $quizId
     * @return View
     */
    public function getSingleQuestionOfQuiz(int $quizId, int $questionId): View
    {
        $quiz = $this->quizRepository->find($quizId);

        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $question = $this->questionRepository->find($questionId);

        if (null == $question) {
            throw new EntityNotFoundException('Question with id '.$quizId.' does not exist!');
        }

        return View::create($question->toResponse(), Response::HTTP_OK);
    }
    
    /**
     * Create a Question of the Quiz
     * @Rest\Post("/api/quiz/{quizId}/question", name="post_question_of_quiz")
     * @param int $quizId
     * @param Request $request
     * @return View
     */
    public function postQuestionOfQuiz(int $quizId, Request $request): View
    {
        $quiz = $this->quizRepository->find($quizId);

        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        // Array
        if (is_array($request->get('value'))) {
            $question = new ChoiseQuestion();
            $question->setValue((array)$request->get('value'));
        }
        // Digit
        elseif (preg_match('|^[\d]+$|', $request->get('value'))) {
            $question = new DateQuestion();
            $question->setValue((int)$request->get('value'));
        }
        // Text
        else {
            $question = new TextQuestion();
            $question->setValue((string)$request->get('value'));
        }

        $question->setQuiz($quiz);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();
        
        return View::create($question->toResponse(), Response::HTTP_CREATED);
    }
    
    /**
     * Replace the Question of the Quiz
     * @Rest\Put("/api/quiz/{quizId}/question/{questionId}", name="put_question")
     * @param int $quizId
     * @param int $questionId
     * @param Request $request
     * @return View
     */
    public function putQuestionOfQuiz(
        int $quizId,
        int $questionId,
        Request $request
    ): View
    {
        $quiz = $this->quizRepository->find($quizId);
        
        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $question = $this->questionRepository->find($questionId);

        if (null == $question) {
            throw new EntityNotFoundException('Question with id '.$quizId.' does not exist!');
        }
        
        // Array
        if (
            is_array($request->get('value')) &&
            $question instanceof ChoiseQuestion            
        ) {
            $question->setValue((array)$request->get('value'));
        }
        // Digit
        elseif (
            preg_match('|^[\d]+$|', $request->get('value')) &&
            $question instanceof DateQuestion
        ) {
            $question->setValue((int)$request->get('value'));
        }
        // Text
        elseif ($question instanceof TextQuestion) {
            $question->setValue((string)$request->get('value'));
        }
        else {
            throw new BadRequestHttpException('Wrong data format!', null, 400);
        }
                
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        return View::create($question->toResponse(), Response::HTTP_OK);
    }
    
    /**
     * Remove the Question
     * @Rest\Delete("/api/quiz/{quizId}/question/{questionId}", name="delete_question")
     * @param int $quizId
     * @param int $questionId
     * @return View
     */
    public function deleteQuestion(int $quizId, int $questionId): View
    {
        $quiz = $this->quizRepository->find($quizId);
        
        if (null == $quiz) {
            throw new EntityNotFoundException('Quiz with id '.$quizId.' does not exist!');
        }
        
        $question = $this->questionRepository->find($questionId);

        if (null == $question) {
            throw new EntityNotFoundException('Question with id '.$questionId.' does not exist!');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();       
            
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}
