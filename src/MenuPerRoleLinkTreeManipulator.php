<?php

namespace Drupal\menu_per_role;

use Drupal;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Menu\DefaultMenuLinkTreeManipulators;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;

/**
 * MenuPerRoleLinkTreeManipulator service.
 *
 * @package Drupal\menu_per_role
 */
class MenuPerRoleLinkTreeManipulator extends DefaultMenuLinkTreeManipulators {

  /**
   * {@inheritdoc}
   */
  protected function menuLinkCheckAccess(MenuLinkInterface $instance) {
    $result = parent::menuLinkCheckAccess($instance);

    if ($instance instanceof MenuLinkContent) {
      // Sadly ::getEntity() is protected at the moment.
      $function = function () {
        return $this->getEntity();
      };
      $function = \Closure::bind($function, $instance, get_class($instance));
      /** @var \Drupal\menu_link_content\Entity\MenuLinkContent $entity */
      $entity = $function();
      if (isset($entity->menu_per_role__show_role)) {

        $config = Drupal::config('menu_per_role.settings');

        $uid1_see_all = $config->get('uid1_see_all');
        if ($uid1_see_all && $this->account->id() == 1) {
          return $result;
        }

        $admin_see_all = $config->get('admin_see_all');
        if ($admin_see_all && $this->account->hasPermission('administer menu_per_role')) {
          return $result;
        }

        $show_role = $entity->menu_per_role__show_role->getValue();
        $show_role = array_column($show_role, 'target_id');
        $hidden_role = $entity->menu_per_role__hide_role->getValue();
        $hidden_role = array_column($hidden_role, 'target_id');

        // Check whether this role has visibility access (must be present).
        if ($show_role && count(array_intersect($show_role, $this->account->getRoles())) == 0) {
          $result = $result->andIf(AccessResult::forbidden()
            ->addCacheContexts(['user.roles']));
        }

        // Check whether this role has visibility access (must not be present).
        if ($hidden_role && count(array_intersect($hidden_role, $this->account->getRoles())) > 0) {
          $result = $result->andIf(AccessResult::forbidden()
            ->addCacheContexts(['user.roles']));
        }
      }

    }
    return $result;
  }

}
