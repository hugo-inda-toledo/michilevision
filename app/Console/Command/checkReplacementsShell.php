<?php
class checkReplacementsTask extends AppShell 
{
    public $uses = array('User', 'Replacement');
    
	public function execute() 
	{
		$replacements = $this->Replacement->find('all', array('conditions' => array('Replacement.end_date =<' => date('Y-m-d'), 'Replacement.active' => 1)));
		
		if($replacements != false)
		{
			foreach($replacements as $replacement)
			{
				$replacement['Replacement']['active'] = 0;
			
				if($replacement['Replacement']['start_date'] != $replacement['Replacement']['start_original_date'])
					$replacement['Replacement']['start_date'] = $replacement['Replacement']['start_original_date'];
			
				$disabledPermissions = $this->RequestAction('/user_permissions/disabledTemporalPermissions/'.$replacement['Replacement']['id']);
			
				if($disabledPermissions== true)
				{
					$this->Replacement->begin();
				
					if ($this->Replacement->save($replacement))
					{
						$this->Replacement->commit();
					}
				
					$this->Replacement->rollback();
				}
			}
			
			exit();
		}
		else
		{
			exit();
		}
    }
}
?>