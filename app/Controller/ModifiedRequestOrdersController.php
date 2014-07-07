<?php
	class ModifiedRequestOrdersController extends AppController
	{
		var $name = 'ModifiedRequestOrders';
		var $helpers = array('Session', 'Html', 'Form','Time');
		var $uses = array('ModifiedRequestOrder', 'PurchaseOrder');
		var $components = array('Password', 'Email', 'Auth');
		var $scaffold;
		var $paginate = array();
		
		function index() 
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/modifiedRequestOrder") == true || $this->RequestAction('/external_functions/verifiedAdministrationUserLogged/'.$this->Auth->user('id')) == true)
			{
				$this->paginate = array('ModifiedRequestOrder' => array('limit' => 10, 'order' => array('ModifiedRequestOrder.created' => 'Desc', 'ModifiedRequestOrder.can_modify' => 'ASC')));
			
				$this->set('requests', $this->paginate());
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function view($id = null)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/modifiedRequestOrder") == true || $this->RequestAction('/external_functions/verifiedAdministrationUserLogged/'.$this->Auth->user('id')) == true)
			{	
				if($id != null)
				{
					$this->ModifiedRequestOrder->id = $id;
					$this->request->data = $this->ModifiedRequestOrder->read();
				}
				else
				{
					$this->Session->setFlash('El id de la solicitud no puede ser nulo.', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
		
		function authorizeForm($requestId = null)
		{
			if($requestId != null)
			{	
				$table = '';
				
				$js = "<script>
					
						function getDataToSign()
						{
							for(i=0; i < document.ModifiedRequestOrderEditForm.can_modify.length; i++)
							{
								if(document.ModifiedRequestOrderEditForm.can_modify[i].checked)
								{
									var comments = document.getElementById('response_message').value;
									var selection = document.ModifiedRequestOrderEditForm.can_modify[i].value;
									var request_id = document.getElementById('id_modified_request_order').value;
									var raiz = '/michilevision';
									

									if(comments == '')
									{
										alert('El comentario no puede ir vacio');
										return false;
									}
									else
									{
										document.location.href = raiz + '/modified_request_orders/authorizingOrder/'+request_id+'/'+selection+'/'+comments;
									}
								}
							}
						}
						
						</script>";
				
				$table .= $js;
				$table .= '<form id="ModifiedRequestOrderEditForm" name="ModifiedRequestOrderEditForm" accept-charset="utf-8">
				<table>
					<tr>
						<td style="border:none;"><input name="can_modify" type="radio" value="1" id="can_modify" checked="checked" onClick="Javascript:hideComments();"/>
						<label for="accion_aprueba">Autorizo</label>
						<input name="can_modify" type="radio" value="0" id="can_modify"/>
						<label for="accion_rechaza">Rechazo</label>
						<input name="id_modified_request_order" type="hidden" value="'.$requestId.'" id="id_modified_request_order" /><br />
						<div id="comment"><textarea name="response_message" style="width:200px;height:80px;" id="response_message"></textarea></div>
						<button type="button" style="padding:5px;float:left;" class="a_button" onclick="javascript:getDataToSign();">Enviar</button>
						<button type="button" style="padding:5px;float:left;" class="a_button" onclick="hide_tipTip();">Cancelar</button></td>
					</tr>
					</table>
				</form>';
				
				return $table;
			}
			else
			{
				return "<table>
							<tr><td>El id no puede estar nulo.</td></tr>
						</table>";
			}
		}
		
		function authorizingOrder($request_id = null, $valueAuthorize = null, $response)
		{
			if($this->RequestAction('/external_functions/verifiedAccess/'.$this->Auth->user('id')."/2/authorizingOrder") == true || $this->RequestAction('/external_functions/verifiedAdministrationUserLogged/'.$this->Auth->user('id')) == true)
			{
				if($request_id != null && $valueAuthorize != null && $response != null)
				{
					$dataRequest = $this->ModifiedRequestOrder->find('first', array('conditions' => array('ModifiedRequestOrder.id' => $request_id)));
					
					$dataRequest['ModifiedRequestOrder']['can_modify'] = $valueAuthorize;
					$dataRequest['ModifiedRequestOrder']['response_message'] = $response;
					
					$blocked;
					
					if($valueAuthorize == 1)
						$blocked = 0;
					else
						$blocked = 1;
					
					$dataRequest['ModifiedRequestOrder']['block'] = $blocked;
					
					$this->ModifiedRequestOrder->begin();
					
					if($this->ModifiedRequestOrder->save($dataRequest['ModifiedRequestOrder']))
					{
						if($this->RequestAction('/notifications/notificationToUserForModifiedOrder/2/'.$dataRequest['ModifiedRequestOrder']['user_id'].'/'.$dataRequest['ModifiedRequestOrder']['purchase_order_id'].'/'.$valueAuthorize) == true)
						{
							$this->ModifiedRequestOrder->commit();
							$this->Session->setFlash('Solicitud actualizada correctamente.', 'flash_success');
							$this->redirect(array('action' => 'index'));
						}
						else
						{
							$this->ModifiedRequestOrder->rollback();
							$this->Session->setFlash('Error al enviar la notificación.', 'flash_alert');
							$this->redirect(array('action' => 'index'));
						}
					}
					else
					{
						$this->ModifiedRequestOrder->rollback();
						$this->Session->setFlash('Error al actualizar la solicitud, intentalo nuevamente.', 'flash_alert');
						$this->redirect(array('action' => 'index'));
					}
				}
				else
				{
					$this->Session->setFlash('La información para autorizar no puede estar nula.', 'flash_alert');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash('No tienes permisos para ver esta pagina, consulta con el administrador del sistema.', 'flash_alert');
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
		}
	}
?>