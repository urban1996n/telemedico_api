<?php

namespace App\Infrastructure\Http\Rest\Controller;


use App\Application\Service\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Class UserController
 * @package App\Infrastructure\Http\Rest\Controller
 */
final class UserController
{
    /**
     * @var UserService
     */
    private $userService;

    

    private $request;
    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        //create serializer(normalizer) instance for adjusting response content
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        
        $this->userService = $userService;
    }

    /**
     * Creates an User resource
     * @Route("/users.{_format}", defaults={"_format"="json"}, methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function postUser(Request $request): Response
    {
        $user = $this->userService->addUser($request->get('username'), $request->get('name'), $request->get('surname'), $request->get('password'), $request->get('role'));
        
        // In case our POST was a success we need to return a 201 HTTP CREATED response with the created object
        //exclude password and apiKey for response
        return new Response($this->serializer->serialize($user, "json", [AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','apiKey']]), Response::HTTP_CREATED);
        
    }

    /**
     * Retrieves a collection of User resources
     * @Route("/users.{_format}", defaults={"_format"="json"}, methods={"GET"})
     * @return Response
     */
    public function getUsers(): Response
    {
        $users = $this->userService->getAllUsers();

        // In case our GET was a success we need to return a 200 HTTP OK response with the collection of user object
        return new Response($this->serializer->serialize($users, "json", [AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','apiKey']]), Response::HTTP_OK);
        
    }

    /**
     * Replaces User resource
     * @Route("/users/{userId}.{_format}", defaults={"_format"="json"}, methods={"PUT"})
     * @param int $userId
     * @param Request $request
     * @return Response
     */
    public function putUser(int $userId, Request $request): Response
    {
        if(strlen($request->get('password'))==0){
            $password = null;
        }else{
            $password = $request->get('password');
        }
        $user = $this->userService->updateUser($userId, $request->get('username'), $request->get('name'), $request->get('surname'), $password, $request->get('role'));
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return new Response(Response::HTTP_OK);
        
    }

    /**
     * Removes the User resource
     * @Route("/users/{userId}.{_format}", defaults={"_format"="json"}, methods={"DELETE"})
     * @param int $userId
     * @return Response
     */
    public function deleteUser(int $userId): Response
    {
        $this->userService->deleteUser($userId);

        // In case our DELETE was a success we need to return a 204 HTTP NO CONTENT response. The object is deleted.
        return new Response(Response::HTTP_NO_CONTENT);
    }
}