<?php

namespace Drupal\taxonomy_sitemap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\taxonomy\Entity\Term;

class TaxonomySitemapController extends ControllerBase {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a TaxonomySitemapController object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  public function sitemapXml(Request $request) {
    $current_language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $vid = $request->query->get('vid');
    if (isset($vid)) {
      // Filter terms by vocabulary ID 'tags'.
      $vocabularies = $vid;
    } else {
      // Load all vocabularies.
      $vocabularies = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->getQuery()->execute();
    }

    $config = $this->configFactory->get('taxonomy_sitemap.vocabulary_config');
    if ($config === NULL) {
      throw new \Exception('Unable to load taxonomy_sitemap.vocabulary_config configuration.');
    }
    $data = $config->get();

    $pager_limit = $data['pager_limit'];
    $translated_terms = [];

    $query = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', $vocabularies, 'IN')
      ->condition('status', 1)
      ->sort('changed', 'DESC')
      ->pager($pager_limit);
    $tids = $query->execute(); 
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($tids);

    foreach ($terms as $term) {
      $translations = $term->getTranslationLanguages();
      if (isset($translations[$current_language])) {
        $url = Url::fromRoute('entity.taxonomy_term', ['taxonomy_term' => $term->id()], ['absolute' => TRUE]);

        $translated_terms[] = [
          'terminfo' => $term,
          'id' => $term->id(),
          'priority' => $data['priority_'. $term->vid->target_id], 
        ];
      }
    }

    $build = [
      '#theme' => 'sitemap_xml',
      '#terms' => $translated_terms,
    ];

    $response = new Response(render($build));
    $response->headers->set('Content-Type', 'application/xml');
    return $response; 
  } 
}