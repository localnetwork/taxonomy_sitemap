# Drupal Taxonomy Sitemap 

## About the Module
The Drupal Taxonomy Sitemap module is designed to generate sitemaps specifically for taxonomy terms. It provides a convenient way to organize and present your taxonomy terms in a structured sitemap format.

## How to use
1. Download the module file and upload it to the /modules folder in your Drupal installation.
2. Enable the "Taxonomy Sitemap" module in the Drupal module administration.
3. Configure the module by navigating to /admin/config/taxonomy_sitemap/vocabulary-config in your Drupal admin interface.
4. You can access the main sitemap index at /taxonomy/sitemap-index.xml.
5. Individual sitemap pages can be accessed at /taxonomy/sitemaps.xml.
 

## Module Features.
1. Compatibility with the Domain Access module, allowing the generation of sitemaps for multi-domain setups.
2. Multilanguage support, enabling the inclusion of language-specific sitemaps. Example: https://yoursite.com/fil/taxonomy/sitemap-index.xml.
3. Customizable index configuration per vocabulary, providing flexibility in organizing and prioritizing your taxonomy terms. 
4. Custom Priority Configuration per Vocabulary.
5. Doesn't need cron to generate sitemap urls. 
6. Supports pager /taxonomy/sitemaps.xml?page=0
7. Supports filter per vocabulary /taxonomy/sitemaps.xml?type=tags


## CONS of this module
1. Compared to other popular modules like xmlsitemap and simple_sitemap, the user-friendliness of this module is limited due to its utilization of plain XML files.

# NEW
- Added a filter per vocabulary in sitemap. 
