<?php

namespace UserBundle\EventSubscriber;

// use Symfony\Component\Security\Core\SecurityContext;
// use Symfony\Component\EventDispatcher\EventSubscriberInterface;
// use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
// use EasyApp\UserBundle\Entity\UserLogin;
// use FOS\UserBundle\FOSUserEvents;
// use FOS\UserBundle\Event\FormEvent;
// use FOS\UserBundle\FOSUserBundle;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


// class LoginListener {
    
//     protected $userManager;
    
//     public function __construct(UserManagerInterface $userManager){
//         $this->userManager = $userManager;
//     }
    
//     public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
//     {
//         $user = $event->getAuthenticationToken()->getUser();
//                 // In order to test if it works, create a file with the name login.txt in the /web path of your project
//         $myfile = fopen("login.txt", "w");
//         fwrite($myfile, 'onSecurityInteractiveLogin succesfully executed !');
//         fclose($myfile);
//         // do something else
//         // return new Response(); 
//     }
// }

class LoginListener{
    protected $userManager;
}

public function __construct(UserManagerInterface $userManager){
    $this->userManager = $userManager;
}
public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
{
    $user = $event->getAuthenticationToken()->getUser();
    //do something
    //
    // $myfile = fopen("login.txt", "w");
    //     fwrite($myfile, 'onSecurityInteractiveLogin succesfully executed !');
    //     fclose($myfile);
    
    //
    //
    $response = new JsonResponse();
    $response->setData(array(
        'data' => 123
    ));
    $event->setResponse($response);

    return new Response(); 
}