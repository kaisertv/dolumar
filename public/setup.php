<?php

/**
 *  Dolumar, browser based strategy game
 *  Copyright (C) 2009 Thijs Van der Schaeghe
 *  CatLab Interactive bvba, Gent, Belgium
 *  http://www.catlab.eu/
 *  http://www.dolumar.com/
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// Autoload.
require_once '../bootstrap/bootstrap.php';

$game = new Dolumar_Game ();

$server = Neuron_GameServer::bootstrap();

$scripts = file_get_contents ('vendor/catlabinteractive/dolumar-engine/dumps/gameserver.sql');
$scripts .= file_get_contents ('dump/dump.sql');

$db = Neuron_Core_Database::__getInstance();

// Check.
echo '<pre>';
echo 'Checking setup.' . "\n";

try {
	$db->select('n_players', array ('*'));
}
catch (Exception $e)
{
	echo 'Installing database' . "\n";
	$db->multiQuery ($scripts);
}