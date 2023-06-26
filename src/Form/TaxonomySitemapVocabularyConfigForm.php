<?php

namespace Drupal\taxonomy_sitemap\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Vocabulary;

class TaxonomySitemapVocabularyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'taxonomy_sitemap_vocabulary_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'taxonomy_sitemap.vocabulary_config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('taxonomy_sitemap.vocabulary_config');
    $data = $config->get();
    $vocabularies = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->loadMultiple();

    $form['pager_limit'] = [
      '#type' => 'number',
      '#title' => 'Items per Page',
      '#default_value' => $config->get('pager_limit'),
    ];

    $form['vocabularies'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Vocabulary Index'),
      '#collapsible' => TRUE,
    ];

    $form['priorities'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Vocabulary Priorities'),
      '#collapsible' => TRUE,
    ];

    foreach ($vocabularies as $vocabulary) {
      $form['vocabularies'][$vocabulary->id()] = [
        '#type' => 'checkbox',
        '#title' => $vocabulary->label(),
        '#default_value' => $config->get($vocabulary->id()),
      ];

      $form['priorities']['priority_'.$vocabulary->id()] = [
        '#type' => 'number',
        '#title' => $vocabulary->label(),
        '#min' => 0,
        '#max' => 1,
        '#step' => 0.1,
        '#default_value' => $data['priority_'.$vocabulary->id()] ? $data['priority_'.$vocabulary->id()] : 0.5,
      ];
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('taxonomy_sitemap.vocabulary_config');
    $values = $form_state->getValues();

    foreach ($values as $vocabulary_id => $value) {
      $config->set($vocabulary_id, $value);
    }

    $config->set('pager_limit', $form_state->getValue('pager_limit'));
    $config->save();
  }
}