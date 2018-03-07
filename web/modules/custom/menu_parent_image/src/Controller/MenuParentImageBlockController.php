<?php

namespace Drupal\menu_parent_image\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
/**
 * Controller routines for block example routes.
 */
class MenuParentImageBlockController extends ControllerBase {


  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->t('Menu Parent Image Block'),
    );
  }

}
