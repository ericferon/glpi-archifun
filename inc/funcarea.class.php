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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchifunFuncarea extends CommonTreeDropdown {

   public $dohistory=true;
   static $rightname = "plugin_archifun";
   protected $usenotepad         = true;
   
   static $types = array('Computer','Software', 'SoftwareLicense');

   static function getTypeName($nb=0) {

      return _n('Functional Area', 'Functional Areas', $nb, 'archifun');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

   switch ($item->getType()) {
        case 'Supplier' :
//      if ($item->getType()=='Supplier') {
			if ($_SESSION['glpishow_count_on_tabs']) {
				return self::createTabEntry(self::getTypeName(2), self::countForItem($item));
			}
			return self::getTypeName(2);
        case 'PluginArchifunFuncarea' :
			return $this->getTypeName(Session::getPluralNumber());
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      switch ($item->getType()) {
        case 'Supplier' :
//      if ($item->getType()=='Supplier') {
			$self = new self();
			$self->showPluginFromSupplier($item->getField('id'));
            break;
        case 'PluginArchifunFuncarea' :
            $item->showChildren();
            break;
      }
      return true;
   }

   static function countForItem(CommonDBTM $item) {

      $dbu = new DbUtils();
      return $dbu->countElementsInTable('glpi_plugin_archifun_funcarea',
                                  "`suppliers_id` = '".$item->getID()."'");
   }

   //clean if funcarea are deleted
   function cleanDBonPurge() {

//      $temp = new PluginArchifunFuncarea_Item();
//      $temp->deleteByCriteria(array('plugin_archifun_funcareas_id' => $this->fields['id']));
   }

   function getSearchOptions() {

      $tab                       = array();
      if (version_compare(GLPI_VERSION,'9.3','ge')) return $tab;

      $tab['common']             = self::getTypeName(2);

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = __('Name');
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'level';
      $tab[3]['name']            = __('Level');
      $tab[3]['datatype']        = 'text';

      $tab[11]['table']          = 'glpi_users';
      $tab[11]['field']          = 'name';
      $tab[11]['linkfield']      = 'users_id';
      $tab[11]['name']           = __('Funcarea Expert', 'archifun');
      $tab[11]['datatype']       = 'dropdown';
      $tab[11]['right']          = 'interface';

      $tab[12]['table']          = 'glpi_groups';
      $tab[12]['field']          = 'name';
      $tab[12]['linkfield']      = 'groups_id';
      $tab[12]['name']           = __('Funcarea Follow-up', 'archifun');
      $tab[12]['condition']      = '`is_assign`';
      $tab[12]['datatype']       = 'dropdown';

      $tab[14]['table']          = $this->getTable();
      $tab[14]['field']          = 'date_mod';
      $tab[14]['massiveaction']  = false;
      $tab[14]['name']           = __('Last update');
      $tab[14]['datatype']       = 'datetime';

      $tab[30]['table']          = $this->getTable();
      $tab[30]['field']          = 'id';
      $tab[30]['name']           = __('ID');
      $tab[30]['datatype']       = 'number';

      $tab[80]['table']          = $this->getTable();
      $tab[80]['field']          = 'completename';
      $tab[80]['name']           = __('Functional Structure', 'archifun');
      $tab[80]['datatype']       = 'dropdown';
      
      $tab[81]['table']       = 'glpi_entities';
      $tab[81]['field']       = 'entities_id';
      $tab[81]['name']        = __('Entity')."-".__('ID');
      
      return $tab;
   }

   // search fields from GLPI 9.3 on
   function rawSearchOptions() {

      $tab = [];
      if (version_compare(GLPI_VERSION,'9.2','le')) return $tab;

      $tab[] = [
         'id'   => 'common',
         'name' => self::getTypeName(2)
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => $this->getTable(),
         'field'         => 'name',
         'name'          => __('Name'),
         'datatype'      => 'itemlink',
         'itemlink_type' => $this->getType()
      ];

      $tab[] = [
         'id'       => '2',
         'table'    => $this->getTable(),
         'field'    => 'level',
         'name'     => __('Level'),
         'datatype' => 'text'
      ];

      $tab[] = [
         'id'        => '11',
         'table'     => 'glpi_users',
         'field'     => 'name',
         'linkfield' => 'users_id',
         'name'      => __('Funcarea Expert', 'archifun'),
         'datatype'  => 'dropdown',
         'right'     => 'interface'
      ];

      $tab[] = [
         'id'        => '12',
         'table'     => 'glpi_groups',
         'field'     => 'name',
         'linkfield' => 'groups_id',
         'name'      => __('Funcarea Follow-up', 'archifun'),
         'condition' => '`is_assign`',
         'datatype'  => 'dropdown'
      ];

      $tab[] = [
         'id'            => '16',
         'table'         => $this->getTable(),
         'field'         => 'date_mod',
         'massiveaction' => false,
         'name'          => __('Last update'),
         'datatype'      => 'datetime'
      ];

      $tab[] = [
         'id'            => '72',
         'table'         => $this->getTable(),
         'field'         => 'id',
         'name'          => __('ID'),
         'datatype'      => 'number'
      ];

      $tab[] = [
         'id'            => '80',
         'table'         => $this->getTable(),
         'field'    => 'completename',
         'name'     => __('Functional Structure', 'archifun'),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'    => '81',
         'table' => 'glpi_entities',
         'field' => 'entities_id',
         'name'  => __('Entity') . "-" . __('ID')
      ];

      return $tab;
   }

   //define header form
   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginArchifunFuncarea', $ong, $options);
//      $this->addStandardTab('PluginArchifunFuncarea_Item', $ong, $options);
      $this->addStandardTab('Notepad', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   /*
    * Return the SQL command to retrieve linked object
    *
    * @return a SQL command which return a set of (itemtype, items_id)
    */
/*   function getSelectLinkedItem () {
      return "SELECT `itemtype`, `items_id`
              FROM `glpi_plugin_archifun_funcarea_items`
              WHERE `plugin_archifun_funcareas_id`='" . $this->fields['id']."'";
   }
*/
   function showForm ($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      //name of funcarea
      echo "<td>".__('Name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //completename of funcarea
      echo "<td>".__('As child of').": </td>";
      echo "<td>";
      Dropdown::show('PluginArchifunFuncarea', array('value' => $this->fields["plugin_archifun_funcareas_id"]));
      echo "</td>";
      //level of funcarea
      echo "<td>".__('Level').": </td>";
      echo "<td>";
      Html::autocompletionTextField($this,"level",array('size' => "2", 'option' => "readonly='readonly'"));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //description of funcarea
      echo "<td>".__('Description').":	</td>";
      echo "<td class='top center' colspan='6'>";
      Html::autocompletionTextField($this,"description",array('size' => "140"));
      echo "</td>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      //comment about funcarea
      echo "<td>".__('Comment').":	</td>";
      echo "<td class='top center' colspan='5'><textarea cols='100' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //groups
      echo "<td>".__('Function Owner', 'archifun')."</td><td>";
      Group::dropdown(array('name'      => 'groups_id', 'value'     => $this->fields['groups_id'], 'entity'    => $this->fields['entities_id'], 'condition' => '`is_assign`'));
      echo "</td>";
      //users
      echo "<td>".__('Function Maintainer', 'archifun')."</td><td>";
      User::dropdown(array('name' => "users_id", 'value' => $this->fields["users_id"], 'entity' => $this->fields["entities_id"], 'right' => 'interface'));
      echo "</td>";
      echo "</tr>";



      $this->showFormButtons($options);

      return true;
   }
   
   /**
    * Make a select box for link dataflow
    *
    * Parameters which could be used in options array :
    *    - name : string / name of the select (default is plugin_dataflows_dataflowtypes_id)
    *    - entity : integer or array / restrict to a defined entity or array of entities
    *                   (default -1 : no restriction)
    *    - used : array / Already used items ID: not to display in dropdown (default empty)
    *
    * @param $options array of possible options
    *
    * @return nothing (print out an HTML select box)
   **/
   static function dropdownFuncarea($options=array()) {
      global $DB, $CFG_GLPI;


      $p['name']    = 'plugin_archifun_funcareas_id';
      $p['entity']  = '';
      $p['used']    = array();
      $p['display'] = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $where = " WHERE `glpi_plugin_archifun_funcarea`.`is_deleted` = '0' ".
                       getEntitiesRestrictRequest("AND", "glpi_plugin_archifun_funcarea", '', $p['entity'], true);

      $p['used'] = array_filter($p['used']);
      if (count($p['used'])) {
         $where .= " AND `id` NOT IN (0, ".implode(",",$p['used']).")";
      }

      $query = "SELECT *
                FROM `glpi_plugin_dataflows_dataflowtypes`
                WHERE `id` IN (SELECT DISTINCT `plugin_dataflows_dataflowtypes_id`
                               FROM `glpi_plugin_archifun_funcarea`
                             $where)
                ORDER BY `name`";
      $result = $DB->query($query);

      $values = array(0 => Dropdown::EMPTY_VALUE);

      while ($data = $DB->fetch_assoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $rand = mt_rand();
      $out  = Dropdown::showFromArray('_dataflowtype', $values, array('width'   => '30%',
                                                                     'rand'    => $rand,
                                                                     'display' => false));
      $field_id = Html::cleanId("dropdown__dataflowtype$rand");

      $params   = array('dataflowtype' => '__VALUE__',
                        'entity' => $p['entity'],
                        'rand'   => $rand,
                        'myname' => $p['name'],
                        'used'   => $p['used']);

      $out .= Ajax::updateItemOnSelectEvent($field_id,"show_".$p['name'].$rand,
                                            $CFG_GLPI["root_doc"]."/plugins/archifun/ajax/dropdownTypeFuncareas.php",
                                            $params, false);
      $out .= "<span id='show_".$p['name']."$rand'>";
      $out .= "</span>\n";

      $params['dataflowtype'] = 0;
      $out .= Ajax::updateItem("show_".$p['name'].$rand,
                               $CFG_GLPI["root_doc"]. "/plugins/archifun/ajax/dropdownTypeFuncareas.php",
                               $params, false);
      if ($p['display']) {
         echo $out;
         return $rand;
      }
      return $out;
   }

   /**
    * For other plugins, add a type to the linkable types
    *
    * @since version 1.3.0
    *
    * @param $type string class name
   **/
   static function registerType($type) {
      if (!in_array($type, self::$types)) {
         self::$types[] = $type;
      }
   }


   /**
    * Type than could be linked to a Rack
    *
    * @param $all boolean, all type, or only allowed ones
    *
    * @return array of types
   **/
   static function getTypes($all=false) {

      if ($all) {
         return self::$types;
      }

      // Only allowed types
      $types = self::$types;

      foreach ($types as $key => $type) {
         if (!class_exists($type)) {
            continue;
         }

         $item = new $type();
         if (!$item->canView()) {
            unset($types[$key]);
         }
      }
      return $types;
   }


}

?>
