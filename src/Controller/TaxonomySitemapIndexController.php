<?php

namespace Drupal\taxonomy_sitemap\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;

class TaxonomySitemapIndexController extends ControllerBase {

  public function TaxonomySitemapIndexXml() {
    $current_language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $config = \Drupal::config('taxonomy_sitemap.vocabulary_config');
    $data = $config->get();
    
    $pager_limit = $data['pager_limit'];

    $vids = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->getQuery()->execute();
    $vocabularies = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_vocabulary')
    ->loadMultiple($vids);

    $host = \Drupal::request()->getSchemeAndHttpHost();

    foreach($vocabularies as $vocabulary) {
        if($data[$vocabulary->id()] === 1) {
            $types[$vocabulary->id()] = $vocabulary->id();
        }
        
    }

    // Determine the total number of pages in the sitemap index.
    $totalPages = ceil($this->getTotalUrlCount() / $pager_limit);


    // Generate URLs for each page of the sitemap index.
    for ($page = 0; $page < $totalPages; $page++) {
      $url = Url::fromRoute('taxonomy_sitemap.taxonomy_sitemap_xml', ['page' => $page], ['absolute' => TRUE]);


      $host = \Drupal::request()->getSchemeAndHttpHost();

      $sitemapUrls[] = $url->toString();
    }

    $build = [
        '#theme' => 'taxonomy_sitemap_index',
        '#terms' => $sitemapUrls,
    ]; 
 
    // return $build;  
    $response = new Response(render($build));
    $response->headers->set('Content-Type', 'application/xml'); 
    return $response;
  }
 
  /**
   * Get the total number of URLs for the sitemap.
   *
   * @return int
   *   The total number of URLs.
   */
  private function getTotalUrlCount() {
    $current_language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $config = \Drupal::config('taxonomy_sitemap.vocabulary_config');
    $data = $config->get();

    $vids = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->getQuery()->execute();
    $vocabularies = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_vocabulary')
    ->loadMultiple($vids);

    foreach($vocabularies as $vocabulary) { 
      if($data[$vocabulary->id()] === 1) {
          $types[$vocabulary->id()] = $vocabulary->id();
      }
    }

    $query = \Drupal::entityQuery('taxonomy_term')
      ->sort('changed', 'DESC')
      ->condition('status', 1)
      ->condition('vid', $types, 'IN')
      ->condition('langcode', $current_language);
    return $query->count()->execute();
  }

}