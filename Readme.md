# Drupal Taxonomy Sitemap 

## About the module
Generates sitemap for taxonomy terms.

## How to use
1. Download the file and upload it to /modules folder
2. Enable the module Taxonomy Sitemap
3. Configure the module /admin/config/taxonomy_sitemap/vocabulary-config
4. You can access the sitemap in /taxonomy/sitemap-index.xml
5. You can access the individual sitemap pages in /taxonomy/sitemaps.xml
 

## Pros of this module.
1. Works with domain access module.
2. Works with multilanguage. https://yoursite.com/fil/taxonomy/sitemap-index.xml
3. Custom Index Configuration per Vocabulary. 
4. Custom Priority Configuration per Vocabulary.
5. Doesn't need cron to generate sitemap urls. 
6. Supports pager /taxonomy/sitemaps.xml?page=0
7. Supports filter per vocabulary /taxonomy/sitemaps.xml?type=tags


## CONS of this module
1. It's not that user-friendly since this is a plain xml file unlike other popular modules like xmlsitemap, simple_sitemap.

# NEW
- Added a filter per vocabulary in sitemap. 
