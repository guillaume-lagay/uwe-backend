<?php


namespace App\Controller;

use App\Entity\Student;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="api_")
 */
class StudentController extends AbstractController
{

    /**
     * @Rest\Get("/students")
     */
    public function getStudents(SerializerInterface $serializer)
    {

        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();

        $data = $serializer->serialize($students, 'json',SerializationContext::create()->setGroups(array('student_list')));


        $response = new Response($data);

        return $response;
    }



}