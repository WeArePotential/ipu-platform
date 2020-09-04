<?php

namespace Drupal\ipu_tweaks\EventSubscriber;

use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Redirect subscriber for controller requests.
 */
class IpuTweaksRequestSubscriber implements EventSubscriberInterface {

  public function checkForCustomRedirect(GetResponseEvent $event) {

    $route = \Drupal::routeMatch();
    if ($route->getRouteName() == 'entity.taxonomy_term.canonical') {
      $term_id = $route->getRawParameter('taxonomy_term');
      $term = Term::load($term_id);
      if ($term->bundle() == 'country') {
        $path = ($term->language()->getId() == 'en' ? '/parliament' : '/fr/parlement') .'/' . $term->field_iso_code->value;
        $event->setResponse(new RedirectResponse($path, $status = 301));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[KernelEvents::REQUEST][] = array('checkForCustomRedirect');
    return $events;
  }
}
