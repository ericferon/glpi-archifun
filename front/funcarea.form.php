<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 archifun plugin for GLPI
 Copyright (C) 2009-2016 by the archifun Development Team.

 https://github.com/InfotelGLPI/archifun
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of archifun.

 archifun is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 archifun is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with archifun. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$dataflow=new PluginArchifunFuncarea();
$dataflow_item=new PluginArchifunFuncarea_Item();

if (isset($_POST["add"])) {

   $dataflow->check(-1, CREATE,$_POST);
   $newID=$dataflow->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($dataflow->getFormURL()."?id=".$newID);
   }
   Html::back();

} else if (isset($_POST["delete"])) {

   $dataflow->check($_POST['id'], DELETE);
   $dataflow->delete($_POST);
   $dataflow->redirectToList();

} else if (isset($_POST["restore"])) {

   $dataflow->check($_POST['id'], PURGE);
   $dataflow->restore($_POST);
   $dataflow->redirectToList();

} else if (isset($_POST["purge"])) {

   $dataflow->check($_POST['id'], PURGE);
   $dataflow->delete($_POST,1);
   $dataflow->redirectToList();

} else if (isset($_POST["update"])) {

   $dataflow->check($_POST['id'], UPDATE);
   $dataflow->update($_POST);
   Html::back();

} else if (isset($_POST["additem"])) {

   if (!empty($_POST['itemtype'])&&$_POST['items_id']>0) {
      $dataflow_item->check(-1, UPDATE, $_POST);
      $dataflow_item->addItem($_POST);
   }
   Html::back();

} else if (isset($_POST["deleteitem"])) {

   foreach ($_POST["item"] as $key => $val) {
         $input = array('id' => $key);
         if ($val==1) {
            $dataflow_item->check($key, UPDATE);
            $dataflow_item->delete($input);
         }
      }
   Html::back();

} else if (isset($_POST["deletearchifun"])) {

   $input = array('id' => $_POST["id"]);
   $dataflow_item->check($_POST["id"], UPDATE);
   $dataflow_item->delete($input);
   Html::back();

} else {

   $dataflow->checkGlobal(READ);

   $plugin = new Plugin();
   if ($plugin->isActivated("environment")) {
      Html::header(PluginArchifunFuncarea::getTypeName(2),
                     '',"admin","pluginenvironmentdisplay","archifun");
   } else {
      Html::header(PluginArchifunFuncarea::getTypeName(2), '', "admin",
                   "pluginarchifunmenu");
   }
   $dataflow->display($_GET);

   Html::footer();
}

?>