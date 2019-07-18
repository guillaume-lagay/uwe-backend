<?php

namespace App\Controller;

use App\Entity\Student;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/auth", name="auth_")
 */
class AuthController extends AbstractController
{
    private $serializer;

    /**
     * AuthController constructor.
     * @param $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /**
     * @Rest\Post("/register")
     * @return Response
     */
    public function register(Request $request, UserManagerInterface $userManager, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);


        $em = $this->getDoctrine()->getManager();

        $student = new Student();

        $student
            ->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setEmail($data['email'])
            ->setEnabled(true)
            ->setRoles(['ROLE_STUDENT'])
            ->setSuperAdmin(false)
            ->setAddress($data['address'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name']);

        $listErrors = $validator->validate($student);
        if(count($listErrors) > 0) {
            return new JsonResponse(["error" => (string)$listErrors], 400);
        }

        try {
            $em->flush();
            $userManager->updateUser($student);

        } catch (\Exception $e) {

            return new JsonResponse(["error" => "email/username already used"], 403);
        }

        $data = $this->serializer->serialize($student, 'json',SerializationContext::create()->setGroups(array('student')));

        return new Response($data);



    }
}