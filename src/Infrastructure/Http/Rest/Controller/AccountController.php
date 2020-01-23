<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Service\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Class AccountController - made for users to manage and create their accounts(only user with ROLE_ADMIN has permission to access UserController, and manage accounts without additional authentication)
 * 
 * @package App\Infrastructure\Http\Rest\Controller
 */
final class AccountController
{
    /**
     * @var UserService
     */
    private $userService;
    
    /**
     * @var \Symfony\Component\Serializer\Serializer;
     */
    private $serializer;

    /**
     * @var int
     */
    private $userId;

    /**
     * AccountController constructor.
     * @param UserService $userService 
     * @param RequestStack $requestStack 
     */
    public function __construct(UserService $userService, RequestStack $requestStack)
    {   
        //create serializer(normalizer) instance for adjusting response content
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $this->userService = $userService;
        
        //getting request headers for authorization
        $request = $requestStack->getCurrentRequest();

        //Authorizating user account with credentials given in request , excluding account creating path from authentication
        if(!strpos($request->getPathInfo(),"api/account/get") && $request->getPathInfo()!="/api/account/new" && $request->getPathInfo()!="/api/login" && $request->getPathInfo()!="/api/account/check"){
            $user = $this->userService->authenticate($request->get('apiKey'),$request->get('username'),$request->get('password'));
            //store User ID for working with it's account with no additional parameters passed to actions
            $this->userId = $user->getId();
        }
    }

    /**
     * Creates new account
     * @Route("/account/new.{_format}", defaults={"_format"="json"}, methods={"POST"})
     * @param Request $request
     * @return Response   
     */
    public function createAccount(Request $request): Response
    {
        if($this->userService->findAll() == null){
            $role = "ROLE_ADMIN";
        }else{
            $role = "ROLE_USER";
        }

        $user = $this->userService->addUser($request->get('username'), $request->get('name'), $request->get('surname'), $request->get('password'), $role);
        
        // In case our POST was a success we need to return a 201 HTTP CREATED response with the created object
        return new Response($this->serializer->serialize($user, "json", [AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','apiKey']]), Response::HTTP_CREATED);
    }

    /**
     * Checks for availability of chosen username for registration
     * @Route("/account/check.{_format}", defaults={"_format"="json"}, methods={"GET"})
     * @param Request $request
     * @return Response   
     */
    public function checkUsername(Request $request): bool
    {
        return $this->userService->getUserByUsername($request->get('username'));
    }

    /**
     * Login action 
     * @Route("/login.{_format}", defaults={"_format"="json"}, methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        $user = $this->userService->login($request->get('username'), $request->get('password'));
        // In case our POST was a success we need to return a 201 HTTP CREATED response with the created object
        return new Response($this->serializer->serialize($user, "json", [AbstractNormalizer::IGNORED_ATTRIBUTES => ['password']]), Response::HTTP_OK);
    
    }
    
    /**
     * Retrieves an User resource by apiKey
     * @Route("/account/get/{apiKey}.{_format}", defaults={"_format"="json"}, methods={"GET"})
     * @param string apiKey
     * @return Response
     */
    public function getUser(string $apiKey): Response
    {
        $user = $this->userService->getUserForApiKey($apiKey);

        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return new Response($this->serializer->serialize($user, "json", [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id','role','password','apiKey']]), Response::HTTP_OK);
    }

    /**
     * Changes the owner's account's details
     * @Route("/account/changeCredientials.{_format}", defaults={"_format"="json"}, methods={"PUT"})
     * @param Request $request
     * @return Response
     */
    public function changeCredentials(Request $request): Response
    {
        if(strlen($request->get('newPassword'))==0){
            $password = null;
        }else{
            $password = $request->get('newPassword');
        }

        $user = $this->userService->updateUser($this->userId, $request->get('newUsername'), $request->get('name'), $request->get('surname'), $password, "ROLE_USER");
    
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return new Response(Response::HTTP_OK);
    }

    /**
     * Removes the owner's account resource
     * @Route("/account/delete.{_format}", defaults={"_format"="json"}, methods={"DELETE"})
     * @return Response
     */
    public function deleteAccount(): Response
    {
        $this->userService->deleteUser($this->userId);
        // In case our DELETE was a success we need to return a 204 HTTP NO CONTENT response. The object is deleted.
        return new Response(Response::HTTP_NO_CONTENT);
    }
}