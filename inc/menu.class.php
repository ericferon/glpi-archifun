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
 
class PluginArchifunMenu extends CommonGLPI {
   static $rightname = 'plugin_archifun';

   static function getMenuName() {
      return _n('Functional Area', 'Functional Areas', 2, 'archifun');
   }

   static function getMenuContent() {
      global $CFG_GLPI;

      $menu                                           = [];
      $menu['title']                                  = self::getMenuName();
      $menu['page']                                   = "/".Plugin::getWebDir('archifun', false)."/front/funcarea.php";
      $menu['links']['search']                        = PluginArchifunFuncarea::getSearchURL(false);
      if (PluginArchifunFuncarea::canCreate()) {
         $menu['links']['add']                        = PluginArchifunFuncarea::getFormURL(false);
      }
      $menu['icon'] = self::getIcon();

      return $menu;
   }

   static function getIcon() {
      return "fas fa-puzzle-piece";
   }

   static function removeRightsFromSession() {
      if (isset($_SESSION['glpimenu']['admin']['types']['PluginArchifunMenu'])) {
         unset($_SESSION['glpimenu']['admin']['types']['PluginArchifunMenu']); 
      }
      if (isset($_SESSION['glpimenu']['admin']['content']['PluginArchifunMenu'])) {
         unset($_SESSION['glpimenu']['admin']['content']['PluginArchifunMenu']); 
      }
   }
}
