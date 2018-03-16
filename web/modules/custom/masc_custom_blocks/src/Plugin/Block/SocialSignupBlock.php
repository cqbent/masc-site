<?php

namespace Drupal\masc_custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SocialSignupBlock' block.
 *
 * @Block(
 *  id = "social_signup_block",
 *  admin_label = @Translation("Social signup block"),
 * )
 */
class SocialSignupBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'social_signup_block',
      '#cache' => array(
        'contexts' => array('url.path'),
      ),
    );
  }

}
