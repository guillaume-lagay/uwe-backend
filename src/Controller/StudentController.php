<?php


namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Student;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="api_")
 */
class StudentController extends AbstractController
{

    private $serializer;

    /**
     * StudentController constructor.
     * @param $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/auth/user", name="get_user_by_token")
     *
     */
    public function getUserByToken()
    {

        $user = $this->getUser();
        $student = $this->getDoctrine()->getRepository(Student::class)->findBy(["username" => $user]);



        $result = $this->serializer->serialize($user, 'json', SerializationContext::create(User::class)->setGroups(array('student', 'student_role')));

        return new Response($result);
    }

    /**
     * @Rest\Get("/students")
     */
    public function getStudents()
    {

        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();

        $data = $this->serializer->serialize(
            $students,
            'json',
            SerializationContext::create()->setGroups(array('student'))
        );


        $response = new Response($data);

        return $response;

    }

    /**
     * @Rest\Get("/students/{id}",name = "get_student")
     */
    public function getStudentById(Student $student = null)
    {
        if (!$student) {
            throw new NotFoundResourceException("student not found");
        }

        $data = $this->serializer->serialize($student, 'json',SerializationContext::create()->setGroups(array('student', 'student_detail' , 'module', 'mark')));

        $response = new Response($data);

        return $response;
    }

    /**
     * @Rest\Delete("/students/{id}",name = "delete_student")
     */
    public function deleteStudentById(Student $student = null)
    {
        if (!$student) {
            throw new NotFoundResourceException("student not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();
        $response = new JsonResponse(["success" => "Student deleted"], 200);

        return $response;
    }

    /**
     * @Rest\Get("/students/{id}/marks",name = "get_student_marks")
     */
    public function getStudentMarksByStudentId(Student $student = null)
    {
        if (!$student) {
            throw new NotFoundResourceException("student not found");
        }

        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository(Mark::class)->findBy(["student" => $student->getId()]);

        $json_data = $this->serializer->serialize($data, "json",SerializationContext::create()->setGroups(array('mark')));

        $response = new Response($json_data);

        return $response;
    }
}