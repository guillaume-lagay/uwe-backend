<?php

namespace App\Controller;

use App\Entity\Component;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api", name="api_")
 */
class ComponentController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/components/{id}", name="show_component", requirements={"id" = "\d+"})
     *
     * @return Response
     */
    public function showComponent(Component $component)
    {
        $data = $this->serializer->serialize($component, 'json');
        $response = new Response($data);

        return $response;
    }

    /**
     * @Rest\Get("/components", name="components_list")
     *
     * @return Response
     */
    public function findAllComponents()
    {
        $components = $this->getDoctrine()->getRepository(Component::class)->findAll();
        $data = $this->serializer->serialize($components, 'json');
        $response = new Response($data);

        return $response;
    }

    /**
     * @Rest\Post("/components", name="create_component")
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can create a component")
     *
     * @return JsonResponse
     **/
    public function createComponent(Request $request, ValidatorInterface $validator)
    {
        $component = new Component();
        $data = json_decode($request->getContent(), true);

        $component->setName($data['name'])
            ->setCoefficient($data['coefficient'])
            ->setPassDate(new \DateTime($data['passDate']));

        $listErrors = $validator->validate($component);
        if(count($listErrors) > 0) {
            $responsejson = new JsonResponse(["error" => (string)$listErrors], 500);
            return $responsejson;
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($component);
        $em->flush();

        return new JsonResponse(["success" => "The component has been created !"], 201);
    }

    /**
     * @Rest\Put("/components/{id}", name="edit_component", requirements={"id" = "\d+"})
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can edit a component")
     *
     * @return JsonResponse
     **/
    public function editComponent(Request $request, ValidatorInterface $validator, Component $component)
    {
        $data = json_decode($request->getContent(), true);

        $component->setName($data['name'])
            ->setCoefficient($data['coefficient'])
            ->setPassDate(new \DateTime($data['passDate']));

        $listErrors = $validator->validate($component);
        if(count($listErrors) > 0) {
            return new JsonResponse(["error" => (string)$listErrors], 500);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($component);
        $em->flush();

        return new JsonResponse(["success" => "The component has been edited !"], 200);
    }


    /**
     * @Rest\Delete("/components/{id}", name="delete_component", requirements={"id" = "\d+"})
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can delete a component")
     *
     * @return JsonResponse
     * */
    public function removeComponent(Component $component) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($component);
        $em->flush();

        return new JsonResponse(["success" => "The component has been deleted !"], 200);
    }
}