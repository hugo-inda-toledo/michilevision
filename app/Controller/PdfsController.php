<?php
class PdfsController extends AppController {
     var  $uses = array('User', 'System');
     var  $helpers = array('Pdf');
      
     function index() {
        $this->layout='pdf';        
     }
}
?>