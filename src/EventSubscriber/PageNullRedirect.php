<?php

namespace Drupal\seo_polish\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Class PageNullRedirect.
 */
class PageNullRedirect implements EventSubscriberInterface {

  /**
   * Constructs a new PageNullRedirect object.
   */
  public function __construct() {
    // Nothing to construct.
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('pageNullRedirect');
    return $events;
  }

  /**
   * Method is called whenever the KernelEvents::REQUEST event is dispatched.
   *
   * @param \Symfony\Component\EventDispatcher\Event $event
   *   The handled response.
   */
  public function pageNullRedirect(Event $event) {
    // Check the GET-Parameter and redirect if page = 0.
    if ($event->getRequest()->query->get('page') !== NULL && $event->getRequest()->query->get('page') == '0') {
      $current_path = \Drupal::service('path.current')->getPath();
      $route = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
      $response = new TrustedRedirectResponse($route, 301);
      $response->addCacheableDependency((new CacheableMetadata())->setCacheMaxAge(0));
      $event->setResponse($response);
    }
  }

}
