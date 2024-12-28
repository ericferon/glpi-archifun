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

class PluginArchifunFuncarea_Itemrole extends CommonDropdown {

   static $rightname = "plugin_archifun";
   var $can_be_translated  = true;
   
   static function getTypeName($nb=0) {

      return _n('Link role','Link roles',$nb,'archifun');
   }
   /**
    * {@inheritDoc}
    * @see CommonTreeDropdown::getAdditionalFields()
    */
   public function getAdditionalFields() {
      return [
            [
                  'name'      => 'itemtype',
                  'type'      => 'text',
                  'label'     => __('Type', 'archifun'),
                  'list'      => false
            ]
		];
   }
   function getSearchOptions() {
	  $opt = CommonDropdown::getSearchOptions();
//      $sopt['common'] = __("App structures", "archifun");

      $opt[2400]['table']       = $this->getTable();
      $opt[2400]['field']       = 'itemtype';
      $opt[2400]['name']        = __("Type");
      $opt[2400]['datatype']    = 'text';

      return $opt;
   }
}

?>
