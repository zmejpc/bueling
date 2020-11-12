<?php

namespace UserBundle\Controller\Frontend;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Google;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GoogleController extends AbstractController
{
    private $em;
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function indexAction(ClientRegistry $clientRegistry, Request $request, UserPasswordEncoderInterface $encoder)
    {
    	$client = $clientRegistry->getClient('google');
    	$provider = $client->getOAuth2Provider();
  //   	$provider = new Google([
		//     'clientId'     => '{google-client-id}',
		//     'clientSecret' => '{google-client-secret}',
		//     'redirectUri'  => $this->generateUrl('google_login', [], 0)
		// ]);

		if (!empty($_GET['error'])) {

		    // Got an error, probably user denied access
		    exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

		} elseif (empty($_GET['code'])) {

		    // If we don't have an authorization code then get one
		    $authUrl = $provider->getAuthorizationUrl();
		    $_SESSION['oauth2state'] = $provider->getState();
		    header('Location: ' . $authUrl);
		    exit;

		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

		    // State is invalid, possible CSRF attack in progress
		    unset($_SESSION['oauth2state']);
		    exit('Invalid state');

		} else {

		    // Try to get an access token (using the authorization code grant)
		    $token = $provider->getAccessToken('authorization_code', [
		        'code' => $_GET['code']
		    ]);

		    // Optional: Now you have a token you can look up a users profile data
		    try {

		        // We got an access token, let's now get the owner details
		        $googleUser = $provider->getResourceOwner($token);
		        $email = $googleUser->getEmail();

		        $user = $this->em->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);

		        if (!$user) {
		            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

		            if(!$user) {
		            	$user = new User();

					    $pass = $encoder->encodePassword($user, uniqid());

					    $user->setSalt(md5(time()));
					    $user->setEnabled(1);
			        	$user->setPassword($pass);
			        	$user->setEmail($email);
				        $user->setName($googleUser->getFirstName());
				        $user->setSurname($googleUser->getLastName());
		            }

			        $user->setGoogleId($googleUser->getId());
			        $this->em->persist($user);
			        $this->em->flush();
		        }

		        $token = new UsernamePasswordToken($user, $user->getPassword(), "database_users", $user->getRoles());
		        $this->get("security.token_storage")->setToken($token);
		        $loginEvent = new InteractiveLoginEvent($request, $token);
		        $this->eventDispatcher->dispatch("security.interactive_login", $loginEvent);

		        if($request->getSession()->has('login._target_path')) {
		            $route = $request->getSession()->get('login._target_path');
		            $request->getSession()->remove('login._target_path');
		        } else {
		            $route ='frontend_shop_cabinet_list';
		        }

		        return $this->redirectToRoute($route);

		    } catch (Exception $e) {

		        // Failed to get user details
		        exit('Something went wrong: ' . $e->getMessage());

		    }

		    // // Use this to interact with an API on the users behalf
		    // echo $token->getToken();

		    // // Use this to get a new access token if the old one expires
		    // echo $token->getRefreshToken();

		    // // Unix timestamp at which the access token expires
		    // echo $token->getExpires();

		    exit;
		}
    }
}