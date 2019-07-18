<?php

namespace App\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/auth", name="auth_")
 */
class AuthController extends AbstractController
{
    /**
     * @Rest\Post("/register")
     * @return JsonResponse
     */
    public function register(Request $request, UserManagerInterface $userManager, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);


        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $user
            ->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setEmail($data['email'])
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
            ->setAddress($data['address'])
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName']);

        $listErrors = $validator->validate($user);
        if(count($listErrors) > 0) {
            $responsejson = new JsonResponse(["error" => (string)$listErrors], 500);

            return $responsejson;
        }

        try {
            $em->flush();
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            $responsejson = new JsonResponse(["error" => "L'email/username est déjà utilisé !"], 500);

            return $responsejson;
        }

        $responsejson = new JsonResponse(["success" => sprintf("%s a bien été inscrit ! ", $user->getUsername())], 201);

        return $responsejson;

    }
}