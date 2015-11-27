<?php

namespace Drupal\menu_per_role;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

class MenuPerRoleServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $container->getDefinition('menu.default_tree_manipulators')
      ->setClass(MenuPerRoleLinkTreeManipulator::class);
  }

}
