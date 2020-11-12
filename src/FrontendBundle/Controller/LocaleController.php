<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

final class LocaleController extends Controller
{
   public function changeLocaleAction(Request $request, $locale)
   {
       $user = $this->getUser();
       if ($user) {
           $em = $this->getDoctrine()->getManager();
           $user->setLocale($locale);
           $em->persist($user);
           $em->flush();
       }

       $request->setLocale($locale);
       $session = $request->getSession();
       $session->set('_locale', $locale);

       $last_route = $session->get('last_route');

       $last_route['params']['_locale'] = $locale;

       if (empty($last_route['name'])) {
           $ref = parse_url($request->headers->get('referer'), PHP_URL_PATH);
           $route = $this->get('router')->match($ref)['_route'];

           try {
            return ($this->redirect($this->generateUrl($route, $last_route['params']), 301)); 
           } catch(\Exception $exception) {
            return $this->redirectToRoute('frontend_homepage', ['_locale' => $locale], 301);
           }
           
       } else {
           if ($last_route['name'] == 'change_locale') {
               return $this->redirectToRoute('frontend_homepage', ['_locale' => $locale], 301);
           }
       }

       $routeArr = [];

       if (in_array($last_route['name'], $routeArr)) {
           $last_route['params']['page'] = 1;
       }

       return ($this->redirect($this->generateUrl($last_route['name'], $last_route['params']), 301));
   }
}
