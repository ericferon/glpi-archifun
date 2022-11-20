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

include ('../../../inc/includes.php');

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$funcarea=new PluginArchifunFuncarea();
$funcarea_item=new PluginArchifunFuncarea_Item();

if (isset($_POST["add"])) {

   $funcarea->check(-1, CREATE,$_POST);
   $newID=$funcarea->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($funcarea->getFormURL()."?id=".$newID);
   }
   Html::back();

} else if (isset($_POST["delete"])) {

   $funcarea->check($_POST['id'], DELETE);
   $funcarea->delete($_POST);
   $funcarea->redirectToList();

} else if (isset($_POST["restore"])) {

   $funcarea->check($_POST['id'], PURGE);
   $funcarea->restore($_POST);
   $funcarea->redirectToList();

} else if (isset($_POST["purge"])) {

   $funcarea->check($_POST['id'], PURGE);
   $funcarea->delete($_POST,1);
   $funcarea->redirectToList();

} else if (isset($_POST["update"])) {

   $funcarea->check($_POST['id'], UPDATE);
   $funcarea->update($_POST);
   Html::back();

} else if (isset($_POST["additem"])) {

   if (!empty($_POST['itemtype'])&&$_POST['items_id']>0) {
      $funcarea_item->check(-1, UPDATE, $_POST);
      $funcarea_item->addItem($_POST);
   }
   Html::back();

} else if (isset($_POST["deleteitem"])) {

   foreach ($_POST["item"] as $key => $val) {
         $input = ['id' => $key];
         if ($val==1) {
            $funcarea_item->check($key, UPDATE);
            $funcarea_item->delete($input);
         }
      }
   Html::back();

} else if (isset($_POST["deletearchifun"])) {

   $input = ['id' => $_POST["id"]];
   $funcarea_item->check($_POST["id"], UPDATE);
   $funcarea_item->delete($input);
   Html::back();

} else {

   $funcarea->checkGlobal(READ);

   Html::header(PluginArchifunFuncarea::getTypeName(2), '', "admin",
                   "pluginarchifunmenu");
   $funcarea->display($_GET);

   Html::footer();
}

?>
