<?php

namespace Drupal\menu_per_role\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Menu per role administration form.
 *
 * @package Drupal\menu_per_role\Form
 */
class MenuPerRoleAdminSettings extends ConfigFormBase {

  /**
   * Display both hide and show role checkbox lists.
   */
  const MODE_DISPLAY_BOTH = 0;

  /**
   * Display only the hide from checkbox list.
   */
  const MODE_DISPLAY_ONLY_HIDE = 1;

  /**
   * Display only the show to checkbox list.
   */
  const MODE_DISPLAY_ONLY_SHOW = 2;

  /**
   * Always display fields on links to content.
   */
  const MODE_DISPLAY_ON_CONTENT_ALWAYS = 0;

  /**
   * Only display fields on menu items if there are no node_access providers.
   */
  const MODE_DISPLAY_ON_CONTENT_NO_NODE_ACCESS = 1;

  /**
   * Never display fields on links to content.
   */
  const MODE_DISPLAY_ON_CONTENT_NEVER = 2;

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_per_role.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_per_role_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('menu_per_role.settings');
    $form['uid1_see_all'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('The administrator (UID=1) sees everything'),
      '#description' => $this->t('When selected, the administrator will not be affected by Menu per Role. (i.e. All the menus are visible to him.)'),
      '#default_value' => $config->get('uid1_see_all'),
    ];

    $form['admin_see_all'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('The menu per role administrators see everything'),
      '#description' => $this->t('When selected, all the menu per role administrators see all the menus, whether they were marked as hidden or not.')
      . ' ' . $this->t('<a href=":url">Check the role</a> assigned the "administer menu_per_role" permission.',
          [
            ':url' => Url::fromRoute('user.admin_permissions', [], ['fragment' => 'module-menu_per_role'])
              ->toString(),
          ])
      . (empty($admin_roles) ? '<br /><span style="color: red;">' . $this->t('IMPORTANT NOTE: No roles were marked with the "administer menu_per_role" permission.') . '</span>' : ''),
      '#default_value' => $config->get('admin_see_all'),
    ];

    $form['hide_show'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select what is shown when editing menu items'),
      '#options' => [
        static::MODE_DISPLAY_BOTH => $this->t('Hide and Show check boxes'),
        static::MODE_DISPLAY_ONLY_HIDE => $this->t('Only Hide check boxes'),
        static::MODE_DISPLAY_ONLY_SHOW => $this->t('Only Show check boxes'),
      ],
      '#description' => $this->t('By default, both list of check boxes are shown when editing a menu item (in the menu administration area or while editing a node.) This option let you choose to only show the "Show menu item only to selected roles" or "Hide menu item from selected roles". WARNING: changing this option does not change the existing selection. This means some selection will become invisible when you hide one of the set of check boxes...'),
      '#default_value' => $config->get('hide_show'),
    ];

    $form['hide_on_content'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show fileds on menu items that point to content'),
      '#options' => [
        static::MODE_DISPLAY_ON_CONTENT_ALWAYS => $this->t('Always'),
        static::MODE_DISPLAY_ON_CONTENT_NO_NODE_ACCESS => $this->t('If NO Node Access Modules are enabled.'),
        static::MODE_DISPLAY_ON_CONTENT_NEVER => $this->t('Never'),
      ],
      '#description' => $this->t('Fields are shown when editing any menu item. This will hide the fields when editing menu items, that point to nodes. This is useful on sites using Node Access modules.'),
      '#default_value' => $config->get('hide_on_content'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('menu_per_role.settings')
      ->set('uid1_see_all', $form_state->getValue('uid1_see_all'))
      ->set('admin_see_all', $form_state->getValue('admin_see_all'))
      ->set('hide_show', $form_state->getValue('hide_show'))
      ->set('hide_on_content', $form_state->getValue('hide_on_content'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
