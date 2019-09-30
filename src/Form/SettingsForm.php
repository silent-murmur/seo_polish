<?php

namespace Drupal\seo_polish\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\seo_polish\Services\SeoPolishService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CredentialsForm.
 */
class SettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'seo_polish_settings';
  }

  /**
   * Additional options besides no indexed sites.
   *
   * @var array
   */
  protected $additionalOptions = [
    'prev_and_next' => 'Add next/prev rel link to pages with pager.',
    'remove_hreflang' => 'Remove the hreflang.',
    'remove_canonical_from_404' => 'Remove the canonical from 404 pages.',
    'remove_canonical_from_noindex' => 'Remove the canonical on noindexed pages.',
    'remove_http_canonical' => 'Remove the http header canonical at all.',
  ];

  /**
   * The SeoPolishService.
   *
   * @var \Drupal\seo_polish\Services\SeoPolishService
   */
  protected $service;

  /**
   * The request path condition.
   *
   * @var \Drupal\system\Plugin\Condition\RequestPath
   */
  protected $condition;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('seo_polish.seo_polish_service')
    );
  }

  /**
   * Class constructor.
   *
   * @param \Drupal\seo_polish\Services\SeoPolishService $service
   *   The Service of this module.
   */
  public function __construct(SeoPolishService $service) {
    $this->service = $service;
    $this->condition = $this->service->condition;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->condition->setConfiguration(['pages' => $this->service->getConfiguration()['noindex']]);
    $form['noindex']['#type'] = 'details';
    $form['noindex']['#title'] = $this->t('Sites with noindex status');
    $form['noindex'] += $this->condition->buildConfigurationForm([], $form_state);

    $form['additional_options']['#type'] = 'details';
    $form['additional_options']['#title'] = $this->t('Additional options');
    foreach ($this->additionalOptions as $key => $label) {
      $form['additional_options'][$key] = array(
        '#type' => 'checkbox',
        '#title' => $this->t($label),
        '#default_value' => $this->service->getConfiguration()[$key],
      );
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $settings = [
      'noindex' => $values['pages'],
    ];
    foreach ($this->additionalOptions as $key => $label) {
      $settings[$key] = $values[$key];
    }
    $this->service->setConfiguration($settings);
  }

}
