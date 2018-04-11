<?php

namespace Drupal\menu_parent_image\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides a 'Example: empty block' block.
 *
 * @Block(
 *   id = "menu_parent_image",
 *   admin_label = @Translation("Menu Parent Image")
 * )
 */
class MenuParentImageBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /*
     * 1) get current page menufeatured image if exists
     * 2) if no feature image exists then get image from menu parent's node
     * 3) return result to theme
     */
    $active_trail_ids = array_filter(\Drupal::service('menu.active_trail')->getActiveTrailIds('main')); // get menu active trail id's
    if (count($active_trail_ids) > 0) {
      $parent_menuitem = array_reverse(array_keys(array_filter($active_trail_ids))); // reverse the list to get top most parent first
      $parent_menuitem = reset($parent_menuitem); // get first item in array
      //$parent_menuitem = $this->page_parent_menuitem();
      $class = count($active_trail_ids) > 1 ? 'menu-parent-image child' : 'menu-parent-image landing';
      $parent_menu_image = $this->menu_item_content($parent_menuitem) ;
      //kint($active_trail_ids);
    }

    if (isset($parent_menuitem['title'])) {
      return array(
        '#theme' => 'menu_parent_image',
        '#image' => $parent_menu_image['image'],
        '#logo' => $parent_menu_image['logo'],
        '#title' => $parent_menu_image['title'],
        '#class' => $class,
        '#cache' => array(
          'contexts' => array('url.path'),
        ),
      );
    }
    else {
      return array (
        '#type' => 'markup',
        '#markup' => t(''),
        '#cache' => array(
          'contexts' => array('url.path'),
        ),
      );
    }
  }


  private function menu_item_content($id) {
    $menu_image = ''; $menu_logo = ''; $menu_link = ''; $menu_title = '';
    $menu_id = explode(':', $id); // just want uuid
    $query = \Drupal::database()->select('menu_link_content', 'mc')
      ->fields('mc', array('id'))
      ->condition('mc.uuid', $menu_id[1], '='); // get menu id from menu_link_content db based on uuid
    $result = $query->execute()->fetchAssoc();
    $entity = \Drupal::entityTypeManager()->getStorage('menu_link_content')->load($result['id']); // load menu_link_content entity
    //$entity = \Drupal::entityTypeManager()->getStorage('menu_link_content')->loadByProperties(['id' => [$result['id']]]);
    if ($entity) {
      $menu_title = $entity->title->getString(); // get title
      $menu_link = $entity->getUrlObject()->toString(); // get menu link path
      if ( $img_field = $entity->get('field_menu_image')->first() ) { // if menu image exists then get the image path
        $menu_image = get_image_properties($img_field);
      }
      if ( $logo_field = $entity->get('field_menu_logo')->first() ) { // if menu image exists then get the image path
        $menu_logo = get_image_properties($logo_field);
        //$logo_entity = $logo_field->get('entity')->getTarget();
        //$menu_logo = file_create_url($logo_entity->get('uri')->getString());
      }
    }

    return array(
      'title' => $menu_title,
      'image' => $menu_image,
      'logo' => $menu_logo,
      'link' => $menu_link
    );
  }

}

function get_image_properties($img_field) {
  $img_entity = $img_field->get('entity')->getTarget();
  //dsm($img_entity);
  return array(
    'src' => file_create_url($img_entity->get('uri')->getString()),
    'alt' => $img_field->get('alt')->getString(),
    'title' => $img_field->get('title')->getString()
  );
}