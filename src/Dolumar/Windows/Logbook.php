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

class Dolumar_Windows_Logbook extends Neuron_GameServer_Windows_Window
{
	private $objVillage;

	public function setSettings ()
	{
		$text = Neuron_Core_Text::__getInstance ();
	
		// Window settings
		$this->setSize ('450px', '250px');
		$this->setTitle ($text->get ('logbook', 'menu', 'main'));
		
		$this->setClassname ('logbook');
		
		$this->setAllowOnlyOnce ();
		
		// Check for input
		$input = $this->getRequestData ();
		if (isset ($input['village']))
		{
			$this->objVillage = Dolumar_Players_Village::getMyVillage ($input['village']);
		}
		else
		{
			$this->objVillage = false;
		}
	}
	
	public function getContent ()
	{
		if (!$this->objVillage)
		{
			$text = Neuron_Core_Text::__getInstance ();
			return '<p class="false">'.$text->get ('login', 'login', 'account').'</p>';
		}
		
		$input = $this->getInputData ();
		
		// Get logs from this village
		$objLogs = Dolumar_Players_Logs::__getInstance ();
		
		$iPage = isset ($input['page']) ? $input['page'] : 0;
		
		$page = new Neuron_Core_Template ();
		
		// Split in pages
		$limit = Neuron_Core_Tools::splitInPages 
		(
			$page, 
			$objLogs->countLogs ($this->objVillage), 
			$iPage, 
			10
		);
		
		$objLogs->addMyVillage ($this->objVillage);
		
		$logs = $objLogs->getLogs ($this->objVillage, $limit['start'], $limit['perpage'], 'DESC');
		
		return $this->getLogHTML ($page, $objLogs, $logs);
	}
	
	protected function getLogHTML ($page, $objLogs, $logs)
	{
		foreach ($logs as $v)
		{
			$page->addListValue
			(
				'logs',
				array
				(
					'date' => date (DATETIME, $v['timestamp']),
					'text' => $objLogs->getLogText ($v, true)
				)
			);
		}
		
		return $page->parse ('logbook.phpt');	
	}
}
?>
