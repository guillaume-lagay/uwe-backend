<?php

namespace App\Controller;


use App\Entity\Module;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api", name="api_")
 */
class ModuleController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    /**
     * @Rest\Get("/modules/{id}", name="show_module", requirements={"id" = "\d+"})
     *
     * @return Response
     */
    public function showModule(Module $module)
    {
        $data = $this->serializer->serialize($module, 'json');
        $response = new Response($data);

        return $response;
    }

    /**
     *  @Rest\Post("/modules", name="create_module")
     *
     *  @return JsonResponse
     * */
    public function createModule(Request $request, ValidatorInterface $validator)
    {
        $module = new Module();
        $data = json_decode($request->getContent(), true);

        $module->setName($data['name'])
            ->setAcronym($data['acronym']);

        $listErrors = $validator->validate($module);
        if(count($listErrors) > 0) {
            $responsejson = new JsonResponse(["error" => (string)$listErrors], 500);
            return $responsejson;
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($module);
        $em->flush();

        return new JsonResponse(["success" => "The module has been created !"], 201);
    }


}