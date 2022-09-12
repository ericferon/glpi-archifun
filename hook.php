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

function plugin_archifun_install() {
   global $DB;

   include_once (Plugin::getPhpDir("archifun")."/inc/profile.class.php");

   $update=false;
   if (!$DB->TableExists("glpi_plugin_archifun_funcareas")) {

		$DB->runFile(Plugin::getPhpDir("archifun")."/sql/empty-1.0.0.sql");
	}

   
   PluginArchifunProfile::initProfile();
   PluginArchifunProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("2.0.0");
   $migration->dropTable('glpi_plugin_archifun_profiles');
   
   return true;
}

function plugin_archifun_uninstall() {
   global $DB;
   
   include_once (Plugin::getPhpDir("archifun")."/inc/profile.class.php");
   include_once (Plugin::getPhpDir("archifun")."/inc/menu.class.php");
   
	$tables = ["glpi_plugin_archifun_funcareas",
					"glpi_plugin_archifun_funcareas_items",
					"glpi_plugin_archifun_profiles"];

   foreach($tables as $table)
      $DB->query("DROP TABLE IF EXISTS `$table`;");

	$tables_glpi = ["glpi_displaypreferences",
               "glpi_documents_items",
               "glpi_savedsearches",
               "glpi_logs",
               "glpi_items_tickets",
               "glpi_notepads",
               "glpi_dropdowntranslations"];

   foreach($tables_glpi as $table_glpi)
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` LIKE 'PluginArchifun%' ;");

   if (class_exists('PluginDatainjectionModel')) {
      PluginDatainjectionModel::clean(['itemtype'=>'PluginArchifunFuncarea']);
   }
   
   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
   foreach (PluginArchifunProfile::getAllRights() as $right) {
      $profileRight->deleteByCriteria(['name' => $right['field']]);
   }
   PluginArchifunMenu::removeRightsFromSession();
   PluginArchifunProfile::removeRightsFromSession();
   
   return true;
}

function plugin_archifun_postinit() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['item_purge']['archifun'] = [];

   foreach (PluginArchifunFuncarea::getTypes(true) as $type) {

      $PLUGIN_HOOKS['item_purge']['archifun'][$type]
         = ['PluginArchifunFuncarea_Item','cleanForItem'];

      CommonGLPI::registerStandardTab($type, 'PluginArchifunFuncarea_Item');
   }
}


// Define dropdown relations
function plugin_archifun_getFuncareaRelations() {

   $plugin = new Plugin();
   if ($plugin->isActivated("archifun"))
		return ["glpi_plugin_archifun_funcareas"=>["glpi_plugin_archifun_funcareas_items"=>"plugin_archifun_funcareas_id"],
					 "glpi_entities"=>["glpi_plugin_archifun_funcareas"=>"entities_id"],
					 "glpi_groups"=>["glpi_plugin_archifun_funcareas"=>"groups_id"],
					 "glpi_users"=>["glpi_plugin_archifun_funcareas"=>"users_id"]
					 ];
   else
      return [];
}

// Define Dropdown tables to be manage in GLPI :
function plugin_archifun_getDropdown() {

   $plugin = new Plugin();
   if ($plugin->isActivated("archifun"))
		return [];
   else
      return [];
}

////// SEARCH FUNCTIONS ///////() {

function plugin_archifun_getAddSearchOptions($itemtype) {

   $sopt=[];

   if (in_array($itemtype, PluginArchifunFuncarea::getTypes(true))) {
      if (Session::haveRight("plugin_archifun", READ)) {

         $sopt[2410]['table']         ='glpi_plugin_archifun_funcareas';
         $sopt[2410]['field']         ='name';
         $sopt[2410]['name']          = PluginArchifunFuncarea::getTypeName(2)." - ".__('Name');
         $sopt[2410]['forcegroupby']  = true;
         $sopt[2410]['datatype']      = 'itemlink';
         $sopt[2410]['massiveaction'] = false;
         $sopt[2410]['itemlink_type'] = 'PluginArchifunFuncarea';
         $sopt[2410]['joinparams']    = ['beforejoin'
                                                => ['table'      => 'glpi_plugin_archifun_funcareas_items',
                                                         'joinparams' => ['jointype' => 'itemtype_item']]];

     }
   }
   return $sopt;
}

function plugin_archifun_giveItem($type,$ID,$data,$num) {
   global $DB;

   return "";
}

////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archifun_MassiveActions($type) {

    $plugin = new Plugin();
    if ($plugin->isActivated('archifun')) {
        if (in_array($type,PluginArchifunFuncarea::getTypes(true))) {
            return ['PluginArchifunFuncarea'.MassiveAction::CLASS_ACTION_SEPARATOR.'plugin_archifun__add_item' =>
                                                              __('Associate to the Functional Area', 'archifun')];
        }
    }
    return [];
}

/*
function plugin_archifun_MassiveActionsDisplay($options=[]) {

   $funcarea=new PluginArchifunFuncarea;

   if (in_array($options['itemtype'], PluginArchifunFuncarea::getTypes(true))) {

      $funcarea->dropdownFuncareas("plugin_archifun_funcarea_id");
      echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\""._sx('button', 'Post')."\" >";
   }
   return "";
}

function plugin_archifun_MassiveActionsProcess($data) {

   $res = ['ok' => 0,
            'ko' => 0,
            'noright' => 0];

   $funcarea_item = new PluginArchifunFuncarea_Item();

   switch ($data['action']) {

      case "plugin_archifun_add_item":
         foreach ($data["item"] as $key => $val) {
            if ($val == 1) {
               $input = ['plugin_archifun_funcarea_id' => $data['plugin_archifun_funcarea_id'],
                              'items_id'      => $key,
                              'itemtype'      => $data['itemtype']];
               if ($funcarea_item->can(-1,'w',$input)) {
                  if ($funcarea_item->can(-1,'w',$input)) {
                     $funcarea_item->add($input);
                     $res['ok']++;
                  } else {
                     $res['ko']++;
                  }
               } else {
                  $res['noright']++;
               }
            }
         }
         break;
   }
   return $res;
}
*/
function plugin_datainjection_populate_archifun() {
   global $INJECTABLE_TYPES;
   $INJECTABLE_TYPES['PluginArchifunFuncareaInjection'] = 'datainjection';
}



?>
