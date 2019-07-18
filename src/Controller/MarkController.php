<?php


namespace App\Controller;


use App\Entity\Component;
use App\Entity\Mark;
use App\Entity\Student;
use App\Exception\ResourceValidationException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/api", name="api_")
 */
class MarkController extends AbstractController
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
     * @Rest\Post("/marks")
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can add a mark")
     * @throws ResourceValidationException
     */
    public function createMark(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $mark = new Mark();

        $data = json_decode($request->getContent(),true);

        if (!$component = $em->getRepository(Component::class)->find($data['component'])) {
            throw new ResourceValidationException("component not found");
        }

        if (!$student = $em->getRepository(Student::class)->find($data['student'])) {
            throw new ResourceValidationException("student not found");
        }

        if (!$component->getModule()->getStudents()->contains($student)) {
            return new JsonResponse(["error" => sprintf('%s %s is not registered on the %s module', $student->getFirstName(), $student->getLastName(), $component->getModule()->getName())], 500);
        }

        $component_marks = $component->getMarks();
        foreach ($component_marks as $m) {
            if ($m->getStudent() == $student) {
                return new JsonResponse(["error" => 'This student already has a mark on this module'], 500);
            }
        }

        $mark->setValue($data['value'])
            ->setComponent($component)
            ->setStudent($student);

        $listErrors = $validator->validate($mark);
        if(count($listErrors) > 0) {
            return new JsonResponse(["error" => (string)$listErrors], 500);
        }


        $em->persist($mark);
        $em->flush();

        $result = $this->serializer->serialize($mark, 'json', SerializationContext::create()->setGroups(array('mark', 'mark_detail','student','component')));

        return new Response($result);
    }


    /**
     * @Rest\Get("/marks")
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can see all student marks")
     */
    public function getMarks(){

        $marks = $this->getDoctrine()->getRepository(Mark::class)->findAll();

        $data = $this->serializer->serialize(
            $marks,
            'json',
            SerializationContext::create()->setGroups(array('mark'))
        );


        $response = new Response($data);

        return $response;
    }

    /**
     * @Rest\Get("/marks/{id}", name = "get_Mark_by_Id")
     */
    public function getMarkById(Mark $mark = null)
    {
        if (!$mark) {
            throw new NotFoundResourceException("mark not found");
        }

        $data = $this->serializer->serialize($mark, 'json', SerializationContext::create()->setGroups(array('mark','mark_detail', 'component', 'student')));

        return new Response($data);
    }

    /**
     * @Rest\Delete("/marks/{id}",name = "delete_mark")
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can remove a mark")
     */
    public function deleteMarkById(Mark $mark = null)
    {
        if (!$mark) {
            throw new NotFoundResourceException("mark not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mark);
        $em->flush();
        $response = new JsonResponse(["success" => "Mark deleted"], 200);

        return $response;
    }

    /**
     * @param Mark|null $mark
     * @Rest\Put("/marks/{id}", name = "edit_mark")
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Only an administrator can edit a mark")
     */
    public function editMarkById(Request $request, ValidatorInterface $validator, Mark $mark = null)
    {
        if (!$mark) {
            throw new NotFoundResourceException("Mark Not found");
        }

        $data = json_decode($request->getContent(),true);



        $em = $this->getDoctrine()->getManager();

        $mark->setValue($data['value']);

        $listErrors = $validator->validate($mark);
        if(count($listErrors) > 0) {
            $responsejson = new JsonResponse(["error" => (string)$listErrors], 400);

            return $responsejson;
        }

        $em->persist($mark);
        $em->flush();

        $result = $this->serializer->serialize($mark, 'json', SerializationContext::create()->setGroups(array('mark')));

        return new Response($result);
    }
}