<?php

namespace Drupal\seo_polish\Services;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Condition\ConditionManager;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class SeoPolishService.
 *
 * @package Drupal\seo_polish
 */
class SeoPolishService {

  /**
   * The request path condition.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  public $condition;

  /**
   * The current route the user called.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRoute;

  /**
   * The user that tries to view this page.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * AdService constructor.
   *
   * @param \Drupal\Core\Condition\ConditionManager $pluginManagerCondition
   *   The configured condition for the ad free pages.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route the user called.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The user that tries to view the page.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function __construct(ConditionManager $pluginManagerCondition, CurrentRouteMatch $currentRouteMatch, AccountInterface $currentUser) {
    $this->condition = $pluginManagerCondition->createInstance('request_path');
    $this->currentRoute = $currentRouteMatch;
    $this->currentUser = $currentUser;
  }

  /**
   * Function for checking if the current route have to be not indexed.
   *
   * @return bool
   *   With or without this seo status.
   */
  public function checkCondition() {
    $this->condition->setConfiguration(['pages' => $this->getConfiguration()['sites']]);
    return $this->condition->evaluate();
  }

  /**
   * Setter for the seo polish configuration.
   *
   * @param array $settings
   *   Different configurations for routes and paths.
   */
  public function setConfiguration(array $settings) {
    \Drupal::state()->set('seo_polish.config', $settings);
  }

  /**
   * Getter for the seo polish configuration.
   *
   * @return mixed
   *   Different configurations for routes and paths.
   */
  public function getConfiguration() {
    $settings = \Drupal::state()->get('seo_polish.config', []);

    return $settings;
  }

}
