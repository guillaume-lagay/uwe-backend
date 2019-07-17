<?php

namespace App\Controller;


use App\Entity\Component;
use App\Entity\Module;
use App\Entity\Student;
use JMS\Serializer\SerializationContext;
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
        $data = $this->serializer->serialize($module, 'json',
            SerializationContext::create(Module::class)->setGroups(array('module', 'module_detail', 'component', 'student')));
        $response = new Response($data);

        return $response;
    }

    /**
     * @Rest\Get("/modules", name="module_list")
     *
     * @return Response
     */
    public function findAllModules()
    {
        $modules = $this->getDoctrine()->getRepository(Module::class)->findAll();
        $data = $this->serializer->serialize($modules, 'json',
            SerializationContext::create(Module::class)->setGroups(array("module")));
        $response = new Response($data);

        return $response;
    }

    /**
     *  @Rest\Post("/modules", name="create_module")
     *  @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can create a module")
     *
     * @return Response
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

        $result = $this->serializer->serialize($module, 'json',
            SerializationContext::create(Module::class)->setGroups(array("module")));
        return new Response($result);
    }

    /**
     *  @Rest\Put("/modules/{id}", name="edit_module", requirements={"id" = "\d+"})
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can edit a module")
     *
     * @return Response
     * */
    public function editModule(Request $request, ValidatorInterface $validator, Module $module)
    {
        $data = json_decode($request->getContent(), true);

        $module->setName($data['name'])
            ->setAcronym($data['acronym']);

        $listErrors = $validator->validate($module);
        if(count($listErrors) > 0) {
            return new JsonResponse(["error" => (string)$listErrors], 500);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($module);
        $em->flush();

        $result = $this->serializer->serialize($module, 'json',
            SerializationContext::create(Module::class)->setGroups(array("module")));
        return new Response($result);
    }

    /**
     *  @Rest\Delete("/modules/{id}", name="delete_module", requirements={"id" = "\d+"})
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can delete a module")
     *
     *  @return JsonResponse
     * */
    public function removeModule(Module $module) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($module);
        $em->flush();

        return new JsonResponse(["success" => "The module has been deleted !"], 200);
    }

    /**
     * @Rest\Post("/modules/{module_id}/students/{student_id}", name="add_module_student")
     *
     * @ParamConverter("module", options={"mapping": {"module_id": "id"}})
     * @ParamConverter("student", options={"mapping": {"student_id": "id"}})
     *
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can add a student on a module")
     *
     * @return Response
     */
    public function addStudent(Module $module, Student $student) {
        if (!$module->addStudent($student)) {
            return new JsonResponse(["error" => sprintf('This student is already on the module %s !', $module->getName())], 200);
        }

        $em = $this->getDoctrine()->getManager();
        
        $em->persist($module);
        $em->flush();

        return new JsonResponse(["success" => sprintf('This student has been added to the module %s !', $module->getName())], 200);
    }

    /**
     * @Rest\Delete("/modules/{module_id}/students/{student_id}", name="remove_module_student")
     *
     * @ParamConverter("module", options={"mapping": {"module_id": "id"}})
     * @ParamConverter("student", options={"mapping": {"student_id": "id"}})
     *
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can remove a student from a module")
     *
     * @return Response
     */
    public function removeStudent(Module $module, Student $student) {
        if (!$module->removeStudent($student)) {
            return new JsonResponse(["error" => sprintf('The student is not on the module %s !', $module->getName())], 200);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($module);
        $em->flush();

        return new JsonResponse(["success" => sprintf('The student has been removed from the module %s !', $module->getName())], 200);
    }


//    /**
//     * @Rest\Post("/modules/{module_id}/components/{component_id}", name="add_module_component")
//     *
//     * @ParamConverter("module", options={"mapping": {"module_id": "id"}})
//     * @ParamConverter("component", options={"mapping": {"component_id": "id"}})
//     *
//     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can add a component on a module")
//     *
//     * @return Response
//     */
//    public function addComponent(Module $module, Component $component) {
//        if (!$module->addComponent($component)) {
//            return new JsonResponse(["error" => sprintf('%s is already on the module %s !', $component->getName(), $module->getName())], 400);
//        }
//
//        $em = $this->getDoctrine()->getManager();
//
//        $module->addComponent($component);
//        $em->persist($module);
//        $em->flush();
//
//        return new JsonResponse(["success" => sprintf('%s has been added to the module %s !', $component->getName(), $module->getName())], 400);
//    }
//
//    /**
//     * @Rest\Delete("/modules/{module_id}/components/{component_id}", name="remove_module_component")
//     *
//     * @ParamConverter("module", options={"mapping": {"module_id": "id"}})
//     * @ParamConverter("component", options={"mapping": {"component_id": "id"}})
//     *
//     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can remove a component from a module")
//     *
//     * @return Response
//     */
//    public function removeComponent(Module $module, Component $component) {
//        if (!$module->removeComponent($component)) {
//            return new JsonResponse(["error" => sprintf('%s is not on the module %s !', $component->getName(), $module->getName())], 400);
//        }
//
//        $em = $this->getDoctrine()->getManager();
//
//        $module->removeComponent($component);
//        $em->persist($module);
//        $em->flush();
//
//        return new JsonResponse(["success" => sprintf('%s has been removed from the module %s !', $component->getName(), $module->getName())], 200);
//    }

}