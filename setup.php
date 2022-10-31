<?php
/*
 -------------------------------------------------------------------------
 Archifun plugin for GLPI
 Copyright (C) 2009-2018 by Eric Feron.
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of Archifun.

 Archifun is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 at your option any later version.

 Archifun is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Archifun. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_archifun() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['archifun'] = true;
   $PLUGIN_HOOKS['change_profile']['archifun'] = ['PluginArchifunProfile', 'initProfile'];
//   $PLUGIN_HOOKS['assign_to_ticket']['archifun'] = false;
   
   //$PLUGIN_HOOKS['assign_to_ticket_dropdown']['archifun'] = true;
   //$PLUGIN_HOOKS['assign_to_ticket_itemtype']['archifun'] = ['PluginArchifunFuncarea_Item'];
   
   Plugin::registerClass('PluginArchifunFuncarea', array(
         'linkgroup_tech_types'   => true,
         'linkuser_tech_types'    => true,
         'document_types'         => true,
//         'ticket_types'           => true,
         'helpdesk_visible_types' => true//,
//         'addtabon'               => 'Supplier'
   ));
   Plugin::registerClass('PluginArchifunProfile',
                         ['addtabon' => 'Profile']);
                         
   if (class_exists('PluginArchiswSwcomponent')) {
      PluginArchiswSwcomponent::registerType('PluginArchifunFuncarea');
   }
   if (class_exists('PluginArchimapGraph')) {
      PluginArchimapGraph::registerType('PluginArchifunFuncarea');
   }
   //Plugin::registerClass('PluginArchifunFuncarea_Item',
   //                      ['ticket_types' => true]);
      
   if (Session::getLoginUserID()) {

      if (Session::haveRight("plugin_archifun", READ)) {

         $PLUGIN_HOOKS['menu_toadd']['archifun'] = ['admin'   => 'PluginArchifunMenu'];
      }

      if (Session::haveRight("plugin_archifun", UPDATE)) {
         $PLUGIN_HOOKS['use_massive_action']['archifun']=1;
      }

      if (class_exists('PluginArchifunFuncarea_Item')) { // only if plugin activated
         $PLUGIN_HOOKS['plugin_datainjection_populate']['archifun'] = 'plugin_datainjection_populate_archifun';
      }

      // End init, when all types are registered
      $PLUGIN_HOOKS['post_init']['archifun'] = 'plugin_archifun_postinit';

      // Import from Data_Injection plugin
      $PLUGIN_HOOKS['migratetypes']['archifun'] = 'plugin_datainjection_migratetypes_archifun';
   }
}

// Get the name and the version of the plugin - Needed
function plugin_version_archifun() {

   return array (
      'name' => _n('Functional Area', 'Functional Areas', 2, 'archifun'),
      'version' => '2.2.7',
      'author'  => "Eric Feron",
      'license' => 'GPLv2+',
      'homepage'=>'https://github.com/ericferon/glpi-archifun',
      'requirements' => [
         'glpi' => [
            'min' => '10.0',
            'dev' => false
         ]
      ]
   );

}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archifun_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '10.0', 'lt')
       || version_compare(GLPI_VERSION, '10.1', 'ge')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '10.0');
      }
      return false;
   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archifun_check_config() {
   return true;
}

function plugin_datainjection_migratetypes_archifun($types) {
   $types[2400] = 'PluginArchifunFuncarea';
   return $types;
}

?>
